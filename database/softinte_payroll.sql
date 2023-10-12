-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 07, 2022 at 06:28 PM
-- Server version: 10.3.34-MariaDB-0ubuntu0.20.04.1-log
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `softinte_payroll`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`softin`@`localhost` PROCEDURE `crosstab` (IN `query` LONGTEXT CHARACTER SET utf8, IN `cwhere` LONGTEXT CHARACTER SET utf8, IN `reportid` INT(11), IN `tmptable` VARCHAR(60) CHARACTER SET utf8)  SQL SECURITY INVOKER
BEGIN
        	SET @rows = null;
            SET @math = null;
        	SET @cols = null;
        	SET @vals = null;
            SET @order = null;
        	SELECT GROUP_CONCAT(_field ORDER BY _serial SEPARATOR ', ') INTO @rows FROM sys_reportfields WHERE (_reportid = reportid) AND (_pivottable = 1) AND (_summarizeby = 'none') AND (_show = 1);
        	SELECT GROUP_CONCAT(CONCAT(_summarizeby,'(',_field,') AS ',_field) ORDER BY _serial SEPARATOR ', ') INTO @math FROM sys_reportfields WHERE (_reportid = reportid) AND (_pivottable = 1) AND (_summarizeby <> 'none') AND (_show = 1);
            SELECT GROUP_CONCAT(CONCAT("'SUM(IF(&col = \\'\',&col,\'\\', ",_field,", 0)) AS \\'\',&col,\'::",_field,"\\''") ORDER BY _serial SEPARATOR ', \',\n \',') INTO @vals FROM sys_reportfields WHERE (_reportid = reportid) AND (_pivottable = 3) AND (_show = 1);
        	SELECT GROUP_CONCAT(REPLACE(@vals, '&col', _field) ORDER BY _serial SEPARATOR ', \',\n \',') INTO @cols FROM sys_reportfields WHERE (_reportid = reportid) AND (_pivottable = 2) AND (_show = 1);
        	SELECT GROUP_CONCAT(CONCAT(_field,' ',_orderby) ORDER BY _orderserial SEPARATOR ', ') INTO @order FROM sys_reportfields WHERE (_reportid = reportid) AND (_orderserial > 0);
    	   
        	SET @getcolx = null;
        	SELECT GROUP_CONCAT(_field ORDER BY _serial SEPARATOR ', ') INTO @getcolx FROM sys_reportfields WHERE (_reportid = reportid) AND (_pivottable = 2) AND (_show = 1);
        	SET @getcols = CONCAT("SELECT GROUP_CONCAT(CONCAT(",@cols,") SEPARATOR ',\n ') INTO @fullcol FROM (SELECT DISTINCT ",@getcolx," FROM ",query,") col1");
        	
        	SET @fullcol = null;
        	PREPARE _sql FROM @getcols;
            EXECUTE _sql;
            DEALLOCATE PREPARE _sql;
            
            SET @droptmp = CONCAT('DROP TABLE IF EXISTS ',tmptable);
            PREPARE _sql FROM @droptmp;
            EXECUTE _sql;
            DEALLOCATE PREPARE _sql;
        	
        	SET @setsum = null;
        	SELECT GROUP_CONCAT(CONCAT('SUM(',_field,') AS ',_field) ORDER BY _serial SEPARATOR ', ') INTO @setsum FROM sys_reportfields WHERE (_reportid = reportid) AND (_pivottable = 3) AND (_show = 1);
        	SET @returnx = CONCAT('CREATE TEMPORARY TABLE IF NOT EXISTS `',tmptable,'` AS (SELECT * FROM (SELECT ',@rows,if(ISNULL(@math),'',CONCAT(', ',@math)),',\n ',@fullcol, ',\n ',@setsum,'\n FROM ',query,' \n ',if(cwhere='','',CONCAT(' WHERE ',cwhere,'\n ')),' GROUP BY ',@rows,'\n ORDER BY ',ifnull(@order,@rows),')) tmp1');
            
            PREPARE _sql FROM @returnx;
            EXECUTE _sql;
            DEALLOCATE PREPARE _sql;
        END$$

--
-- Functions
--
CREATE DEFINER=`softin`@`localhost` FUNCTION `avgrate` (`_cdate` DATE, `_citem` VARCHAR(60) CHARACTER SET utf8) RETURNS DECIMAL(15,4) READS SQL DATA
BEGIN
            DECLARE GVALUE DECIMAL(15,4);
                SELECT IFNULL(SUM(`_qty` * `_rate`) / SUM(`_qty`),0) AS _value
                INTO GVALUE
                FROM inv_stockreg
                WHERE (`_type` = 'In') AND (`_date` <= `_cdate`) AND IF(ISNULL(`_citem`), 1=1,_item = `_citem`);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `avgvalue` (`_cdate` DATE, `_citem` VARCHAR(60) CHARACTER SET utf8) RETURNS DECIMAL(15,4) READS SQL DATA
BEGIN
            DECLARE GVALUE DECIMAL(15,4);
                SELECT (SUM(`_qty`) * (SELECT IFNULL(SUM(`_qty` * `_rate`) / SUM(`_qty`),0) AS _value
                FROM inv_stockreg
                WHERE (`_type` = 'In') AND (`_date` <= `_cdate`) AND (`_item` = `t1`.`_item`))) AS _value
                INTO GVALUE
                FROM inv_stockreg t1
                WHERE (`_date` <= `_cdate`) AND IF(ISNULL(`_citem`), 1=1,_item = `_citem`);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `coption` (`okey` VARCHAR(20) CHARACTER SET utf8) RETURNS LONGTEXT CHARSET utf8 READS SQL DATA
BEGIN
        	DECLARE GVALUE LONGTEXT;
        	SELECT `_value`
        	INTO GVALUE
        	FROM `sys_option`
        	WHERE (`_masterkey` = okey);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `fgroup` (`ledger` VARCHAR(60) CHARACTER SET utf8) RETURNS VARCHAR(60) CHARSET utf8 READS SQL DATA
BEGIN
            DECLARE GVALUE VARCHAR(60) CHARACTER SET utf8;
        	SELECT `_group`
        	INTO GVALUE
        	FROM `acc_ledger`
        	WHERE (`_ledger` = ledger);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `fidcost` (`id` INT(11)) RETURNS VARCHAR(60) CHARSET utf8 BEGIN
            DECLARE GVALUE VARCHAR(60) CHARACTER SET utf8;
        	SELECT `_name`
        	INTO GVALUE
        	FROM `acc_costcenter`
        	WHERE (`_id` = id);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `fidgroup` (`id` INT(11)) RETURNS VARCHAR(60) CHARSET utf8 BEGIN
            DECLARE GVALUE VARCHAR(60) CHARACTER SET utf8;
        	SELECT `_group`
        	INTO GVALUE
        	FROM `acc_group`
        	WHERE (`_id` = id);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `fiditem` (`id` INT(11)) RETURNS VARCHAR(60) CHARSET utf8 BEGIN
            DECLARE GVALUE VARCHAR(60) CHARACTER SET utf8;
        	SELECT `_item`
        	INTO GVALUE
        	FROM `inv_item`
        	WHERE (`_id` = id);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `fidledger` (`id` INT(11)) RETURNS VARCHAR(60) CHARSET utf8 BEGIN
            DECLARE GVALUE VARCHAR(60) CHARACTER SET utf8;
        	SELECT `_ledger`
        	INTO GVALUE
        	FROM `acc_ledger`
        	WHERE (`_id` = id);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `fidstore` (`id` INT(11)) RETURNS VARCHAR(60) CHARSET utf8 BEGIN
            DECLARE GVALUE VARCHAR(60) CHARACTER SET utf8;
        	SELECT `_store`
        	INTO GVALUE
        	FROM `inv_store`
        	WHERE (`_id` = id);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `mgroup` (`code` INTEGER) RETURNS VARCHAR(60) CHARSET utf8 READS SQL DATA
BEGIN
        	DECLARE GVALUE VARCHAR(60);
        	SELECT `_group`
        	INTO GVALUE
        	FROM `acc_master`
        	WHERE (`_code` = code);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `midledger` (`code` INTEGER) RETURNS VARCHAR(60) CHARSET utf8 READS SQL DATA
BEGIN
        	DECLARE LVALUE VARCHAR(60);
        	SELECT (SELECT `_id` FROM `acc_ledger` WHERE (`_ledger` = `acc_master`.`_ledger`)) AS _ledger
        	INTO LVALUE
        	FROM `acc_master`
        	WHERE (`_code` = code);
        	RETURN LVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `mledger` (`code` INTEGER) RETURNS VARCHAR(60) CHARSET utf8 READS SQL DATA
BEGIN
        	DECLARE LVALUE VARCHAR(60);
        	SELECT `_ledger`
        	INTO LVALUE
        	FROM `acc_master`
        	WHERE (`_code` = code);
        	RETURN LVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `purrate` (`_cdate` DATE, `_citem` VARCHAR(60) CHARACTER SET utf8) RETURNS DECIMAL(15,4) READS SQL DATA
BEGIN
            DECLARE GVALUE DECIMAL(15,4);
                SELECT IFNULL(MAX(`_rate`),0) AS _value
                INTO GVALUE
                FROM inv_stockreg m1
				WHERE (`_date` = (SELECT MAX(`_date`) AS e1 
                FROM inv_stockreg s1
                WHERE (_type = 'in') AND (`_date` <= `_cdate`) AND (`_rate`> 0) AND `s1`.`_item` = `m1`.`_item`))
                AND IF(ISNULL(`_citem`), 1=1,_item = `_citem`);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `purvalue` (`_cdate` DATE, `_citem` VARCHAR(60) CHARACTER SET utf8) RETURNS DECIMAL(15,4) READS SQL DATA
BEGIN
            DECLARE GVALUE DECIMAL(15,4);
                SELECT (SUM(`_qty`) * (SELECT IFNULL(MAX(`t2`.`_rate`),0) AS _value
                FROM inv_purchase t1 INNER JOIN inv_purchasedetail t2 ON t1._id = t2._no
                WHERE (t1._date = (SELECT MAX(_date) AS e1 
                FROM inv_purchase s1 INNER JOIN inv_purchasedetail s2 ON s1._id = s2._no
                WHERE (`s1`.`_date` <= `_cdate`) AND (`s2`.`_rate`> 0) AND IF(ISNULL(`_citem`), 1=1,`s2`.`_item` = `m1`.`_item`)))
                AND IF(ISNULL(`_citem`), 1=1,`t2`.`_item` = `m1`.`_item`))) AS _value
                INTO GVALUE
                FROM inv_stockreg m1
                WHERE (`_date` <= `_cdate`) AND IF(ISNULL(`_citem`), 1=1,_item = `_citem`);
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `savgvalue` (`_cdate` DATE, `_citem` VARCHAR(60) CHARACTER SET utf8, `_cqty` DECIMAL(15,4)) RETURNS DECIMAL(15,4) READS SQL DATA
BEGIN
            DECLARE GVALUE DECIMAL(15,4);
          		SELECT SUM(`t1`.`_qty` * `t1`.`_rate`) AS _value
            INTO GVALUE
                FROM (SELECT `_date`, `_category`, `_item`, if((SELECT SUM(`_qty`)
                FROM inv_stockreg s2
                WHERE (`s2`.`_date` >= `s1`.`_date`) AND (`s2`.`_date` <= `_cdate`) AND (`s2`.`_item` = `s1`.`_item`) AND (`s2`.`_type` = 'In')) >
                (SELECT SUM(`_qty`) + `_cqty` AS Qty
                FROM inv_stockreg s3
                WHERE (`s3`.`_date` <= `_cdate`) AND (`s3`.`_item` = `s1`.`_item`)),
                (SELECT SUM(`_qty`) + `_cqty` AS Qty
                FROM inv_stockreg s3
                WHERE (`s3`.`_date` <= `_cdate`) AND (`s3`.`_item` = `s1`.`_item`)) -
                (SELECT IFNULL(SUM(`_qty`), 0)
                FROM inv_stockreg s2
                WHERE (`s2`.`_date` > `s1`.`_date`) AND (`s2`.`_date` <= `_cdate`) AND (`s2`.`_item` = `s1`.`_item`) AND (`s2`.`_type` = 'In')), SUM(`_qty`)) AS _qty,SUM(`_qty`*`_rate`)/SUM(`_qty`) AS _rate
                FROM inv_stockreg s1
                WHERE ((SELECT IFNULL(SUM(`_qty`), 0)
                FROM inv_stockreg s2
                WHERE (`s2`.`_date` > `s1`.`_date`) AND (`s2`.`_date` <= `_cdate`) AND (`s2`.`_item` = `s1`.`_item`) AND (`s2`.`_type` = 'In')) <
                (SELECT SUM(`_qty`) + `_cqty` AS Qty
                FROM inv_stockreg s3
                WHERE (`s3`.`_date` <= `_cdate`) AND (`s3`.`_item` = `s1`.`_item`))) AND (`_type` = 'In') AND (`_date` <= `_cdate`)
                AND if(`_citem`>'',`_item` = `_citem`, 1=1)
                GROUP BY `_date`, `_category`, `_item`) t1;
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `taka` (`cValue` DECIMAL(15,4)) RETURNS VARCHAR(30) CHARSET utf8 BEGIN
            DECLARE GVALUE VARCHAR(30) CHARACTER SET utf8;
            SELECT IF(`_value` = 0, REPLACE(FORMAT(cValue, 2), ',', ''), IF(`_value` = 1, FORMAT(cValue,2,'en_US'), FORMAT(cValue,2,'en_IN'))) AS _value
        	INTO GVALUE
            FROM `sys_option` 
            WHERE `_masterkey` = 'currencyformat';
        	RETURN GVALUE;
        END$$

CREATE DEFINER=`softin`@`localhost` FUNCTION `unserialized` (`_input_string` TEXT CHARSET utf8, `_key` TEXT CHARSET utf8) RETURNS TEXT CHARSET utf8 BEGIN
            	DECLARE __output_part,__output,__extra_byte_counter,__extra_byte_number,__value_type,__array_part_temp TEXT;
            	DECLARE __value_length,__char_ord,__start,__char_counter,__non_multibyte_length,__array_close_bracket_counter,__array_open_bracket_counter INT SIGNED;
            	SET __output := NULL;
            	
            	
            	IF LOCATE(CONCAT('s:',LENGTH(_key),':"',_key,'";'), _input_string) != 0 THEN
            	
            		
            		SET __output_part := SUBSTRING_INDEX(_input_string,CONCAT('s:',LENGTH(_key),':"',_key,'";'),-1);
            		
            		
            		SET __value_type := SUBSTRING(SUBSTRING(__output_part, 1, CHAR_LENGTH(SUBSTRING_INDEX(__output_part,';',1))), 1, 1);
            		
            		
            		CASE 	
            		WHEN __value_type = 'a' THEN
            			
            			SET __array_open_bracket_counter := 1;
            			SET __array_close_bracket_counter := 0;
            			
            			SET __array_part_temp := SUBSTRING(__output_part FROM LOCATE('{',__output_part)+1);
            			
            			
            			WHILE (__array_open_bracket_counter > 0 OR LENGTH(__array_part_temp) = 0) DO
            				
            				IF LOCATE('{',__array_part_temp) > 0 AND (LOCATE('{',__array_part_temp) < LOCATE('}',__array_part_temp)) THEN
            					
            					SET __array_open_bracket_counter := __array_open_bracket_counter + 1;
            					SET __array_part_temp := SUBSTRING(__array_part_temp FROM LOCATE('{',__array_part_temp) + 1);					
            				ELSE
            					
            					SET __array_open_bracket_counter := __array_open_bracket_counter - 1;
            					SET __array_close_bracket_counter := __array_close_bracket_counter + 1;
            					SET __array_part_temp := SUBSTRING(__array_part_temp FROM LOCATE('}',__array_part_temp) + 1);					
            				END IF;
            			END WHILE;
            			
            			SET __output := CONCAT(SUBSTRING_INDEX(__output_part,'}',__array_close_bracket_counter),'}');
            			
            		WHEN __value_type = 'd' OR __value_type = 'i' OR __value_type = 'b' THEN
            			
            			
            			SET __output := SUBSTRING_INDEX(SUBSTRING_INDEX(__output_part,';',1),':',-1);
            			
            		WHEN __value_type = 'O' THEN			
            			
            			
            			SET __output := CONCAT(SUBSTRING_INDEX(__output_part,';}',1),';}');
            			
            		WHEN __value_type = 'N' THEN 
                        
                        SET __output := NULL;		
            		ELSE
            			
            			
            			SET __value_length := SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(__output_part, ':', 2),':',-1),';',1);
            			
            			
            			
            			
            			SET __output_part := SUBSTRING(__output_part, 5+LENGTH(__value_length));
            			
            			SET __char_counter := 1;
            			
            			
            			SET __non_multibyte_length := 0;
            			
            			SET __start := 0;
            			
            			WHILE __start < __value_length DO
            			
            				SET __char_ord := ORD(SUBSTR(__output_part,__char_counter,1));
            				
            				SET __extra_byte_number := 0;
            				SET __extra_byte_counter := FLOOR(__char_ord / 256);
            				
            				
            				
            				WHILE __extra_byte_counter > 0 DO
            					SET __extra_byte_counter := FLOOR(__extra_byte_counter / 256);
            					SET __extra_byte_number := __extra_byte_number+1;
            				END WHILE;
            				
            				
            				SET __start := __start + 1 + __extra_byte_number;			
            				SET __char_counter := __char_counter + 1;
            				SET __non_multibyte_length := __non_multibyte_length +1;
            								
            			END WHILE;
            			
            			SET __output :=  SUBSTRING(__output_part,1,__non_multibyte_length);
            					
            		END CASE;		
            	END IF;
            	RETURN __output;
            	END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `acc_account`
-- (See below for the actual view)
--
CREATE TABLE `acc_account` (
`_transaction` varchar(15)
,`_sl` int(11)
,`_id` varchar(14)
,`_date` date
,`_type` varchar(20)
,`_reference` varchar(30)
,`_costcenter` varchar(60)
,`_shortnarr` varchar(200)
,`_group` varchar(60)
,`_ledger` varchar(60)
,`_dr_amount` decimal(37,4)
,`_cr_amount` decimal(17,4)
,`_narration` varchar(200)
);

-- --------------------------------------------------------

--
-- Table structure for table `acc_assets`
--

CREATE TABLE `acc_assets` (
  `_id` int(11) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_use_date` date NOT NULL,
  `_quantity` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_opening_date` date NOT NULL,
  `_initial_cost` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_depreciation` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_percent` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_budget`
--

CREATE TABLE `acc_budget` (
  `_id` int(11) NOT NULL,
  `_budget_name` varchar(50) NOT NULL,
  `_start_date` date NOT NULL,
  `_end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_budgetdetail`
--

CREATE TABLE `acc_budgetdetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_costcenter`
--

CREATE TABLE `acc_costcenter` (
  `_id` int(11) NOT NULL,
  `_name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_group`
--

CREATE TABLE `acc_group` (
  `_id` int(11) NOT NULL,
  `_related` varchar(60) NOT NULL,
  `_group` varchar(60) NOT NULL,
  `_sort` varchar(40) NOT NULL,
  `_purchase` enum('1','0') NOT NULL DEFAULT '0',
  `_sales` enum('1','0') NOT NULL DEFAULT '0',
  `_show` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acc_group`
--

INSERT INTO `acc_group` (`_id`, `_related`, `_group`, `_sort`, `_purchase`, `_sales`, `_show`) VALUES
(1, 'Primary', 'Assets', '1.1', '0', '0', '0'),
(2, 'Primary', 'Expenses', '1.2', '0', '0', '0'),
(3, 'Primary', 'Income', '1.3', '0', '0', '0'),
(4, 'Primary', 'Liabilities', '1.4', '0', '0', '0'),
(5, 'Assets', 'Current Assets', '1.1.1', '0', '0', '0'),
(6, 'Assets', 'Non Current Assets', '1.1.2', '0', '0', '0'),
(7, 'Current Assets', 'Account Receivables', '1.1.1.1', '0', '1', '0'),
(8, 'Current Assets', 'Advance & Prepayment', '1.1.1.2', '0', '0', '0'),
(9, 'Current Assets', 'Cash and Bank Balances', '1.1.1.3', '0', '0', '0'),
(10, 'Current Assets', 'Inventories', '1.1.1.4', '0', '0', '0'),
(11, 'Current Assets', 'Other Receivables', '1.1.1.5', '0', '1', '0'),
(12, 'Non Current Assets', 'Receivables Non Current', '1.1.2.4', '0', '0', '0'),
(13, 'Non Current Assets', 'Property Plants and Equipments', '1.1.2.3', '0', '0', '0'),
(14, 'Non Current Assets', 'Pre-Production Expenses', '1.1.2.2', '0', '0', '0'),
(15, 'Non Current Assets', 'Investments', '1.1.2.1', '0', '0', '0'),
(16, 'Liabilities', 'Current Liabilities', '1.4.1', '0', '0', '0'),
(17, 'Liabilities', 'Non Current Liabilities', '1.4.2', '0', '0', '0'),
(18, 'Liabilities', 'Equity', '1.4.3', '0', '0', '0'),
(19, 'Current Liabilities', 'Account Payables', '1.4.1.1', '1', '0', '0'),
(20, 'Current Liabilities', 'Bank Loans', '1.4.1.2', '0', '0', '0'),
(21, 'Current Liabilities', 'Capital Account', '1.4.1.3', '0', '0', '0'),
(22, 'Current Liabilities', 'Current Tax Liabilities', '1.4.1.4', '0', '0', '0'),
(23, 'Current Liabilities', 'Other Payables', '1.4.1.5', '1', '0', '0'),
(24, 'Current Liabilities', 'Suspense Current', '1.4.1.6', '0', '0', '0'),
(25, 'Current Liabilities', 'VAT Payable', '1.4.1.7', '0', '0', '0'),
(26, 'Non Current Liabilities', 'Provisions', '1.4.2.3', '0', '0', '0'),
(27, 'Non Current Liabilities', 'Suspense Non Current', '1.4.2.4', '0', '0', '0'),
(28, 'Non Current Liabilities', 'General Suspense', '1.4.2.1', '0', '0', '0'),
(29, 'Non Current Liabilities', 'Payables Non Current', '1.4.2.2', '0', '0', '0'),
(30, 'Inventories', 'Stock-in-Hand', '1.1.1.4.1', '0', '0', '0'),
(31, 'Cash and Bank Balances', 'Cash-in-Hand', '1.1.1.3.3', '0', '0', '0'),
(32, 'Cash and Bank Balances', 'Cash-at-Bank', '1.1.1.3.2', '0', '0', '0'),
(33, 'Cash and Bank Balances', 'Bank Deposit', '1.1.1.3.1', '0', '0', '0'),
(34, 'Property Plants and Equipments', 'Fixtures and Fittings', '1.1.2.3.1', '0', '0', '0'),
(35, 'Property Plants and Equipments', 'Motor Vehicles', '1.1.2.3.2', '0', '0', '0'),
(36, 'Property Plants and Equipments', 'Plant and Machinery', '1.1.2.3.4', '0', '0', '0'),
(37, 'Property Plants and Equipments', 'Property', '1.1.2.3.5', '0', '0', '0'),
(38, 'Property Plants and Equipments', 'Office Equipment', '1.1.2.3.3', '0', '0', '0'),
(39, 'Equity', 'Opening Profit & Loss Account', '1.4.3.1', '0', '0', '0'),
(40, 'Equity', 'Profit & Loss Account', '1.4.3.2', '0', '0', '0'),
(41, 'Income', 'Operating Revenue', '1.3.1', '0', '0', '0'),
(42, 'Income', 'Other Income', '1.3.2', '0', '0', '0'),
(43, 'Operating Revenue', 'Sales Account', '1.3.1.1', '0', '0', '0'),
(44, 'Operating Revenue', 'Sales Return', '1.3.1.2', '0', '0', '0'),
(45, 'Other Income', 'Commissions Received', '1.3.2.1', '0', '0', '0'),
(46, 'Other Income', 'Discount Received', '1.3.2.2', '0', '0', '0'),
(47, 'Other Income', 'Insurance Claims', '1.3.2.3', '0', '0', '0'),
(48, 'Other Income', 'Interest Received', '1.3.2.4', '0', '0', '0'),
(49, 'Other Income', 'Miscellaneous Income', '1.3.2.5', '0', '0', '0'),
(50, 'Expenses', 'Cost of goods sold', '1.2.1', '0', '0', '0'),
(51, 'Expenses', 'Direct Expenses', '1.2.2', '0', '0', '0'),
(52, 'Cost of goods sold', 'Opening Stock', '1.2.1.1', '0', '0', '0'),
(53, 'Cost of goods sold', 'Purchase Account', '1.2.1.2', '0', '0', '0'),
(54, 'Cost of goods sold', 'Purchase Return', '1.2.1.3', '0', '0', '0'),
(55, 'Cost of goods sold', 'Closing Stock', '1.2.1.4', '0', '0', '0'),
(56, 'Cost of goods sold', 'Manufacturing Overheads', '1.2.1.5', '0', '0', '0'),
(57, 'Direct Expenses', 'Admin Honnarium Expenses', '1.2.2.1', '0', '0', '0'),
(58, 'Direct Expenses', 'Administration Overheads', '1.2.2.2', '0', '0', '0'),
(59, 'Direct Expenses', 'Commission Expenses', '1.2.2.3', '0', '0', '0'),
(60, 'Direct Expenses', 'Depreciation', '1.2.2.4', '0', '0', '0'),
(61, 'Direct Expenses', 'Discount Payment', '1.2.2.5', '0', '0', '0'),
(62, 'Direct Expenses', 'General Expenses', '1.2.2.6', '0', '0', '0'),
(63, 'Direct Expenses', 'Interest Expenses', '1.2.2.7', '0', '0', '0'),
(64, 'Direct Expenses', 'Staff Salary', '1.2.2.8', '0', '0', '0'),
(65, 'Expenses', 'Indirect Expenses', '1.2.1', '0', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `acc_grouplevel`
--

CREATE TABLE `acc_grouplevel` (
  `_level1` varchar(60) DEFAULT NULL,
  `_level2` varchar(60) DEFAULT NULL,
  `_level3` varchar(60) DEFAULT NULL,
  `_level4` varchar(60) DEFAULT NULL,
  `_level5` varchar(60) DEFAULT NULL,
  `_level6` varchar(60) DEFAULT NULL,
  `_level7` varchar(60) DEFAULT NULL,
  `_level8` varchar(60) DEFAULT NULL,
  `_level9` varchar(60) DEFAULT NULL,
  `_group` varchar(60) DEFAULT NULL,
  `_sort` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_history`
--

CREATE TABLE `acc_history` (
  `_id` int(11) NOT NULL,
  `_date` datetime NOT NULL,
  `_type` varchar(20) NOT NULL,
  `_reference` varchar(30) NOT NULL,
  `_costcenter` varchar(60) DEFAULT NULL,
  `_group` varchar(60) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_dr_amount` decimal(15,4) NOT NULL,
  `_cr_amount` decimal(15,4) NOT NULL,
  `_narration` varchar(200) NOT NULL,
  `_user` varchar(15) NOT NULL,
  `_action` varchar(6) NOT NULL,
  `_actiontime` timestamp NOT NULL DEFAULT current_timestamp(),
  `_actioncount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_ledger`
--

CREATE TABLE `acc_ledger` (
  `_id` int(11) NOT NULL,
  `_group` varchar(60) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_address` varchar(150) DEFAULT NULL,
  `_phone` varchar(100) DEFAULT NULL,
  `_email` varchar(30) DEFAULT NULL,
  `_credit_limit` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_balance` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acc_ledger`
--

INSERT INTO `acc_ledger` (`_id`, `_group`, `_ledger`, `_address`, `_phone`, `_email`, `_credit_limit`, `_balance`, `_user`) VALUES
(1, 'Cash-in-Hand', 'Cash', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(2, 'Purchase Account', 'Purchase', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(3, 'Purchase Return', 'Purchase Return', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(4, 'Sales Account', 'Sales', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(5, 'Sales Return', 'Sales Return', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(6, 'Discount Received', 'Purchase Discount', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(7, 'Discount Payment', 'Sales Discount', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(8, 'Commission Expenses', 'Commission on sales', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(9, 'VAT Payable', 'Sales Vat', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(10, 'Administration Overheads', 'Conveyance', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(11, 'Administration Overheads', 'Electricity, Gas & Water Bill', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(12, 'Administration Overheads', 'Cleaning Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(13, 'Administration Overheads', 'Repair & Maintenance', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(14, 'Administration Overheads', 'Entertainment Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(15, 'Administration Overheads', 'Printing Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(16, 'Administration Overheads', 'Office Stationery Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(17, 'Administration Overheads', 'Postage & Courier Bill', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(18, 'Administration Overheads', 'Phone, Mobile & Internet Bill', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(19, 'Administration Overheads', 'Travelling Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(20, 'Administration Overheads', 'License Renewal Fee', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(21, 'Administration Overheads', 'Consultancy & Audit Fees', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(22, 'Administration Overheads', 'Office Rent', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(23, 'Administration Overheads', 'Delegate Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(24, 'Administration Overheads', 'Fuel & Oil Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(25, 'Administration Overheads', 'Insurance Premium', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(26, 'Administration Overheads', 'Miscellaneous Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(27, 'Administration Overheads', 'Medical Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(28, 'Administration Overheads', 'Business Promotional Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(29, 'Administration Overheads', 'Other Service Charge', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(30, 'Administration Overheads', 'Donation Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(31, 'Administration Overheads', 'Office Fooding Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(32, 'Administration Overheads', 'Marketing Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(33, 'Administration Overheads', 'Other Office Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(34, 'Administration Overheads', 'News Paper & Cable-Tv Bill', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(35, 'Administration Overheads', 'Tax & VAT Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(36, 'Administration Overheads', 'Legal Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(37, 'Administration Overheads', 'Remuneration Expenses', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(38, 'Administration Overheads', 'Bank Charges', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(39, 'General Expenses', 'Rounding Off', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(40, 'Miscellaneous Income', 'Rounding Add', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(41, 'Indirect Expenses', 'Basic Pay', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(42, 'Indirect Expenses', 'House Rent', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator'),
(43, 'Indirect Expenses', 'Special Allowance', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(44, 'Indirect Expenses', 'Medical Allowance', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(45, 'Indirect Expenses', 'LFA', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(46, 'Indirect Expenses', 'Festival Bonus', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(47, 'Indirect Expenses', 'Performance Bonus', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(48, 'Indirect Expenses', 'Sales Incentive', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(49, 'Indirect Expenses', 'Leave Encashment', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(50, 'Indirect Expenses', 'Other Allowance', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(51, 'Indirect Expenses', 'Gross Salary', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(52, 'Indirect Expenses', 'ITAX', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(53, 'Indirect Expenses', 'Mobile Recovery', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(54, 'Indirect Expenses', 'Miscellaneous Deduction', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(55, 'Indirect Expenses', 'Salary Advance', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(56, 'Indirect Expenses', 'Notice Deduction', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(57, 'Indirect Expenses', 'Gross Deduction', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(58, 'Indirect Expenses', 'Net Pay', NULL, NULL, NULL, '0.0000', '0.0000', 'admin'),
(60, 'Indirect Expenses', 'Conveyance Allowance', NULL, NULL, NULL, '0.0000', '0.0000', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `acc_master`
--

CREATE TABLE `acc_master` (
  `_code` int(11) NOT NULL,
  `_group` varchar(60) NOT NULL,
  `_ledger` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acc_master`
--

INSERT INTO `acc_master` (`_code`, `_group`, `_ledger`) VALUES
(1013, 'Account Payables', NULL),
(1012, 'Account Receivables', NULL),
(1002, 'Cash-at-Bank', NULL),
(1001, 'Cash-in-Hand', 'Cash'),
(1015, 'Closing Stock', NULL),
(1018, 'Cost of goods sold', NULL),
(1011, 'Discount Payment', 'Sales Discount'),
(1010, 'Discount Received', 'Purchase Discount'),
(1019, 'General Expenses', 'Rounding Off'),
(1020, 'Miscellaneous Income', 'Rounding Add'),
(1003, 'Opening Profit & Loss Account', NULL),
(1014, 'Opening Stock', NULL),
(1017, 'Operating Revenue', NULL),
(1004, 'Profit & Loss Account', NULL),
(1006, 'Purchase Account', 'Purchase'),
(1008, 'Purchase Return', 'Purchase Return'),
(1007, 'Sales Account', 'Sales'),
(1009, 'Sales Return', 'Sales Return'),
(1005, 'Stock-in-Hand', NULL),
(1016, 'VAT Payable', 'Sales Vat');

-- --------------------------------------------------------

--
-- Table structure for table `acc_voucher`
--

CREATE TABLE `acc_voucher` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_type` varchar(20) NOT NULL,
  `_reference` varchar(30) NOT NULL,
  `_costcenter` varchar(60) DEFAULT NULL,
  `_narration` varchar(200) NOT NULL,
  `_user` varchar(15) NOT NULL,
  `_verify` varchar(200) DEFAULT NULL,
  `_noedit` int(11) NOT NULL DEFAULT 0,
  `_entry_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acc_voucher`
--

INSERT INTO `acc_voucher` (`_id`, `_date`, `_type`, `_reference`, `_costcenter`, `_narration`, `_user`, `_verify`, `_noedit`, `_entry_time`) VALUES
(1, '2012-01-01', 'JV - Journal Voucher', 'N/A', NULL, 'Opening Balance', 'admin', NULL, 0, '2012-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `acc_voucherdetail`
--

CREATE TABLE `acc_voucherdetail` (
  `_id` int(11) NOT NULL,
  `_voucher_id` int(11) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_costcenter` varchar(60) DEFAULT NULL,
  `_shortnarr` varchar(200) DEFAULT NULL,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_entry_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `acc_voucherdetail`
--
DELIMITER $$
CREATE TRIGGER `acc_voucherdetail_delete` AFTER DELETE ON `acc_voucherdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - (old._dr_amount - old._cr_amount)) WHERE `_ledger` = old._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `acc_voucherdetail_insert` AFTER INSERT ON `acc_voucherdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + (new._dr_amount - new._cr_amount)) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `acc_voucherdetail_update` AFTER UPDATE ON `acc_voucherdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - (old._dr_amount - old._cr_amount)) WHERE `_ledger` = old._ledger;
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + (new._dr_amount - new._cr_amount)) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cus_assignleave`
--

CREATE TABLE `cus_assignleave` (
  `_id` int(11) NOT NULL,
  `_employee` int(11) NOT NULL DEFAULT 0,
  `_type` int(11) NOT NULL DEFAULT 0,
  `_balance` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_start` date DEFAULT NULL,
  `_end` date DEFAULT NULL,
  `_note` longtext DEFAULT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_assignleave`
--

INSERT INTO `cus_assignleave` (`_id`, `_employee`, `_type`, `_balance`, `_start`, `_end`, `_note`, `_user`) VALUES
(1, 2, 2, '7.0000', '2020-11-25', '2020-11-25', '', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cus_attendance`
--

CREATE TABLE `cus_attendance` (
  `_id` int(11) NOT NULL,
  `_employee` int(11) DEFAULT NULL,
  `_number` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_datetime` timestamp NULL DEFAULT NULL,
  `_user` varchar(60) NOT NULL,
  `_type` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_attendance`
--

INSERT INTO `cus_attendance` (`_id`, `_employee`, `_number`, `_datetime`, `_user`, `_type`) VALUES
(1, 3, '1001.0000', '2019-10-08 19:40:00', 'admin', 'In'),
(2, 3, '1001.0000', '2019-10-08 19:40:00', 'admin', 'Out'),
(3, 3, '1001.0000', '2020-11-24 09:15:00', 'admin', 'In'),
(4, 3, '1001.0000', '2020-11-24 09:15:00', 'admin', 'Out');

-- --------------------------------------------------------

--
-- Table structure for table `cus_attendproduct`
--

CREATE TABLE `cus_attendproduct` (
  `_id` int(11) NOT NULL,
  `_type` varchar(50) NOT NULL,
  `_unit` int(11) NOT NULL DEFAULT 0,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_costcenter`
--

CREATE TABLE `cus_costcenter` (
  `_id` int(11) NOT NULL,
  `_code` varchar(20) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_department`
--

CREATE TABLE `cus_department` (
  `_id` int(11) NOT NULL,
  `_department` varchar(50) NOT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_department`
--

INSERT INTO `cus_department` (`_id`, `_department`, `_user`) VALUES
(1, 'Accounting', 'Administrator'),
(2, 'Marketing', 'admin'),
(3, 'Client Relation', 'admin'),
(4, 'Admin & HRM', 'admin'),
(5, 'Accounts & Finance', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cus_education`
--

CREATE TABLE `cus_education` (
  `_id` int(11) NOT NULL,
  `_level` varchar(50) NOT NULL,
  `_subject` varchar(50) NOT NULL,
  `_institute` varchar(100) NOT NULL,
  `_year` varchar(4) DEFAULT NULL,
  `_score` varchar(20) NOT NULL,
  `_edsdate` date DEFAULT NULL,
  `_ededate` date DEFAULT NULL,
  `_user` varchar(60) NOT NULL,
  `_education` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_emergency`
--

CREATE TABLE `cus_emergency` (
  `_id` int(11) NOT NULL,
  `_name` varchar(60) NOT NULL,
  `_relationship` varchar(50) NOT NULL,
  `_mobile` varchar(20) NOT NULL,
  `_home` varchar(20) NOT NULL,
  `_work` varchar(20) NOT NULL,
  `_user` varchar(60) NOT NULL,
  `_emergency` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_empaddress`
--

CREATE TABLE `cus_empaddress` (
  `_id` int(11) NOT NULL,
  `_type` varchar(20) NOT NULL,
  `_address` varchar(200) NOT NULL,
  `_post` varchar(50) NOT NULL,
  `_police` varchar(50) NOT NULL,
  `_district` varchar(50) NOT NULL,
  `_eaddress` int(11) NOT NULL DEFAULT 0,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_empaddress`
--

INSERT INTO `cus_empaddress` (`_id`, `_type`, `_address`, `_post`, `_police`, `_district`, `_eaddress`, `_user`) VALUES
(1, 'Present & Permanent', '5, Shakh Para', 'Khulna', 'Khulna', 'Dhaka', 1, 'Administrator'),
(2, 'Present & Permanent', '5, Shakh Para', 'Khulna', 'Khulna', 'Khulna', 2, 'Administrator'),
(3, 'Present', 'Vill: 19/C,Golapbag, P/O: Wari(Dhaka-1203)', 'Wari', 'Wari', 'Dhaka', 3, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cus_employees`
--

CREATE TABLE `cus_employees` (
  `_id` int(11) NOT NULL,
  `_number` varchar(30) NOT NULL,
  `_photo` int(11) DEFAULT NULL,
  `_name` varchar(60) NOT NULL,
  `_father` varchar(60) NOT NULL,
  `_mother` varchar(60) NOT NULL,
  `_spouse` varchar(60) NOT NULL,
  `_user` varchar(60) NOT NULL,
  `_mobile1` varchar(15) NOT NULL,
  `_mobile2` varchar(15) NOT NULL,
  `_spousesmobile` varchar(15) NOT NULL,
  `_nid` varchar(30) NOT NULL,
  `_gender` varchar(10) NOT NULL,
  `_bloodgroup` varchar(3) NOT NULL,
  `_religion` varchar(50) NOT NULL,
  `_dob` date DEFAULT NULL,
  `_education` varchar(30) NOT NULL,
  `_email` varchar(50) NOT NULL,
  `_jobtitle` int(11) DEFAULT NULL,
  `_department` int(11) DEFAULT NULL,
  `_grade` int(11) DEFAULT NULL,
  `_location` varchar(60) NOT NULL,
  `_officedes` varchar(120) NOT NULL,
  `_bank` varchar(100) NOT NULL,
  `_bankac` varchar(40) NOT NULL,
  `_tradeing` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_project` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_costcenter` int(11) DEFAULT NULL,
  `_profitcenter` int(11) DEFAULT NULL,
  `_active` varchar(1) NOT NULL DEFAULT '1',
  `_doj` date DEFAULT NULL,
  `_tin` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_employees`
--

INSERT INTO `cus_employees` (`_id`, `_number`, `_photo`, `_name`, `_father`, `_mother`, `_spouse`, `_user`, `_mobile1`, `_mobile2`, `_spousesmobile`, `_nid`, `_gender`, `_bloodgroup`, `_religion`, `_dob`, `_education`, `_email`, `_jobtitle`, `_department`, `_grade`, `_location`, `_officedes`, `_bank`, `_bankac`, `_tradeing`, `_project`, `_costcenter`, `_profitcenter`, `_active`, `_doj`, `_tin`) VALUES
(1, '10001', NULL, 'Md. Alamgir Hossain', 'Md. Abul Hossain', 'Momtaz', '', 'Administrator', '01711066089', '', '', '12673618263', 'Male', 'AB+', 'Muslim', '1983-11-28', 'BBA', 'goodakas@gmail.com', 3, 1, 0, '', '', '', '', '0.0000', '0.0000', 0, 0, '1', NULL, ''),
(2, '10002', NULL, 'Md. Alamgir Hossain', 'Md. Abul Hossain', 'Momtaz', '', 'Administrator', '01711066089', '', '', '12673618263', 'Male', 'AB+', 'Muslim', '1983-11-28', 'BBA', 'goodakas@ymail.com', 1, 1, 0, '', '', '', '', '0.0000', '0.0000', 0, 0, '1', NULL, ''),
(3, '0001001', NULL, 'Khaled Mohammed Ismial', 'Md Sha Alam Chowdhury', 'Kodeza Akter', 'Sarmin Akter', 'Administrator', '01916567580', '01916567581', '019162177302', '7522108075311', 'Male', 'B+', 'Islam', '1977-01-11', '1st Division', 'findismile@gmail.com', 4, 1, 0, '', '', '', '', '0.0000', '0.0000', 0, 0, '1', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `cus_experience`
--

CREATE TABLE `cus_experience` (
  `_id` int(11) NOT NULL,
  `_company` varchar(100) NOT NULL,
  `_jobtitle` int(11) DEFAULT NULL,
  `_wfrom` date DEFAULT NULL,
  `_wto` date DEFAULT NULL,
  `_note` longtext DEFAULT NULL,
  `_user` varchar(60) NOT NULL,
  `_experience` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_grade`
--

CREATE TABLE `cus_grade` (
  `_id` int(11) NOT NULL,
  `_grade` varchar(20) NOT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_guarantor`
--

CREATE TABLE `cus_guarantor` (
  `_id` int(11) NOT NULL,
  `_name` varchar(60) NOT NULL,
  `_father` varchar(60) NOT NULL,
  `_mother` varchar(60) NOT NULL,
  `_occupation` varchar(60) NOT NULL,
  `_workstation` varchar(100) NOT NULL,
  `_address1` varchar(200) NOT NULL,
  `_address2` varchar(200) NOT NULL,
  `_mobile` varchar(30) NOT NULL,
  `_email` varchar(100) NOT NULL,
  `_user` varchar(60) NOT NULL,
  `_nationalid` varchar(50) NOT NULL,
  `_guarantor` int(11) NOT NULL DEFAULT 0,
  `_photo` longtext DEFAULT NULL,
  `_dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_holidaydetail`
--

CREATE TABLE `cus_holidaydetail` (
  `_id` int(11) NOT NULL,
  `_name` varchar(60) NOT NULL,
  `_date` date DEFAULT NULL,
  `_type` varchar(10) NOT NULL,
  `_user` varchar(60) NOT NULL,
  `_holidaysid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_holidaydetail`
--

INSERT INTO `cus_holidaydetail` (`_id`, `_name`, `_date`, `_type`, `_user`, `_holidaysid`) VALUES
(1, 'International Mother Language Day', '2018-02-21', 'Full', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cus_holidays`
--

CREATE TABLE `cus_holidays` (
  `_id` int(11) NOT NULL,
  `_dfrom` date DEFAULT NULL,
  `_dto` date DEFAULT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_holidays`
--

INSERT INTO `cus_holidays` (`_id`, `_dfrom`, `_dto`, `_user`) VALUES
(1, '2018-01-01', '2018-12-31', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `cus_itaxconfig`
--

CREATE TABLE `cus_itaxconfig` (
  `_id` int(11) NOT NULL,
  `_maxinvestment` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_itaxconfig`
--

INSERT INTO `cus_itaxconfig` (`_id`, `_maxinvestment`, `_user`) VALUES
(1, '25.0000', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `cus_itaxledger`
--

CREATE TABLE `cus_itaxledger` (
  `_id` int(11) NOT NULL,
  `_ledger` int(11) DEFAULT NULL,
  `_exlimit` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(60) NOT NULL,
  `_ledgerno` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_itaxledger`
--

INSERT INTO `cus_itaxledger` (`_id`, `_ledger`, `_exlimit`, `_user`, `_ledgerno`) VALUES
(1, 41, '0.0000', 'Administrator', 1),
(2, 42, '300000.0000', 'Administrator', 1),
(3, 60, '30000.0000', 'Administrator', 1),
(4, 44, '120000.0000', 'Administrator', 1),
(5, 45, '660000.0000', 'Administrator', 1),
(6, 46, '0.0000', 'Administrator', 1),
(7, 47, '0.0000', 'Administrator', 1),
(8, 50, '720000.0000', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cus_itaxpayable`
--

CREATE TABLE `cus_itaxpayable` (
  `_id` int(11) NOT NULL,
  `_plevel` varchar(2) NOT NULL,
  `_plimit` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_ppercent` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(60) NOT NULL,
  `_payableno` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_itaxpayable`
--

INSERT INTO `cus_itaxpayable` (`_id`, `_plevel`, `_plimit`, `_ppercent`, `_user`, `_payableno`) VALUES
(1, '1', '250000.0000', '0.0000', 'Administrator', 1),
(2, '2', '400000.0000', '10.0000', 'Administrator', 1),
(3, '3', '500000.0000', '15.0000', 'Administrator', 1),
(4, '4', '600000.0000', '20.0000', 'Administrator', 1),
(5, '5', '3000000.0000', '25.0000', 'Administrator', 1),
(6, '6', '10000000.0000', '30.0000', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cus_itaxrebate`
--

CREATE TABLE `cus_itaxrebate` (
  `_id` int(11) NOT NULL,
  `_rlimit` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rpercent` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rlevel` varchar(2) NOT NULL,
  `_user` varchar(60) NOT NULL,
  `_rebateno` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_itaxrebate`
--

INSERT INTO `cus_itaxrebate` (`_id`, `_rlimit`, `_rpercent`, `_rlevel`, `_user`, `_rebateno`) VALUES
(1, '250000.0000', '15.0000', '1', 'Administrator', 1),
(2, '500000.0000', '12.0000', '2', 'Administrator', 1),
(3, '10000000.0000', '10.0000', '3', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cus_job`
--

CREATE TABLE `cus_job` (
  `_id` int(11) NOT NULL,
  `_jobtitle` int(11) DEFAULT NULL,
  `_specification` longtext DEFAULT NULL,
  `_status` varchar(50) NOT NULL,
  `_joindate` date DEFAULT NULL,
  `_location` varchar(70) NOT NULL,
  `_responsibility` varchar(60) NOT NULL,
  `_salary` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(60) NOT NULL,
  `_job` int(11) NOT NULL DEFAULT 0,
  `_jtype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_jobcontract`
--

CREATE TABLE `cus_jobcontract` (
  `_id` int(11) NOT NULL,
  `_ctype` varchar(60) NOT NULL,
  `_csdate` date DEFAULT NULL,
  `_cedate` date DEFAULT NULL,
  `_cdetail` longtext DEFAULT NULL,
  `_user` varchar(60) NOT NULL,
  `_jobcontract` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_jobtitle`
--

CREATE TABLE `cus_jobtitle` (
  `_id` int(11) NOT NULL,
  `_title` varchar(30) NOT NULL,
  `_specification` longtext DEFAULT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_jobtitle`
--

INSERT INTO `cus_jobtitle` (`_id`, `_title`, `_specification`, `_user`) VALUES
(1, 'CTO', 'Test', 'Administrator'),
(2, 'GM', '', 'Administrator'),
(3, 'MD', '', 'Administrator'),
(4, 'Senior Account Officer', 'Senior Account Officer', 'admin'),
(5, 'CEO', 'Chief Execute Officer', 'admin'),
(6, 'COO', 'Chief Operating Officer', 'admin'),
(7, 'CTO', 'Chief Technology Officer', 'admin'),
(8, 'Junior Manager', 'Junior Manager', 'admin'),
(9, 'Senior Manager', 'Senior Manager', 'admin'),
(10, 'Manager', '', 'admin'),
(11, 'Chairman', '', 'admin'),
(12, 'Junior Software Engineer', '', 'admin'),
(13, 'Senior Software Engineer', '', 'admin'),
(14, 'Software Engineer', '', 'admin'),
(15, 'Software Developer', '', 'admin'),
(16, 'Junior Programmer', '', 'admin'),
(17, 'Senior Programmer', '', 'admin'),
(18, 'Junior System Annalist', '', 'admin'),
(19, 'Senior System Annalist', '', 'admin'),
(20, 'System Annalist', '', 'admin'),
(21, 'Executive Marketing', '', 'admin'),
(22, 'Executive Sales', '', 'admin'),
(23, 'Executive Director', '', 'admin'),
(24, 'Executive Sales & Marketing', '', 'admin'),
(25, 'Executive Marketing & Client R', '', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cus_language`
--

CREATE TABLE `cus_language` (
  `_id` int(11) NOT NULL,
  `_language` varchar(30) NOT NULL,
  `_fluency` varchar(20) NOT NULL,
  `_competency` varchar(20) NOT NULL,
  `_lnote` longtext DEFAULT NULL,
  `_user` varchar(60) NOT NULL,
  `_languageid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_language`
--

INSERT INTO `cus_language` (`_id`, `_language`, `_fluency`, `_competency`, `_lnote`, `_user`, `_languageid`) VALUES
(1, 'Bangla', 'Speaking', 'Mother Tongue', '', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `cus_leavebalance`
-- (See below for the actual view)
--
CREATE TABLE `cus_leavebalance` (
`_id` int(11)
,`_eid` int(11)
,`_ltype` varchar(50)
,`_lday` decimal(15,4)
);

-- --------------------------------------------------------

--
-- Table structure for table `cus_leaveentitlement`
--

CREATE TABLE `cus_leaveentitlement` (
  `_id` int(11) NOT NULL,
  `_lemployid` longtext DEFAULT NULL,
  `_ltype` int(11) NOT NULL DEFAULT 0,
  `_lday` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_period` int(11) NOT NULL DEFAULT 0,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_leaveentitlement`
--

INSERT INTO `cus_leaveentitlement` (`_id`, `_lemployid`, `_ltype`, `_lday`, `_period`, `_user`) VALUES
(1, '1', 4, '7.0000', 1, 'Administrator'),
(2, '1,2', 1, '7.0000', 1, 'Administrator'),
(3, '1,2', 2, '7.0000', 1, 'Administrator'),
(4, '1,2', 3, '7.0000', 1, 'Administrator'),
(5, '2', 4, '6.0000', 1, 'Administrator'),
(6, '3', 1, '1.0000', 1, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cus_leavetypes`
--

CREATE TABLE `cus_leavetypes` (
  `_id` int(11) NOT NULL,
  `_type` varchar(50) NOT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_leavetypes`
--

INSERT INTO `cus_leavetypes` (`_id`, `_type`, `_user`) VALUES
(1, 'Annual Leave', 'Administrator'),
(2, 'Casual Leave', 'Administrator'),
(3, 'Funeral Leave', 'Administrator'),
(4, 'Sick Leave', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `cus_nominee`
--

CREATE TABLE `cus_nominee` (
  `_id` int(11) NOT NULL,
  `_nname` varchar(60) NOT NULL,
  `_nfather` varchar(60) NOT NULL,
  `_nmother` varchar(60) NOT NULL,
  `_ndob` date DEFAULT NULL,
  `_nnationalid` varchar(50) NOT NULL,
  `_nmobile` varchar(50) NOT NULL,
  `_naddress1` varchar(200) NOT NULL,
  `_naddress2` varchar(200) NOT NULL,
  `_nrelation` varchar(50) NOT NULL,
  `_nbenefit` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(60) NOT NULL,
  `_nominee` int(11) NOT NULL DEFAULT 0,
  `_nphoto` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_payheads`
--

CREATE TABLE `cus_payheads` (
  `_id` int(11) NOT NULL,
  `_ledger` int(11) NOT NULL DEFAULT 0,
  `_type` varchar(10) NOT NULL,
  `_calculation` varchar(20) NOT NULL,
  `_user` varchar(60) NOT NULL,
  `_onhead` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_payheads`
--

INSERT INTO `cus_payheads` (`_id`, `_ledger`, `_type`, `_calculation`, `_user`, `_onhead`) VALUES
(1, 41, 'P', 'Fixed', 'admin', ''),
(2, 42, 'P', 'Fixed', 'Administrator', ''),
(3, 60, 'P', 'Fixed', 'Administrator', ''),
(4, 43, 'P', 'Fixed', 'Administrator', ''),
(5, 44, 'P', 'Fixed', 'Administrator', ''),
(6, 45, 'P', 'Fixed', 'Administrator', ''),
(7, 46, 'P', 'Fixed', 'Administrator', ''),
(8, 47, 'P', 'Fixed', 'Administrator', ''),
(9, 48, 'P', 'Variable', 'Administrator', ''),
(10, 49, 'P', 'Variable', 'Administrator', ''),
(11, 50, 'P', 'Variable', 'Administrator', ''),
(12, 52, 'D', 'Itax', 'Administrator', ''),
(13, 53, 'D', 'Variable', 'Administrator', ''),
(14, 54, 'D', 'Variable', 'Administrator', ''),
(15, 55, 'D', 'Variable', 'Administrator', ''),
(16, 56, 'D', 'Variable', 'Administrator', '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `cus_payheadview`
-- (See below for the actual view)
--
CREATE TABLE `cus_payheadview` (
`_id` int(11)
,`_ledger` varchar(60)
,`_type` varchar(10)
,`_calculation` varchar(20)
,`_onhead` longtext
);

-- --------------------------------------------------------

--
-- Table structure for table `cus_payinfo`
--

CREATE TABLE `cus_payinfo` (
  `_id` int(11) NOT NULL,
  `_ledger` int(11) DEFAULT NULL,
  `_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_period` varchar(10) NOT NULL,
  `_effect` enum('1','0') NOT NULL DEFAULT '1',
  `_empid` int(11) NOT NULL DEFAULT 0,
  `_payheads` int(11) NOT NULL DEFAULT 0,
  `_payinfo` int(11) NOT NULL DEFAULT 0,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_payinfo`
--

INSERT INTO `cus_payinfo` (`_id`, `_ledger`, `_amount`, `_period`, `_effect`, `_empid`, `_payheads`, `_payinfo`, `_user`) VALUES
(1, 1, '5000.0000', 'Monthly', '1', 0, 0, 1, 'Administrator'),
(2, 41, '16795.0000', 'Monthly', '1', 0, 0, 3, 'Administrator'),
(3, 42, '8397.0000', 'Monthly', '1', 0, 0, 3, 'Administrator'),
(4, 60, '2500.0000', 'Monthly', '1', 0, 0, 3, 'Administrator'),
(5, 43, '1372.0000', 'Monthly', '1', 0, 0, 3, 'Administrator'),
(6, 44, '1600.0000', 'Monthly', '1', 0, 0, 3, 'Administrator'),
(7, 45, '3000.0000', 'Monthly', '1', 0, 0, 3, 'Administrator'),
(8, 41, '10000.0000', 'Monthly', '1', 0, 0, 2, 'Administrator'),
(9, 52, '0.0000', 'Monthly', '1', 0, 0, 3, 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `cus_payunit`
--

CREATE TABLE `cus_payunit` (
  `_id` int(11) NOT NULL,
  `_unit` varchar(10) NOT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_payunit`
--

INSERT INTO `cus_payunit` (`_id`, `_unit`, `_user`) VALUES
(1, 'Pcs', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `cus_profitcenter`
--

CREATE TABLE `cus_profitcenter` (
  `_id` int(11) NOT NULL,
  `_code` varchar(20) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_recruitment`
--

CREATE TABLE `cus_recruitment` (
  `_id` int(11) NOT NULL,
  `_name` varchar(60) NOT NULL,
  `_contactno` varchar(20) NOT NULL,
  `_email` varchar(100) NOT NULL,
  `_user` varchar(60) NOT NULL,
  `_vacancy` int(11) NOT NULL DEFAULT 0,
  `_resume` longtext DEFAULT NULL,
  `_comment` longtext DEFAULT NULL,
  `_date` date DEFAULT NULL,
  `_qualification` varchar(50) NOT NULL,
  `_experience` varchar(200) NOT NULL,
  `_salary` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_selected` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_reward`
--

CREATE TABLE `cus_reward` (
  `_id` int(11) NOT NULL,
  `_rcategory` varchar(30) NOT NULL,
  `_rtype` varchar(100) NOT NULL,
  `_rcause` varchar(100) NOT NULL,
  `_rnote` longtext DEFAULT NULL,
  `_user` varchar(60) NOT NULL,
  `_rewardid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_salarystructure`
--

CREATE TABLE `cus_salarystructure` (
  `_id` int(11) NOT NULL,
  `_empcode` int(11) DEFAULT NULL,
  `_month` varchar(2) NOT NULL,
  `_year` int(11) DEFAULT NULL,
  `_paydays` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_arrdays` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(60) NOT NULL,
  `_verify` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_salarystructure`
--

INSERT INTO `cus_salarystructure` (`_id`, `_empcode`, `_month`, `_year`, `_paydays`, `_arrdays`, `_user`, `_verify`) VALUES
(14, 1, '6', 2019, '0.0000', '0.0000', 'Administrator', NULL),
(15, 2, '6', 2019, '0.0000', '0.0000', 'Administrator', NULL),
(16, 3, '6', 2019, '1.0000', '0.0000', 'Administrator', NULL),
(17, 1, '9', 2019, '0.0000', '0.0000', 'admin', NULL),
(18, 2, '9', 2019, '0.0000', '0.0000', 'admin', NULL),
(19, 3, '9', 2019, '0.0000', '0.0000', 'admin', NULL),
(20, 1, '9', 2019, '30.0000', '0.0000', 'admin', NULL),
(21, 1, '11', 2020, '0.0000', '0.0000', 'admin', NULL),
(22, 2, '11', 2020, '0.0000', '0.0000', 'admin', NULL),
(23, 3, '11', 2020, '0.0000', '0.0000', 'admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cus_sstructuredetails`
--

CREATE TABLE `cus_sstructuredetails` (
  `_id` int(11) NOT NULL,
  `_ledger` int(11) DEFAULT NULL,
  `_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(60) NOT NULL,
  `_structureno` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_sstructuredetails`
--

INSERT INTO `cus_sstructuredetails` (`_id`, `_ledger`, `_amount`, `_user`, `_structureno`) VALUES
(2, 1, '5000.0000', 'Administrator', 5),
(3, 41, '16795.0000', 'Administrator', 7),
(4, 42, '8397.0000', 'Administrator', 7),
(5, 60, '2500.0000', 'Administrator', 7),
(6, 43, '1372.0000', 'Administrator', 7),
(7, 44, '1600.0000', 'Administrator', 7),
(8, 45, '3000.0000', 'Administrator', 7),
(9, 41, '10000.0000', 'Administrator', 6),
(10, 1, '5000.0000', 'Administrator', 14),
(11, 41, '10000.0000', 'Administrator', 15),
(12, 41, '167950.0000', 'Administrator', 16),
(13, 42, '8397.0000', 'Administrator', 16),
(14, 60, '2500.0000', 'Administrator', 16),
(15, 43, '1372.0000', 'Administrator', 16),
(16, 44, '1600.0000', 'Administrator', 16),
(17, 45, '3000.0000', 'Administrator', 16),
(18, 52, '0.0000', 'Administrator', 16),
(19, 1, '5000.0000', 'admin', 17),
(20, 41, '10000.0000', 'admin', 18),
(21, 41, '16795.0000', 'admin', 19),
(22, 42, '8397.0000', 'admin', 19),
(23, 60, '2500.0000', 'admin', 19),
(24, 43, '1372.0000', 'admin', 19),
(25, 44, '1600.0000', 'admin', 19),
(26, 45, '3000.0000', 'admin', 19),
(27, 52, '0.0000', 'admin', 19),
(28, 41, '50000.0000', 'admin', 20),
(29, 1, '5000.0000', 'admin', 21),
(30, 41, '16795.0000', 'admin', 23),
(31, 42, '8397.0000', 'admin', 23),
(32, 60, '2500.0000', 'admin', 23),
(33, 43, '1372.0000', 'admin', 23),
(34, 44, '1600.0000', 'admin', 23),
(35, 45, '3000.0000', 'admin', 23),
(36, 41, '10000.0000', 'admin', 22),
(37, 52, '0.0000', 'admin', 23);

-- --------------------------------------------------------

--
-- Table structure for table `cus_test`
--

CREATE TABLE `cus_test` (
  `_id` int(11) NOT NULL,
  `_name` date DEFAULT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_training`
--

CREATE TABLE `cus_training` (
  `_id` int(11) NOT NULL,
  `_type` varchar(60) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_subject` varchar(60) NOT NULL,
  `_organized` varchar(60) NOT NULL,
  `_place` varchar(60) NOT NULL,
  `_trfrom` date DEFAULT NULL,
  `_trto` date DEFAULT NULL,
  `_result` varchar(30) NOT NULL,
  `_note` longtext DEFAULT NULL,
  `_user` varchar(60) NOT NULL,
  `_training` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_transfer`
--

CREATE TABLE `cus_transfer` (
  `_id` int(11) NOT NULL,
  `_tfrom` varchar(100) NOT NULL,
  `_tto` varchar(100) NOT NULL,
  `_ttransfer` date DEFAULT NULL,
  `_tjoin` date DEFAULT NULL,
  `_tnote` longtext DEFAULT NULL,
  `_user` varchar(60) NOT NULL,
  `_transfer` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cus_vacancies`
--

CREATE TABLE `cus_vacancies` (
  `_id` int(11) NOT NULL,
  `_name` varchar(50) NOT NULL,
  `_jobtitle` int(11) NOT NULL DEFAULT 0,
  `_hiring` int(11) NOT NULL DEFAULT 0,
  `_user` varchar(60) NOT NULL,
  `_nop` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_description` longtext DEFAULT NULL,
  `_active` enum('1','0') NOT NULL DEFAULT '1',
  `_department` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_vacancies`
--

INSERT INTO `cus_vacancies` (`_id`, `_name`, `_jobtitle`, `_hiring`, `_user`, `_nop`, `_description`, `_active`, `_department`) VALUES
(1, 'General Manager', 2, 1, 'Administrator', '1.0000', '', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cus_weekworkday`
--

CREATE TABLE `cus_weekworkday` (
  `_id` int(11) NOT NULL,
  `_monday` varchar(5) NOT NULL,
  `_tuesday` varchar(5) NOT NULL,
  `_user` varchar(60) NOT NULL,
  `_wednesday` varchar(5) NOT NULL,
  `_thursday` varchar(5) NOT NULL,
  `_friday` varchar(5) NOT NULL,
  `_saturday` varchar(5) NOT NULL,
  `_sunday` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cus_weekworkday`
--

INSERT INTO `cus_weekworkday` (`_id`, `_monday`, `_tuesday`, `_user`, `_wednesday`, `_thursday`, `_friday`, `_saturday`, `_sunday`) VALUES
(1, 'full', 'full', 'Administrator', 'full', 'full', 'off', 'full', 'full');

-- --------------------------------------------------------

--
-- Stand-in structure for view `cus_year`
-- (See below for the actual view)
--
CREATE TABLE `cus_year` (
`_id` int(5)
,`_year` int(5)
);

-- --------------------------------------------------------

--
-- Table structure for table `inv_batchlot`
--

CREATE TABLE `inv_batchlot` (
  `_id` int(11) NOT NULL,
  `_batchlot` varchar(40) NOT NULL,
  `_expiredate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inv_batchlot`
--

INSERT INTO `inv_batchlot` (`_id`, `_batchlot`, `_expiredate`) VALUES
(1, 'N/A', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_cost`
--

CREATE TABLE `inv_cost` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_costfor` varchar(20) NOT NULL,
  `_number` int(11) NOT NULL,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `_narration` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_costdetail`
--

CREATE TABLE `inv_costdetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_costfor`
--

CREATE TABLE `inv_costfor` (
  `_costfor` varchar(13) DEFAULT NULL,
  `_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_costitemdetail`
--

CREATE TABLE `inv_costitemdetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_item` varchar(60) NOT NULL,
  `_percent` decimal(15,4) NOT NULL,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_history`
--

CREATE TABLE `inv_history` (
  `_inventory` varchar(20) NOT NULL,
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_reference` varchar(30) NOT NULL,
  `_orderno` int(11) NOT NULL,
  `_invledger` varchar(60) NOT NULL,
  `_costcenter` varchar(60) DEFAULT NULL,
  `_group` varchar(60) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_subtotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_distotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_vattotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_discount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cash` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_narration` varchar(120) NOT NULL,
  `_item` varchar(60) NOT NULL,
  `_store` varchar(30) NOT NULL,
  `_batchlot` varchar(40) NOT NULL,
  `_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_value` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_vat` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_action` varchar(6) NOT NULL,
  `_actiontime` timestamp NOT NULL DEFAULT current_timestamp(),
  `_actioncount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_inventory`
--

CREATE TABLE `inv_inventory` (
  `_type` varchar(3) DEFAULT NULL,
  `_inventory` varchar(15) DEFAULT NULL,
  `_id` int(11) DEFAULT NULL,
  `_date` date DEFAULT NULL,
  `_item` varchar(60) DEFAULT NULL,
  `_store` varchar(30) DEFAULT NULL,
  `_batchlot` varchar(40) DEFAULT NULL,
  `_drqty` decimal(18,4) DEFAULT NULL,
  `_drrate` decimal(18,4) DEFAULT NULL,
  `_drvalue` decimal(18,4) DEFAULT NULL,
  `_crqty` decimal(23,4) DEFAULT NULL,
  `_crrate` decimal(23,4) DEFAULT NULL,
  `_crvalue` decimal(23,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_item`
--

CREATE TABLE `inv_item` (
  `_id` int(11) NOT NULL,
  `_item` varchar(60) NOT NULL,
  `_description` varchar(400) DEFAULT NULL,
  `_category` varchar(60) NOT NULL,
  `_unit` varchar(15) NOT NULL,
  `_orderpoint` int(11) NOT NULL DEFAULT 0,
  `_orderqty` int(11) NOT NULL DEFAULT 0,
  `_saleprice` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_purprice` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_vat` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_active` int(11) NOT NULL DEFAULT 1,
  `_stock` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchase`
--

CREATE TABLE `inv_purchase` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_reference` varchar(30) NOT NULL,
  `_orderno` int(11) NOT NULL DEFAULT 0,
  `_invledger` varchar(60) NOT NULL DEFAULT 'Purchase',
  `_costcenter` varchar(60) DEFAULT NULL,
  `_ledger` varchar(60) DEFAULT NULL,
  `_subtotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_narration` varchar(120) NOT NULL,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inv_purchase`
--

INSERT INTO `inv_purchase` (`_id`, `_date`, `_reference`, `_orderno`, `_invledger`, `_costcenter`, `_ledger`, `_subtotal`, `_dr_amount`, `_cr_amount`, `_narration`, `_user`, `_entrydatetime`) VALUES
(1, '2012-01-01', 'N/A', 0, 'Purchase', NULL, NULL, '0.0000', '0.0000', '0.0000', 'N/A', 'admin', '2016-01-31 23:38:49');

--
-- Triggers `inv_purchase`
--
DELIMITER $$
CREATE TRIGGER `inv_purchase_delete` AFTER DELETE ON `inv_purchase` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - (old._subtotal +(old._dr_amount - old._cr_amount))) WHERE `_ledger` = old._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inv_purchase_insert` AFTER INSERT ON `inv_purchase` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + (new._subtotal + (new._dr_amount - new._cr_amount))) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inv_purchase_update` AFTER UPDATE ON `inv_purchase` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - (old._subtotal + (old._dr_amount - old._cr_amount))) WHERE `_ledger` = old._ledger;
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + (new._subtotal + (new._dr_amount - new._cr_amount))) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchaseaccount`
--

CREATE TABLE `inv_purchaseaccount` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchasedetail`
--

CREATE TABLE `inv_purchasedetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_item` varchar(60) NOT NULL,
  `_store` varchar(30) NOT NULL DEFAULT 'Main Store',
  `_batchlot` varchar(40) NOT NULL DEFAULT 'N/A',
  `_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_billqty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_value` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_purchasedetail`
--
DELIMITER $$
CREATE TRIGGER `pur_stock_delete` AFTER DELETE ON `inv_purchasedetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - old._qty) WHERE `_item` = old._item;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `pur_stock_insert` AFTER INSERT ON `inv_purchasedetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + new._qty) WHERE `_item` = new._item;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `pur_stock_update` AFTER UPDATE ON `inv_purchasedetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - old._qty) WHERE `_item` = old._item;
            UPDATE `inv_item` SET `_stock` = (`_stock` + new._qty) WHERE `_item` = new._item;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchaseorder`
--

CREATE TABLE `inv_purchaseorder` (
  `_id` int(11) NOT NULL,
  `_orderno` varchar(50) NOT NULL,
  `_date` date NOT NULL,
  `_expiry` date DEFAULT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_currency` varchar(100) NOT NULL,
  `_currate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_status` varchar(15) NOT NULL,
  `_note` varchar(1000) NOT NULL,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchaseorderdetail`
--

CREATE TABLE `inv_purchaseorderdetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_item` varchar(60) NOT NULL,
  `_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_value` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchasereturn`
--

CREATE TABLE `inv_purchasereturn` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_reference` varchar(30) NOT NULL,
  `_orderno` int(11) NOT NULL DEFAULT 0,
  `_invledger` varchar(60) NOT NULL DEFAULT 'Purchase Return',
  `_costcenter` varchar(60) DEFAULT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_subtotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_narration` varchar(120) NOT NULL,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_purchasereturn`
--
DELIMITER $$
CREATE TRIGGER `inv_purchasereturn_delete` AFTER DELETE ON `inv_purchasereturn` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - (old._subtotal - (old._dr_amount - old._cr_amount))) WHERE `_ledger` = old._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inv_purchasereturn_insert` AFTER INSERT ON `inv_purchasereturn` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + (new._subtotal - (new._dr_amount - new._cr_amount))) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inv_purchasereturn_update` AFTER UPDATE ON `inv_purchasereturn` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - (old._subtotal - (old._dr_amount - old._cr_amount))) WHERE `_ledger` = old._ledger;
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + (new._subtotal - (new._dr_amount - new._cr_amount))) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchasereturnaccount`
--

CREATE TABLE `inv_purchasereturnaccount` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchasereturndetail`
--

CREATE TABLE `inv_purchasereturndetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_item` varchar(60) NOT NULL,
  `_store` varchar(30) NOT NULL DEFAULT 'Main Store',
  `_batchlot` varchar(40) NOT NULL DEFAULT 'N/A',
  `_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_value` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_purchasereturndetail`
--
DELIMITER $$
CREATE TRIGGER `purret_stock_delete` AFTER DELETE ON `inv_purchasereturndetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + old._qty) WHERE `_item` = old._item;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `purret_stock_insert` AFTER INSERT ON `inv_purchasereturndetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - new._qty) WHERE `_item` = new._item;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `purret_stock_update` AFTER UPDATE ON `inv_purchasereturndetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + old._qty) WHERE `_item` = old._item;
            UPDATE `inv_item` SET `_stock` = (`_stock` - new._qty) WHERE `_item` = new._item;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_purchaseslcode`
--

CREATE TABLE `inv_purchaseslcode` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_code` longtext NOT NULL,
  `_item` varchar(60) NOT NULL,
  `_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_vat` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_note` varchar(50) NOT NULL,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_sales`
--

CREATE TABLE `inv_sales` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_type` varchar(10) NOT NULL DEFAULT 'sales',
  `_reference` varchar(30) NOT NULL,
  `_orderno` int(11) NOT NULL DEFAULT 0,
  `_invledger` varchar(60) NOT NULL DEFAULT 'Sales',
  `_costcenter` varchar(60) DEFAULT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_subtotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_distotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_vattotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_roundtype` varchar(10) DEFAULT NULL,
  `_round` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_paid` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_narration` varchar(120) NOT NULL,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_sales`
--
DELIMITER $$
CREATE TRIGGER `inv_sales_delete` AFTER DELETE ON `inv_sales` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - ((old._subtotal-old._distotal+old._vattotal+old._round)-(old._dr_amount - old._cr_amount))) WHERE `_ledger` = old._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inv_sales_insert` AFTER INSERT ON `inv_sales` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + ((new._subtotal-new._distotal+new._vattotal+new._round)-(new._dr_amount - new._cr_amount))) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inv_sales_update` AFTER UPDATE ON `inv_sales` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - ((old._subtotal-old._distotal+old._vattotal+old._round)-(old._dr_amount - old._cr_amount))) WHERE `_ledger` = old._ledger;
        UPDATE `acc_ledger` SET `_balance` = (`_balance` + ((new._subtotal-new._distotal+new._vattotal+new._round)-(new._dr_amount - new._cr_amount))) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_salesaccount`
--

CREATE TABLE `inv_salesaccount` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_salesdetail`
--

CREATE TABLE `inv_salesdetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_code` varchar(30) DEFAULT NULL,
  `_item` varchar(60) NOT NULL,
  `_store` varchar(30) NOT NULL DEFAULT 'Main Store',
  `_batchlot` varchar(40) NOT NULL DEFAULT 'N/A',
  `_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_value` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_discount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_vat` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_salesdetail`
--
DELIMITER $$
CREATE TRIGGER `sal_stock_delete` AFTER DELETE ON `inv_salesdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + old._qty) WHERE `_item` = old._item;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sal_stock_insert` AFTER INSERT ON `inv_salesdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - new._qty) WHERE `_item` = new._item;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sal_stock_update` AFTER UPDATE ON `inv_salesdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + old._qty) WHERE `_item` = old._item;
    UPDATE `inv_item` SET `_stock` = (`_stock` + new._qty) WHERE `_item` = new._item;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_salesorder`
--

CREATE TABLE `inv_salesorder` (
  `_id` int(11) NOT NULL,
  `_orderno` varchar(50) NOT NULL,
  `_date` date NOT NULL,
  `_expiry` date DEFAULT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_currency` varchar(100) NOT NULL,
  `_currate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_status` varchar(15) NOT NULL,
  `_note` varchar(1000) NOT NULL,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_salesorderdetail`
--

CREATE TABLE `inv_salesorderdetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_item` varchar(60) NOT NULL,
  `_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_value` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_salesreturn`
--

CREATE TABLE `inv_salesreturn` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_type` varchar(10) NOT NULL DEFAULT 'sales',
  `_reference` varchar(30) NOT NULL,
  `_orderno` int(11) NOT NULL DEFAULT 0,
  `_invledger` varchar(60) NOT NULL DEFAULT 'Sales Return',
  `_costcenter` varchar(60) DEFAULT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_subtotal` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_narration` varchar(120) NOT NULL,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_salesreturn`
--
DELIMITER $$
CREATE TRIGGER `inv_salesreturn_delete` AFTER DELETE ON `inv_salesreturn` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - (old._subtotal+(old._dr_amount - old._cr_amount))) WHERE `_ledger` = old._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inv_salesreturn_insert` AFTER INSERT ON `inv_salesreturn` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + (new._subtotal + (new._dr_amount - new._cr_amount))) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `inv_salesreturn_update` AFTER UPDATE ON `inv_salesreturn` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `acc_ledger` SET `_balance` = (`_balance` - (old._subtotal + (old._dr_amount - old._cr_amount))) WHERE `_ledger` = old._ledger;
            UPDATE `acc_ledger` SET `_balance` = (`_balance` + (new._subtotal + (new._dr_amount - new._cr_amount))) WHERE `_ledger` = new._ledger;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_salesreturnaccount`
--

CREATE TABLE `inv_salesreturnaccount` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_ledger` varchar(60) NOT NULL,
  `_dr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cr_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_salesreturndetail`
--

CREATE TABLE `inv_salesreturndetail` (
  `_id` int(11) NOT NULL,
  `_no` int(11) NOT NULL,
  `_code` varchar(30) DEFAULT NULL,
  `_item` varchar(60) NOT NULL,
  `_store` varchar(30) NOT NULL DEFAULT 'Main Store',
  `_batchlot` varchar(40) NOT NULL DEFAULT 'N/A',
  `_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_value` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_user` varchar(30) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_salesreturndetail`
--
DELIMITER $$
CREATE TRIGGER `salret_stock_delete` AFTER DELETE ON `inv_salesreturndetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - old._qty) WHERE `_item` = old._item;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `salret_stock_insert` AFTER INSERT ON `inv_salesreturndetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + new._qty) WHERE `_item` = new._item;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `salret_stock_update` AFTER UPDATE ON `inv_salesreturndetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - old._qty) WHERE `_item` = old._item;
            UPDATE `inv_item` SET `_stock` = (`_stock` + new._qty) WHERE `_item` = new._item;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_stock`
--

CREATE TABLE `inv_stock` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_reference` varchar(30) NOT NULL DEFAULT 'N/A',
  `_type` varchar(15) NOT NULL,
  `_rate` varchar(15) NOT NULL,
  `_note` varchar(1000) NOT NULL,
  `_user` varchar(15) NOT NULL,
  `_entrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_stockindetail`
--

CREATE TABLE `inv_stockindetail` (
  `_iid` int(11) NOT NULL,
  `_ino` int(11) NOT NULL,
  `_iitem` varchar(60) NOT NULL,
  `_istore` varchar(30) NOT NULL DEFAULT 'Main Store',
  `_ibatchlot` varchar(40) NOT NULL DEFAULT 'N/A',
  `_iqty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_irate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_ivalue` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_iuser` varchar(30) NOT NULL,
  `_ientrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_stockindetail`
--
DELIMITER $$
CREATE TRIGGER `sjin_stock_delete` AFTER DELETE ON `inv_stockindetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - old._iqty) WHERE `_item` = old._iitem;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sjin_stock_insert` AFTER INSERT ON `inv_stockindetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + new._iqty) WHERE `_item` = new._iitem;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sjin_stock_update` AFTER UPDATE ON `inv_stockindetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - old._iqty) WHERE `_item` = old._iitem;
            UPDATE `inv_item` SET `_stock` = (`_stock` + new._iqty) WHERE `_item` = new._iitem;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_stockoutdetail`
--

CREATE TABLE `inv_stockoutdetail` (
  `_oid` int(11) NOT NULL,
  `_ono` int(11) NOT NULL,
  `_oitem` varchar(60) NOT NULL,
  `_ostore` varchar(30) NOT NULL DEFAULT 'Main Store',
  `_obatchlot` varchar(40) NOT NULL DEFAULT 'N/A',
  `_oqty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_orate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_ovalue` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_ouser` varchar(30) NOT NULL,
  `_oentrydatetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `inv_stockoutdetail`
--
DELIMITER $$
CREATE TRIGGER `sjout_stock_delete` AFTER DELETE ON `inv_stockoutdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + old._oqty) WHERE `_item` = old._oitem;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sjout_stock_insert` AFTER INSERT ON `inv_stockoutdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` - new._oqty) WHERE `_item` = new._oitem;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sjout_stock_update` AFTER UPDATE ON `inv_stockoutdetail` FOR EACH ROW BEGIN
        IF (@DISABLE_TRIGGERS IS NULL) then
            UPDATE `inv_item` SET `_stock` = (`_stock` + old._oqty) WHERE `_item` = old._oitem;
            UPDATE `inv_item` SET `_stock` = (`_stock` - new._oqty) WHERE `_item` = new._oitem;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_stockreg`
--

CREATE TABLE `inv_stockreg` (
  `_type` varchar(3) DEFAULT NULL,
  `_inventory` varchar(15) DEFAULT NULL,
  `_id` int(11) DEFAULT NULL,
  `_date` date DEFAULT NULL,
  `_item` varchar(60) DEFAULT NULL,
  `_store` varchar(30) DEFAULT NULL,
  `_category` varchar(60) DEFAULT NULL,
  `_unit` varchar(15) DEFAULT NULL,
  `_qty` decimal(16,4) DEFAULT NULL,
  `_rate` decimal(65,24) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inv_store`
--

CREATE TABLE `inv_store` (
  `_id` int(11) NOT NULL,
  `_store` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inv_store`
--

INSERT INTO `inv_store` (`_id`, `_store`) VALUES
(1, 'Main Store');

-- --------------------------------------------------------

--
-- Table structure for table `inv_unit`
--

CREATE TABLE `inv_unit` (
  `_id` int(11) NOT NULL,
  `_unit` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_audittrail`
--

CREATE TABLE `sys_audittrail` (
  `_id` int(11) NOT NULL,
  `_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `_script` int(11) DEFAULT NULL,
  `_action` varchar(50) DEFAULT NULL,
  `_user` varchar(60) DEFAULT NULL,
  `_table` varchar(100) DEFAULT NULL,
  `_rowid` int(11) DEFAULT NULL,
  `_value` longtext DEFAULT NULL,
  `_rowdata` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_audittrail`
--

INSERT INTO `sys_audittrail` (`_id`, `_datetime`, `_script`, `_action`, `_user`, `_table`, `_rowid`, `_value`, `_rowdata`) VALUES
(1, '2021-06-29 13:40:50', 0, 'Login', 'softin', '103.16.25.186', 0, '', ''),
(2, '2022-03-02 06:26:35', 0, 'Login', 'admin', '175.41.44.62', 0, '', ''),
(3, '2022-09-07 05:06:37', 0, 'Login', 'admin', '175.41.44.62', 0, '', ''),
(4, '2022-09-07 05:22:30', 0, 'Login', 'softin', '103.137.81.10', 0, '', ''),
(5, '2022-09-07 12:25:34', 0, 'Login', 'softin', '103.137.81.10', 0, '', ''),
(6, '2022-09-07 18:27:33', 0, 'Login', 'admin', '103.58.92.18', 0, '', ''),
(7, '2022-09-07 18:27:48', 0, 'Logout', 'admin', '103.58.92.18', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `sys_chats`
--

CREATE TABLE `sys_chats` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_from` int(11) NOT NULL,
  `_to` int(11) NOT NULL,
  `_data` longtext NOT NULL,
  `_last_msg` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_currency`
--

CREATE TABLE `sys_currency` (
  `_id` int(11) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_code` varchar(3) NOT NULL,
  `_symbol` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_currency`
--

INSERT INTO `sys_currency` (`_id`, `_name`, `_code`, `_symbol`) VALUES
(1, 'Algerian Dinar (DA)', 'DZD', 'DA'),
(2, 'Argentine Peso ($)', 'ARS', '$'),
(3, 'Australian Dollar ($)', 'AUD', '$'),
(4, 'Azerbaijani Manat (man.)', 'AZM', 'man.'),
(5, 'Bahraini Dinar (BD)', 'BHD', 'BD'),
(6, 'Bangladeshi Taka (Tk)', 'BDT', 'Tk'),
(7, 'Barbadian Dollar (Bds$)', 'BBD', 'Bds$'),
(8, 'Belarusian Ruble (p.)', 'BYR', 'p.'),
(9, 'Botswana Pula (P)', 'BWP', 'P'),
(10, 'Brazil Reais (R$)', 'BRL', 'R$'),
(11, 'Brunei Dollar (B$)', 'BND', 'B$'),
(12, 'Bulgarian Lev (лв.)', 'BGL', 'лв.'),
(13, 'Cambodian riel (៛)', 'KHR', '៛'),
(14, 'Canadian Dollar ($)', 'CAD', '$'),
(15, 'Central Africa CFA Franc (FCFA)', 'XAF', 'FCFA'),
(16, 'Chilean Peso ($)', 'COP', '$'),
(17, 'Chinese Yuan Renminbi (元)', 'RMB', '元'),
(18, 'Colombian Peso ($)', 'COP', '$'),
(19, 'Costa Rican colón (¢)', 'CRC', '¢'),
(20, 'Croatian Kuna (kn)', 'HRK', 'kn'),
(21, 'Czech Koruna (Kč)', 'CZK', 'Kč'),
(22, 'Danish Krone (kr.)', 'DKK', 'kr.'),
(23, 'Dominican Peso (RD$)', 'DOP', 'RD$'),
(24, 'Eastern Caribbean Dollar (EC$)', 'XCD', 'EC$'),
(25, 'Egyptian Pound (LE)', 'EGP', 'LE'),
(26, 'Estonian Kroon (kr)', 'EEK', 'kr'),
(27, 'EU Euro (€)', 'EUR', '€'),
(28, 'Fijian Dollar (FJ$)', 'FJD', 'FJ$'),
(29, 'Georgian Lari', 'GEL', 'Lari'),
(30, 'Ghanaian Cedi (GH₵)', 'GHS', 'GH₵'),
(31, 'Guatemalan Quetzal (Q)', 'GTQ', 'Q'),
(32, 'Honduran Lempira (L.)', 'HNL', 'L.'),
(33, 'Hong Kong Dollar ($)', 'HKD', 'HK$'),
(34, 'Hungarian Forint (Ft)', 'HUF', 'Ft'),
(35, 'Icelandic króna (kr.)', 'ISK', 'kr.'),
(36, 'Indian Rupee (Rs)', 'INR', 'Rs.'),
(37, 'Indonesian Rupiah (Rp)', 'IDR', 'Rp'),
(38, 'Iranian Rials (Rls)', 'IRR', 'Rls'),
(39, 'Israeli New Shekel (₪)', 'ILS', '₪'),
(40, 'Japanese Yen (¥)', 'JPY', '¥'),
(41, 'Jordanian Dinar (JD)', 'JOD', 'JD'),
(42, 'Kazakh Tenge (T)', 'KZT', 'T'),
(43, 'Kenyan Shilling (Ksh)', 'KES', 'Ksh'),
(44, 'Korean Won (₩)', 'KRW', '₩'),
(45, 'Kuwaiti Dinar (KD)', 'KWD', 'KD'),
(46, 'Latvian lats (Ls)', 'LVL', 'Ls'),
(47, 'Lebanese Pound (L.L)', 'LBP', 'L.L'),
(48, 'Libyan Dinar (LD)', 'LYD', 'LD'),
(49, 'Lithuanian Litas (Lt)', 'LTL', 'Lt'),
(50, 'Macanese pataca (MOP)', 'MOP', 'mop'),
(51, 'Macedonian Denar (Дэн)', 'MKD', 'Дэн'),
(52, 'Malaysian Ringgit (RM)', 'MYR', 'RM'),
(53, 'Maldivian Rufiyaa (Rf.)', 'MVR', 'Rf.'),
(54, 'Mauritian rupee (Rs )', 'MUR', 'Rs'),
(55, 'Mexican Peso ($)', 'MXN', '$'),
(56, 'Mongolian Tugrik (₮)', 'MNT', '₮'),
(57, 'Moroccan Dirham (DH)', 'MAD', 'DH'),
(58, 'Mozambican Metical (MZN)', 'MZN', 'MZN'),
(59, 'Namibian Dollar (N$)', 'NAD', 'N$'),
(60, 'New Zealand Dollar ($)', 'NZD', '$'),
(61, 'Nicaraguan córdoba (C$)', 'NIO', 'C$'),
(62, 'Nigerian Naira (N)', 'NGN', 'N'),
(63, 'Norwegian Krone (kr)', 'NOK', 'kr'),
(64, 'Omani Rial (RO)', 'OMR', 'RO'),
(65, 'Pakistani Rupee (Rs)', 'PKR', 'Rs'),
(66, 'Papua New Guinean kina (K)', 'PGK', 'K'),
(67, 'Peruvian Nuevo Sol (S/.)', 'PEN', 'S/.'),
(68, 'Philippine Peso (Php)', 'PHP', 'Php'),
(69, 'Polish Zloty(zł)', 'PLN', 'zł'),
(70, 'Qatari Riyal (QR)', 'QAR', 'QR'),
(71, 'Romanian Leu (lei)', 'RON', 'lei'),
(72, 'Russian Ruble (руб)', 'RUB', 'руб'),
(73, 'Saudi Riyal (SR)', 'SAR', 'SR'),
(74, 'Serbian Dinar (Din.)', 'CSD', 'Din.'),
(75, 'Sierra Leone Leone (Le)', 'SLL', 'Le'),
(76, 'Singaporean Dollar ($)', 'SGD', '$'),
(77, 'Slovak Koruna (Sk)', 'SKK', 'Sk'),
(78, 'South African Rand (R)', 'ZAR', 'R'),
(79, 'Sri Lankan Rupee (₨.)', 'LKR', '₨.'),
(80, 'Sudanese Pound (SDG)', 'SDG', 'SDG'),
(81, 'Surinamese Dollar (SRD)', 'SRD', 'SRD'),
(82, 'Swedish Krona (kr)', 'SEK', 'kr'),
(83, 'Swiss Franc (Fr.)', 'CHF', 'Fr.'),
(84, 'Syrian Pound (S£)', 'SYP', 'S£'),
(85, 'Taiwan Dollar (NT$)', 'TWD', 'NT$'),
(86, 'Tanzanian Shilling (TSh)', 'TZS', 'TSh'),
(87, 'Thai Baht (฿)', 'THB', '฿'),
(88, 'Trinidad and Tobago Dollar ($)', 'TTD', '$'),
(89, 'Tunisian Dinar (DT)', 'TND', 'DT'),
(90, 'Turkish New Lira (YTL)', 'TRY', 'YTL'),
(91, 'U.A.E. Dirham (DH)', 'AED', 'DH'),
(92, 'Ugandan Shilling (USh)', 'UGX', 'USh'),
(93, 'UK Pound (£)', 'GBP', '£'),
(94, 'Ukrainian Hryvnia (грн.)', 'UAH', 'грн.'),
(95, 'US Dollar ($)', 'USD', '$'),
(96, 'Venezuelan Bolivar (Bs)', 'VEB', 'Bs'),
(97, 'Vietnamese Dong (₫)', 'VND', '₫'),
(98, 'West African CFA Franc (FCFA)', 'XOF', 'FCFA'),
(99, 'Zambian Kwacha (ZK)', 'ZMK', 'ZK');

-- --------------------------------------------------------

--
-- Table structure for table `sys_customeffect`
--

CREATE TABLE `sys_customeffect` (
  `_id` int(11) NOT NULL,
  `_account` text DEFAULT NULL,
  `_inventory` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_customeffect`
--

INSERT INTO `sys_customeffect` (`_id`, `_account`, `_inventory`) VALUES
(1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sys_customfields`
--

CREATE TABLE `sys_customfields` (
  `_id` int(11) NOT NULL,
  `_serial` int(11) NOT NULL,
  `_entryform` varchar(30) NOT NULL,
  `_tablename` varchar(30) NOT NULL,
  `_field` varchar(60) NOT NULL,
  `_fieldname` varchar(60) NOT NULL,
  `_datatype` varchar(1) NOT NULL,
  `_defaultvalue` varchar(60) DEFAULT NULL,
  `_option` longtext DEFAULT NULL,
  `_active` enum('1','0') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_customfilter`
--

CREATE TABLE `sys_customfilter` (
  `_id` int(11) NOT NULL,
  `_title` varchar(100) NOT NULL,
  `_type` int(11) NOT NULL,
  `_query` longtext DEFAULT NULL,
  `_execute` longtext DEFAULT NULL,
  `_pagesetup` longtext DEFAULT NULL,
  `_template` longtext DEFAULT NULL,
  `_event` longtext DEFAULT NULL,
  `_reportid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_customfilter`
--

INSERT INTO `sys_customfilter` (`_id`, `_title`, `_type`, `_query`, `_execute`, `_pagesetup`, `_template`, `_event`, `_reportid`) VALUES
(1, 'Make Monthly Salary', 1, 'SELECT CURRENT_DATE() AS _date', 'INSERT INTO cus_salarystructure(_empcode, _month, _year, _user)\r\nSELECT _id, @_date_month AS _month, @_date_year AS _year, @_user AS _user\r\nFROM cus_employees t1\r\nWHERE (_active = 1) AND ((SELECT COUNT(_id) AS _id FROM cus_salarystructure WHERE CONCAT(_empcode, _month, _year) = CONCAT(t1._id, @_date_month, @_date_year))=0);\r\n\r\nINSERT INTO cus_sstructuredetails(_ledger, _amount, _structureno, _user)\r\nSELECT t2._ledger, t2._amount, (SELECT _id FROM cus_salarystructure WHERE CONCAT(_empcode, _month, _year) = CONCAT(t1._id, @_date_month, @_date_year)) AS _structureno, @_user AS _user\r\nFROM cus_employees t1 INNER JOIN cus_payinfo t2 ON t1._id = t2._payinfo\r\nWHERE (t1._active = 1) AND ((SELECT COUNT(i2._id) AS _id FROM cus_salarystructure i1 INNER JOIN cus_sstructuredetails i2 ON i1._id = i2._structureno WHERE CONCAT(i1._empcode, i2._ledger, i1._month, i1._year) = CONCAT(t1._id, t2._ledger, @_date_month, @_date_year))=0);\r\n\r\nUPDATE cus_salarystructure p1, cus_sstructuredetails p2, (\r\nSELECT IF(SUM(_ttex)>0, SUM(_ttex) - SUM(_rtex),0) AS _tax\r\nFROM (SELECT _ttex, _rtex FROM(\r\nSELECT IF(@_balance>_plimit,(_plimit * (_ppercent/100)),(ABS(@_balance) * (_ppercent/100))) AS _ttex, 0 AS _rtex,\r\n@_balance:= IF((@_balance-_plimit)>0,@_balance-_plimit,0) AS _bbalance\r\nFROM cus_itaxpayable, (SELECT @_balance:=SUM(_ttincome) AS _tin \r\nFROM (SELECT IF(((t2._amount * 12) - t3._exlimit)>0,(t2._amount * 12) - t3._exlimit,0) AS _ttincome\r\nFROM cus_salarystructure t1 INNER JOIN cus_sstructuredetails t2 On t1._id = t2._structureno \r\nINNER JOIN cus_itaxledger t3 On t2._ledger = t3._ledger\r\nWHERE (t1._month = @_date_month) AND (t1._year = @_date_year) AND t1._empcode = 3) ptax) c2 ORDER BY _plevel) d1\r\nUNION ALL\r\nSELECT _ttex, _rtex FROM(SELECT 0 AS _ttex, IF(@_balance>_rlimit,(_rlimit * (_rpercent/100)),(ABS(@_balance) * (_rpercent/100))) AS _rtex, \r\n@_balance:= IF((@_balance-_rlimit)>0,@_balance-_rlimit,0) AS _bbalance \r\nFROM cus_itaxrebate, (SELECT @_balance:=(SUM(_ttincome)*0.25) AS _tin \r\nFROM (SELECT IF(((t2._amount * 12) - t3._exlimit)>0,(t2._amount * 12) - t3._exlimit,0) AS _ttincome\r\nFROM cus_salarystructure t1 INNER JOIN cus_sstructuredetails t2 On t1._id = t2._structureno \r\nINNER JOIN cus_itaxledger t3 On t2._ledger = t3._ledger\r\nWHERE (t1._month = @_date_month) AND (t1._year = @_date_year) AND t1._empcode = 3) ptax) c2 ORDER BY _rlevel) d2\r\n) ggt\r\n\r\n) p3 SET _amount = p3._tax \r\nWHERE p1._id = p2._structureno AND p2._ledger = 52;', 'a:2:{s:8:\"pagesize\";s:4:\"auto\";s:10:\"pagemargin\";s:23:\"0.5in 0.5in 0.5in 0.5in\";}', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sys_customform`
--

CREATE TABLE `sys_customform` (
  `_id` int(11) NOT NULL,
  `_serial` int(11) NOT NULL DEFAULT 0,
  `_table` varchar(100) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_type` enum('0','1','2','3') NOT NULL DEFAULT '1',
  `_groupmenu` varchar(60) DEFAULT NULL,
  `_underfrom` varchar(100) DEFAULT NULL,
  `_option` longtext DEFAULT NULL,
  `_event` longtext DEFAULT NULL,
  `_tabletype` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_customform`
--

INSERT INTO `sys_customform` (`_id`, `_serial`, `_table`, `_name`, `_type`, `_groupmenu`, `_underfrom`, `_option`, `_event`, `_tabletype`) VALUES
(1, 1, 'cus_employees', 'Employees', '2', NULL, '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"1\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', '', 0),
(2, 4, 'cus_empaddress', 'Address', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(3, 5, 'cus_payheads', 'Pay Heads', '2', 'Payroll Information', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', 'a:1:{s:6:\"client\";a:2:{s:8:\"add_page\";a:1:{s:14:\"startup_script\";s:164:\"dYxBCsMgEEXXEbzDYANRKLlAyR16A5HJpAoyQtS0UHL3KnTZ7j7/Pd6o1eVlLbqINboSEiszJ9YTescPmq6wVcb+awNvKYaw6eJDng8XK8GygLrTjsRFmY6HsQV3axN7cmtrZZ+e2twaOoFiJvhp+bDS15Li7EOKP84H\";}s:9:\"edit_page\";a:1:{s:14:\"startup_script\";s:188:\"dY5BCsMgFETXEbzDxwpRKLlAyR16AxHzUwVRiJoWinevQheFJrthZpg3XLDLSymjvSleZxcDk1MMYjRWhweOV1hLMN0XEt6UDG4V2bo07doXhHkGdsfNYMhM9njgbXBTKgaLemlbycankLcWVUCfEA5b1i34bVFSu6CkkfjRu0ZuX/7Ip+Af7im2fgA=\";}}}', 0),
(4, 2, 'cus_employees', 'Employee HR Details', '2', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"1\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 1),
(5, 6, 'cus_payinfo', 'Pay Detail', '3', NULL, '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', 'a:0:{}', 0),
(6, 7, 'cus_payunit', 'Payroll Unit', '0', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(8, 12, 'cus_recruitment', 'Candidates', '2', 'Recruitment', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(9, 10, 'cus_jobtitle', 'Job Title/Designation', '2', 'Payroll Information', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', '', 0),
(10, 11, 'cus_vacancies', 'Vacancies', '2', 'Recruitment', 'cus_recruitment', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(11, 9, 'cus_department', 'Department', '2', 'Payroll Information', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', '', 0),
(12, 13, 'cus_job', 'Job', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(13, 14, 'cus_education', 'Education', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(14, 15, 'cus_experience', 'Experience', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(15, 16, 'cus_emergency', 'Emergency Contact', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(16, 17, 'cus_guarantor', 'Guarantor', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(17, 18, 'cus_nominee', 'Nominee', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(18, 19, 'cus_transfer', 'Transfer', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(19, 20, 'cus_jobcontract', 'Job Contract', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(20, 3, 'cus_employees', 'Employee Payroll Details', '2', NULL, '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 1),
(21, 21, 'cus_training', 'Training', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(22, 22, 'cus_language', 'Language', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(23, 23, 'cus_reward', 'Reward/Punishment', '3', NULL, NULL, 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(24, 24, 'cus_holidays', 'Holidays', '2', 'Leave', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"1\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(25, 25, 'cus_holidaydetail', 'Holiday Detail', '3', NULL, '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(26, 26, 'cus_weekworkday', 'Week Work Day', '2', 'Leave', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(27, 27, 'cus_leavetypes', 'Leave Types', '2', 'Leave', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(28, 28, 'cus_leaveentitlement', 'Leave Entitlement', '2', 'Leave', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"1\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(29, 29, 'cus_assignleave', 'Assign Leave', '2', 'Leave', '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"1\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', NULL, 0),
(30, 30, 'cus_attendance', 'Attendance', '1', NULL, '', 'a:8:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:9:\"printlist\";a:0:{}}', '', 0),
(31, 31, 'cus_grade', 'Grade', '2', 'Payroll Information', '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 0),
(32, 32, 'cus_costcenter', 'Cost Center', '2', 'Payroll Information', '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 0),
(33, 33, 'cus_profitcenter', 'Profit Center', '2', 'Payroll Information', '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 0),
(34, 34, 'cus_itaxledger', 'ITAX Ledger', '3', NULL, '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 0),
(35, 35, 'cus_itaxpayable', 'ITAX Payable Calculation', '3', NULL, '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 0),
(36, 36, 'cus_itaxrebate', 'ITAX Rebate', '3', NULL, '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 0),
(37, 37, 'cus_itaxconfig', 'Income Tax Configuration', '2', 'Payroll Information', '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 0),
(38, 38, 'cus_salarystructure', 'Salary Structure', '1', NULL, '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"1\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:2:{i:0;a:4:{s:9:\"printname\";s:8:\"Pay Slip\";s:8:\"pagesize\";s:2:\"a4\";s:10:\"pagemargin\";s:23:\"0.5in 0.5in 0.5in 0.5in\";s:12:\"printscripts\";s:2980:\"7Rxdb+K49nkq9T/4Io2gI2gp046uaIuWFmanqxYqyurqPiGTuCUzIWEd0ymL+t/32HEgCU5ISNrSnfIwA/Hx+fD5tH3S+dxhM5M8Pe3uMDw0CZrv7iD4/DR0Nqqjw2r148nuDozuG9adjZjuAQxtqhMKEJNH5NimoaOhibUfEljgMqwV+AqzJ/45jmE+EHoSAKHG/YjVUW0B9HNkMCJhJljXDeteDEtactrQZiOgF0tOsIj+Y4wnNmXYYkHCgIHZ4zXwPoqAfAOCgGA+P1is+u7Oqbvw4slZ4c62WMUx/iZ88UHGQoND0MYpLOUqSI2DIPHgJ3HXbWib+gli5JFVsGncW3WkEYvBIhcac80eT7A1G1h4TJ5OD5jegH9oLIXDo1QUQD2UOE5S5LVUyCcj24rleySRHIuFS8jDIedBSfAGz9CtaUyAQ4rYiKAxTBsh+w7NtakzcLCJ6cxhdKqxKSUDMfpUUY7NCKZPmRlqmiZqju2pxRwE3nXe6idYjM9V5Wrs7nwA4IZrvfzj2mHgAfX9+uBDLaPDMQSHgg8kiAlpJnacs4KMBWpAqniqoHQkKLXHE3Rh60SIkmjeFzGv7uqLjCemPSPEGVjT8ZBIfSi4OlCyFc2sYKwDPhXDmIKJhRPmwUKLTDBlY7CUBEzoC2Df1zx5ccBuMTNsK9WKfLeHzGBmnqvS/SMVB7r9PT/iED6EH7TwzEnARThkTPBMh5lp+TkIu7F8qm+XL1/Z2joDSebLpkSUn97OsfUjvTsPYVbOPDQ1jcf61GxgLT9G+pedVPSZkaMmLmyHoQuR/dIrRIPJbubMj6EmpZDInU1dGlP6XC4dmhucI2ElTGSlcBhXKXjAUK8zQ8OmV5xAxRsIHKoywgsfvlK9EKh8AtVfIT5S8TigLo/amFqwOVgf7I4iUbSIDrpajUvh5V0pjTKGzs1XSCWmXzST3LETFIi9LcPRqDFJGH79yMTebIHt8PhjodHDjGTHcs1rZ3OWHZH00OyI+jbDZjpHnc//mtLVx/xz275qX/TRRbdz0eyXitx+imX0e6/7581APpT/KZguJldtsTwwiX5PaLkoeC+mQKdYhGKZ4R+4NKCg5L18UWKxj8kbqav9l2aVf/ZAxzfNXrPf7aGiMBChZHcW/4KatwiSkqme/rXXvUalWOO5M3RXtyVW25dqdrFKlaOShKQw7jKNYqgF0iWih+iy02n30B/dy44YgwJUHPrQGup2YHx/YOjoTCCXQ2rk//vW7rVRaTGBwRego8HODdjttGBowT8fXgrjSsONraxGzRaClVFVAEuNrxEzlIiBpbCwzmJQJwwbpgO0uNhsIQWQXsBY8aIvJhWVZYChPxXlSngaG7DZhCz5hfUdEaw7EqFysTj6m+JelDVS8vdPbK2OQoji507hxxmjWSh/QVEQm76K5Wxh7ndqOw7y5/t8XP32z2sZ7PINIQKvDCPPgVmGvVdhmn/eg9x7kHsDQS714ci6zc4Wl/pfci31IexCbT2OOY7Yxpr4S841sViFN1cVLuPJLxpAWr9clfTFVyUFjzTyMf4tLQ3eTb31srly9VhMld3COELXniGr7xCGbvCsHpOW19wYu3YbJqvwVLnW3Jovv5bY532x6mdQcfijZrmy/C6rN5WNvZhtrVawrmmwzwJHoOz87P0IokhjlsGZCmt5hpPoWtRJtLSflfPmmNIrt/PndaeqJKrUSnjqWIP4ejEdT03MjAeyOY4ban8nGiP65iialjVdOf1MMb/9CHshtvn8Pn4EPcYb1u7Ohw/Cp5HSqYNl5fqqMlFePZYFpTIVeXmwnAyxQuys82V29oWrrDydKJCiTzCSg7QSsYy85NE0xgZr+Ah94mTCv32we7kyUVoREVWQn1qjWl4DUq5GVUMb7gMy5xQrMqeg2KRiMPwoswhPK1aatJIgqyynyXyS1JUzVtApnFzc/yBRQte3xK95neIvRfJz7iBm17Tzcy5ffbWRl+fOSU6uHuHr7z4drBHzrQAzdy2K/oF0B3uJm0RC2xtJ8b+coio8QZkjitdLS7PHsNuj9hjdiqWNOGRT46+F2xmkA6w/TVSHXUUIfg7viT8QeFlXyd1tMrpQgg26Yuu1UWNTtM0WGldk5QApo1FKl35Gnpu6XkddNgL1hPyrR3gb/sq+aCvlQFFHIyGZ7l4gZryCpG71JWUEkdEVHhrAKXuPjO+RcRsi4ynwZ1v3jUvrgTiifx5dcf2dHsgBVKodf+TvivAXR0JOC5vAJsgmLNybvvcLGDY8r+7Xjt/te9vtO86c+uQRMukwuu0zb4tNftW2vJsCQnsJbpziL5wG1CQPxCzzL8KEEbjCb4Mh6M/SSMN76v2/GHEJG8K1YXRCKO8vLqMFgHuHIH+UIwxSSaskv4AnlRaYDw6r/OCp1Dy/LfmYWAVxGYO1iaK5mF0/4/SX2CqSLnf0lYfg2gLx0BNvzeUb9ysqLGjZzeOjnHPQ+ZQg5Lx8uMk11CQOM3tIA2Z7LSB9/n/PvqMuDPW7V41KoCxngq2zQq0QdzjwKjHQK0whFMq3vJ4tFlb3q9VXDPaBOvzfJG6hcY3pvWGBeHx/wWw0JIgSzX4g4NrylV/Dcd/5zWfH+IKycZHcfTvIwgzTRDeUPBj21EHXb1Ig2NUb/AzCVdcbY1+9qzYsp3EuM+bSBD2tnR7w8TfjZ9scrfutW/QV9lz9b2103e30v23Tqm74vu5R0lNsdX+Eh20sYmCF3395fzbhBZpWA11q/lCV/G3goyinzrFJdcNOgsQXjV57qsQiTLPTvG57aHlBx/9wRLlYKZb5D5GL4Ff1sMgvn+RTDhJ1RZb8mszf3qdoIFhFm6G19UXb8GRx7K+ni8e1omi383Xtw+NF7x0gVrw8IFvv3D8eEVlmS3RPqka8ReXtU7CnU58uk/fopb4Qz9RMLorBetY+8jz29ysNec+3s9us43MrzC2TyeW208v79XVv7B8=\";}i:1;a:4:{s:9:\"printname\";s:10:\"Income Tax\";s:8:\"pagesize\";s:2:\"a4\";s:10:\"pagemargin\";s:23:\"0.5in 0.5in 0.5in 0.5in\";s:12:\"printscripts\";s:2068:\"7Vptc9o4EP6czOQ/7DGTMXQcgkmbD6RhSlN6zV0CHULn5j55DFaCrsbmZNEkx+S/30qWjQHzZpxr2jMfSizJz65W+/KsymTi80eHPD0d7Je51XMIdYHbMDnYB/z0PGYTdsS9UQ2M0QP4nkNt8KnzjbCzmSWM3g14DarRovsB5UStGVm2Td07OY1DQpZ6refxAcpbKa7nWP2v8AsdjjzGLZfPCkYE7g3XrI9JRPAUAhFgMjmOTHWw/1YaC+TIeeGe2nyAEJXK4Rncei4/8uk/BAfEhgt1sZzV36Jd1frYkmoFlwTv3JPAiD3Psc+Akwd+ZDn0zq1Bn7gcLV6oT/recGS5j6ZrDcnT22Nu1/EftlKC8XorCXhWjPj+puDVrcBHA8/dWG9DQCfitHD34N1CczhyvEeCiyf9sW8S9ehvZZ7lYj4QHwcsTj13QcJfXo9T7qyUMlAWOalIN1inz4wBC3WjDBeW0x87UgGx3UsXj59sINBIFniwv4eL64Hvi89yLy7MrGLQdyzfPy/EwrawSvfpy3sx9eJGdsgtPwMl8o2U+Nl6hE/EsqXa6xFkyokgjMohIhAG16jOIBXEKSK0vLKwdHoQo4ooXY9bjjouKHZ/L6XfUvPBGo6kA6THmarUtR7kiUvVkhCVt+ztTSZ/jxlMJ8TnpnnVvOjCRbt10egWNeFSmv5rp/3ls6nG1Nfsa9rmLqDpt9R2iH1HWJFXy2bwZ0nXpJqavhlwghk1nVtfLYlpDb2xy3fDPJWQRnU3xarBluc0g1c4k+GWT8omeXDokO64Z+lGCvTyY7G4oDIcQVxYvaKvWaJXSpFKsxqV0Nc+NzqNbrsDmvRJ6WzBavEHNG7A7HvO7GsfO+1rEHnatxyLPfqcjft8zAhwAy5brWYHfmtftoIV0aRNuEUdH3gV2kh+jLKJHOAchOrRGtebC4U5NMqth8BXcX8SJnJeAXUSPsyC/PGp2WlOJWqTBNVx6ilmHAxKQUA2jc+5vI1EZ2Xa3tQxEiI3Ib+QWkYufPPlOh65u4duVkE7q1jg4tlFrkSPBVR24SuQswvhJTGcx2oQq7HSKmJDFVtVd1W9XcrkqqmoY3WBOmJgAvIroUD2/FElGdU/Fr4/nzw9PQzoe59RSZ524E0zGS09j+uMXRd7YHiPLuP2SQZELjzPVTRuOiLrxfTxOalcYH1NVwjmyCHfiKNrZdB0zDrq+dzQNQy+j5T5HLpfxaR4biFq8FgKchUuV9wF3mk6mKMRYaJD07VDLbuMaFIX00J2udu0esFBl7KjiCauy44vifxbXHCI8LBAmV0HPLF3ptpMPRwNv6OZQIA0YvyMIFog50Oj6LEMnYgfnjqWnGKEdoyZBItNsfH+phgTvLgkUAatFZcTvVE7FzKnCEehh2G1WxjE+ibBemp81n5RPRkFoahDUdkxJi3bYpt8kM9fSHcuohsX0BL0UanOBxT3/s/QIac4JbBvF2osprq1Ce6/4MJB6ltI0bUMeZvKVBmkqixZsMxN2xPBPAnlSeinSULfgeifJBL9DulZfAeev0boNYq4dL8Rnw/R22t4PnsRw0y+kXiGpvNVpVx9879tLeNut8E5v8nbOVVIp377olq5IGJXdHIvopFjc40c266RY7ONHHuuRk6ccN7JhacFyu7zJEqNht8LJEpYUZ8e0goSlYgbHrdkRmw9eWLLydMK0sSSSBNLJE0INMOWmAy5RLKUl6qdS9Ucf2I/chOnknPGTZzMUj91F5cnoDwB/VAJaJMGLtP27XVi+3ZFrR51KH/M4H9qXjaln+bYaM9QvCK+H+svoeE43r2ICz/dz19OXh8K9cLaMt+SBvdFGDRRTpaB4hJO3SA6FlNblCvjF06VWN5KecNFl19x0bV3XHSjSy6a4paLbn/NReczJk286KKZ3XTR/Krr+a66jIP9L63LdgsaV1cLvhw5vnTJ+dDYKBpyppAzhZfMFKoH++LrJOF3WMnXNcsL6bqOJVUdbREOoog5URW99Ri44gbGqMIw9Y9Zty2dx8Kp8uqZV8+8eubVM6+eefVMVz2n/ffeXPOtJv4F\";}}}', '', 0),
(39, 39, 'cus_sstructuredetails', 'Salary Details', '3', NULL, '', 'a:9:{s:10:\"audittrail\";s:1:\"0\";s:9:\"saveprint\";s:1:\"0\";s:9:\"copytoadd\";s:1:\"0\";s:10:\"formdelete\";s:1:\"1\";s:11:\"idextsearch\";s:1:\"0\";s:10:\"multitable\";s:1:\"0\";s:9:\"userwhere\";s:1:\"0\";s:5:\"where\";s:0:\"\";s:9:\"printlist\";a:0:{}}', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sys_customformfields`
--

CREATE TABLE `sys_customformfields` (
  `_id` int(11) NOT NULL,
  `_tableid` int(11) NOT NULL,
  `_field` varchar(100) NOT NULL,
  `_name` varchar(50) NOT NULL,
  `_datatype` int(11) NOT NULL,
  `_size` int(11) NOT NULL,
  `_childtable` longtext DEFAULT NULL,
  `_unique` enum('1','0') NOT NULL,
  `_list` enum('1','0') NOT NULL DEFAULT '1',
  `_group` enum('1','0') NOT NULL DEFAULT '0',
  `_required` enum('1','0') NOT NULL DEFAULT '1',
  `_readonly` enum('1','0') NOT NULL DEFAULT '0',
  `_extsearch` enum('1','0') NOT NULL DEFAULT '0',
  `_default` varchar(50) NOT NULL,
  `_tabs` int(11) NOT NULL DEFAULT 0,
  `_add` enum('1','0') NOT NULL DEFAULT '1',
  `_edit` enum('1','0') NOT NULL DEFAULT '1',
  `_serial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_customformfields`
--

INSERT INTO `sys_customformfields` (`_id`, `_tableid`, `_field`, `_name`, `_datatype`, `_size`, `_childtable`, `_unique`, `_list`, `_group`, `_required`, `_readonly`, `_extsearch`, `_default`, `_tabs`, `_add`, `_edit`, `_serial`) VALUES
(1, 1, '_photo', 'Photo', 11, 0, 'a:3:{s:12:\"default_root\";s:1:\"1\";s:9:\"img_width\";s:3:\"200\";s:10:\"img_height\";s:3:\"220\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(2, 1, '_name', 'Name', 1, 60, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(3, 1, '_father', 'Father\'s Name', 1, 60, NULL, '0', '0', '0', '1', '0', '0', '', 0, '1', '1', 6),
(4, 1, '_mother', 'Mother\'s Name', 1, 60, NULL, '0', '0', '0', '1', '0', '0', '', 0, '1', '1', 7),
(5, 1, '_spouse', 'Spouse\'s Name', 1, 60, NULL, '0', '0', '0', '0', '0', '0', '', 0, '1', '1', 11),
(6, 2, '_type', 'Type', 7, 20, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:7:\"Present\";i:1;s:7:\"Present\";}i:1;a:2:{i:0;s:9:\"Permanent\";i:1;s:9:\"Permanent\";}i:2;a:2:{i:0;s:19:\"Present & Permanent\";i:1;s:19:\"Present & Permanent\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(7, 2, '_address', 'Address', 1, 200, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(8, 2, '_post', 'Post', 7, 50, 'a:1:{s:8:\"textsize\";s:2:\"12\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(9, 2, '_police', 'Police', 7, 50, 'a:1:{s:8:\"textsize\";s:2:\"12\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(10, 2, '_district', 'District', 7, 50, 'a:1:{s:8:\"textsize\";s:2:\"12\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 5),
(12, 1, '_eaddress', 'Address', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:14:\"cus_empaddress\";s:5:\"field\";s:38:\"_type,_address,_post,_police,_district\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 30),
(13, 1, '_mobile1', 'Mobile-1', 1, 15, 'a:3:{s:8:\"textsize\";s:2:\"30\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 8),
(14, 1, '_mobile2', 'Mobile-2', 1, 15, NULL, '0', '0', '0', '0', '0', '0', '', 0, '1', '1', 9),
(15, 1, '_spousesmobile', 'Spouse\'s Mobile', 1, 15, NULL, '0', '0', '0', '0', '0', '0', '', 0, '1', '1', 12),
(16, 1, '_nid', 'N-ID', 1, 30, NULL, '0', '0', '0', '1', '0', '0', '', 0, '1', '1', 16),
(17, 1, '_gender', 'Gender', 7, 10, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:2:{i:0;a:2:{i:0;s:4:\"Male\";i:1;s:4:\"Male\";}i:1;a:2:{i:0;s:6:\"Female\";i:1;s:6:\"Female\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 18),
(18, 1, '_bloodgroup', 'Blood Group', 7, 3, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:8:{i:0;a:2:{i:0;s:2:\"A+\";i:1;s:2:\"A+\";}i:1;a:2:{i:0;s:2:\"B+\";i:1;s:2:\"B+\";}i:2;a:2:{i:0;s:3:\"AB+\";i:1;s:3:\"AB+\";}i:3;a:2:{i:0;s:2:\"O+\";i:1;s:2:\"O+\";}i:4;a:2:{i:0;s:2:\"A-\";i:1;s:2:\"A-\";}i:5;a:2:{i:0;s:2:\"O-\";i:1;s:2:\"O-\";}i:6;a:2:{i:0;s:2:\"B-\";i:1;s:2:\"B-\";}i:7;a:2:{i:0;s:3:\"AB-\";i:1;s:3:\"AB-\";}}}', '0', '0', '0', '1', '0', '0', '', 0, '1', '1', 20),
(20, 1, '_religion', 'Religion', 7, 50, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 19),
(21, 1, '_dob', 'DOB', 2, 0, NULL, '0', '0', '0', '1', '0', '0', '', 0, '1', '1', 13),
(24, 1, '_education', 'Higher Education', 7, 30, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 15),
(25, 1, '_email', 'Email', 1, 50, NULL, '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 10),
(26, 3, '_ledger', 'Ledger', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:10:\"acc_ledger\";s:7:\"display\";a:1:{i:0;s:7:\"_ledger\";}s:7:\"orderby\";s:6:\"_group\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(27, 3, '_type', 'Type', 7, 10, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:2:{i:0;a:2:{i:0;s:1:\"P\";i:1;s:7:\"Payable\";}i:1;a:2:{i:0;s:1:\"D\";i:1;s:10:\"Deductions\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(28, 3, '_calculation', 'Calculation', 7, 20, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:6:{i:0;a:2:{i:0;s:5:\"Fixed\";i:1;s:12:\"Fixed Amount\";}i:1;a:2:{i:0;s:7:\"Percent\";i:1;s:10:\"Percentage\";}i:2;a:2:{i:0;s:10:\"Attendance\";i:1;s:13:\"On Attendance\";}i:3;a:2:{i:0;s:10:\"Production\";i:1;s:13:\"On Production\";}i:4;a:2:{i:0;s:8:\"Variable\";i:1;s:8:\"Variable\";}i:5;a:2:{i:0;s:4:\"Itax\";i:1;s:10:\"Income TAX\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(29, 1, '_number', 'Employee Number', 1, 30, NULL, '1', '1', '0', '1', '0', '1', '', 0, '1', '1', 2),
(30, 3, '_onhead', 'On Head', 10, 0, 'a:11:{s:6:\"height\";s:3:\"250\";s:6:\"column\";s:1:\"1\";s:8:\"dropdown\";s:1:\"1\";s:6:\"search\";s:1:\"1\";s:5:\"table\";s:10:\"acc_ledger\";s:7:\"display\";a:1:{i:0;s:7:\"_ledger\";}s:7:\"orderby\";s:7:\"_ledger\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 4),
(31, 4, '_photo', 'Photo', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"2\";s:8:\"htmlcols\";s:3:\"200\";s:8:\"htmlrows\";s:3:\"230\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 1),
(32, 4, '_number', 'Employee Number', 1, 30, NULL, '1', '1', '0', '1', '1', '1', '', 0, '0', '1', 2),
(33, 4, '_name', 'Name', 1, 60, NULL, '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 3),
(34, 4, '_jobtitle', 'Job Title', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:12:\"cus_jobtitle\";s:7:\"display\";a:1:{i:0;s:6:\"_title\";}s:7:\"orderby\";s:6:\"_title\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 4),
(37, 4, '_mobile1', 'Mobile-1', 1, 15, 'a:3:{s:8:\"textsize\";s:2:\"30\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 5),
(39, 4, '_email', 'Email', 1, 50, NULL, '0', '1', '0', '0', '1', '0', '', 0, '0', '1', 6),
(48, 4, '_department', 'Department', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:14:\"cus_department\";s:7:\"display\";a:1:{i:0;s:11:\"_department\";}s:7:\"orderby\";s:11:\"_department\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 7),
(50, 5, '_ledger', 'Ledger', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:15:\"cus_payheadview\";s:7:\"display\";a:1:{i:0;s:7:\"_ledger\";}s:7:\"orderby\";s:7:\"_ledger\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 1),
(51, 5, '_amount', 'Amount/Percent', 4, 15, NULL, '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 2),
(53, 5, '_period', 'Period', 7, 10, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:6:{i:0;a:2:{i:0;s:4:\"Only\";i:1;s:4:\"Only\";}i:1;a:2:{i:0;s:6:\"Hourly\";i:1;s:6:\"Hourly\";}i:2;a:2:{i:0;s:5:\"Daily\";i:1;s:5:\"Daily\";}i:3;a:2:{i:0;s:6:\"Weekly\";i:1;s:6:\"Weekly\";}i:4;a:2:{i:0;s:7:\"Monthly\";i:1;s:7:\"Monthly\";}i:5;a:2:{i:0;s:6:\"Yearly\";i:1;s:6:\"Yearly\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(55, 5, '_effect', 'Effect in Pay', 3, 1, NULL, '0', '1', '0', '1', '0', '0', '1', 0, '1', '1', 4),
(57, 6, '_unit', 'Unit', 1, 10, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(60, 8, '_name', 'Candidate Name', 1, 60, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(61, 8, '_contactno', 'Mobile', 1, 20, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(62, 8, '_email', 'Email', 1, 100, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(63, 9, '_title', 'Job Title', 1, 30, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(64, 9, '_specification', 'Job Specification', 5, 0, NULL, '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 2),
(65, 10, '_name', 'Vacancy Name', 1, 50, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(66, 10, '_jobtitle', 'Job Title', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:12:\"cus_jobtitle\";s:7:\"display\";a:1:{i:0;s:6:\"_title\";}s:7:\"orderby\";s:6:\"_title\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(67, 10, '_hiring', 'Hiring Manager', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:13:\"cus_employees\";s:7:\"display\";a:2:{i:0;s:7:\"_number\";i:1;s:5:\"_name\";}s:7:\"orderby\";s:7:\"_number\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(68, 1, '_jobtitle', 'Designation', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:12:\"cus_jobtitle\";s:7:\"display\";a:1:{i:0;s:6:\"_title\";}s:7:\"orderby\";s:6:\"_title\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(69, 10, '_nop', 'Number of Positions', 4, 15, 'a:7:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 5),
(70, 10, '_description', 'Description', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 6),
(71, 10, '_active', 'Active', 3, 1, NULL, '0', '1', '0', '1', '0', '0', '1', 0, '1', '1', 7),
(72, 8, '_vacancy', 'Job Vacancy', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:13:\"cus_vacancies\";s:7:\"display\";a:1:{i:0;s:5:\"_name\";}s:7:\"orderby\";s:5:\"_name\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:13:\"(_active = 1)\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(73, 8, '_resume', 'Resume', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"3\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:196:\"Save,NewPage,Preview,Templates,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,BidiLtr,BidiRtl,Language,Link,Unlink,Anchor,Flash,PageBreak,Iframe,About\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 9),
(74, 8, '_comment', 'Comment', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"40\";s:8:\"htmlrows\";s:1:\"4\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 10),
(75, 8, '_date', 'Date of Application', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 5),
(77, 11, '_department', 'Department', 1, 50, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(78, 1, '_department', 'Department', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:14:\"cus_department\";s:7:\"display\";a:1:{i:0;s:11:\"_department\";}s:7:\"orderby\";s:11:\"_department\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 21),
(79, 10, '_department', 'Department', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:14:\"cus_department\";s:7:\"display\";a:1:{i:0;s:11:\"_department\";}s:7:\"orderby\";s:11:\"_department\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 3),
(80, 8, '_qualification', 'Edu-Qualification', 7, 50, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 6),
(81, 8, '_experience', 'Experience', 7, 200, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 7),
(82, 8, '_salary', 'Expected Salary', 4, 15, 'a:6:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"2\";s:7:\"summary\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 8),
(83, 8, '_selected', 'Selected', 3, 1, NULL, '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 11),
(84, 4, '_eaddress', 'Address', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:14:\"cus_empaddress\";s:5:\"field\";s:38:\"_type,_address,_post,_police,_district\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 8),
(85, 12, '_jobtitle', 'Job Title', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"20\";s:6:\"search\";s:1:\"1\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:12:\"cus_jobtitle\";s:7:\"display\";a:1:{i:0;s:6:\"_title\";}s:7:\"orderby\";s:6:\"_title\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:1:{s:14:\"_specification\";s:14:\"_specification\";}}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 3),
(86, 12, '_specification', 'Specification', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(87, 12, '_status', 'Status', 7, 50, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 5),
(88, 12, '_date', 'Date', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(89, 12, '_location', 'Location', 7, 70, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 6),
(90, 12, '_responsibility', 'Responsibility', 7, 60, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 7),
(91, 12, '_salary', 'Salary', 4, 15, 'a:7:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"2\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 8),
(96, 4, '_job', 'Job', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:7:\"cus_job\";s:5:\"field\";s:79:\"_jtype,_date,_jobtitle,_specification,_status,_location,_responsibility,_salary\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 9),
(97, 13, '_level', 'Level/Degree', 7, 50, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(98, 13, '_subject', 'Subject', 7, 50, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(99, 13, '_institute', 'Institute', 7, 100, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(100, 13, '_year', 'Year', 1, 4, 'a:3:{s:8:\"textsize\";s:1:\"8\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(101, 13, '_score', 'GPA/Score', 7, 20, 'a:1:{s:8:\"textsize\";s:2:\"10\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 5),
(102, 13, '_edsdate', 'Start Date', 2, 0, NULL, '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 6),
(103, 13, '_ededate', 'End Date', 2, 0, NULL, '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 7),
(104, 4, '_education', 'Education', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:13:\"cus_education\";s:5:\"field\";s:65:\"{value},_level,_subject,_institute,_year,_score,_edsdate,_ededate\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 11),
(105, 14, '_company', 'Company', 1, 100, 'a:3:{s:8:\"textsize\";s:2:\"20\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(106, 14, '_jobtitle', 'Job Title', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:12:\"cus_jobtitle\";s:7:\"display\";a:1:{i:0;s:6:\"_title\";}s:7:\"orderby\";s:6:\"_title\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(107, 14, '_wfrom', 'From', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(108, 14, '_wto', 'To', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(109, 14, '_note', 'Note', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 5),
(110, 4, '_experience', 'Experience', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:14:\"cus_experience\";s:5:\"field\";s:44:\"{value},_company,_jobtitle,_wfrom,_wto,_note\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 12),
(111, 15, '_name', 'Name', 1, 60, 'a:3:{s:8:\"textsize\";s:2:\"20\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(112, 15, '_relationship', 'Relationship', 7, 50, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(113, 15, '_mobile', 'Mobile', 1, 20, 'a:3:{s:8:\"textsize\";s:2:\"13\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(114, 15, '_home', 'Home Telephone', 1, 20, 'a:3:{s:8:\"textsize\";s:2:\"13\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 4),
(115, 15, '_work', 'Work Telephone', 1, 20, 'a:3:{s:8:\"textsize\";s:2:\"13\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 5),
(116, 4, '_emergency', 'Emergency', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:13:\"cus_emergency\";s:5:\"field\";s:47:\"{value},_name,_relationship,_mobile,_home,_work\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 13),
(117, 16, '_name', 'Name', 1, 60, 'a:3:{s:8:\"textsize\";s:2:\"20\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(118, 16, '_father', 'Father\'s Name', 1, 60, 'a:3:{s:8:\"textsize\";s:2:\"16\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(119, 16, '_mother', 'Mother\'s Name', 1, 60, 'a:3:{s:8:\"textsize\";s:2:\"16\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(120, 16, '_occupation', 'Occupation', 7, 60, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 9),
(121, 16, '_workstation', 'Work Station', 1, 100, NULL, '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 10),
(122, 16, '_address1', 'Address-1', 1, 200, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 11),
(123, 16, '_address2', 'Address-2', 1, 200, NULL, '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 12),
(124, 16, '_mobile', 'Mobile', 1, 30, 'a:3:{s:8:\"textsize\";s:2:\"20\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 6),
(125, 16, '_email', 'Email', 1, 100, 'a:3:{s:8:\"textsize\";s:2:\"20\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 7),
(126, 16, '_nationalid', 'National ID', 1, 50, 'a:3:{s:8:\"textsize\";s:2:\"16\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 8),
(127, 4, '_guarantor', 'Guarantor', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:13:\"cus_guarantor\";s:5:\"field\";s:105:\"_photo,_name,_father,_mother,_dob,_mobile,_email,_nationalid,_occupation,_workstation,_address1,_address2\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 14),
(128, 16, '_photo', 'Photo', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"2\";s:8:\"htmlcols\";s:3:\"190\";s:8:\"htmlrows\";s:3:\"220\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(129, 16, '_dob', 'Date of Birth', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 5),
(130, 17, '_nname', 'Name', 1, 60, 'a:3:{s:8:\"textsize\";s:2:\"20\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(131, 17, '_nfather', 'Father\'s Name', 1, 60, 'a:3:{s:8:\"textsize\";s:2:\"20\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(132, 17, '_nmother', 'Mother\'s Name', 1, 60, 'a:3:{s:8:\"textsize\";s:2:\"20\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(133, 17, '_ndob', 'Date of Birth', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 5),
(134, 17, '_nnationalid', 'National ID', 1, 50, 'a:3:{s:8:\"textsize\";s:2:\"15\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 6),
(135, 17, '_nmobile', 'Mobile', 1, 50, 'a:3:{s:8:\"textsize\";s:2:\"15\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 7),
(136, 17, '_naddress1', 'Address-1', 1, 200, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 10),
(137, 17, '_naddress2', 'Address-2', 1, 200, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 11),
(138, 17, '_nrelation', 'Relation', 7, 50, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 8),
(139, 17, '_nbenefit', 'Benefit (%)', 4, 15, 'a:7:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 9),
(140, 4, '_nominee', 'Nominee', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:11:\"cus_nominee\";s:5:\"field\";s:103:\"_nphoto,_nname,_nfather,_nmother,_ndob,_nnationalid,_nmobile,_nrelation,_nbenefit,_naddress1,_naddress2\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 15),
(141, 17, '_nphoto', 'Photo', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"2\";s:8:\"htmlcols\";s:3:\"190\";s:8:\"htmlrows\";s:3:\"220\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(142, 18, '_tfrom', 'From', 7, 100, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(143, 18, '_tto', 'To', 7, 100, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(144, 18, '_ttransfer', 'Transfer Date', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(145, 18, '_tjoin', 'Join Date', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(146, 18, '_tnote', 'Note', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 5),
(147, 4, '_transfer', 'Transfer', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:12:\"cus_transfer\";s:5:\"field\";s:44:\"{value},_tfrom,_tto,_ttransfer,_tjoin,_tnote\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 16),
(148, 12, '_jtype', 'Type', 7, 20, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:7:\"Joining\";i:1;s:7:\"Joining\";}i:1;a:2:{i:0;s:9:\"Promotion\";i:1;s:9:\"Promotion\";}i:2;a:2:{i:0;s:8:\"Demotion\";i:1;s:8:\"Demotion\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(149, 19, '_ctype', 'Type', 7, 60, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(150, 19, '_csdate', 'Start Date', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(151, 19, '_cedate', 'End Date', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(152, 19, '_cdetail', 'Contract Details', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 4),
(153, 4, '_jobcontract', 'Job Contract', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:15:\"cus_jobcontract\";s:5:\"field\";s:39:\"{value},_ctype,_csdate,_cedate,_cdetail\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 10),
(154, 20, '_department', 'Department', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:14:\"cus_department\";s:7:\"display\";a:1:{i:0;s:11:\"_department\";}s:7:\"orderby\";s:11:\"_department\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 7),
(155, 20, '_email', 'Email', 1, 50, NULL, '0', '1', '0', '0', '1', '0', '', 0, '0', '1', 6),
(156, 20, '_jobtitle', 'Job Title', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:12:\"cus_jobtitle\";s:7:\"display\";a:1:{i:0;s:6:\"_title\";}s:7:\"orderby\";s:6:\"_title\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 4),
(157, 20, '_mobile1', 'Mobile-1', 1, 15, 'a:3:{s:8:\"textsize\";s:2:\"30\";s:8:\"keyboard\";s:1:\"0\";s:8:\"datalist\";a:0:{}}', '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 5),
(158, 20, '_name', 'Name', 1, 60, NULL, '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 3),
(159, 20, '_number', 'Employee Number', 1, 30, NULL, '1', '1', '0', '1', '1', '1', '', 0, '0', '1', 2),
(160, 20, '_payinfo', 'Pay Info', 9, 11, 'a:5:{s:8:\"rowlimit\";s:1:\"0\";s:9:\"rowadddel\";s:1:\"1\";s:7:\"tableid\";s:1:\"5\";s:5:\"table\";s:11:\"cus_payinfo\";s:5:\"field\";s:39:\"{value},_ledger,_amount,_period,_effect\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 8),
(161, 20, '_photo', 'Photo', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"2\";s:8:\"htmlcols\";s:3:\"200\";s:8:\"htmlrows\";s:3:\"230\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '1', '1', '0', '', 0, '0', '1', 1),
(162, 21, '_type', 'Type', 7, 60, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(163, 21, '_name', 'Name', 7, 100, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(164, 21, '_subject', 'Subject', 7, 60, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(165, 21, '_organized', 'Organized', 7, 60, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(166, 21, '_place', 'Place', 7, 60, 'a:1:{s:8:\"textsize\";s:2:\"15\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 5),
(167, 21, '_trfrom', 'From', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 6),
(168, 21, '_trto', 'To', 2, 0, NULL, '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 7),
(169, 21, '_result', 'Result', 7, 30, 'a:1:{s:8:\"textsize\";s:2:\"13\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 8),
(170, 21, '_note', 'Note', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 9),
(171, 4, '_training', 'Training', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:12:\"cus_training\";s:5:\"field\";s:74:\"{value},_type,_name,_subject,_organized,_place,_trfrom,_trto,_result,_note\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 17),
(172, 22, '_language', 'Language', 7, 30, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(173, 22, '_fluency', 'Fluency', 7, 20, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:7:\"Writing\";i:1;s:7:\"Writing\";}i:1;a:2:{i:0;s:8:\"Speaking\";i:1;s:8:\"Speaking\";}i:2;a:2:{i:0;s:7:\"Reading\";i:1;s:7:\"Reading\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(174, 22, '_competency', 'Competency', 7, 20, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:4:{i:0;a:2:{i:0;s:4:\"Poor\";i:1;s:4:\"Poor\";}i:1;a:2:{i:0;s:5:\"Basic\";i:1;s:5:\"Basic\";}i:2;a:2:{i:0;s:4:\"Good\";i:1;s:4:\"Good\";}i:3;a:2:{i:0;s:13:\"Mother Tongue\";i:1;s:13:\"Mother Tongue\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(175, 22, '_lnote', 'Note', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 4),
(177, 4, '_languageid', 'Languageid', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:12:\"cus_language\";s:5:\"field\";s:45:\"{value},_language,_fluency,_competency,_lnote\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 18),
(178, 23, '_rcategory', 'Category', 7, 30, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:2:{i:0;a:2:{i:0;s:6:\"Reward\";i:1;s:6:\"Reward\";}i:1;a:2:{i:0;s:10:\"Punishment\";i:1;s:10:\"Punishment\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(179, 23, '_rtype', 'Type', 7, 100, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(180, 23, '_rcause', 'Cause', 7, 100, 'a:1:{s:8:\"textsize\";s:2:\"20\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(181, 23, '_rnote', 'Note', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"80\";s:8:\"htmlrows\";s:1:\"8\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 4),
(182, 4, '_rewardid', 'Rewardid', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:10:\"cus_reward\";s:5:\"field\";s:40:\"{value},_rcategory,_rtype,_rcause,_rnote\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 19),
(183, 24, '_dfrom', 'From', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(184, 24, '_dto', 'To', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(185, 25, '_name', 'Name', 1, 60, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(186, 25, '_date', 'Date', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(187, 25, '_type', 'Type', 7, 10, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:2:{i:0;a:2:{i:0;s:4:\"Full\";i:1;s:8:\"Full Day\";}i:1;a:2:{i:0;s:4:\"Half\";i:1;s:8:\"Half Day\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(188, 24, '_holidaysid', 'Holidaysid', 9, 11, 'a:3:{s:8:\"rowlimit\";s:1:\"0\";s:5:\"table\";s:17:\"cus_holidaydetail\";s:5:\"field\";s:25:\"{value},_name,_date,_type\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 3),
(189, 26, '_monday', 'Monday', 7, 5, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:4:\"full\";i:1;s:8:\"Full Day\";}i:1;a:2:{i:0;s:4:\"half\";i:1;s:8:\"Half Day\";}i:2;a:2:{i:0;s:3:\"off\";i:1;s:7:\"Off Day\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '0', '1', 2),
(190, 26, '_tuesday', 'Tuesday', 7, 5, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:4:\"full\";i:1;s:8:\"Full Day\";}i:1;a:2:{i:0;s:4:\"half\";i:1;s:8:\"Half Day\";}i:2;a:2:{i:0;s:3:\"off\";i:1;s:7:\"Off Day\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '0', '1', 3),
(191, 26, '_wednesday', 'Wednesday', 7, 5, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:4:\"full\";i:1;s:8:\"Full Day\";}i:1;a:2:{i:0;s:4:\"half\";i:1;s:8:\"Half Day\";}i:2;a:2:{i:0;s:3:\"off\";i:1;s:7:\"Off Day\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '0', '1', 4),
(192, 26, '_thursday', 'Thursday', 7, 5, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:4:\"full\";i:1;s:8:\"Full Day\";}i:1;a:2:{i:0;s:4:\"half\";i:1;s:8:\"Half Day\";}i:2;a:2:{i:0;s:3:\"off\";i:1;s:7:\"Off Day\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '0', '1', 5),
(193, 26, '_friday', 'Friday', 7, 5, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:4:\"full\";i:1;s:8:\"Full Day\";}i:1;a:2:{i:0;s:4:\"half\";i:1;s:8:\"Half Day\";}i:2;a:2:{i:0;s:3:\"off\";i:1;s:7:\"Off Day\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '0', '1', 6),
(194, 26, '_saturday', 'Saturday', 7, 5, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:4:\"full\";i:1;s:8:\"Full Day\";}i:1;a:2:{i:0;s:4:\"half\";i:1;s:8:\"Half Day\";}i:2;a:2:{i:0;s:3:\"off\";i:1;s:7:\"Off Day\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '0', '1', 7),
(195, 26, '_sunday', 'Sunday', 7, 5, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:3:{i:0;a:2:{i:0;s:4:\"full\";i:1;s:8:\"Full Day\";}i:1;a:2:{i:0;s:4:\"half\";i:1;s:8:\"Half Day\";}i:2;a:2:{i:0;s:3:\"off\";i:1;s:7:\"Off Day\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '0', '1', 1),
(196, 27, '_type', 'Type', 1, 50, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(198, 28, '_lemployid', 'Employees', 10, 0, 'a:11:{s:6:\"height\";s:3:\"400\";s:6:\"column\";s:1:\"4\";s:8:\"dropdown\";s:1:\"1\";s:6:\"search\";s:1:\"1\";s:5:\"table\";s:13:\"cus_employees\";s:7:\"display\";a:2:{i:0;s:7:\"_number\";i:1;s:5:\"_name\";}s:7:\"orderby\";s:7:\"_number\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(199, 28, '_ltype', 'Leave Type', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"1\";s:8:\"allowadd\";s:1:\"1\";s:5:\"table\";s:14:\"cus_leavetypes\";s:7:\"display\";a:1:{i:0;s:5:\"_type\";}s:7:\"orderby\";s:5:\"_type\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(200, 28, '_lday', 'Entitlement Days', 4, 15, 'a:7:{s:8:\"rangemin\";s:1:\"1\";s:8:\"rangemax\";s:2:\"99\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '1', 0, '1', '1', 4),
(201, 28, '_period', 'Period', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"1\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:12:\"cus_holidays\";s:7:\"display\";a:2:{i:0;s:6:\"_dfrom\";i:1;s:4:\"_dto\";}s:7:\"orderby\";s:6:\"_dfrom\";s:4:\"type\";s:4:\"DESC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(202, 29, '_employee', 'Employee', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:13:\"cus_employees\";s:7:\"display\";a:2:{i:0;s:7:\"_number\";i:1;s:5:\"_name\";}s:7:\"orderby\";s:7:\"_number\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(203, 29, '_type', 'Type', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"1\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:16:\"cus_leavebalance\";s:7:\"display\";a:1:{i:0;s:6:\"_ltype\";}s:7:\"orderby\";s:6:\"_ltype\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:1:{s:9:\"_employee\";s:4:\"_eid\";}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:1:{s:8:\"_balance\";s:5:\"_lday\";}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(204, 29, '_balance', 'Balance', 4, 15, 'a:7:{s:8:\"rangemin\";s:1:\"1\";s:8:\"rangemax\";s:2:\"99\";s:8:\"readonly\";s:1:\"1\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(205, 29, '_start', 'Start', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(206, 29, '_end', 'End', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 5),
(207, 29, '_note', 'Note', 5, 0, 'a:4:{s:10:\"htmloption\";s:1:\"1\";s:8:\"htmlcols\";s:2:\"40\";s:8:\"htmlrows\";s:1:\"4\";s:11:\"htmltoolbar\";s:0:\"\";}', '0', '1', '0', '0', '0', '0', '', 0, '1', '1', 6),
(208, 30, '_employee', 'Employee', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"1\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:13:\"cus_employees\";s:7:\"display\";a:2:{i:0;s:7:\"_number\";i:1;s:5:\"_name\";}s:7:\"orderby\";s:5:\"_name\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:1:{s:7:\"_number\";s:7:\"_number\";}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(209, 30, '_number', 'Number', 4, 15, 'a:7:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"readonly\";s:1:\"1\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 2),
(210, 30, '_datetime', 'DateTime', 6, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 4),
(211, 30, '_type', 'Type', 7, 3, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:2:{i:0;a:2:{i:0;s:2:\"In\";i:1;s:2:\"In\";}i:1;a:2:{i:0;s:3:\"Out\";i:1;s:3:\"Out\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 3),
(212, 31, '_grade', 'Grade', 1, 20, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(213, 1, '_grade', 'Grade', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"1\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:9:\"cus_grade\";s:7:\"display\";a:1:{i:0;s:6:\"_grade\";}s:7:\"orderby\";s:6:\"_grade\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 5),
(214, 1, '_location', 'Location', 7, 60, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 22),
(215, 1, '_officedes', 'Office Description', 1, 120, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 23),
(216, 1, '_bank', 'Bank Name', 7, 100, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 24),
(217, 1, '_bankac', 'Bank A\\C No.', 1, 40, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 25),
(218, 1, '_tradeing', 'Tradeing MIS (%)', 4, 15, NULL, '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 26),
(219, 1, '_project', 'Project MIS (%)', 4, 15, NULL, '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 27),
(220, 32, '_code', 'Code', 1, 20, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(221, 32, '_name', 'Name', 1, 100, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(222, 33, '_code', 'Code', 1, 20, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(223, 33, '_name', 'Name', 1, 100, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(224, 1, '_costcenter', 'Cost Center', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:14:\"cus_costcenter\";s:7:\"display\";a:1:{i:0;s:5:\"_name\";}s:7:\"orderby\";s:5:\"_name\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 28),
(225, 1, '_profitcenter', 'Profit Center', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:16:\"cus_profitcenter\";s:7:\"display\";a:1:{i:0;s:5:\"_name\";}s:7:\"orderby\";s:5:\"_name\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 29),
(226, 34, '_ledger', 'Ledger', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:15:\"cus_payheadview\";s:7:\"display\";a:1:{i:0;s:7:\"_ledger\";}s:7:\"orderby\";s:7:\"_ledger\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 1),
(227, 34, '_exlimit', 'Limit of Exemption', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"2\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 2),
(228, 35, '_plevel', 'Level', 7, 2, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:9:{i:0;a:2:{i:0;s:1:\"1\";i:1;s:9:\"1st Level\";}i:1;a:2:{i:0;s:1:\"2\";i:1;s:9:\"2nd Level\";}i:2;a:2:{i:0;s:1:\"3\";i:1;s:9:\"3th Level\";}i:3;a:2:{i:0;s:1:\"4\";i:1;s:9:\"4th Level\";}i:4;a:2:{i:0;s:1:\"5\";i:1;s:9:\"5th Level\";}i:5;a:2:{i:0;s:1:\"6\";i:1;s:9:\"6th Level\";}i:6;a:2:{i:0;s:1:\"7\";i:1;s:9:\"7th Level\";}i:7;a:2:{i:0;s:1:\"8\";i:1;s:9:\"8th Level\";}i:8;a:2:{i:0;s:1:\"9\";i:1;s:9:\"9th Level\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(229, 35, '_plimit', 'Limit', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"2\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 2),
(230, 35, '_ppercent', 'Percent', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 3),
(231, 36, '_rlimit', 'Limit', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"2\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 2),
(232, 36, '_rpercent', 'Percent', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 3),
(233, 36, '_rlevel', 'Level', 7, 2, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:6:{i:0;a:2:{i:0;s:1:\"1\";i:1;s:9:\"1st Level\";}i:1;a:2:{i:0;s:1:\"2\";i:1;s:9:\"2nd Level\";}i:2;a:2:{i:0;s:1:\"3\";i:1;s:9:\"3th Level\";}i:3;a:2:{i:0;s:1:\"4\";i:1;s:9:\"4th Level\";}i:4;a:2:{i:0;s:1:\"5\";i:1;s:9:\"5th Level\";}i:5;a:2:{i:0;s:1:\"6\";i:1;s:9:\"6th Level\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(234, 37, '_maxinvestment', 'Max Investment (%)', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 3),
(235, 37, '_ledgerno', 'Ledger No', 9, 11, 'a:5:{s:8:\"rowlimit\";s:1:\"0\";s:9:\"rowadddel\";s:1:\"1\";s:7:\"tableid\";s:2:\"34\";s:5:\"table\";s:14:\"cus_itaxledger\";s:5:\"field\";s:16:\"_ledger,_exlimit\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 1),
(236, 37, '_payableno', 'Payable No', 9, 11, 'a:5:{s:8:\"rowlimit\";s:1:\"0\";s:9:\"rowadddel\";s:1:\"1\";s:7:\"tableid\";s:2:\"35\";s:5:\"table\";s:15:\"cus_itaxpayable\";s:5:\"field\";s:25:\"_plevel,_plimit,_ppercent\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 2),
(237, 37, '_rebateno', 'Rebate No', 9, 11, 'a:5:{s:8:\"rowlimit\";s:1:\"0\";s:9:\"rowadddel\";s:1:\"1\";s:7:\"tableid\";s:2:\"36\";s:5:\"table\";s:14:\"cus_itaxrebate\";s:5:\"field\";s:25:\"_rlevel,_rlimit,_rpercent\";}', '0', '1', '0', '1', '0', '0', '0', 0, '0', '1', 4),
(238, 38, '_month', 'Month', 7, 2, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:12:{i:0;a:2:{i:0;s:1:\"1\";i:1;s:7:\"January\";}i:1;a:2:{i:0;s:1:\"2\";i:1;s:8:\"February\";}i:2;a:2:{i:0;s:1:\"3\";i:1;s:5:\"March\";}i:3;a:2:{i:0;s:1:\"4\";i:1;s:5:\"April\";}i:4;a:2:{i:0;s:1:\"5\";i:1;s:3:\"May\";}i:5;a:2:{i:0;s:1:\"6\";i:1;s:4:\"June\";}i:6;a:2:{i:0;s:1:\"7\";i:1;s:4:\"July\";}i:7;a:2:{i:0;s:1:\"8\";i:1;s:6:\"August\";}i:8;a:2:{i:0;s:1:\"9\";i:1;s:9:\"September\";}i:9;a:2:{i:0;s:2:\"10\";i:1;s:7:\"October\";}i:10;a:2:{i:0;s:2:\"11\";i:1;s:8:\"November\";}i:11;a:2:{i:0;s:2:\"12\";i:1;s:8:\"December\";}}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 2),
(239, 38, '_year', 'Year', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"1\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:8:\"cus_year\";s:7:\"display\";a:1:{i:0;s:5:\"_year\";}s:7:\"orderby\";s:5:\"_year\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 3),
(240, 38, '_paydays', 'Pay Days', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"1\";s:8:\"rangemax\";s:2:\"99\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 4),
(241, 38, '_arrdays', 'Arr Days', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"1\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 5),
(242, 38, '_empcode', 'Employee Code', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:13:\"cus_employees\";s:7:\"display\";a:2:{i:0;s:7:\"_number\";i:1;s:5:\"_name\";}s:7:\"orderby\";s:7:\"_number\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 1),
(243, 39, '_ledger', 'Ledger', 8, 11, 'a:11:{s:8:\"textsize\";s:2:\"30\";s:6:\"search\";s:1:\"0\";s:8:\"allowadd\";s:0:\"\";s:5:\"table\";s:15:\"cus_payheadview\";s:7:\"display\";a:1:{i:0;s:7:\"_ledger\";}s:7:\"orderby\";s:7:\"_ledger\";s:4:\"type\";s:3:\"ASC\";s:5:\"where\";s:0:\"\";s:6:\"filter\";a:0:{}s:6:\"fwhere\";a:0:{}s:8:\"autofill\";a:0:{}}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 1),
(244, 39, '_amount', 'Amount', 4, 15, 'a:8:{s:8:\"rangemin\";s:1:\"0\";s:8:\"rangemax\";s:10:\"9999999999\";s:8:\"negative\";s:1:\"0\";s:8:\"readonly\";s:1:\"0\";s:6:\"format\";s:1:\"2\";s:7:\"summary\";s:1:\"0\";s:13:\"autoincrement\";s:1:\"0\";s:11:\"calculation\";s:0:\"\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 2),
(245, 38, '_structureno', 'Structure No', 9, 11, 'a:5:{s:8:\"rowlimit\";s:1:\"0\";s:9:\"rowadddel\";s:1:\"1\";s:7:\"tableid\";s:2:\"39\";s:5:\"table\";s:21:\"cus_sstructuredetails\";s:5:\"field\";s:23:\"{value},_ledger,_amount\";}', '0', '1', '0', '1', '0', '0', '0', 0, '1', '1', 6),
(246, 1, '_active', 'Active', 7, 1, 'a:2:{s:8:\"textsize\";s:2:\"30\";s:3:\"row\";a:2:{i:0;a:2:{i:0;s:1:\"1\";i:1;s:6:\"Active\";}i:1;a:2:{i:0;s:1:\"0\";i:1;s:8:\"Inactive\";}}}', '0', '1', '0', '1', '0', '0', '1', 0, '1', '1', 31),
(247, 1, '_doj', 'DOJ', 2, 0, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 14),
(248, 1, '_tin', 'TIN', 1, 40, NULL, '0', '1', '0', '1', '0', '0', '', 0, '1', '1', 17);

-- --------------------------------------------------------

--
-- Table structure for table `sys_custompage`
--

CREATE TABLE `sys_custompage` (
  `_id` int(11) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_template` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_customreport`
--

CREATE TABLE `sys_customreport` (
  `_id` int(11) NOT NULL,
  `_title` varchar(100) NOT NULL,
  `_type` int(11) NOT NULL,
  `_where` enum('1','0') DEFAULT '0',
  `_query` longtext NOT NULL,
  `_template` longtext DEFAULT NULL,
  `_event` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_dashboard`
--

CREATE TABLE `sys_dashboard` (
  `_id` int(11) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_option` longtext DEFAULT NULL,
  `_template` longtext NOT NULL,
  `_predata` longtext DEFAULT NULL,
  `_sort` int(11) NOT NULL DEFAULT 0,
  `_show` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_editortemplates`
--

CREATE TABLE `sys_editortemplates` (
  `_id` int(11) NOT NULL,
  `_table` varchar(100) NOT NULL,
  `_title` varchar(100) NOT NULL,
  `_template` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_filterfields`
--

CREATE TABLE `sys_filterfields` (
  `_id` int(11) NOT NULL,
  `_filterid` int(11) NOT NULL,
  `_serial` int(11) NOT NULL,
  `_field` varchar(60) NOT NULL,
  `_datatype` int(11) NOT NULL,
  `_title` varchar(60) NOT NULL,
  `_filterby` varchar(10) NOT NULL DEFAULT 'none',
  `_option` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_filterfields`
--

INSERT INTO `sys_filterfields` (`_id`, `_filterid`, `_serial`, `_field`, `_datatype`, `_title`, `_filterby`, `_option`) VALUES
(1, 1, 1, '_date', 10, 'Date', 'monthyear', '');

-- --------------------------------------------------------

--
-- Table structure for table `sys_image`
--

CREATE TABLE `sys_image` (
  `_id` int(11) NOT NULL,
  `_related` int(11) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_type` varchar(255) NOT NULL,
  `_image` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_image`
--

INSERT INTO `sys_image` (`_id`, `_related`, `_name`, `_type`, `_image`) VALUES
(1, 0, 'Employee', 'folder', NULL);
INSERT INTO `sys_image` (`_id`, `_related`, `_name`, `_type`, `_image`) VALUES
(2, 1, 'Akash', 'image/jpeg', 0xffd8ffe000104a46494600010101006000600000fffe003b43524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763830292c207175616c697479203d2039300affdb0043000302020302020303030304030304050805050404050a070706080c0a0c0c0b0a0b0b0d0e12100d0e110e0b0b1016101113141515150c0f171816141812141514ffdb00430103040405040509050509140d0b0d1414141414141414141414141414141414141414141414141414141414141414141414141414141414141414141414141414ffc000110803e802bc03011100021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00fa0a69c1da221bb39cb0ed5fa1a47c04d11890ab061c73c536888b20931b9db2323afb55d3442d195a460190a9ce7dba56a82a32bcce3732919aa5b09b20382549c900e781dc54a7a91b897932ccf1b0c055041e2a9333ab2bb22203e72a5f038c56e99a47622561b00008f6c543666b61b092263823278c9ea4d42083b31b70e27c00c460f4f7adac6acacc5f7055da31d72334588b90cb29070e4673818ee2a24cab5e2472e1e02472c48239eddeb6a64b8fb85390a5b3e76332e72541eb9f5ad2ed68cf36b732921c00903141818c8029a508eacb4df3ab9ed7f08a11a67c10f1f6a24e1a6fdc839e7818c67fe055f2199b757194e29ec7d9e02cb08ee7853230c2a8f9b3c639cd7d851d28e87cbd78a6ddcddf09e9235af889a1d9062124be895d546718607fa57262e5ecb0b26c5838deb44ebff6a0d48ddfc53d4515862dada35033d30b938af172387ee1c8fa0ccdf35589d3f8f57fe11ffd9d3c15a6962b25f30b9700633952fcff00df42bcea7fbccc5cd1e84bf778448875406c3f64ed3820cb5ddfef03fbd966ff00e26b46b9f336cd2a6b853e7c914ab12e7db26bef29ec7c34bdd9682db6a77764505bdfdddb47bb958266507df00f1f5a4e37b9bc710d591f4545e26d5b48fd95ad7528b53bcfb74b7e447726763211b88c16272471d2bf3ba94e32ccacd1f5fcd52184bdce37c69f17bc5fa069be1eb6b6f105e412cb6024b90487dc4fa961c715f4787c0e1aacdb68f9f78dacb4b9e2975319669262dbd9896627ebc9fd6be8a11e5d0f2eabb95d955cef6009da463a0ad99ca914c2ed04639f6add6c6b4b417cb3bd4804f1922a12d4a6ae4385f377e013d3950715a111d01e351b5b0413d73dab36742573e99f05acbf0abf65bd5fc496802eabe2098428cff2b08b25703d3e50e7f115f9de31fd6f318d05b23ec30b1fab611cd9f2dcf302404015324e00c639afd030f0b68fa1f27527ccdc844944596da0e2b7b18d35620336e47529f788c9f4e73c516365223c0393c735b4598a5a973c3369f6df126936c016f3af624007725d78af3b1ba509b3d1c0ff0014f75fdb46743f1792138ff46d3a0403d01c9af8fe1e577391f4d8dd2c6ce88862fd872f980da67d4a4eff0078799ffd6ae6afae6d134bda814be0947b7f661f8b85b3d55481db08bc7eb579afbb8da45e09fb8d9f30dd26d908e9cf6afbec3ca5c88f94c54b9a6cedbe0d7c26baf8bbe2d9740b4bf86c255b592e7cdb842eac171c003b927ad70e3f18b2f873c95ee746170d2aff0009e7da85a3dacf242c07991b32b1072090c41c7e55e8e12ac7111f691ea79d89a5530d53518acc7687e481d3dab7946e11f7923e97d2571fb09ea4c4eedfaf8c103181bd2bf3ac4c7fe168fb7a1eee148fe02daa3fecdbf1988639d9195c1e4614ff005a9cdd7fb5d22b09fc1645fb0e211f1335c507225d0a5e7e8cbfe35d39f2ff00658fc8785dd9f33eae49d46e4313b967914e7d43115f6396ff00bbc0f031dfc433cb1595971c8fe23cd7a763cd4b522572a189202838f4e69729bc11622982e1f3ca8c8f5159ca26d0763deff65af8d50f85b56b8f077894fdbbc13e213e44f6b39dd1db48df2ef507a0638c8e9d0d7c767996fb687b686e8f470b88bcb93a0dfdb57e1fa7c3ef19784aced9da4d1d349682d246fe2024c819e848040fa7359f0de33dac2509fc48e2c5e1e51a9ccb63e7b918cb280f903dbd6bee2d73170e62024c631b8924924d1c8676e521b8621b396e7de92457419b51a50ceaace06039193f9d1244c16a3a2452c484553fc2cb9523e847359db52e30dceb3c47ac5dc5e1ff000b4b6d737d6cef6b3248cb72ea41590600c1e383dab8e2bf78614e3b99b07c41f115981e46bba8c6e0704dc33fe277120fe35d1c85ca0692fc6ef1bda0007886575503fd7448ec7f31fca9fb32a1034ed7e3ff008a4a1fb44b637a1b3ff1f16aa79ec3814bd9f29bc7456264f8e13dc0c5ff0085bc3d7aa0e5b7db90c7deb9e54f98a8ad495be27785ae46fbdf00e984b72df63764cfe62b2717735b685dd02ffe1b78b754b5d3dbc297ba6cf396c4a978194ed52fb42f727180289dd214e56819728f8437a5180f1069fb81c64960bcf70bd2ae3266f4db940b961e0df865af3a269fe28d41242780f665f24750cc71815126cd273f670295cfc2fd3ed3539123d41f51b751f24cf1ec2df864d6ca4796ebf348a49f096f354be58adafecade327e57ba62ab9f4240a6e674cb11a58d493f679f138dc62fb05e607cbf66bb5391dbaff8d73aa875519de45ff0ff00c36f1ae8117913f87a4308cb1921657627df69c1eb4dcce2c43e699bc3c3baadae3ed7a65ddb161951242c33f4e2b2e73c98c24729e2dd62ff004946b7834a9de42092f2c0c5573e83049a212d4f630cda3ca6fad2e6497f7b6ec3cce596442b9fa835bc667a5362d8de5ce99206827f29c1380adb4fd31deb572b984e37449a9eb9a86b718fb55cb4aa8085446c2f4f6ef513699853a3c8cf4cf8c08b7ff0afe146ac002cfa6cb6271d4323720faf19fcabcba324aab2a8d9d53c76e91d00233918ce0e2bd28ccf4a714d8e888900f97040e4e71cfa539cae6aa2ac57109624b12ac400a4f5a7cc1b48320a105703ee9e7209ef8a398d2abd09a0425a35473b76f3c672be959df98a8bb8d90fcc010caca7076313807a7e345f94e944a80b060a0871f786d39c7a935327766337a8d62a66025976800280de9df9aae86ab6222aa1d5f2028055481c9e78c5482346ce410c455ad8b7cc704f715999b3f41a49f6a0000000e0015e2a47c24ddc0bfee8172319ca8a96851454b8b806451b48dc3078fe75505a184dd9913c9b5f254edc552dc4f5455b8953e50a3e63ea739ada0b4264c8cb9f94ff0077278a9e5b31264219e5009380c0b63d7eb4d44e69ee34ca2270327915ba89b29590c67280e7bd4a891095c4858492a900e473914389696a366980cee23939ce3a53b973958a73cdf672c7390781ee2937639b9b51932908a48193c8078ac1ea75295a231c92a391c0cd765244ca7ee0ba369f71afea96b616ca24bcb99045121380589f534b115634a0e723929529e22691e98ffb2ff8c227f90e9d2672182dc6d3f8e457850cea8b7692d0f6e59554e747a358fc23f1168df022ff00c3715ac4759b8bb699a25997615dc30771e3a015f3189c653a98e8d486c7d161f0f528615c25b9e2faefc1cf16f84ad0eabaa698b6f671901e64955f6e781c039eb5f5d4332a355c68c773e66a60ebd3bce7b0ef803a79d53e32689819585a5b8639f453fd48a79c4f9706ec6996d352aa647c5dbd3adfc52d74a659a4be302639c8dc1462a32cfdd601c8eac66b5d1e8ffb504c960de16d0a3253ec5a6ae547456fba3ff41af272a8fb494ea33d3c6e94e311bf1288d3ff00670f035b2b156958cabf5193ff00b35184f7f319334c42e4c323e789e6323b0383bb926bef62ac8f849ee4722a0e4b80769e3ad345a6b43df7c784e9dfb2f780ac81c35ccbb8ae704f2dff00c557c1526ea6673b9f63555b06b53ccbe2fdd247e23b7b440bbe0b18571ff01afadc14526ee8f94563cfa598484b0000c60e3d6bd6309a2b4938e99f7aae5b9cd7b100725b181cfbd689685465644e1de25241e71cd42dce9a6ee53c8c9e7e63ce3a56accfa8348cc8c31938ae69bb459d30d5a3ea2fda0246d37f676f87b670aed8e4456207a84cff00ecc6bf3cc02e7cc24dee7d763a5cb835147ca2c8092001cf22bf494ed6b1f1d256b4489a62bb90afcca3191deb46c98bb2229194aa8653c7a566d84756340214ee271e99ab5a491a753a7f84d662ff00e28784add9802faa4195f50181e3f2af3734972e1a47a197abd53d13f6bdb9fb4fc74d59b076c51451b77e8a08fc326be7387a3fb9723e831efde48ece626d7f620b61b4969754665503afef58e3f215e5cddf34b9bd556c3a29fc1862ff00b2a7c5972be59698af3feeaff8d5e68bfdba91382fe1c99f3768fa0ea7e2cd5a1d3349b29750d4a7cac36b07df72064e3f006beee788861e8a723e6b91d4aad1f4c7ec81f0a3c63e0af8bff6ed7fc3f79a55abe993c22598650312a40c8e0922be1f3bccf0d89a3c9167d36070d3a4fde3e7df1978275bb6f126ac0e85a92225d4a33f6370bf789e0e3915efe578ec37d5d294ac79d8da329d4d11c5ddab452e1832b2f1820823db9afa38d452574792e1c88fa760c45fb04963200b26b6093d15479a07f4afcf715351ce5367d6d177c36845fb3f989ff66ef8cfe595f31638c9e7d14f26a7379a78ba4d32b07fc1656fd8618a7c5ed4e23c03a34e319ebf329aeccf249e16367d8786f899f387886365d6f53076822ee76233ff004d1abeb72dfe040f171d1fde18ecd8666c83f4af64f2efa91070c186c1cf7a499d14f613278551c9e94dc6e0d6a3e3c1c87c30c1dc09eb5c552164d4b6084fd9af33ee4f18f8660f8dbfb1de8d73b85cf88bc3ba7a5fc05b0d23c4a08607be0a8fcd6bf2d954fececcd4e1f0b3e8694a389a56ea7c2b38f9c3838c80702bf55a53e6499e4cd72684257ab961f43d6ba1b3925ef15e53b9ce49c11d3153717419b08c10063a5172a087c6e0b639e067e945b52e0ef73a6d67cc9bc09e1395c7cb1c97ca589e7efa95fd2b8e2bf782a4b739312a4a3200c8e33eb5dd636922003cd041e39fd2a6f61c1007f294281dfad12f789946cc9b7808491f502a2d63a14741ac0610a3eec8e78fba6b376b96e3ee9b7e04df1f8cf402809df7a911f60d9527f2359cd23cfaeed131ef9c47a85d460101279500ec36bb0fe944628f46834a068787fc4b2e86f26c4560c46770e48f6a2514557b4a07a1691e2fb3d4a153bcc530e0abae3f5e958a89e37b168abaef8e2db4f80c50e2ea56383b1be45fafafe152e26d1a4e679e6a7aedc6a176677678a5030ac8ec857e98349523d5a34ec74b6fe29d5ad3e1ec97316ab76af6ba92421d276042b44480dcfcc720d270385c6f56c52b3f8bbe31b4f9d3c47a87036a83316039ebcf7a3d9591d8a8a3723f8fde3ab68828d616663fc5730abe7f018ac631b335a7049964fed0be275551716ba1de39e19a6d394861ee01ce7df35af258bab1b0adf1c52f9b75e782bc3d7520c066580a0231f8f7a4ee60de82a7c56f073e7ed3f0eaccc9cf16b398867e9d3f1a9e56c22b98f47bff00107803c41f02b46d5eff00c3ba95b695a7ea5258c3670dc65e07601998b67e653918af1e316aa9c94e2e354f3a797e0e6a05c8b8f12e96cc472210ebfa826bd28b3d0d5b14f84be14cfb9a0f1bdd5ab8c656fadf0075ef8c0ad373aecd2211f097c21a8899ec3e2469185e8b71b94aff004a57672b9fbc483f67f33a0361e2af0edf2edcee17403127dcf6c5173a66ef1225fd9cbc592c444034fbc63ff3ef76ac147e79f4ace55b9498cac54baf805e3bb32a46813dc460619a3757391ec0e6b355f98e95330ef3e1478c2cdb371a0ea41578c790cc17f11d29a9dd98ce4ee635cf8475b81e5377a45c2a118f9e16e07d31cd6ee5a1aa9d919725bcf049b1a2923cafca0a107e9d38a8e612a83d328087745607a714b98db73f429c01f21700fa6e15e24247e7ed8c211adf6970c01c8c1e8d9ab7a96a4412b95dc7249618ab86c613d482490b0270c78eac2a96e4c515f19562573b46463f95690434ba90c4dbd95883819c8ce3e95ab57253bbb0c79541da08181f881492b18c96a579dc9da4677751dab548993d0453800920827079e4552269e84f66fb1663b7803a7b1ace4ae6ee45399c1e7230055b4a0ac65395ec7a2fc0cf0b5a7897c47a84ba9da2de69b6564f23a483e5dd8e3f406be6f36c54a84545753dccbb08aa4f9d9e6977b64bb9ca26d412b04527a2ee38fd2bd6c22bc533ccc543ded0a77019fe5009c7279c607ad7a708d99c527a58f4afd9b3435d67e28d9cac032d844d72463807185fd4d7879cd451c3bb753d8c995aa11fc477f14cde36d5b517b5d56de27b96f2cc6240817385e9c578f80953f61cb37a9ed6239a75b991dd7c47d7351d17e0bf81a24bbba8ef6e57cd96459583b00b9c16ea7ef0eb5e66129d3ab8b9367a35a4bd8a6413eb17937ecb6b71777325d4b7576e3cc99cbb6d0ed8193e9b6ba631b63d46073d6d70b7390fd986eacecbe254d777b770da450d9b8569e40abb9880064f19c57bb9cd273a2bd9ab9e2e5d26aadde87576bfb3c5cea5e3683523e24d1ef626be5b97b78a4cb3a87dc40c1c93815f3f1cc6ad1c37b174ec7b12c2aad5b9af736be347c10d7fe2278cae352b2d434e5b531ac4914d29575c0c104638e735396635e1e0dc95ee6d8aa129cd5ce5ff00694d3db40f05f80f459248da7b485d64589b2b9daa09fa6735e964d1753133ab232cc5ca34d289f385c864919107d726bee923e0eb7c4579c0f21989ced0735525eeb3a69bd8fa43e325b9b5f007c27d21828768e3241e40cece9f99af80c1c54f175247d7631b8e1123c57e28c91cbe3dd5a541b40758bebb5702becb08f9a9dd1f1ca5d0e35d53731c839e4a835e9c4cf9b429bb800f2320d6d6b9cf7d4ac497914e71fa55c89270cf11d858bff78f4ac8e883b11314572546067b9cd5b60e400866c1182dc0f4ae4a9aa67552dee7d31f156ebfe125fd94fc0daadb1fb42e9f32db4c73ca90190e7f151f9d7e7f86fdd667252ea7d755bd7c273763e5933900b0539cd7e9105cdb9f1ced27a911727248e4f35a584c6348480817dcd4b0431e42cc49c0e3a7a56895e37368ae6474bf0afc4d67e0ff0088de1ed7b50577b3d3aed6e2448d72c400471f9d7998ea0f138674e3b9d5859fb295d9ef3e30f15fc06f893e27bbd7b54bbf1059ea5784198a8250718c85e71c0af8fc360f31c1c5c207d054af4b10d36cf50d6bc33f0d9ff671d26c0f89eeb4af09497266b5d4e74dd2172ed90463a6491d2be7d7d69636c97bc7ab38d3951577a1c1ea927803e1c7ecf3e39f0f681e35b7d72eb54fdf471b0d92162546d03bf02bd6543178ac54275e1a23175214a938c4e0bf628b0b5b9f8cb36a1772c707f67d848f0995828677c2e39ee066bdccf94de1b929a3ccc04632aae523e96f857e1bf8a361f122eeff00c45e26b7d53c2f289d92d21b8590a316ca70173c0e319afcef12e9aa11528da47d3479a7aa673c6e7f685b0f103196cf4ed4b4a5bce10884bb5befedd0e76d765378554367cc64e124eed9f3cfedb5e168340f8df3dd411ac10ea76315c88d142aee5ca31e3bf0335f6fc3f579a8a848f9fcc29454ae8a7f09ff006a17f877f0fa2f07dd785b4dd7b4c49de7dd7ac48259b772a720906ba733c8a18daeab27634a18b54a163dfbe157c6ed27c4df08be22f8823f05e9b636ba5c63ceb0846d4bbc29e1fdbfa57c4e3f2d9e17110a3cf7b9eb51ad19ae6b1e5da3fed95a1784efa4974af867a7d85ebc661696cdf69da7b7d335f471e1b9568c652a97b1c3f5d8c2adac7cb7a9dc1bfd42eee4f06795e6da7a82cc5b1f86715f6f84a7ece3ca79f889f33b99ad1ee91149daac79c7615d69d99e7495889805f901079ed4d94a4473021c60e31deaa3a16083e7c1e73d6b16ae4c7568fbbff66bd7208f5ff02e88a14d86b9e18b8b6910f43b1836307d8b7e75f976774fde6cf772e5efb3e21f19693ff08ff8ab5bd2c654596a1716e33fecb915f7995d4f6b8589863216a862b03b403fad7b5b26704f42bca7e719078a8b98c56a2190e0023e503f5a2e7545585320e00183d288ee445f29d0eaa5a4f87fa1900ffa35fdca86cf5565538c7a7535cb1fe21951bbaa7311614be47519f5aee3b24ee44ad919009cfb56721c04b871c154eb8e09a69681515d81738c63ad4adcea82ba3a6f0c78506ae5a5965da808c46a416c7f4a89239ebcb94f42d2b4cb7d1d145bc421dbc86518627ae73eb586c78f567cc73fe22f07c17a259eddcc32312ccb81b493c939a71d4e9c3d4b9e6b7303dadc98d9c36d38dcbd0fd2ba2d73d394b41e2e02e012c41f7a868ba70b8d33061c119ea6b268e98c6c57725c724d39ad10d9d4e9910bdf871e24b7270d6b756774983d7e66539f6e6a26ecd1c725fbe4723e6142ab8e49e6ab73b1ee58de587359491bf4227cfa8c0f7aa9214a37238e6209001c13826a65b1cce035b25b6838cf4e2aa0b41f225147af7854bea3fb3778e74e20c8da7ea56fa874e1549018fe55e3d55cb5a273d4825347934ce46e8e27caedce01c1f6aeee53d1a71b10bdc799852fb62037024649607f955246b3219332321da3b8dc464fe15a72e870461663d70e790870b8cb20cd63cba9dad68767f0beea693c522d1e49563b8b1b9840591b3bca1646ebd772fe1c563563ee9cb35ee9976fe36f10d88db16b7a84440da4fda1b8c7e359421ee9bc17b86c587c5ff1969a0341e27d454800956977807d466ad405adcd04fda1fc7f691b37f6e35d313d6e2256cfd78156e98e49d8bebfb4578cfcf5fb42e9176a14165bad3d5f7fb03918ff003cd61cb192bb30f672722dbfed11a84780fe11d06672012eb6a141fc37571b9c533d7842a729f658ff00847656c16be407fba8a5413f4ae2e5b1f9bcd588bec5a1c9bf6ea924641e7743d07e15611239744d3ae398b5b85413d0a367f5a707a03229fc3d0c84a47abda961d3cc6db8a69ea348825f09ddc65d85d58c8480571301f8fe34d4ec5457bad95dfc15ac82cc96f1c99191e5caa735aa99c307efb2b7fc221ab6d2e6c26238c9041fcb06a9c824f52adc681ab21264d3ee3a95042939aa8cc992d0a4d61770a7efade48c74e50e6b5e6318b1a6295209c0dc8580553823345ee69b949c887e57e78c100f345ef16c97ef389ed7f064ae8bf0bfc77ad601dd1fd9d0f73f2918fcd857c866d255711081f619745c284e4788ddca59b71182700e2beaf0cad047cd56a976ca4d2b067407391f367d0d7645ea796e7ad8d4f0f78b356f07debdf68b78fa7de4836178d73b87a1078238ae7af4296260a1515cf5f075ead15ce8ee2cff00692f1d5946aada85b5e382370b9b65c1fa95c578f3cab08e2da8ebea7a2b339a9599df78b7f6a0b3963b08b49d2edf5251166e63be88aec93038519e9d79af230f90ce3294e72b1e957cce838a82386f883f1bff00e135f06ae929a143a5450ca26630382b939e36e38c93d6bd6c2e56b0f555552b9c35f1ea54b922789cac0312b8c9e4e4715f4cd2946c91f3ae4a5aec751f0d7c76fe05f18586b525a35f0b42d8803edcee523209e9d6bcfc6e169e2a97b3968cefc1e29d195ef720f12f8a6e35ff00136a3ab24f35b4b7b3b4cd124c48504f0b9f61ed5587c2430f49422ae5e271739cf439fbbd52e2ee4513cf3c854e0798dbb6fd335db0a51a4fdd39bdbcea43de29dc2b9c07054824eecf51ef5d28e3adb956580bc4c51c7231f5aa6ae995195923dafc43f146d3e28f8b3c036b696735aae928227f3882188519200edf2d7c9d3c0cb08ea4dbdd9ede2b1eaad350478ff8b2f65bff00126ab7072525ba9368c8181b8ff857d16121ece947ccf067f168619dbbf90403c6e1dabbad60e8413a857246083efcd6d16636d481240188cf4a6b510b1cc49908600e38c9c0a968772bf54d849c939fc68b026480b10a013f28c01eb58ca276c2563e9cfd9b6ea0f1e7c31f14fc35d46e163b8be496e34d2c3241006e23fdd6da7e99af83cf28ba15618881f5b97d65529ba2cf98b56d2eebc3d7d71a65fc4d15ed9cad0caac3e65607041afb1c16256269a923e671145c2a3453620e303df35ea23063252494da173cf39a968b48872093dce71c7a5537cb1b149f290bc9f3e33d29db95d824c7a5c491c8083c1f4ac650e6e85d29fd9b1eb7ab7c5c6f12fc0ab3f05ff667910e8b2a482ecca0994b3371b7b6326be7a39653a78bf6d7d4f62be393a2a8a5a9e4372cacd965560bf749038afa1505238255a71d190b5ecb085314ad1927f818aff2ab508bd24c9f6d286b13dfff0062dd66e6e3e3b584335ddc4aad65704234cc5385f42707f2af8ee23a4951b72d8fa0cbebca72e46cf37f1af8fbc47a3f8f3c416f6fafea304515f4e1634ba60ab9763c739c7b575e5384a6f0b1bc6e71e2717385774ee725e24f176abe269619f58d4ae7549e3431c525d3976452725413d066bdda386a541fba8e1a95a551ea614418be4f4cf4aee946db117677fe07f885adf86fc05e37d02c6ea38f4cd46c4c97513c2ae588200dac7a70c6bc9c4e0a9d6ad0ab516a8ecfaf4a842c79badd393c90ccc41e383d2bd88a6924f631552535ce33ce772781d793dc525a6c42a8e4c6cedbba119aa68aa8f42af9a62e464fe19a466856b912a6154e7dea8d94b42bfda5d189006474aae532a72f791f6f7ecd36735e78dfe0eba2111da68d7cccc4f18c63f9b015f97e78f591f4197bfde33e4cf8b776979f14bc5f2c63f76dabdcb291d194c8706bec72285b091b8f1aff0078725bc12477f7af7f7b9e74caf210f3b1c60803a547299c15d8c77f2704a939ed4729d5600eacc0938c827ad35b9cf25791d334e07c317748fced9ab2a96feeee8cf3f4e0572c7f886717c93394ca870c3b76f5aee48ea11416672a393e959c89e6b1afa37866ef51da5d3c9427ef30ededdea53d0c9d5d0ddd47c0c6287364ed7040cb06f9589c76ac94b5269e275b1cb48d79a2dd06065b6901e704a9c0fe94ef737a8fda1d5e9df114a5930bc40eea3865ea48f51dbeb59c91c93a56450f891ab5d5af886f74b5ba6fb3405368550bb94a2bf51d7ef77a742374560e9ea71d7336769c93f5ad53d4f5ea46c866fdbd54941d4e6915427a0d79016013a1a9b1729d98849c707ad4cf645ad6c75be13547f0bf8da372db9b4f8e450a327092a93f8f35cf5b746525fbd38e919436f3c8cd690367b9283b86734a68dafa112158c00e7193c77cd1235dc7a4a22563cb64e3005292d0ca4326ce4303c8f7c5117a9124f43d87e0779ba8f83fe2ae8bb04ab7ba109917f8834459b8fcc7e55e4631f2d58330c4ab34cf1c561346ad10da5915b70efc57a5d0eb8c88caaca4663388f3803b93493346ee288cc72ab00377753fcb35b5f425ab31cee142ef0a02f2403c8ac3a9a37a1d0fc399e387c6da15cefdaab7912b83d36b36d23f235954f84e6a9f099daedaff0067eb3a8d9cb8dd15d491b37419dc78fc88a74d7b86917ee19e32aa405386f977eee460f4c55246cad71ae04a9824aed190c781c75cfb5394ac8e8693d0f64f849fb3fdd78d74e8b5bd76ebfb1bc3caa6557650b24e83b8dd8dabfed1ce7d2be571798493e4823e972cca255d7b4aba23babad77e06786e41a70d25b5810a85fb64303dc07fabf193eb818af23d962ea7bc8fa2e4cbe9fbacf41f0cdeea70e10fcd003b76480855f400f5afa8923f9ceb3bb3a65b908416c06eb5972931d1092cc1dc9048214920f0688c46d98fa96bb6763107772d21fe1db9fc2ba634ae4a22b1d62d6e50b40e3cc03e68dc60f343a36365a44bb6f34864568a49549180a8c47e02b370b1e6ed2273a85ca1223bab8423a9f318107f3a9944da4b5b891789355b6621352b9503a06918e7f3a23132acee8993c59ad3619752989078dc4373f422ab94e7b1717c6bac94f9ae627727e6692dd5891e9d38ab50b9d54d10c9e32bb64266b3b194060306dd7e61ee2a2cd30749c9dcf5d97c429a4fecfb6d7474fb5dda9dded3005f2d1d771393b7d96be3aa255f1dbec7d5a97b3c1d8f1cbbf14e912604fe1ab7209c130bb0535f654ed4d72dcf8dab149f3329cdaaf865c3197c3f2a8e48f26e4e71f8d74c39a5a44f3e7fc552895fed7e0e746234fd4edd395077ee0bf866ab959d709d958714f05dc46264b8d52020ed0c55582ffc06a5c5827adcaf2697e169650e3c4379083ff3d600437d315aeb6329bbb2c7f60680d04822f15448b210079d6ec338e83dab1d6e7445e9a998de11d3db6c9178aec183123e746502b78b76d8c9cb51ff00f0804a1d4c7ad6973c4c38fdf6d27d08cd4eeca72b2236f871ac96244b60411c30bb5e7e82af989752ec493e1a6bfb41160b2907efa5ca7f8d3e6354f42aea1e03f11a2853a3cf2647063656c01f434e1241565732e5f0bebd6f963a2de151c16f289c7e55b391973d8e8fe18e97736be2c8ae67b2b88d62b795c1921651bb69c0c915e662a56858e88cb991c24f312679678a44690972ac8c36b13ee2bba83b462631d2453f3e28d36385048c8ddc67f1aea72454ed6293b06638208039dbc902b48c91826915a53142c4e76eee793c55c6c0da10ece49008c64f15526871b31d24676ae5719e739a99346ae290850edc80791dbad65746b7491d2f877c5da8781aefc3dab6992c905ed94ed7231fc63a156f553d08ae3c4d086268b854ea7761eafb19aa88f6af8fde0ab2f8b5e0bb5f8bbe0f85e576840d674d8d72e9b472e17aee5e41c751835f1d97d69e5789786abf0bd8fa2c661e38ca3ed61b9f30a30930e87e43d315fa1c24e5adf43e3fde8e8c898e24e48c7404f7aae6b3bc4e8f764acb72391821dbb483dc8e9426422bcbb42eec92dd87ad6971362b37c8093803033ee6b3b6a694f6352c495f0c6ba48240681724719dc48fe758d45fbc889ee62bee0849031eb5d08ea92d085d431e4820f24d51cb15a9afe12f1a6ade03d662d6f42bd361a9c6ad1c570801650c30c30411c8ae6af878d7872d75747a34ab3a1aa31b53d46e357d46e6eee5e492eae1da49659570cec4e493f5357468aa51b523cf9d5756a7332a4849001ea07ad74a5dce87b21c64f2d1093f7877a9923a1e8d1a7a0c426d2bc483cc099b123a67199179acaaaf7a271e255e473d344d1630403d41ee2bb2f78b3aa32f72c4485d5b903eb511d8c20c8e5504313d59b77d0d2ea755ae37af070723393c5572d851890b4a0a840b862dc95ed522b598c78b6316e58e0e2b48bbb6d0e2b5b9fa07f002683c15fb3ea7c44bf2a8da768d35a5aee6e448cc3a7d5828fcebf22cd5fb7c7fd5e1dcfa8c1d3f631f68cf80f51bb92eee1e795b74f2b33c8c3bb3124fea6bf4ec253f63048f2f153e69dca4720919249f5aefdc896b122794c529c93c8f4e9445134f623925331e738fa5096a3b92417310186183d33eb5335aa1b5768e974d90cdf0eb5d531b6d8352b5707b10caca7f5ae692b55465385aaa393752320015d2dd8e992d096d2e8d94cb2940d8e70c38a86cc22d26771a478b6d65d893660206003d1bf1a9b1c352373a1b8d5ecec2069a5957a65406f988f61d4d652899460d1c0f8a3c5716a8a638a14d9fc3238cbfff005a9c51ec5389cbc8cd8919707e538c8e338ab7b1d0e174749f13d07fc25b24f803ed1676927d4f92a33fa56545d8e6c3bb1cb75014f22b56f53b5bb8ae08185e14fad171c1590d652a012783522e5d414820b672476a996e7525ca8ea3e1e5c96d5b5680a16fb468f78a101e18ac7b8023bf4cd655d6871d5a966718653751a3a8081f0d81e98e95513a93b9309769da092807de3fcaa99aa1ae4bb600c7f74fbd637d4d2f71c8e87764f39c100f4ab92ba011d8125493b76f201c7eb531d2e24bdd3d6ff65fb946f8972e9f96f2f53d26eecd8b364f2991c77eb5e5e315e9dce5aeaf13c9ae6d9ecee24b7ddb0c2cd08c2f01958af4fc2bb29be6a68ea8404793cbc3aaaee07a1f5a1fc2cdb62a4f8694090373caaa9eadebef8ad20b4256ac5f31cc84942e40ea3a62a7a8542d69f70b05d4130f37cc8a5494040319560791d6b1a825f0d8e8be27c31c3e3ad699db31ccd1cea57a6d7457fe79a299c7877eed8e592576937924a8c60ff8d6ddcf464ac92ee7a67c06f877ff000b1bc630a5ee1f49b21f68ba42a70e01c2a0c7f79b939e300d7cee638a5429b89f4995605e2aaa7d11d27ed15f1564f116afff0008c69732c7a0599f2a6580ed49e4423e538eaabd87aad79f82a2f96ecf7338cc3d8c7eab4cf177980620dc48a7d158e2be96105ca7c4aacd1f7f0705154a0000c28ae1933f3493bb22383c0037ed241a929e88e77c43a85edb249b54ac2abcbc67f9fa56d045d8e24dc3c8dbdc92c4e5998e49fc7bd77c121a89143e6bce668d98303c1538c56938ab19b9743b7d165bcbab461720150bf2b918627df1fcebcf9bb1cd38f52fa83948839563d4e7d2b290ef7032470f32b81cf196e1a9c49e5e618ea7710303fdd34364380fb92610143104a6e07f0a5cf647453dec7b1dbfc19f0edb785f46bdf10789a5d1ef751844ab13aae06707038cf423ad7cccb34abed250a71ba47d2470a9534cf40f881f0ef4dbcf05f87bc38de23b7d2e1b34cc725c2806618c0206463bfe75f3f86ab57eb52a908dd9e956a09d1513cd64fd9c26d4a4f2b4bf176937b311f741f98faf009cd7d13cd654e37a94da3c1ad95fb5b462cf24f187866ebc1be20bcd16f268e79ed4852f09255b8cf19afa4c2d786268fb48687cfe230df55abc8ce6ee7cc8f00e1a3638f5da6bd08ab9c55572b191b4d18ca91b7182302a9c416c0aea028201e7803a5371d0b70d496fa416fa744a060cb9233d80e2a210bb2a5a2321ae03aa83bb701819e82b7f67a1875167b869150800004e463afbd4461a84c89f6dc1070781db228e5262aecb1f6a78d5409655c0e42c8c01fd69729d8d684536ad7d925352be8303e548ee1803f8038ab8c0ca5ab248bc55acdbb029acdfab0fe2329cfe47815ab81138d91de7c32f14eaf730f882e6f7549ae92d6ccba348c3e42580071d33d6bc7c6c6ca28e9a11f74e527f899e258a629f6f8e6cb1277c28df8e715e95385a99854d24467e28788c9124a34e9c2e55e396cd30dc71cf5ab748cd5d91dc7c51b92cab2689a2cf27f114b60b8f6e3ad690a56339459049e3bb4b8c2dd7847459d41fb803039fc29a83ee2926397c5be1f738bbf04db246c39f26e0820fe75328b5d4d29c585c6bbe0a995437866f701bf86ebeefd2b36a474cd310def80d95c3586bd6b81c159237073e99a94a4434ec58bf83c11f64b4125feaf6d132168e49225624679c81ef9e95518b98e0ddb53b5f827f13bc35f0abc4335d59f892eee34bba012f74fbab525255c63771c0619e3db835e2e6597bc642cbe25b1ebe031d2a53e596c751e3bfd9cfc3be39b5b8f1c7c3bd5a2ff847ee034b3d845096fb3c9d5995472a3d571c751c57cf6131d89c14beaf883d6c5e0d575edf0db1e22bf0dec9d8987c5ba448a3bc8cd19cfa60f435f6f4711cf0b23e6b5e6bcb4187e12ddca85edb5bd12e3393b56ed467d31ffd7ae8e715ca337c1cf116c5588e9b70c793b2f9081f8f7ab53224d8cff8541e2d08eaba509c0e8d14aadbb1e9cf349d4b1d54f62cc7f0dbc4d6de1bbdb79747ba59a59a26f2826e25572492471e9f9d6352a5ea45a13dce6aff00c1dafdac44cba25f4473d3c863c7e02ba148ea93d0c89741d42dcb07b3b85c1e4344c31f98ad548e58bd4cc9008b2acb246fd9648d971fa55def2b1729f2c75214059981ddbfe869735a563076946e859947cb87556231c9c64d3e73b96c84756c2a9da00ef9a39ee744f746b787a23269de2645c13fd9d8033d732a7359cddda671cdf3339eb88672cac509cf1cf6ad213ba68d16c45b589031f5ad232d02d62360581c0ce29296a7541e8461369c939cf4cf6ab948de0b406555c923073529dcc24acceb3e16fc34d67e2ff8e2c7c31a1a169ee4eeb8ba5194b3847de91be9d87738af271b8f8e0694a4d9d785a5ed2563e95fdb2aef4ff047c27f01fc3bf0ddd89344b49da2ba78dffd74912e486c75f98b311ea6be1f26a7f58c5bc5543d8c455e48fb347c6f32166ca9f9739f735fa75d5f43c96aeee5760d9f94fe15bbf7504b6229f1e676ce39a517a19c46121b008c6284f504f501186c9cf03bd4b7aa34a6eed1d468a2497c0be278236c9cdac815393c48473f9feb5cd55daa22eaab541743f04c977b65bb611ae770504ee23dc76aa93396755a46edd785f4f9ed842b118f00fcea39cd42679ded9dce4358f0e5ce9c8eea37c2879607fa56b0773be0b98c39ae242a033b151ea7a5391d0a9a2b4ee0b003278eb4923b12b0f18f2c8ce011fd2948d6fa1d2f8f94cafe1fb860a0cfa3c0cc7a96219973ff008e8ac29a38692b1cc2e14fb56bd4f4211b8e707048ef4ae36acc81d72028c11df9e699a3422c8b1c2c9d1f3c7ad12dcd64f437fe1b4889e3ad34396d9225c40557bef85940fcf1535168795555d9cb4711b38d5402ca8028c7af4fe94a277c0786790bae1573d0d53371ea040b8c138fd6b9a4acc6910b2e64de3a8e719c56fd0894ac4fe50752080491823ae6b9efb9d16f74eebe03dc47a47c65f08dc00db4de085b0d8c0652b8fc6b8f10af4998555ee185f12adce93f11bc4ba798b0f69a94f1818c02a5b70fe75a611f3524754355739d62cc63725413f28c375f6fad6abaa14c8ca3840c46e61918a717645d38dd8be4964551c12b9504e4562e6afb9ad5a6edb12430a44983ba39830c9cf279f6a73d4c7975b1d678fa012a787b51204b1dde9716e74ea4a33a727bf4acd6879b415a7638e8a2796655525493b71e99ad64ed0e63da71e68dfb1f53fc343ff0acbf67cd5bc4a1164bfbc123c793b4364948b9fc49af80c6c9e2310a99fa36591585c0bafdcf9791ce37ced2cb2b64bbb3025d8f258fbe7a9afafa149469e87c255abed26e53dd8d46700811ab60e0962339aec8ec7338a4cfd01276f041cd790cfcc56e31d8f048c1504629a2e6ec88d99251b48de318248e9570763484b439ed43c256b793064905b91c80064135d6a7625c8769be1fb6d35f6ac2aec7259df2581fa1a2755f25d9cd7bbd4ddb1b2bad46616f040d713bfcab1a0e4fbd79389c4d3a14f9ea3d0df927f651dde9bf052e25449753be5b3cf261806e7ffbe8d7c263b8ae9e1dda92e63d3596cdeacdeb4f805e16b953f6cfb6de1071b5a7da09f5c015f3d3e2ac5cf5a7ee9e9d1cba0d6a74961fb3e783e45082d6ea2f422e0923f314e3c478e5bcae764329a6d6e56d53f660b49afe1b8d3355716eaebba1b9196da08ce1877c7b57af4788eaca36998bca20a574cc6f8dc64d6be29687a0408c2380416d1ae300863927e98c7e55e8e5d5232a33a8ce8ad1709a4637ed2daaadc78f92c11cb2d85a471609e0161babdec969270737d4f2f35abc8d157f66bd305f7c4cfb7ba15874eb39263267805be5e7f33f955676d53a1c8ba9c795b73adcecf39f1dea275bf146a3a8b49b8dc5cc8c581fe1dc76fe82bd9cb697b2a115dcf3b1f2be224ce66502405b1c9e066bd88e8793575201279522a95f976fe756c98c7410c514cac092113a73827db356f545cb41fac4a02d9c4a09458875f5279a9a6b539a552da198630ece40ce0678ad8717a0c4f9890470067352171c24cbf246d5e3159dc9a2b9aa0ac012477a573baaae56559fe6981ce31ed5d11305b8c99d18119c301d8568cb9bd0ecfc2662d3be1f78beec1e4a476f9faf39af1712f9aaa46d07cb0386209d8c48e46411d2bd54b664cd73c4843234bb829ddd096e47e55a18dac556890664523e538233fad6b1563193228d9642e41ce0f6340415c7ae48c03f5cd6123a611229090d85c7a7d2a9a1bd591bb10083d4f1cd24826ac8bbacc6b69069f0fcdb8db09324f763d053a7a9972d918cf71e590a003bbad69c9a954f73bcf855f173c41f0a75c5bed16edbc96c09ec266cc13af70c3b1f461c8af1b1b97d2c6ab35f33dbc263aae19d97c27b56a5e0af87ff00b515a49aaf852ee0f0778edd4b4ba55c85f22e9c7525075cff007d39e84835f2caae2b289f2cbde81ecce187c6abc3491f3978d3e1cf897e1aead258f89f449f496276fda99775b4a33805251f29cfa75f6afaac366787c67f0e5f23e7ebe16a529de6ac734ea231b227281324157233f957acb5d998b8cb92c29ba9d202526b804907899801fad69c8d750714fa9d1e9bafea963e14bc9a1d46ee365b88d15fce66da0839ea7e9584e9f333372b15a2f899e29b665035fbec0ce32fbbf98a3d99bc9de289cfc5df1740771d62466233f3c6a703f2aa503092f790a7e35789dfe69a6b39c8fe09ad148cfe557c86725762a7c66d54860fa5689759037192c867f0c74a870b9ba8e84b2fc5db295905cf83b46980e0ed8f69cfad4fb3634cad3fc4af0c5dee8ef3e1fd90078062b965cffb591d28f64cdb9997bc39e28f034f6dab11e0eb8b058ed374ad05f6fde85d41019b9eb834a77d8e6bfbc5196ffe18dcb92da6eb96f9e32b72bd29535247625a1524b7f85d7532bc5a9f89ad8a8c146b6057eb8039fcc568b996e6527cc487c31f0fee48f2fc69796c5d70bf69b1e33ee00c8fce86e4b646d069211be1a7862745f2be2069cb1f526e6dd90e7b63d07e7512a935d0d6351db54747e09fd95b5af8a0f2c1e16f11e8daa448c3cdbc8cb6d80138c9e39efc57898ccd69e055e5bf637a14e78895a28fa12fb4dd37f677f86d3f84be18dfe9d7fe2dbffddeade20b8ba412a30186206786c642af45fad7ca4235f34abedeb46d0ec7b0f110c0fee56acf14f1b7c31f11eb7f0bfc17650c115f6a3a75c5dbdcb25ca953e6720ee27e663dfdebe87093a54ab3a6f63c375252ab77b9e577bf057c7369927c3570e9d8c6ead9fc8d7d0c71115d4ec4ec62cbf0e3c5711cbf867535c1c13e41207e238ad7eb117d4c273b99f7de13d52d24759b48be5703afd99b1fca9431108fc7235a7a231a4d3ee4380f6b71160f3e642c9fcc74adfeb34a5f0c8cddee36de333b3aa61881fc3d693ab17a735ce9a29aea75fe16d3eee1f0d78ab28211369e16266e18c82556e07d07f3acaacd49a77b9555b651d2bc5d71a7ef59a332212300f55f5e6b5d26715483945b46e5cf8cec16dcba10d2b63f761b95fad1eebf89ec790a1525248e3f58f135dea9be3dde5c44ff00aa55e00fad6f0d8f66926918ae84296600af4a4af73a63b913c4301b390474ed52e705d4ed6fddb0427208c60f62685283ea2bda363a6f1733dc786fc1574e577b69f343851fdc9db1fceb2835cd239294bf7ace50e1f25b009e4d6abe13be2eed8e91f6c408e4545c7263124046d20823a1a66b4d88cd1e1b3866fe55a72a7aa66d78dda66d78098c7e35f0eb96daaba842588eca5b1fd6b9eb4eeacce5746366e450d474d9ff00b5ef608e379985d4a8046ac41f9d80038ae48d78d25ef48efa186a9557b91b9717c09e2294009a16a32027864b5908fcc2d62f34c22f8a47a74728c649e91253f0efc4f044c64d17506c0c8cdb37af4ae6fed4c1d47eec86f29c5c35944a37fe18d5f4c2a2eb4bba81987c9be1600faf6ade38da0be191c95307556f02a085e29046e8caf8e559719fa6692c4c25bb2a54aad387bc8d0f0dea0fa3f89f48bd55cb5bde432f271f75c1eb5a4ad28be539269b86c75bfb4b5a1b4f8dde2545053cf686e94e38c3a0c7ea0d6796cef499861ea7353e5be871fe0cf06eade3fd7e3d334ab53713228669083e5c39fe2661c0e878fad675f1d470c9b9ee7b586c0d4c5c92a68f7bb4f837e02f85b60977e39d546a5a948a1859ab30456eeaa8a373038fe2c57cd55c6e3318f970f1b23ed70f94e1f05ef6265a8c5f16fc19d4dd6da5f0e0b380823ed0d19509ea495391f5c1a8583c5d25cce5a9e94abe5b517248e4be2cfc181e1bd3a0f10f86255d4fc3b3aab33eeded0a9fbad91f794e7af51debab05984dd4fabe23467ce66193a827568ea8e3757f32e7e197876e65009b2b8bab5381f746e565047e67e95f45397beb999f0d0a76aadcb639282291e60cadcb0e081fa53ab2e68bf23d5a49b9a67d31f1fa31e1df801e13d090794ccd0065cfde2a9bb27fe055f0f867ed718e4b647e858bff0066cb5533e6362778724afb0e41afb8a72e5f74fce131a60790ee11b60fb51aa0733f4017f78a586083dc1af2d9f98c772394819f73c5341558d9080a0264803b9ef441ea5c36295ce646ce0803b0aea48c9ee68f87b43bff00116b16d676716fb8b860aa1467007563ec2b831b8a861e939cb635861dd6a8923e9bf0bfc3ab4f06698a91a89af597f7d7257e624f503dbdabf12cdb34ab8a9349fba7dcd0c0c69c1730ba8c705b5bcd2cf32c2b18c92ec15557d727a57cba83a8f963ab3b250718dd9e7571fb43fc3af0eddf9179e28b6675382902b39c8383d01cf35eee1f23c7545cd189c34ebc232b33d1be1e7c5af057c45985ae87afda5e5fa82df65395978eb80704e3daa679762f0df1c4f6294e2d6c7a340df28e08ebc1e0d6716de8c6dc6fb187e22f0669fae6b3a36b330f2ef74b984ab281cb27756c7503391e86bd8c362a54a3ecbb9854a4a7ef33e44f8b3a98d73e22eb7728c1d0dcbaab86e0853b463f015fade571f678789f0b9ab73abca77bf0654f877e1678e3c48edf34f18b5846318f97079fab7e95e46693fac57a74cefcb69fb3a1399e0771b958932b393d148e9f8d7dbe1e3ee45763e5ab4fda4db2b1627391c7f3ae9b1cf31a7e6c9ea070282af6441206380a723233494b43293b89aa296ba665270a00c75c715507a9c9257654c167dc49c9ad0e882d06a29dee13f2aa434b5123952dc9727383c86f5a8e51d25cb5088ea0926481c75cfa51ca77545ccc63cab236e1c7d3a56b1387a95e672dc6de3b9ab613676b14f6f65f07a78e470b2dd6a2015ce5995475c75c578f357af63a2d781c1995ce020f96bd6da22bf2c4adbca48413c7d2b48995caaca448c4139639c678ad6c6725725815324b9c11d71eb5948d29a1f10299e78ea33d6b1354ec468c4c9b980eb90a2b562a7ab22bd0f217703e503a77acee6d555916bc5aa5350b440e484b38863d09193fad3a0734a5a18814b9c9383d4d75362a5a8f50647c6383c66a172ca3cbb1d10bc49dda5b768d8092268d832491b147461d0a91c835cbec20fdd92ba37f69286bb1ee1e0efdaa3524d0c685e38d2adfc6de1f3b637fb6a83708a06090c73b9bdce0fbd7cd63322a137cf41f2c8f7b0f997ba9578f3235eebe05fc33f8c71fda7e19f8c5742d564cb1d175424267fbaaa70c31ec48af3e9e2f1f977bb5e3cd13ba786c3e31de833cb7c67fb3a7c46f03cf2c7a8f87e796d226f96f6cc79f138f5caf2a7d8d7bd87cef0b59dae78d5f055a945b48e261b59e0b396d5e40913c819a36521830e3a1af7a35a1515e0cf0a0ab735a512a5ce9ae23590619776062b4b9d8b58997788fb8bb0c0e82aa2c99fc4882285e62ca017edc7357233b6a5a9f4a960b72429078e31595cdef65628c96ee5c12a41e841156d951572bcf08249c64d5c353a634cd9f0f4a5746f114fb19e38ec31b4750de62f3f4c56556dcd630953b3300c5b8b20077fa8e715a3492b9d115a0aa8e88c5d82e7b9359b7193f79d8850bad0ddf08780bc4fe3b7783c3da05f6b320608c6da225158f4dcc70147bd7998cccb05835ef4cda860e7559f41787ff00654f0e7c30d3a3f10fc61f155be9702812ae8562dbe7978ced6c64b739fba3f1af90c466f8acc7dcc047e67a94f011c33e6c448a7f127f69abdbbd0ffe110f857a6af827c308361b9b7502e6742393bbf87273ea7debb701924e2fdae2df3489c666108c7968687cdf79a1cb6b970af248cc59e40c77124e727df35f5bcb151b451f391a939d4bcb567d39f0dfc13a778ebe0e784d3535ba45b7699d1619990862c410df966bf9478cf8e713c3f9b4a8d08dd1f5983c0a9bf68b734ffe147e9367317b6d57c4301f48b506d9f4c1af84ff0088b798f63d2ab80b22c45f0f12cb887c47e234da7e61f6cddcfe35b47c5acc7aa3cf7812c43a0dc59c876f887599011ff2d6447c7e04569ff114b1b3f8a264e959d8d5b6d11ae4abcba9dc4b8e3f7f144dc7b715cf3f13b1ebe08fe26ff574d1a775f09b44f13599b7bbc857c65e082246231d885c8ae4abe2c66508a505667a7472b52ea5683f657f074d1e0de6a4a838d9bd7b7e1584fc64ce94938e88f56391c67d4b167fb25fc3bb78dd2ead2e2f99d8e247970ca3d06063f3ac27e31e73cc9df436791d249a4786fed07e09f853f06fc57a2e8d2e81aa5ccdaa5a3ddabdadce000adb48c64735fbff0086fc6989e258cd577a9f3398651f55b4a2797b4ff092e5999ed3c4da793c712a3a28fa67ad7efd18d4b1e3a8d902e87f0a24f31d7c4bad58a95c7efed43a29f5e3bd351a9708ee430f837e1b5eb030fc4611ed3c89ecdbfa0a99b9f63a6fef58964f859e16bf981d33e2368ef8f999658d9370f51bba7e3445cff94e3ab51a9d8d6d5fe0c4ba8f87b4382c7c53e1db9fb21b80276b8640e1d958051ed8e79ace33b4a44d3d2a33047ece5e25ba20417ba2de91d5a2be5503f3356aae876425ab2bdd7ecfbe3b8d0a47a3c576abdedeee36527b0c8347b52a5332ae3e0b78eac80597c29a892464f92164207afca7a5275ec6b4e63bc3ff0005bc61ae6b1159368175a740c4b3dd5ea79688a3a9393c9f403ad7057cd70d463bea7b580c0d7c6d4fdcad0f68d3be0d7833e169b1bcf105dcba9ea4650d07ee0ed0dfc255573800f7635f2b5731c562e565f09f7f4f2cc0e0e95f16f521d7be3de9fe0dd5afb4ed27c24a1a195924b87509e6b7761b474e4739e6b6a1973abf15434a79de06847f7293392bafdaa7c512f99e459d85b81c0568d9980f5f99b9aed593e1fed6a724b8a217d62528bf6a6f15e096b6d2eea3272098990823dc3106a964b41743997142bfc26b59fed5ba881e5dff0086ec2643cb3473365bf0618159cf2584b691dd4f8929c96b0469a7c77f02ebc9b75bf07ac4ad8cb451ab903d720020fd2b86a65388ff0097733b166d97d58daaa1b2699f04bc4c897297d2e91c8665dcd1f4e70739f4aca34b30c3e97d099d0ca7150f74eabe2a7837e1beb9ab5af887c43ae9d367bab18c42e25052681061586473c1c5614b118b84ed40f3f0d94e5f49b8f31c96bff18fc2df0afc216ba4fc3e305f5dce407d43f81547f133632cdcf03a0f6aeea581af8ba9cd893d8a98dc265941aa0b53e77d575dd4356bf96e6eeee5b9bb95cbc934ac599dbd727b7a0e8057d3d1a4a8ab44fcf3138eab8995ea48ab0debc512a95de41ddbb77007a7d6b59d34f5328d556dcf7cfd9dbe235b0b59bc19afb449a66a2196d5e770512463f32127ee860781d322be4b3ac33b3c452f8a27da6578fe7a4e955d8eda6fd98445e1bb8d161d6d4c0d786e5249612580dbb769c1e78c7271d2bf04c778a15b0b55d0943589f238ba76c4be5d8c6d2bf64168afeda697c4be6c31ba3343f6523e50c095073df919f7ae2c4f8b55bd9fee63ab37c3b5a1ea3f193e0a4bf161b4a8a2d5d34fb5d3d5c6cf24bee24019f6c018ef5e6e57e27548565ed63bb3dfc7637dbd2513e1cb8863b7bfbeb75919c5bdc49072392558ae7db38afeaacaf18b19858d7ee7ca35633e7b9324ac7708fa0db9e9c57d0464a4ae72c9ea7e8380230c149c139e4d78d167e770d08d88645c901f938cf6a994d43709a73217940046f1939c1269425166ea0d1017df222b38f9b246de838eff005aeae78c4c650773e82fd9a3c349f63d4f5e95019370b6878e40032c47d7207e15f9c71262aef94fabca68fbdcc7b3dc424c7800e0f627a57e613d51f58d7bc7cb5fb66a6b77a7c2de1fd3e596d345d4ccdf6eb889b687640088d88e795dc71dff000afa3e1aa14eae2af3dcf3b1bee533c3347f871a0e8411a0b2877a8ff59247bdcfb127a8afdab96305ee9f035ea375523e80f897e11d27c31f0b7c0a2dace2d3f598945eadf5ba88e74655dca772e09193d0f1815f1f082c6622a29f43eb9d5f6346299f437c36f140f1b782749d6090f2cd0859881806453b5b8edf3026be171f86586af2823d6a52f694ce99a212c6e9d03295cfd45712dd48d2d2bfbc7c7d6fe26f845a7cb7563aee95aac7aa59dc496f75345b995e456219b00f43f4afd4f06b19568a9517a1f2f8d8e1fda7be5df1bfc4df064df0dbfe118f052dd476f25c89e669908e3af53d7271f957461f2eacf13edabee615b19469517081e273ca32064707a0afb4826a373e35caedb2939292e5c8446f53803dea9b51ea612e7bf993c76ed7594811a771d044a5b3f88ae3a989853f89a5f33ae309cd594752dd968f7a64412e9f74a8586e66858003f2ac563b0fd248d6383aaf74666a9776f1dec91bb797264800f1f2e7038eb5d746a42aed244d4a1c9ba290c310c18617a015d91f73e239a9da4b9623247628e621b246c004d3d53e65b0e2937c8cc6b89a569cc722e581e4e0e0d68d5d6827eecae8b96fa6dedcda87b6d3aee78c9c79890b3293e831d6b82589a74df2ce563d654a75217489e1f0deaf2c8c534dbd45ed9b761cfd08a5fda1878fdb473470f51ad89a2d0b515564974dbe041ed6aedfc854fd7f0f2fb6829e0e5cd768eb35bf084b1f813c3f32691772dfcc64f31840fb9573f2e4638fc6bcb8e6145e21b7346ee84bb1c4cbe1ad6619013a66a1e537185b572d9fc057a31c761dc9be74612c2cdf4324c6eafb6452ac0e183290411d4107906bd6a525569a77ba38e7174dda45eb4d0ef350591eced6e2ec26037910b3edfae056357154e8a5cd2b1d34e84eaabc113c7e15d65d037f635eae41daa6d5c31faf15cb2cc30ffcc8e8faa565b4489b40d5d5d90e8da886e841b5931f9e3158ff006861bf9918cb0f5d7d9248bc2bab964234bbd27d3eced9fe554f34c33fb68da1839a95ec4975e18d5c90eda45f200781f65724f3e98a8fed1c37f3a3b6b61e4e3f0947c61a66ab05c36a17ba3df59da95487cd9a0608bc6064e38fa9adb0f98e1672e48c8f1a7879dafca73b6aca1d95ce01ed5ed49c5ad0e68c6da1a36c85e411a44d267fb8b923deb8ead48525cd2763d4c361e756ca2ae5892c6ea46cb412151921b69e6bcd966b85a7a3a88e9a982c4bba507f719d716332c4cdf6796303279538ad238fc2cda4a4afea670c2d7868e2d7c8cef31ade78a756293467292a9dae9fee91c8af56318c934d5d1179c354ec7a97823f6a5f885e0a31456fad36a16919c0b5be5f39187f772791f506bcac4e4b84aedb71b337599e228c2f7ba3d4e2fda0bc0bf115214f1d7c308a59c8dcf7fa5a8ca9c72c3eeb1fccd7c4e2f0b2cbe7fb9ae7bf86c64731518d5a7f80cb8f007c06f18c863d1bc63a8f85a77e562d4118440ffc0d40ff00c7ab9a86758da73b73731db532aa3cb74b9519b79fb17dceafbdf41f1e683aea93f282c2361e83e52c3eb5ef43886a3769536923cf9e4da5a13b99527ec63f11f490eb0e9ba75e464f12dbdd825bdf0706bb3fd63a0b4b1c1fd8d88b5d339fbbfd9c7e2342e607f0dcd2499eab321c8cff00bdfad672e28c1d27cb52561c325c6cfde51b99f7bfb37fc419b7eef0add4640c03e6c7fd1aa3fd6ccb5fdb3a296538d72e574d95edbf644f8ad7aea23f0c3aa37dd69678d01cf72777f2cd74ae24c325cd0d4ef9e0674d7235a9dc785bf623f88905a6ab6fa8be91a4dbdedb88fcc96ef794218313b547b77358d5e22a552ce11b9cef2ca8e7ac8d3d3ff646f02f841927f187c50b3f342e5adec4a9c91d40c127f4ae779ce3eba6a852b1d0b01083f7a45b3a8fecf3f0e245974ef0f5e78df5243b965bdfb99ff816171f506a6384cd31bfc5972a25e270386db5391f1c7ed93e3692cdf4df0d69da7782b492195069d106931d07cc4614fd057751e1fa3077aef99983cd79bf848f09935f3e21d59aef5abdbbd4af9d8b7da2f6432609f424fcbf862be928e1e3495a2ac79d88ad3abb9b5b910009800f400d74256678b5854c28cb01827073459a4eda9104cf7cf87ff18be1efc3df879e1eb5f11f8862d26ea7170504c8c43e2521b0d8c7191c57f17f895c279a6333475f0b4af73f48caaa47d9a4d9af3fed3bf084b7c9e36b162bc9daad91efd2bf228702e772bb745dcf727283d2e1a57c6ff865e26d5ad34ed33c5967797b7b28861823524bb13802b0c4f0a66b81a0f1189a2d24733509688e8aff004b68d89e30490001d2be4632b3b1cb568d8ab66e6290239e33fd6b66ee6505ca7529acd9e8ba6dc5edf5ca5a69f6c8669ae24e11100e58fb0ae48d0ab52aa8c15dc8f7b0f3496a6769ff00b467c2a7452be3fd18b13cfefb07f2af565c2d9bdef1a0da67ad46ac3ab3447ed11f0a98f1f1034562065b6dc03b7ebe95ccf857356ed2a2cd7db5392e5b9f1d7edb3e3ff0d7c40f8afe0bb9f0d6bf67ac476fa2dc2cd3594a1c46c661856f42457f56f84d9262b2b57c442c7839b4e2e9f2a3c24c84280493cf24d7f59d35a1f0b61eaa446c416fa66ab96c5588c48ca4a83d471e9516828dac6918847b7ba81ce7eb5128de366c89d2e63a4bc852f3e1a5ac8090f6baebae47a35b8e3e9f2d72c63695ec61c92848e5c6090002003920311fcab4b7bd7378464e5ea685a6ad7909cc17b75013cfc93b0e7e99c529249371dc52872b514771f0c6dbc59e3bf13c3a659788759b341896eaea1bc9310c60f5c6ec127a01ea6bc4c7623d851777a9efe5d94cb1d5d416c7ad7c61f8e07c128be1bf0d5df9b796ea12e75094f98d090070a0f05cf524e40c918af0707972c6cb9eb23f43c46328e4f4beaf4373c765fda2bc7a8ccebe2167ce32d2c28c3683c8231e95f55f51c3c63cb147e558dc4d5c4547393d4eabc7bf1e3c59a1f8a27b589b4db8b458619a249ed15c10e818e7f13452c3df63cea1793462afc7ebfb9880d47c31e19bc27059bec67faf4adbd8f2b3ba516ae452fc6bd198edbbf86fe1d9222d9cc019093f4e82a9413f89936b31eff117e1f5ecaa24f8676f0a8f9b7c1705707d81e959f2a8f53a2eec1ff0957c2ab826597c19ad5a6eef1df06fc94b6056318cd3dcde4dd87c737c1b9d7090f896ce475cb794cace0f7e4d4d68c9a30854947a9e85e3ed27e1ff008b3e1ff81755d435dd4b4dd2e1b692c34f9fecbb9e408d96120c70c31ed9af0e8d371aed1cf1c45652dcf388fc05f0d2fa32f61f1156dd8f016e74d60491f5c62bdaa57716e48f43dbd492d48dbe0f7862f89fb1fc49d1a77ed1cabe5fe673915bb6e2724a7ef0d8ff0067b9ee4e2dbc61e1c9d4121a25bafde11ea338a5ced9bb7a13c1fb3cf8aa29c3da5fe892a27cc8e97abf291d0e39c9af3f1bef5192f2674c713c87d87a3444e8f60242ad2adac424dad91b8280c73f506bfcf4e27a7ecf31a89f73ae357da1a50aa220d80649f9857c833a2968cd7d3a30cc10f0adc0e3be2a233e59a3d551ba3f357c45e18beff84dfc5b05bd8dccb15beab70a648a16650c5c9209031debfbff0080aacb11945272ec7898997b39d8a2de1cb8e37e9f233e393b1abf45956717647953926ee7dcd286837649fbc5973d719c572456a7c425a1ea3f053c35a478a21d525d56182e5e07458c4edb400464f1919af1f31a9287c2776169a9bd4f533f0cfc16d8074ad3d9c0e76cbb76ff00e3d5e12ad888fda3b5c2256b8f85fe06f26597fb26d7e4462089bae07d7ad6cabe224d6a66e11343e032db8f87482d86116ee65233d3e6e3f4c57c9f11a6ab247d1e576f6773bc987cb5f192d2563d94ee731e29d0f4fd7ec0daea3691ddc1bc3ed917383ea0f507e94f0d889e1ab7341ea73e261ed299f3cfc56f08e99a7f8d34cd3b47b4fb34573122ec19c3485f1debf5ecaf35fac507ccf53e32be16d551d3fed0304fadf8c748f0d5846a6482d52de34276a8661ebf402b5cad5e352bc8eaccdf238451d1784bc5ba57ece5f0d6c6cbc777d1e992bdcca61102b4a1f71ce06d079ebd6be5f32a5f5dc45e07b7859f2c1146eff6d3f08797bb46d07c4faf303802d74d60adee18f515c94b2db4b966cecf6b16f73e5db9b6bbd6b5cd675bd5348bbd10ea57d35e436178bb644476246e1f4c57ea395d354e872459f1f98d48f3b079230557022099c6c18047bd7d0d3a6945ccf979cdb90ba2e877fe25d620b2d3a0373733b6114703dc93d80f5ae4c4e2a960e8bab5999d3a756bcf9628fa53c09f00b43f0e44b71aba1d5f5120331980f2d1bfbaabd08f735f8ce69c4f5ab371a2ec7d760b298435a9b9e9967a7d959c4b15b59db5b20fbab0c2a9fc857c854c657a9ef4e4fef3df8d0a71d20b535ed9c701b6baf4e541ac5579f4933b634d2dd153c43f0e7c2de34b592db5ad06caf164520bf92ab20fa30e47e75e9e1b30c453775226584a553747c8df1cbf67abdf844e9ab6897536a7e1795b614b96065b4727856603e6523a13cfafad7eb39171147176a15b73e2f31c9e541fb4a3b1e54939e0e02e47e55fa32d1729f332d35ea480ac880b9cf38cd449f2a7734a51e649b3ef2fd9d74d8f4df831e1b455526485a4248ea4bb1fe55f8367f88a8f16e3191fa5e02843eae9b47a1965dc7289c0ce76815f2f2a937d59eb7253dac2155701000b93e83352aace3bb2254a2b643629048b85219412a7775e297b47ceddd8dd3876278e40ce3a003b60538d59ef70f6706b63f2af5bd76ef5ff001778aaea775914eb1742228a000825603a57f45e4516f05077bb3f35cda9a55da89f637ec3f098fe1eeb972ca15a5d48a8c8ecaa057e7dc5d5aa46b4545d8fa4c8d41506e48fa225b9d8464e493e9c57e693ad3fe667d224bb10497848238c67b0ae675e7fcccbb43f94a6f78548c919e992a3352aad4eeccdc21d89e29f73609e7049c81daafdb4fbb0518cba1e33fb6b6b9268dfb35789ae51da2766822048e46e956be9722acde3611e632c5422e93f74fcfe7258230003100d7f48d2bc60ec7e5d88a77aaec75be0899964b92065822e08f4cf35f9271fd79d0c17341d99fadf00e1a33c4b53573ab6b99970c1db3d383dabf93a799629d4fe233fa0de1f0db382fb8ccf19eaf3e9be09f10ddc526c9e1b191d1ffba76f06be8727cc7132cc2927276bf73c1cc28d05464b952d3b1e3ba0decd7fa069f3dc3896496156665e8491cd7f726025fb987a1fca798538fb693db52e47192400481db15db527251958f2d434513d974eb978b46b1c1c03029e3be057f14f19e3b134733972d4763faa386f0587780839c55fd065fc6357b296dae0ee4946d1b86769ec7f035e0e579ee23095e33e6d11f458eca30d8ba12a6a1b9e51a84771a4de989de482e203b7314acbc762304641afec4c8730a199e0e338d9b7b9fcd19a616b65589745e8d17b4df895e2bd16426cbc43a942080a556e9f18edc66be8fea587925781e0ac657d7926771a778ff59f10699e7ea7aadeddceafb434d33390a31c0e7a57f38f1ff2e1715cb4f43fa2f80683c6615cab6a387896f5ae5116f6e02b305004ad91cf6e6bf1da18897b58fbc7ec1fd9f412b7223cdf47f8eff1135782f4cde33d60c315e4d6e91a5d32aaaab1000e78c018afeb9e17c2d1ab818cea46e7f29f12ca54333a904f4356c3c61aaead65ac5dea3aaea37af6f0ab8696e9f032c17079e739afb3fab61e9b4a113e06bd7aaa7f115acf594d4252cd262403051893b6ba952504dd8e094e7396b216e35ab6b552f9dee0e0023bfbd6d1869b9cca9f33f775397d475a9ef9ca3ed11672b1a7407d6b48c6ccf528a94374663ef0dbc82327b74a1e8ceb938d8b765ad3d84a082cd9e0a1e41a728e879decfda325bef12dc5c8c822353c05c74a9516959970a4a4ec75fe24d36d2f7f66df0d4b3dbc774d1f88ae144b2a8678c3292541eb8271c578389a6aa632d2573bb0f5274aa72a67938d16c77b28b68f1e9b17fa57a51c3d38a4f951e9fd666e76b9db7c13b0b6b2f8a1e1678eda223fb4a11ca8183bb8c7d3ad7c271a6169d6c92aa714b43a30b5e52af63efdd76dc6e60060827f139aff0035e6ed564bcd9f5d5a2ac7217a8d14ab2676e18601ee41cf35d31773c59e8cc5f8b3761fe08f8f23200dda35c92319fe1afa1c8a2e799d0b773ab0f539a27c31f09fc2fa56a9ade856f776505c413447cc12a83b8f94d8cf7eb5fe8c65f82c3cb0507c8af65d0f2f158a9d2968cc74d0ac4c019ad915b3d028e306bd596030ee29ce9afb8e4862ea463cf71cba5d9da4a258a248dcf560b826bbe8e1a14dda0924555c4caac3999336580c75cf3f4addbe5679f714396f90820138cd675eb469479a4ec8b4ee77de14f817e35f19402e6c34792ded89c2cd7e7c956f7c1e71ef8afcc336f11f27caa4e0ea7333d18c4ee22fd903c5bb416d534943c6e4667dc3db2063f1af80abe3160212bf25d1d70a4a46ddafeca3e2a7f0a5ee926ff4bf325be8eee176959554842ac0fcbce73ea3a572d6f18f2c8439a288fa84e751156dbf621f1dca91917da2f5e4995c0c7b7cb5e63f1bb0918dd533d5595ce3a8975fb0ff00c41b381a4865d26ff61cec8a7657fc32315d947c6ecb25675636345956b7674fa3786350fd9f3e11788355d534c92db5e66258b9ca82414894374f9796f4cd7b384e35c0f10e217b1a8923edb2ca71cbf0ee5d4f93eeeea799e4799ccb2b316676e37127258fd4926bf75c2af7535b1f9de36a3949d57b94dd4bab0c0f98723b57a0f95ec8f11de5bee749f10ae45cea7a25da0262bad1ada4076f00aee423ebf2d4463c873d05cad9ceb10ca3922a6dcccf623153b0d95914fcc8a5179f9ba0a875634a0e551e82a94accee3c27f08bc69e318049a67872f0db95cf9f74be4260fa6ec66bf37cd38df2acae4d4aa26ceaa743991d3ffc32bfc44c0dda55be3ae3ed4a3f0c9af8fa9e2ce5117b9d2b0f743e3fd967c7eae0ae95130f5fb4afcbfe35c553c5cca598fd5753bed7be0378cf52f801a278712c60fedbd375b92e8466e5788194e7e6e99c9ce2b9bfe228e52aa7b4b9c72c2c933c67c4df043c7de168649eef4195a051b8cb6ec245f7e0738afb5cabc41ca33292a4a76b9afb292479f38564494140c467700073e86bf4aa7561595e2ee8e1a94ed221790caee8dbbe65f998632d8ed9eb5d1ca91b5b42586f6452cd092ac17950e5770e9838ae5ad4d3a72319536cfbfbe16eacf71f0cbc333118dd631ab679c80315fe7df1d4152cdea451e961e363b282f576051c03d6bf3ddcf423a33a6d3640648f2548383c8e40ae792b3563d5a52b9f05fc45f89be2df86ff00193c75a4693abfd8ac67d50dc341b4152cca33d477e3d7a57f70f8758a4b25a73ad2b247938da0ea54d0dbd2fc7bf19f5ab24b9b1d36e2eed8fcab2b694a09c7fc079fad7d46238bb26a351c27555d18ff0066c9ea7bf481d8b10cbe843726bed12d4fcce3a102332f0b9c7d71cd3a90bad4ce4df425370f1310bbc7cbc90c45631a49f43aa359a4557bc9159019a48cf51890f35d51a4bb11ed9dcfa03f65ff0016c66db52f0d4cc166de6eedf711f303c3281ea300fd2bf3fe27c2f2c7daa3ea72aaf7f70f7394650807a8c66bf32a91518687d43f73531af9786e2bcf9ad6e899ea8e3758d1acaff54d32eeea0f324d3ee56e216070460f2bee0fa1e3bd7a385c6ca8e87955295ddcf21f1e78c9f4ef8c173ae794d2f91720c304d95dea1768fc39c8afd7f2774b1583b41fa9f379849fb65d8d3bff00da3a0d49a25bcf09d9de323128d2ca1c293c1c6e1c66ba3fb16127cc9997d7d43dc153f69636c00b6f0c59dbe3801252831f80a6b2357bdcdfebdca8f31f89ff0010ff00e139d6db50bab68ec5c42b12a2bee0704f39f5e6be8f0583fabab1e162b11ed5dcf33d4b56324a6389008ce41663cd7ad28fbace350e6573e84fd9cb433a5f8567d7e542d797ee6384ede5625f4fa9afc6f8b71fed24a827f09f539561797df67af26a52e0b312c4d7e5cddd33e8e2733f163e2fd8fc23f04dc7882f206bb70e21b7b58facd2b7dd5c8e9f5aeec061658d92a08b6d4352afecf7f1d2ebe31e9ba93ea3e1f6f0f5ed93a958bcd0eb2c4c3e5618e878c1fc2bd9cd324965d18cae674710a6cf69b7bb51825b04d7ce539a70b33d5e6d0c4f89ba52f89fe1bf8974d386692c6568c81921d54b2903d72057a582c47b3c44668c270e7a7247e7a69d2c77b6504e4ed322862bdc1ef5fd3386aaea538cfc8fca3134bd9d5922e4855471800296ce38e95751f33b99c3789fa09f0a21fb27c2ef0bc406dc58c6700fa8cff5afe7acf257c7499fac60ff00828ea5b95c160bfd6bc44fa33b766798fc73f8adacfc2eb5f0e2681a15bf88355d66f4da456b7171e40caa96c86e99e3bd756030f0c655f6736675256478878ebf6bff0089ff000faeed62d67e1459c4d7285d1e3d483aaa8383b88fbb5f6387e1d8d69693b1c15311c915ed0e427ff828678ea25262f8556d20e46e4d409fc7a74af5a9706d3e55394ee724b33a3091e15e1837bfd972dd5fc2b0de5dcf2dd4b103c21918b63f0cd7e9d83a0b0f4a308e87c5e63595495d753e8af807f147c6fe11f07df697e11f06c3e2c0b786e26dd75e532171c718e9f2f5af8ee25cba9579a954958f7329aea31b3e87aff837e2cfc58d6fc4f6369e20f8616da1e8d2b9175a80bede615c750bd49ed5f9663b2fa74a9b942573e8a35949e87a75c6b601f91f00e48cf7af924ecb53a653d0ae9ad236d2e402c0700e39a4a56ba467cfcccf9abe2e7edafaff00c3cf8a7acf84b44f05c1af269cd129b96b828c4b2863bb03000ce2bee724e1b9663454dcec675ebc282d4f13f8ebfb59f8bbe38f80ee3c157de008f44867ba8657bc4b9326d08c188dbc7eb5f7b9670a2c1e2a3539ef6f238b138fa7ecec9995e0cf86be24f8851dd3e81a4c9a8a5ab2acec8caa109190393e82bf42ab8aa184d272dcf884bdad46d1d6dbfc34f127802e0a6bfa649a78b98ff7259810e41e718e95f86788398c6bd151a47ec5c15fb9a8d9334611f001f5e7a8afe64a91e56dc8fd7eae39268e4fe2ece2dfe16f8ae5c8c0b16539e3392062bddc9b5c5524bb9f3b98e314e948f32f0e4421f0f69287040b58fb73f76bfbcf2f77c3417923f9bf1ef9ab48d16758d03c586c67a9ae9ada425e870c7e24cf5fb551fd9d6688723c943f4c8e95fc35c5e9bccaa7a9fd47c3b592c1c1079786c9383d31eb5f1119fb38a4f767dd4657472be3ad1c5fdb8bb8d435c40b86dab92e9f872715fb6f007117d4f14b0d59fbacfcff008cb21fed1c27d6282f7d1e760146c93907a1afeb2a7355358ec7f34ca84a32e57d0ebf4490a68b1e173ba56c63ae702bf993c4c95b1314ba9fd57e1bd3b60e562486e248ef2275c0756073d2bf0fc272fb4517dcfd9270b53679178230ba76a040fbd7f70c4f5c92e79afedce134e596c394fe32e2c85f3399e81a15d08bc2be2bcaab6eb388018e7fd72726beb6aa50a88fceabab3499cdc72ba370c464576257f789e4d1b23f35de5208241ee2ab979ddd954e16217f98938279c60d67393f69ee9d5610b09633e66739e0134d5ee6ce1a10c6719241381c6055cef639e3a3217c392a46430c1268849ad19a2f88f5cde5bf667d3bcc1c2f89a55191c64a7f80af9ea8b9717744c5fbe792cb84772475e98af716c753d8eabe14163f13bc22a0000ea70f4ee3757c0f1a3ff846ade875e097efd1fa11ac44acd267b3119ebcd7f99351daacbd59fa0cd7ba725abc4cf1918dce71838fd6ba60cf0ab2d4f3ff008a1b87c28f1c40ced86d26e78f7d86beb3873fe4674bd4e7a32e5923e3af840eabac78427601f2d17cb9c64b29520fe75fe90e58af8287a1e5e35de6ca17b1182eaea0638314d22727d1881fa57bb05781e7d39685399031c039cf6cd525ee957d46f0bc93b768c924f007ad6355a853e7e8825ae87d31f013e0bda69d636de25d76cc5f6a53013595a4919d96e8470cc0f0cc473edc57f20788dc7f5aa55965b8195ac75d0a2f73de56eae772bed2aaa3807231e9f857f3155c44eacdb9bb9ebc69589ed7556901054976201dc3159f3294790ea8e8759a415bb8948225dbc06e2b9674ea3972c51ead09289d5d9db189559b3c7009ec2bc8aaa719f2cf43dca3665e20939ce3d7d6b89fb925735b15b54d2ed35db292cb53b58afece518786e14329fc0d77d3c554c3555568c9ab1d09e963e11fdaa3f66b6f861149e27f0da35c785679809eddb264b0918f1eed1927fe039f4afec7f0c7c45a98c9472dcc5de5d19f2199615c3589f380f95f048183cd7f58d3a8a6bbdcf988c6fa48e87c4e45cf86bc19382302c66b56627ab24c483ff7cb50a4e556c72515fbd91ce33154dcc41c01b8fa9acaa548528394de88f520f547d77f00bf66bd3741d36cfc4be3083edbad4e8b35be9b28cc568a795247f139f53c0ed5fc6be20f1fe271589965b8095a0b767ab187323e83575daa0103b051c051d862bf9c275ebcdb6e4d9d34e3cac70dc5f24e4d66a6e7eeb5a9d29169a30e4648c15c9c0c607e1537945f2d8a6924539702472b9001ce47ad6b4ddd5ea2d4c124d19578e158a4676aedeb9e083d6bb28d49d397353d19c535caee7c83fb51fc39b1f0f5d5af88f4b892cedeee7f22ea245015a52b95703f873820fa9e6bfab7c32e26c5579fd4f112f43cfa8b9a47811645073d46738afe9d4fdde7413f86c10215c10e00079040f97d3151557b8c515789f7ff00eced64fad7c12f0bdc24459562922259b9055d863debfcf6f1097b2ce2adfa9ebd1a4dc0ecef74d7b4c9556c11c64723f0afcc95446d2a4d1634cd55e09d124c71f773557263270398d07f67df0f5c7c61f1178f35b86db52bbbf7896cad671bd220a06642bd0b16071e9d6bece7c4d8bc365ab0345d975f33b29494d9ef70cc046042a238d7855551802bf37a9527293736efea7aaa2ac7cdf21022326c04e7d2bfd535b9f823d04473e51254104819fee9ad67b13b90caec589c003a0f7a5030954b3b15e5258a9200238c75cd6ed9b6963d63f674d31b51f1adcdd67c98ac2ca494cc3aab31014fd3ad7c867af9a9283ea7d1650ff7ecf78f879f11ec3e23e94f2db910ea36ade55dd9b37cd1373b5bdd580c83fe15f98e3b032c3a49f53eba9d75564e269dd82598638cd7cdca3ca6e626a30654aed393df158a68c268f38f88bf0fe1f15d979902797aa40b8864271bfd55bd8f6f4afa2c97359602bf25fdd3c4c5e194fd4f9e6ee36d3eee4b6b9468e7898a346571b48ed8afde70b88862a8aa90d8f86c45371a9caca12cb90c40dde80d7a118c89e6be8665e590bc192ec1fa0e7a7e15ba4c86605d4535b5d3c6549651c11c83f8d5cdda0d9ad1579a81f727827458b48f05e85670a2c4b1da2128a30012327f535fccd9dd475319393ea7e8386a7c94d23527b750a491f4af9d7ba4743d19f31fed7f2adceb3e01d19cb1b7334b7d2460f0c54055ddeb8e6bf40e0fa2a788751f43cec6557089d57ecc3379377e24997a04893d00c9cd7d0f185a9d381e5e5755cdea7d0b6da93bbe09c8c751dabf2272692b1f5b1909e28d6934cf0a6b976e7882c27909f6086ba30dad48af329cecac7e747842679fc2fa7cb201b9d4b13ec58e3f4afe9fc03b61e31f23f37ccbfdeac6d4b0996d9950ed9194aafa1c8c735bd57c909338a947547e8cf852dcd878634480923cab18148ed9da2bf9c7329fb4c4d47e67eaf848da8a35b71907078e9c75af2e4fdeb9d2f739ef15783742f192d8aebba55bea9f6190cd6c67073139fe2523a1c77aaa729d39f3d366155183ab7c30f086ad379b7de1db4bc9f017cd9959db1d8727a577bcd3170b352b1c1569aa8ad33e24f8ef3e91a67c69f11e87a369b6fa7d96991dba18a051b0b32e49c7635fb3f0b626ae230a9d5773e3336a10a524e071139263e32b9ec3a57df4236db53c093725e874ff000ffe2a788fe1bbdd2e8b78b69f6a03cd0f187dc474eb5c78bc153c6fc71b9d186c43a6eddcf63f845f1e7c6be39f1fd9691a96a29359c914af2c690a8c855c8c1038e6bf3ee21cba951c13942363e87078973958fa1a1d2e7b8dad2ab6475cf15f84c57baf98fae8abc5166df4432c881482a0f73cd66f45a94e3667e7ef8de4379fb4cf8e4953c6ad05aa90739223c9fc302bf7ce12a717818c923e4f3ce78ad0e4afae8cf7d3b9f9b73b11f9d7e974a3d648f8c955a8f46cf5dfd9efe3d5afc18b7d66dee749b8d486a73a4bbe299504415718e6bc1cc72b58ab38cac7a7849f2ee761e3ff008dd6df17d6d26b5d367d3fec25958cd287ddb803c600afc278d702f05455cfd4f86b15cb3958e01e49198919e6bf9fe7ef36e47defb77391c57c6c475f843e282ce591a18d4f1eb228c7b57d370d52f6b8f847ccf3718dfb291c4e9b179561691f4db0aae3d30a2bfba7070e4a497923f0ec53fdec8b32cd952142e546303bd5e23484bd0e184b667b0d836cb2b55c1ff529ff00a08afe20e2a57cc6a3f33fa0723c435868226910b0e4e7dc57c14da4ef2e87e8d84c4dda4fa19d781e2c381c03d3a71e95d387af3a5554a2ecd1f574f92aae45d4f32f1368df61bfdf182d0ca4b2903856eebfe7b57f62704f107f68e11519bf791fce7c67c3cf2bc47d660bdc91a9a4c861d221d833994b115f9878933ff69523f5ff000e9a78076268199afb7281ca9ce47ddfa57e3584e5752329773f5baaed4d9ec3fb33fec8de13f885f07f4cf11ea7a8ead0dddecf3b4a904aa133e6301b46dce303d6bfaa72acc6ae0f0b4e9d2ec7f1af143be673393f8d7f09f4ef84fe2af1268ba4dddc5c599d160b81f6b20b966979e807ca302befb2ec5cb190e79ee7e778e8f2cd1e17347e5ae09c955cd7d3d395d58cdbb4190c7bd70cac7f1ad9cba208b3a2f01783efbe23f8df46f0c59dc416f73a94a6249e7c854c29624e393d3f5af3b158a584a2ea33b62ae7bd7fc30078b01755f13694ccdce195b00fe55f22f89927f01dd1826868ff00827f78cb731ff8487482147dd5ddfe156f8995be125504d9e2df19be106a9f053c4f67a2eb173697773756a2e91ec998a81bb6e1b70e0f15f419763d660ae8c254ed235ae15ee3f65a563859a0f132e36b71cae39fc2b093b62ecce0daa1e4f264b007071d79af7a3a9d5cda1d47c29931f133c2921c90ba95baa9ed92c057c471a42f9356f43d2c0ff191fa25abc444d2af38dc4e071c57f97f5f4ab2f567e813d51ce5f400853921b6f231c56b499e5ce173cf3e2cda96f873e2cd885ddf49ba52bd323ca6afafe1d7ff000a74bd4f35c79648f87be1948167f0acb939596dce3a63e60057fa49953be061e87878a7efb25f11446dbc53af46e725750b851f412357d052f80f3a96a66a63cd3c01dfdeb5b7ba74c56a74bf0f3438fc4bf103c39a54ea0c179a845148bb411b776483f957c7713e3a780caead75d11d3085e691fa2106922594f1b40e14018c01c607a57f9979963278ac554ad37ab67d5d2c32e44cb4da4068c2000e06304726bc9f6d76743a0723e349a0f0ae83ab6b1748c2db4db57ba940e18aaae703ebc0af5b2bc33c5e2e34fb9cd2a4d1f0cc5f143e217c61d4755d46e7c4b77e1eb1b5d366be834fd31da38d238f185e3a939e59bb835fdadc39c039651c2aa95e3cd2672ceb3a6ced3e06fed37e35f86dab5826a9abdc789bc3cccab756d7d8695118f2f1b750467a1c82076af3f8abc34cbb1985955c3c796491ea50c5bb23f462daf22bcb786e2162619e359509e0956008fd0d7f0b6330df55c44a854e8cfa58bb9233e7a1ae14ada22d98fe2bd2acbc57e19d5744d42359ed2fad64b796223208652063dc6720fb0af7328c555c16229d5a0ecee73d64a707cc7e4acd6af63797765291e759cd2db30ef9462bcfe02bfd3de17cc6599e574ab75b6a7c2d785a6ec6eea8cf79f0d3c37204545b7d52f616dc7920aa32e07a707f2afa972519dd1e453d2ac8d5f81fe1f8bc4ff13bc3b677015edd6e7ce9637190cb1a970307fda02be178d3172c0e5556b45f43ae2f547deefa89bb652efcaf4c7422bfce7c53788af2a927d4f7213e5411dc219386cbe3a66b95b56d0d2352ecafe30f17d87813c23aaf88b52ddf62d32ddae25087e66c0e157dc9e2bb72fc1d4cc7151c3c776ced4ec8f8635dfda97e2878d3519afad7585f0ee9b2022dec6cd17722e7e52cc412cd8c67b57f5de47e1ae5f2c2c678985e47155acd1ebffb337c70f19f8c3c4efe1ef104b0eb10792d30bc61b27882900b363e561cf4fa5783c5de1de1b0b8475b091d51e73c572b3deafaedde4600650f538e057f31fb0f62dd3a8b542a957995cf06fdae0093e11c577bf22df5480b67d092bf8f5afd5fc3f6e39bd2b3143de3e5397cb24feed839f98aff00857f77d28fb89c7a8a62d94be64d08246580e98208cfe955535831c363f453f639892efe045922f48afae1707b7ce48fe75fe7e78ab0f679bbf33eab0904e07aede69092a64af2c704e3915f8a46a1d75692b9cb6b3a218602e10ae3a357741dcf3713479568721aff00c46b0f86be1ebad6f5cbb16da7592ee67dbb9d8f6551d598f402be872ccb6799575420ae725197233e72d47f6fff001adf5dc92e85a1d969fa61244515d21964233c3311c024638ed5fb4e1fc2955a9a9ce5ab3d35883dabf78cd1a310119b04fa0c7535fd7bcdef591f8968e366562c587ca7e40d9e0f5c71557bcaccc64d42364566c96254e493c8ada0b43913b8aa58900f2075c534b53a13b1ee3f06dbfb07e15f8eb5f971fbc1f638b0707eee3afd5c7e55f1d99be7c5c299f5d97af6749d53c7f42f146abe0cf11daebba44fb350b720491b31d971167e689c7707b1ec7915ecd7cbe8e2a8384b7b1e4d1c73a559c8fb0bc1be31d3be217866df59d318b452fcb342df7ede51f7a36f707bf715f88667809e12a38491f7f87aeabc6e8d0b8b72d93c11ed5e1d48da3a1a38f33d4c7bbb1dc300819e303a9a88a51d1ec73ce09b3cc3e2cfc283e23b57d574c80b6b16e84bc4081f6841dbfde1dbd4715f7391e772c249426fdd3e7330cbfdb2ba3e707531390e8c8c0e19586083e95fb750ad1ab4555a7d4f8a9a74b4646c4364a103d8f5aedbdd5c98cae436ea2ef51b6819322491633f89005638997261a4df634c36b8847dc76d6c20b5b78c2e0244abc74e00afe5fc74af88933f50a2bdc43678777415e7495d2346b43e47fda9a4927f8efe1cb62079769a1993ae70c643dbe82bf51e0ca7ace47cfe68ed4cf46fd9ea145f0deab76176bcb70a9d3aed18a7c673bca313cecb17bb73d76dae027059b2bea6bf2a93ba47d4a97ba73ff19f596d2fe0e78d2f7a797a5ca8a3d4b2e3fad77e5f1e6c54517cd6bb3e29f0ca2c3e1ed36251d2d93a8f6afe9ec146d4a2bc8fcf71cf9b12d9b5649e76a16712fde79e3418f52c055e297ee64fc8cf0fad748fd23b688dbdbda4040cac2ab8fa2d7f34e2ddaac9f99faa61f48226e03743e99ae57cae29b3667937c64fda47c15f04356b3d3bc512df0bbbc80dcc4b676c655f2c36dc923a735eae5d9754c7b6e91c55ea7b3576795cdff0505f8421948bbd540dd818b36241fa57b53e14cc251e568f3557856776cf96758f1cd87c4bf89fe39f15697e64da4ea17ca2d1ae10c72150830594f4e7b57ea9c3f80ab80a11a533e6b38a909ab4425727ae3f0afb648f96457933bd739e7a55328f6ffd91f4c377f15da5033e469f2b15cf62401fa9afceb8be5cb82b1f479446f58fb6a1b4cf3c607a9c62bf024bdf3efd2d0992d7cc9a3181b43039acdae593344bdd3f33358952eff688f1edca618ff6f4e4e0f4c2e3fa57f40f09ab65f13e273df88e4a642d2c84e3924f1d3ad7e8903e2ed7208a42af827e51daae5137a3a33bdf02444e9f76c4600986067fd915fcede2555b46313f54e19a5cf7b1d1842485f5ee6bf9adcaf13f57a585bd8e07e3ec9e4fc24d722527f7ad0c608ee4c8b5f5fc212be6948cb32c33a7849338db542b040a0f2b180c01e8702bfb9b0dfc247f36e2ddab489edf99402bd58039f7a9c67bb464fc8cf0d0e79c51ecf1c0be44609dbb114000fa0afe1ee219f3e2ea7a9fbb60293a7422c4858b480100715f0d51734cfa0c257e596a36f232dd464679f6acdcf9647e8d81aea491cdeb36115ddb3a3700f0b83c6eec6bed321ce679662a3569bd0edcd72ba79be1254aaf531ec2d2486cd2029b6589995d4f623dabea38cb33a799ce33833838432f9e558774ea6e88009e2badac31e6021b0318e2bf3cc35fdac5b5d4fd06a6b49fa3313e157c58f19685e0cb5b4d3bc53a8da59c534ab15ac72ed8d06f6e0773ebd7bd7f66f0fe1e955c0539ca3d0fe2ae27a9cb8f9fa9d0cbe23d57c53a5789ef758d4ae355d44dac311b9b972ce504a36ae4f61cf15f531a31a6a2a2ac8f80ad539e47113c2570aebc2ae067b8af5a2d22f6894829dca0a8284f453835ab9e9622fa23a1f02f8caf3e1ef8cf4ef1169b04325f58333429703726594a9ce3d8d71e2f0f1c453e5676467668f718ff006eef1dc2df3e99a4007fbd111fd6be7ffd5fc3b3ae356f21c3f6f6f1cc458ff64690ca7bed6007b7534bfd5dc39a46ada678afc62f8c3aafc65f125b6afacdadadbdc416c2d512cd4e31bb76496f735efe07014f050f708e7bc8d6b75117ecb9a901f7bfe123899949e9b8631f90ae4aa9cf19138deb50f288a13797f6b079820f3a5588395dd825828e3bf5af725a42ecd6daa3edcf0efec276fe13d5ac35f1e2f9663a74f1de792d6e06fda436d3e99f6afc4b8af3d7532ead4651e87af8556aa8f5dd46469e6720008c49c7f2aff3ceb3bd493f367dd4b63265070091cf38cf4a22ce46ae71ff00126cd9be1f78a41503769573c8ff00ae4d5f4fc3f525fda34d476b9c13a4b9ed13f3bfc0ce5f45d1e5e49c42c4fb865ff0aff4ab259a583a6dbd1a3c3c6525cd63d1bc49e06d6f5ff1d6bd1695a1df5e2497d24d1c915bb6cdac41c16385cfcdeb5dff00da3428af7a41976558ac42b528dcdab1fd993c6da83ee782cf4fc641134e1cfe4a0ff3af3aa711528bb415cfa7a3c2b8a6ef55729e83f0bff666f107873c75e1dd62ff0055d3e25b2bd8e664855d9df07a02781d6bf3ae34cedd6ca2ad3e5e87a5feacfb15ceea1f5f5bc686562bc3063ce7ae0f6aff003cab4e33a936fb954e0e2f918a22203028a493c7a8f7ae6e6d0ddc0f3ff8dfa7cb7ff09bc670244f24b2e99322c683731f94f402be9321aea96614e4de97470d581f0efc20f06eb8f73e4cfa45edbc33e8b756accd032aee78be51d39cb015fe85e57c4395cb0507ed12691f2f8ba72a93f7516bc39f00bc6fae43127f643e991b2aa3cd7cc1028e84ed1927a7a573e69c5b96d3c24e6aa26ec7b185c0622504d23f463c3492e9fe1ad1ad277579a0b28a2774ced62aa01233dabfcf1cf6ac7178fab563b367d253d3465c9b52091824e33c023835e04637491729d8c6b9d5424f8009c1e4fa715e8e1a16a91f538e75343f2cfc688f6bf13fc790b28e35bb82a48c617773fa9aff0047fc3d77c9e9af23e5319bb66c0952e7e155d80d896cb5b824dbd7e592165fc3eed7e9ad5a47cdc676a88dff00d9e2ec5b7c58d04965512bba2fab12a78afcd3c408b9e4d56c77af899f6ba9230a401eb5fe7bd5d26cf529bd0b36e144f1951f39cf4e95848de9bf78e07f6a1b632fecefe38ca9c8b5562bd410194935f55c25a66b49bee7a719687c11195fb04054856550460fe38aff0045f2e872d087a1e26225a9ec1fb2ceaa6cfe3468c85b1f6c8a6b561fc3f3212323eaa31519961e96230d384d6e78b88bbd493c53fb4efc47b2f105f59c71691e5dadd4b02e206dc76b15c1f9baf00d7e333f0e3038c4e725b9df057a48e33e207c71f1afc48f0a4fa06b29a51d3a69a3918db5b9593f76dbbef1271c8e715e964bc0985ca314ab535b1b537a1c3ac67280b918c7cc7e95fb6d285acd32b764f6fb9a52480b8e98ebc7a52a9f0134dd99fa11fb085fc97df07b568dc29306ab22aa83fc0554e7f9d7f04f8c7071ccb9bb9f6397bba3e86316e52319f5afe754ec7ace3a993aad909a06040c75c67815d94ea58e6ad0ba3e17fdbdda65f10782f465f92cc99af581276b11f28e3b9ebd6bfa4bc2bc346b579d47d2c7813a7ca7cd84f97851c80319dc17f4afec7a5865c88e473b1fa4da4783f4ff00126a7169da6ebeaf773b6c8e37b76f9b23b9af3f115bead1f68cfcb29d375aa590df107c3b1e1ad466d3af35bb486f61650e815b1c8ce33f422a70f89fac479d158ba0e8e8cc51e16819b7a6bba716ced2b2314c7bd7a709e8797118de099de54f2759d32462723131c629739a3dec7b46afe1bbbd23e01689a124b6e979a85c19a52ce155864b75efc6daf8c84fda660dcba1f6905ecb0563c664f875ac3480c66d6407fbb70b96afb48ca125747c84a579729d0780dfc5df0b75e7d574fb117361280ba869e93ab09e31fc4a3b3af63dfa1af0337c0d2c6d169af78fa2cbf18f0f25191f5269ba9da6bfa65b6a560c66b3b840eac7861eaa47620f047b57e238ac2cb0d51c2a6e7dca97b58a9212eedd241903e527a66bcfb5972c83d9df521588f96db460f1b5b3efcd11bc747b116be8cf993f681f039d1bc6305ed85b48f6daa2bcbe4c4992b20c06c28f5e0fe35fb1709e60e50f6151ec7c2e7184549dd1e4d2699a8c53ed36170303f8a161cfe55fa6f3250723e413e534fc1ba54f3f8c346b69ad6e10bde44adba260b8dc3be2bccccea72e0e4cefc22bd747dc12db8f31c01c67000ed5fcd588f7a5291faa515ee2217836100753cd72af84d9ad0f8afe3fdf25c7ed21ad24a062c749b7b753eacc0b7f5afd778415a873773e57377681ee5f0234c8e3f86d6f2e3e69ee2470deb8e2bc0e2d9f362944cf2a85e95ceda48c23ee04003b11cfd6bf36bee7b915d0f38fda7755363fb3df8b1cb00d3471da8f7dd228c7e59af6b275cd8fa5126bbe5a6d9f326951c7fd9b02870be5c481541f61c57f4e50d1247e7b5e77a8d9d0f83ace3bdf1b787edfab49a85be411d46f048ae7cc6a726164fc8db03ae211fa34d1e6427b03dfa8afe6ca8fda3937dcfd521a410cb851c73b4f4150d4546cca6f53e3ef8d063d47f6c5b7f391668748f0bb4852440caa58b1ce0f5afd238562e1425289f3f9ad4e489f395e59db4b2974b789048cc49f280dd963cf4afd6e9c55949a3e0eae2671768b228e148576a2845538215428fd2bb63a5da38e5394b5910c8e10700e73e956dd88444c48619ce6929147d17fb105a0b8f893aedc72443a58420741ba4073fa57e71c68ed84b1f59912e6a973ecf5842124206c1e878afc363bdcfbb8ad5934483cd42063dbd29b8f3345dbdd3f2bb4f264f8a5e3ebd61b4cbac5fcaa31e8c57f957f43f0ed3f658081f0b9f7c473e58891b7f4ec57a57db40f8e86a47085798e1b207af5ab948e882d4f43f01c7e5e8d7258fcc67c81edb462bf973c4fab6ab147ecfc1b479e2cdb0496048231ef5fcf6a5d0fd9e850f791e71fb414824f867730701a7bdb745e38186cf3f957dc70646f9ad238b3f828e0a4733010a41248ca8ea3af15fdd1434a48fe4ec4abd7916aca3dd7084618ef0369f722b1c74af8797a335c0afdec51ed822192ae0295000c1e09c57f0767951ac6d4f53fa430d42f858bb155d0a480e4282719271ffebfa57814973c8f2e5fbb91664844917e1d2bceaead50fb2cb2bbb1877fa7811b14401b3f9d6b4ab722b9fa26165cea2999335a32ee7e7731cb1cf53d326bb255a53872df53d3a6d6efa95242911999c8251198b11d0053dbbd7460a3375a29bea556a96a4fd19e5fe05b75b6f0e5b23c611f74849739249763fd6bfb4721a91a580a706f647f18f1143da63e6fccee341889d13c4e51d4b9b58b626792c241c8efd2be86789d23a9f175a8b8cb439c960770c25241c74f4aee8574dee6b1a778ec451da04c6f0360ebcf3f95752aca72b18386856961009273c7a568a4dcac5495995d622edb8e40f43c55a512a9bf78ab711b6081d2b54a20dda6570a72720e7d6b449246917ef1eb1a542b77fb31f88124257cad7addb713c9f9460ff4af9fafceb1917132e6f7cf258e47b5bb8e58c94962612238c1d841cab7e079af79abdafb1d49ec7d09e10fdaf7e25eafe23d1349bdd66d6e2c6eaf20b5990d9286642c15b91dc8afccf8af28c2d3caeb55e5d6c7ab877fbd47d657836cae8a490ac5707fa57f9a95f4ab3f57f99f72f628188ca0654b38e833dab34cced730bc6f6de6f833c48b9c83a65d82319cfee5ebe8324a8a38ea72ecccd5357d4f947e15e8de02f85ff0cfc37acebae752d667b25b848001232039236a8e071ddabfbff2a8e2f1986a718e9148fa28e1f2cc352f6f887a9afe3ffda2759d2b5a9f4cd22082ce18a28a4599fe77659103a9c701783d2beaf099445fc7a9e74f8aa9525cb85a6795eb1f173c5baac8649f5fbc076ed5485f629f72140cd7bd4f2fa34fe181e3cf88f1d59fc4697c2ef156ab73f123c22971a9ddcbe66ab6eac649d88605803919e95f21c5b82a3532dabeef439166b89ab3b39e87e8cadaafda480876ee206074aff3131ee3ede715dd9f59495e0a4f72cbc023048033debcee6dd1b58cabb8c796e7afd2bb28cdd36a48e3a88e235ef32de5211d9541c32a9e2bea70b8ec53825ceec7ce629abdee49a45e1588018c83924f24d2af5f11cae0e4da65e1eb3dae7496fa83797b4b11f2fcac4700fa57cfce0ef77d4f5a15886f673244bbc919ce76511a36d42754c7baba8d433f57c05dd9e715df4a16699e754acee7e70fc5a85e2f8e3f10a0c2a2aeacedb7a100aa9e3dabfd04f0eaa5f2a823c8c54ae893438a39fe1f78d536319a136375b81ecb2b2f1f831afd7a48f9d9ab5645ef8312083e29784db2326fd507b92ac2be278c68fb4c9eb7a1e9bd0fb9c1114e43125558f435fe7362236a92f5676d37a172d645f3090481d738c9ae39ad0de9bd4c0f8e164faafc12f1c420eedda54a791dc0cff4af7787aafb2cc28cbccf563f09f9d5616c64d3ed1ce32630c0678e40aff4872c9f361a0fc91e3d7dced7e126b1fd83f13fc2d7cccd1ac3a942ac597b330538fc0d7a75d2a94a4ac79f5a378dcb7f1bf423e1df8bde31b0423f77a9cd30c74dae77a91f9d70e112952bf635a4fdc384e5d98870a3f847bd75461cda3358b0689b66645dedc0e3a7e95a72f44cd23a896e333221601065988ec00e956fe1b0ad667ddbff0004fbbf2fe08f17daa92563bd8986460e0af27f1afe25f1a682588a73ef73ebb2cd8fa91a32fc639ebd715fcb07b8f72bdc4798c8c8271d71eb56ae135747c3ff00f0508d3845e21f87fa81dc062e2d8900904ed0474fa1afe9af08eb38e2274bbd8f0b191e547caceaaeec4a32f3c0f6afed8a6bdd47ce37a9faf3f0d7e046a7e16f1b69daa4da9e9f7b6b6a59d85bb9ddf748185fa9afce31d98ba9439651b1f3784cb2787a9796a64fc48f829e32f1278b353d52d6ced6e239e72c87ed0a0ece8b907a715ae031746850b49ea4e3f095312ff76798f8bbe187897c1b6b1dc6b3a5a5b5b48de5acbb95c648ce303e95efe1f1d4310b920f53c0ad9757c3c7da491b7e00f819adf8ef488b52b17b1b6b0323c61e663bf2383800571e619ac308f92da9db84cb67898aa973debc77f05878eacb43b33aafd821d36d4421522dfbdb0013d471c57c461f3154310e4d5ee7d7cf0d1a94fd99f32df7c3dd7edf50bf8acb4cbed5acadae64816eede1251f69da7a7b8e95fa061f30a55209b76b9f1b572fa94a76899f75e1af1159807fb1f54849600efb790601ebd3a574cb1341c7de64470f51fbccf7af10f8f2cfe086afe15d3a6b62da35e5829d55901cc0dd16703bf39dc3d39ed5f03572bfed3752b45edb1f5ab150c3538d393d4f58061bab78ae2de44b8b7955648a58db723a9e43023b115f9dd7a32849c6a1ee426aa534d0cda4b127a1e800e95cad740b7bd73c3ff6b7d425f0ff0082340d56da57b7ba8356485668db6b05914865cfa1c0e2bed385e5fedaa078d9a457b0773c0c78ff00c451b6cfeda9f7762c14e7f31c57ee9ec95fd4fca5c3de676bf04fc59e20f107c52d1ec6eaf25b8b3776665655e70b919206715e067f6a78299ec655072c41f5dfd9c9ed5fcf93d64cfd4e9c6d00366263b707713c565c9eed8b6ae7c35e37f17de69bfb407c497b48e09a3373042de7c42400ac63d7dabf6ae18a5cb814cf8ace5df43ea2f86f1bddf80346b99628a19678ccacb0aec4049ea076afcef895df1723d2cae16c3a2edf591dac49c9fe55f15357b1e9a8da4cf0bfdae2f0dbfc1511002569f58b38d908c8701b71047e15f49c334b9f32a6fb1c589d69b3cd63f1be9abb54f82f490981ca314c7e1c935fd1f4e97bb6b9f9d56d2a33b5f839ad687e22f89be1cb3ff8456d6da77badf1dc2ccc423282c0aaf73c1af0f3a4e1829a3d3caa1cd5d1f6d3e159b3eb9afc0a5f133f504bdd488c9048f4e9594fe1292b33e35f1ceab68dfb4bfc53beba81a6feccd12da1f91b0c576659476c9cf5ed5fa870cc5aa1ea7c9e77b1e52f7df0fe54517167addb92a00f2d8395fc87eb5fac4632491f9dcaed91de5bfc395854f9baec2e0f6dac7f4a7792095d22bcb67f0fa5042ea5aec4dc6336ea464fa7ad36e5d8952d48a7d33c04aab9f116ad1b05ced7b2dcdf90e94e2e7d8d2a3ba3e8efd8c745d0ad355f12dde8dac4fa8e6de38e58ee2dbca2996241fd2bf30e35ad274e1167d970eeecfa8b04120818ec6bf218ad8fbb4395bcb5773d154b7e429c96b11deccfcc1f03785e2d7dbc41abaf88b49b56b8babe93ecf72ec1e1dd330dcd818c0c7eb5fd1b94be5c2533f3acf2a5ea5822f85264000f19786ae011f2f9573b57f1cf35f491a9a6c7cb4742583e0e6a287f75ae6833b918005e855fae6a67555b63ae26ee9fe1fbbf0b58c96974f6f2ca5fccf32d651223647623e95fcade254f9b1091fd01c0f49ba2e4452dca9c100820f26bf0cde499fb451a7b1e73f1c20b9d5bc27656164a1af2ef55b6822563c125ba66bf4be06a7ff0ab091e0715c7972d9b3587c23f184691a3f876e885f970a54b7e59afecea551462b53f90aabf7d8e83e1a789ed2e964b8d06f61895833168fa283927f4ae0cc2bdf0f3b763ab2ff7b170f53d1154193009fc6bf84b3a6e58d9fa9fd654e97ee23e870df1e2deee3f8765ad1655ba3a85a888c2a59f7798082a0724d7770dd0f6f8f549ec7c8e694791739d9f876ea6d4b4b865bbb76b4bce16785d190ab7a8040e0f5af4b8c322596d6f6b05ee333c9f1d194b96e58bcb05618206335f9c2515ba3f53c2e225a58c3bcb6b6824111950348d8018f1f9f6aefc3c14dfba8f71622cbde6739a95f5a059e04daf318de32782092a78dddabeb32fc062615e326b439eb66347d9cd36790c77963a2787200751b49e641b1c2cc03ab77dc0e315fd1381c57b2c3a8c8fe69c7d2f6f8a9bf3390f106afa8cb6f04b6cd30826909f3e0980550077c1c9cf4f4a278eabcd7b99430747aa29e99e2cbf8268cbdd3cc9b9410cbf70763b81efee294331aca57b8e782a2f647730f8b64b2bb6b2d41195d5436e0a48653d183746fc39af6b0b9e4a32b553cc9e54a6ae6e1922b8804b110eac060af7afb7c3e296223cc99f375e84a8bb343194018604b7ae7a576c2bdce15c89956e1046a7209ddd335d30a88cab4a2b62932f9a0903ee8c135ba9c6ccde2fddb9e97a0ac937ecd7e3220600d6ad00c76e0720578d88947dbc4e252b48f256465639e4f5cd7b919c6c8eae6d8daf00003c7be1a771851aa5b938ff00ae8b5f27c58e32caab7a1eb6165fbd47e90ea19fb439c01925b15fe5be295ab4fd5fe67e80b58954463390c707b1f5ac6fa1315a94bc491a7fc223e2207b69975dbbf92f8fd6bd4ca9db154df9a2fe167e5bf83de5bdf0c5940892492cd10894282ceccdc05007249278aff4e7204965f097923e43329b94ed73d2fe21783b59ff0084ae4961d275078dac2c9722ca5c0658555973b79208af5a8e3e8c74723c7a506b539997c29ad2a82746d45792369b49339fa6de2ba563b0ff00cc74c53b9abe06b1bcd2fc7be1192e6d2eacf3abdb6d1710b444fce3a6e033f857ccf13622954caab283e8ceaa11f7d1fa91e5ec9e4c73862057f9638d76ad513eeff33f4ca4bdc42b47bc104e2b8ad7b32cc4d7a5361a65f5d15592482092619f94310a5803f5c57a382a7f58af187739eb2d0f859bf6e4f10f88cc220f015b30964d88cb76d96c92001f2f193c726bfa932ef0b1e2a8c2a529fe07c662a5ac8adff0da7aee97792c52f80a38cdbc8c8e8d79b5b70ebc8522bd8a9e11d4734e32b9e7d1a9645fb4fdbc75654f9fc060c63e6dbf6ef9be9f77f5af367e0ee25cafcc7a31ada924dfb79df9001f87e40232035f01cfa9216b0ff88398ae6bf31aceb6867cdfb734f22166f024b95e582dd86cf1d0702ba978438a8db9a6723a973c13c41e2897c79e3ff12789dec4e9c9aa5c89d6d59b7b4436850a5b0327e5cd7f4770ae452c8f0ca837739aabba36fc172092c7c6366537fda34691d416e0189d5b38efd715f7ee36699e4577cb38b24f86b38b3f88be159dd4009a9c0dc718cb01fd6be5f8963cd97565e47a4bdf95cfbeb54b72b339072c49c81dabfcd7c6bb6264bcd9d4d5886d26f24300a18e7904e315ccd5d92a5613c7016efe1ef892dce184fa5dcae07af96dc577e569c7194daee8f5e8ced13f3434494cba4da10728b100093ce3d0fbd7fa4392bf6b82a72f2479957e235f4dba6b5ba8672c1de19a3955ba05dac0ff004eb5f42ff87639e5f0347acfed4b688bf1725bf8d488f52d3ad2ec38ce1894c1af3705a73239b08f468f2078c370a7e63ced278c0aee3b65a079a8a5408c039e4e73cfd29d87063022ee662a06724a9f4f5a51775766b15ef1f657fc13e350737de33b2df98fecd04abe84ee218fd7a57f2478db412a545f7b9f4f96fc67d8cfca0210123a67a8afe393e816831f3c8238c5340f53e46ff8286e9a4783bc1f7a809f2b5375c8ff0069303f515fbe785159433349f53cdc5c7f747c4d35c15701e32cd8e49615fdeb17a23e35ad4fd5bfd9b1e58f5af136a664261b4d3c8cb31383f7875fa57c26754e2dc6278596ce6e7273d4f2abcf1a6b4b3cf3a6af7918625b093b05e791819c57bd43074e1423a5ce0af88729b51d0f5af8b9a95c45f06bc0d05e4ef2ddce82691e52599b0a3a93fef57cf6063178e9ba6b4477e26527864aa33d03f663bc8ee3e18ac0ae59edaf255619e8490c3f435e07114671aea72d8efca9c1d1b44f5a56f98123bd7c8c746e527b1ec5a2df323e54f89de28f197c26f881ac5969fa9496da3ea131d46c95503200f8deb820f46ce47be7bd7e8593468e2e92e657b1f3f99d7961973230e0fda57c710c786d4ede7edf3da2e0fb1afa99651869bb28dafe67cec733acdf2b394f17fc40d57c7f7eda86ad2432dcc510855234d89b724e00fc4d7561b0b470f174e9a33c462ea579c5f63debf6609e66f864d66fb9adad2f24580924855273b573fc20e78ed5f91f13d282c4da99f6b95d47530da9eb6621c119f7af8f695ec7b7cba5cf94bf6e6f134335c780bc1e8ead35d5e9bf9a307a2203b49fc7f957df709e15cab3add8f9fcda76a4d1e32d2a98990104eee723a57edaa37499f984dee7abfecb96a975f166d48c6f82da57fc318c7eb5f21c552e5c0bb1f4391ae6ad73ec8f208da540ed9feb5f845af33f495a21e96f8990ff00b59ab6ac6915789f9b7ac0fb77c4ef1fced96965d7ae17039f954ed18f7c66bf68c85f265c8f84cd759d8fbd7c1da1be93e0ad16c24011a2b48d5948ee464ff3afc9b389fb6c54ae7d1e061cb462892fec06d208e3d477af9b9c6ccec946cd9f24fedb17be4699e05d1d0b092f75769c85600158d39dc3af7c8afb6e10a1cd8dbf63c4c64b929b3c8d244640b9605475ec79afe828c2d2b1f9dce5cf519ea9fb32db09fe32e821981f2c4b201e9843fe35f2bc48f930933e8b2557aa8fb9e6b8f9013dce33fd6bf9fdcb56cfd153d8ad7178a8c103124b01c7b9eb5cf295e28873b33e17f17ea6b77f103e3b6b1850a2f2db4f5e73bb0a030fa7f8d7ecfc374ffd9e9799f219d54d0f221728e7080e36fd315fad5958f878c936579dc248b96049e95164c755a48a93ca09dc0127b8273ffeaa764723d18aae64dae4b673d2aac91bdae8fafbf612b70749f18dd952b235c40993cf014d7e3bc6d25cf089f7dc3f4ed73ea4de33ce076eb5f95cd5a48fb0457d56636ba55fce7848eda4723e8a4ff4a695e51f513d99f935f0da588783f57b861cdc2c929c75f9a6241cd7f496590b61299f99e6def55647342b2302c510a8c93b4135f4b18f91e06c470849a572cfb31ff8f54cd3b6c74533d23c26c91f86e0f2804576662a3fde35fc8be232be31a3fa7f81692fa84644d33e588c9c751f5afc516d73f61a54f53cdbe35cae748f0e202555b5a85b20e181c1e41afd6f80a0a58e8b3e4b8c55b2d99607883554611c7ac6a2814f1b2e9874fc6bfaf611f711fc6b53e391bda3789f5d9aea141addf7965c064f3db0e09e411dc11c1af1f336a3879fa1e9650af8c87a9e8ab206b96200019bb0afe16cd66beb93f567f635282f610f4392f8ddaa5ee9de07b5b9b39a48ae5353b531c8870c8c1b2adf863a7d6be8b83e2de35547d0f8bcf29da9348d0b0f8d0353da3c43a8a5d346095b979555c123246deac09afdcf3ec3e1b31c14a9cd6e7e6b97b951aad9cf78a7e2cacda54f7da36b7a3dedbe0855b79824e83a7fab704b329ebb78afc8e970ce1d3f7ddcfd270d9ace92d4f09f16fc55d66f4aa5d6a33383904aaac6c7d70ca057d061b29c350f822462b38ab3f859ca5cf8d2fdce6d3557b57c00cc5c313e839fd6bd3a54ecef63c3ab8bad396e417fe21d5dbc3125d3bcb35e5a5ee2791406f36ddd7e424631c30238ec6bd2f6ad2b1c3ecd4a47311eb50dccbf6b6b242eec04a0028cc3b67f3ad652bc2f721ab743565b7d352657b3f3623292089b042b7a8dbd4565f66f705e86a4d75a84f6d0dba5c2dc5adba964465f9c31ea15ba81d78e952a5344462dea6d783be269f0a5e7917560b7f0b70d6b76c55587701d7953dc1e9915e8d0cc6b61ff0086ce0c561235d688fa2bc33a97c2cf1ee97f6ed334ef10453dbae2eb4f8a6479233dd82f0597fda19afb8c1e69ed3767c4e33073a3b445365f0aae8c8934de29b69197e5558d4853f522be8e95773d8f124e5d62670f0bfc27dbf278b35d8a4931fbb96db79cfa0c015ea46aceda22e32f74ef341f08f830fc14f16595a78bae174637f6b35cea37364dbede45fbaa5472c0e7a818af3abce4ebc5d8e17f11e6b77f0ebc0f2cfe5dbfc52d3a377e14dc59b75238e0104d7aceac925ee9d7d8d2f0afc26d16dfc51a3c96ff0011b43b89a2bc85d6111b23cc55d485553dcf4ebdebe6f89a7296555bdde876d09dab23ed5d46759246e4052c40c9c11ed5fe64e3616ad3f57f99fa4d295e288e171b4e47d335c0d686d17a90eb10bc9a0eae085286c6e339ea7f74c381dfad75e01f2d7a6fcd7e63f899f047ecf9f073c5eb2f81bc4b0e98afa245a845742749d4b88d26e58a6720f07e5c66bfd31c9aabfec885bb1f1199be4a87e9c4df12b40121324b76e7248096acc31f4c57cdcf0d5d3b2470c6bab149fe30785ad4b194df329cf3fd9b2918edd16b4782c4b5745c6b267cdbfb5a6bd69f10757f86b71e1c8351bc9349d58dcdc2c7a74aaa88590724a8c0383f95736328d58606b29f63a684ef347d3130c4ef8c104e47e35fe74e671ff699c7cd9fa75195e088d885182735e5ded646c8cad5adbedd69756e082678248941f5652a3f9d7a9809fb1c4c64fba32acb43f26f45d2eebc1badc3a75fa35aea3a65f79532c80808cb2ff22391eb5fe97f0763e8e3b2ba6e9493d0f80cc5fb37234fc7d1410f8dfc451871b05f49f316eb921bbfd6befe0a295dbd4f070d51cd1cfc6103615810dc0f98722b77ac6e77c66ee4924670a141207de19e302851f76e6b39bb0c9210ee1597e95cce9a7ef99f33207b7f29c9c75ad69c5456ac99ccea7e1843f6cf11dc5a9c8175a75e5ba900f53112bf8656b4ab2b42e7162f58c598fe0fba31eb5a2dcb9c347776cecc0703122e48af9fcf1f3e0aa2f27f91e852768dcfd1fd56dffd25890082372fba9e95fe6a663171c54fd5fe67ab257460ca443300480589da3d715c89ea723885c1fb5e9d7b67fc57104910e3f8994a83fad77606a2a189837dce98ceda1f9ada669d716313d94e8d04f6b23c2e92295c156208e7e95fe87f0b62a389cb60e2fa18552c3473059064348ca5707a1e3a57d9f9194f768f68fda0ae0ea7e1cf857ae824c779e1ff00259829c7991b2ae3f9d7958476ab2470615fbed1e34f300b82412082303aff009f4af42e7ad38916e0ac19863233b88c13ec29dcce3a0fdecd2e421283a01fd692e88dd3f78faabf600d4443f11fc4368582c73697bd5770e583f3c7e55fcc5e33e19d5c042a7f2dcfa4cb65ef9f7289b771c71d6bf884fa5639c8319c9c534247cc5ff0501d385d7c084bb058b596ab6d2021882016da79edd7f9d7ebde1ad570ce29aee72e255e933e07bbb8b68a6db3aab381d769e9dabfd09a72bc11f1725a9fab1f0a9a4d2be0b78eb5509b1ee08b6539c738da4ffe3d5f1d9a5473c65389f35815285194da3c41e33232c2c376e70bf99e95f5efdda4ddba1f394e52a959e87b67ed29702d8f84b4a1958ed34f0ce80772001ffa0d7c9e450e7af52aa3decce4e9c69c10dfd983e205ae89e26baf0f5dcde5c7ab61ad4b70be728fba4f405874f5c55710e09d6a4a7133ca716a9cdd09f53ea47c83c64107a1afc99b719f2a3ed1596e60f8c7c17a478f74a361abdb7989c98e543892263dd5bb57761715570b3bc0e5c4e19575691e1f7ffb2334772c6c7c4cbe412485b9b71b80f4254f35f5d0e2c9417bf0b9f3af204ddf98bba17ecada6d9cccfaaebb35e2332968a08420603b127b7d2b92bf1556a97f651b1b432249df98f64d0f46b1d074c834fd36d92d6ce15da9122e001fd4fbd7c4626bd5c43e79eacfa5a34634636899bf10fc79a27c2ff095f788fc4174b69a7da296c7f1cadfc28a3ab313c003d6ab0f86962e4a9d3453a896e7e72cbe25d53e2d78ef5af883acc6d14d7ede5d85a4838b7b7538551e87039f7cd7eef9360160e82a6cf88cd714aa5d22fc9233ae09381c1afa7b724394f8a96acf49fd9dfc536de16f8b5a2cd72eb15b5d97b36909c00ce30a0fd5b15f399fe1a589c23a713dbc9eb2a38851e87de463d870401f4e95f834a94a9b9463b9fa829394935b0d6211f713c0ac9c5c95d949fbc78b5f7ecb9e1ebdf89373e295bf9e286eeec5edce98b12f9724bc67e6ea1588048fd6be870f9c55a58674a9b3ceaf828d59f31ebf2c6369c0e31803d3dabe62b4e5295e47a34d722b142e6cc36e3950304e49c051ef58548a4bdc5a90f7d4fce1f8f1e3db4f8bbfb40dd5ce993a5ce87e1885b4f8248d8324b29fbec3dc1e323d2bf60e11cb2584a3f5992d59f1f9a5649346326e5650cc095efeb5faa4569767c3a7cd2b9ecffb2b657e29b4ec0148ec65656cf7240e2be038b65cb853e972776aa7d7035453b8e49faf515f804a47dd46a14ceac4dd4783f2961d0f5acb9ad1b12e7a1f09497ef3f847e22deb48649b54f17cea0f1ca4671f90c57ef7c2f17ec29aec7c566b3bb38660413938c0e76d7ea2b63e53adc8e401f0cc7a74e694773593ba227982a10a01f73458e64357704521b209ce28b1aa3ed3fd86a229e00f11ceea14c9a9041ee020c7f3afc4b8da5255a28fd1721d29b3e8e7903315efeb8afcc5b94b53eae4f4463f8e6efec7e02f12cc4e3cbd32e5f3e988dab5c3abd68af3256ccfcabf87a08f85f34aa76b18635cede80b13c7ae6bfa732d8274a2bc8fcd734fe332a4921fe23966cd7bbd4f9f6892df0e4003181c9f7a9abb1d5875767a2f86d8af86ec9547f09393d47cc7f3afe3af111a963e5a9fd5fc110e4cba372775ca81ce7ad7e38eda6a7ea909599e6df1961655f09c5f3367520e0678e14d7ec3e1d466f1eefd0f88e34a96caa685b702472477e4d7f5a46a349499fc7f357932e5bebf61e1f9e2bbbe9e38ada370ccceeaa011d8935f3b9ad78cb0f384b767bd92414711095b4b987e2bfda82347787c396b1ce598aa5d48aee01c75da063afa9afe5eff0056feb1889ceaed73fa5ea6754618751a7bd8f3ad4fe2378abe22e6df52d6a4ba8e30cf159246912348075507f8b1c0e6bea30580c3e05fee8f8cc7639e2158c3f096a72cfafdadcdd585ddba43771b22dd32ef6e79ff00747bfbd7bf56afb58d99e1d3a7adcd2d5a5b9bb697cab76c866658d177ec5ddd88f73ce05798a99d8a7639dbff0008eab35b3dc496cd0a3301b9d7183e9cf5fc2b551b14e7717fb12e6daca2b3bdb48a784ae63f3a1dbf377c48b824fb13c7a538a3245ad174a8bc3d7724ad1ce6396268e5b759cba3a11fed02060e0e7ad5b8968ce8fc1c67ba12452a84989dc85816438e0e38a9b99bdc8ee6dae74d8a37b4dcb1b7ca7ce6077377c7a555c53d11a7e1cb6d71678aee21a3410e0aecd5af9218e6f5519391ec7d6a93251b92da6b36cc8f77a7c1042e3e401967b63eeb32fae7b9e05680c8ee7c3771a15c5a5e69a6e745d6636df0dd5a5cab21e70cab8f5e873c62ba2156db18ce9f3ee763e1cf1d8d5ee3ec9aac8f6d7f1821848bf31edbb8ea33debe870599ba4eccf1f1397c65a9b4b219090ac33ec3a0afd0f0f89855ea7cae230aa3b1e8de19fde7c08f88b13b932fdaec9d7071d3ff00ad4aae9560787569ca2f43c7b5484864ce0a86c2b118cd7bd0941c8ed8d3f74bde1890dbf89f4400001b51b4dd919183325783c43cb2cbaa5bb32a8d3f7d1fa086e5eeafeea4e91b48c131cfca0d7f9919a26b1753d59f7f49fba4cb290a7033b5b91debcc69f2a378ceccb5732b4fa45dafde2f6d32e36f5ca3715ae162fdbd34fbafccea4f43f2c7c25ac5ec3a3c1e45fddc5b1e455f2ae5d029dc73800d7fa7fc2c94b2aa6a4ba23e1b3457ac7a0f883c65aadbe8fe1192db58bf89ee74b7694a5d382d224ec85b39ce7181f857d22a117525a1f374e0f9e48a117c51f16d9f16fe29d5d7e5c645d3671f9d6f0c3c7b1df083e548faf3f646f075e7c66f87da8eb5ad78b7c4cd7b6da8b5aaf917d852a154e3054f2093cd7e7d9fce5424e9c56e76d1bc4fa564d20687f67d3c4f3dc34512aac972774b228182cc7b9f538afe12e3ae1aab97e31e2e0bdc9796c7e8797d78ba6a2d90ca768c106bf23718f567ad7d6e63df487691cfb678aea83bab416a6755a92394d5fc3da46a924d3dee95677570f82d3490297c8ff6b19afd1b87f8bf1f90cd7b19be5ec7cae3b0caad370395bff04785ef9d96ebc39a75d6ee4efb6562c7d4f1c9afd5d78ab8bd27c9f89f334f09ecfdc31ae7e14f821dc2bf83f4d453d0a44108cfa63a57747c57c53972d8b54dc199d77f00be1c6a04097c21017272ce8ec0e7f3adbfe22ee2e12e5e53ab91c900fd987e1bcac4ff0062dc439fee4ec42f1dbb563ff118b1919d9c3f1378506ddcf15f8afe09f83bf0c3c60ba06a3a6788ee2e8dac77caf6979f22ab960061bafdd3c57eebc1bc535b88f0f2aaddac79f8883a73b993e0bbbf841178bf4b3a73f8aadaf6490c31c4eaae8ece0aff00535fa84d54e43cfc426e064cba1fc1b99e7893c47e22b0f2d9a225ad03edc12a429ef8c7ff005eb9a74e752938bea6f4a5eec4fab7e1b78f7c37e3af0f8b7d0b557d4e4d3628a19de685a391805c2b956e4e71d7919afe13e3de19c4e5598d4aed7eedf53ddc3d4f76ccd1beb2dcad2124951f282307e95f9442c9a5b9128dd94f6b29040e7d6b74e1257d990796fc4afd9eb44f8837ada8c5732e85abb03ba78103c53138cb4887ab7fb4083f5afd4f8778f31bc3f15453bc49dce023fd8d75012e53c556e50b10aed68460e3bfcd8afd3e1e30c232bb81a28731e85e27fd9a6f7c4bf0bbc27e195d72de2bbd0a49d96e9adc949524fe10091b7afbd71ffc45c842bfb5e4230f85fdedce1e4fd87f5d9b6c69e29d3c024024da3e41f73bba57a34bc61a356b4632a5bf99ec7b1b23c9e4f821e36be698d8787aeb52b5b6964b71736ac8c8ed1b6d62b96c9e9f5e6bfa0b2dcca9e634238b8eccf2aa7baca72fc14f8810e04be0fd5e30a0e17c8c823ea0fe35ecc669c5a4cca33927ca7b87ec69e12f107853e3540753d1750d3edae34eb889a5bab7645ddc32804f5e86bf01f16a129e4eda67b3975593abca7dd484a90401cfaf6afe0a92d4fb84c787ce7383c74f43523bb3c27f6d3b0fb7fece1e283b371b7682e33d80122924fb57e95c095552ce69338f10f969b3f37d225b98d242b236e50410c70457fa4382a89e1e2cf82ab53df67ecccde06f0ee87f0622d0dfc591c3a6df4e665d5a48c01364eec000e3f1f6afcbe75aad7c5f3286c70aa31861adcc79cdafc27f01d9dcdadd4bf11ed4a4532c857628df839dbc9ef5f46f178e9c5c2303cb8e0a9d27cfcc607ed11e2dd3fc4fe3d79b4cba8ef2ca0b58e259a23952dc93835df92e16585a4d496ace3cd7131ab28c21d0f2af3645706195a17eab2c6c55d08e43291d08afa19d152a7cb23e72954942b29b3de7e1a7ed776da4c10691f11d9ad0a2aa43e238d0b4130ede705c98dba7cdd09f4afccb34c81c26e7873f42c063d6215a5b9f42e95e2cd1bc436eb71a4eaf63a942c010f6b748e307d81e2be0eb50af09f2c933e835ebb1766908c7ca79e8720d714a328f461177ea64eb1e24d2741b792e354d56c6ca1419669ee15303f134469d6a8eca17094ac780fc4afdbcbe1ef83f7d9786fcef19eb2415486c7fd40603f89fd3a74cd7d2e0b86f195da94f489c73c653a3bb3e5bf1af88fc63f1dbc4d0eb9e36ba10e9f01dd65a2439586207b95fef638c9e7bf15fa8e5b93d0c0c796daf73e7b1b9b53b5a26a47b82a428816255c22e005551d857d5d3a5a731f0b52b3a8c491c302a58723071d6b5dcc65b15d220ae58160548395621948e841ec7dea26b9a367d45466e9c93ea7d15f0b3f6cab6f0e5a43a47c4332c76f0e22b7f10c69bd597a013a8e430e06e0307a915f97e6bc3956a4bda61fa9fa565d9946a41465b9f49e85f103c2de2eb24bbd1bc43a6ea16b20043c1748dfa6723f1af81ad85c4506e3389f42a4a51ba345afed225cfdae055033969940fe75e5c28cda764fee139351b9c778d3e38f80be1f5bbcbaff8b74bb2d9ff002cfed21e4638e8154939af528e5b8ac46908dc5ed533e37f8f9fb696a7f166cef3c29f0cede7d3b4cb8cc379addc0d92ba138db181f75587f1139c76afb8cab866716a58947998bc62a48f21f0d78760f0de9a967002db4ee9253cb3b1eac4f7afd3e8508d38da2b447e7b98621d4958d72c50e40e48af42f78d91e745595cf61fd9a6e6dacbc4babdddcdc4369e5d9ac6ad34aa81896e7049f6afcef8c22de1ed147d0657512a87d032f89f4df2d81d52c8e79c7da5791f9d7e152c3d4e5f85fdc7d5aac4316bfa70fde7f6958ae016cfdaa351c0cf5dd4a961a72a8972bfb88756e7c6d6f307f8476372ae33a86b7a85df1c82ad210083dc715fd0d9151f654d23e4f32776734ec49c91dabeea3b1e02d88b871f77e5079f4a5d4a86acad35be64c01b50b753d2ac86ac031bf006429fc2815ec7da7fb206b7a5e91f0ae7177a9d95acd71a84afe5dc5c2a3e0600f94907a57e25c5f87ab5710ac8fd132595a933dc078bf4418275ed2ce4e062ee3fd7e6afce5612b287c2cfa4e7ba4721f1b3c77a3d97c1bf1bcd06ada7cb30d22e36aa5ec64b12840030d9efd2bb301839cb131bc5932a964cfcddf072341f0d6388e54b7928c4743f2e7fad7f4b6169fb3515e47e6599d46eb3236886140033eb9af4ba9c16d092dd4a48411c115157e13bb0cb53d47437b48745b28cde5aabac786479d55873dc120d7f2271de0b13531d26a0d9fd45c2989843011572c34d672b9517768187fd3741ff00b357e49fd9d8b725fbb7f71fa2471705d4f34f8c2d14da9782e18ae2de631ea3234a91caaec8be5f7da4e01ec6bf62e02c0e230d899549ab1f05c658b8d5c14a116637896fedb45d3de6dca64246107ccd9f602bf7cc5662b0f4f959fcf387c0bab33c7fc41757d05d34a6d18dc336e0b70c199173c707815f0d8ac73aaeecfb5c361a34941450ef0f5bc5e2885b4e30df2ead3ca65f3ec995a3da3962cb81b578f998b63d2be6ebb73d11ef465ecf5668c1e1ed3b4d73fda0c2fa555dcf67652b04527b348003db24003eb5c6a9462f43293464ea5ae69f602e2594c5633003c882052e83f0249fcebb34b6a38bb9c72f8faf5903a5e4855492a6242a0f3f5fe543d06e24579f11e7b98763cf73349fc3e67cc33f9d66d94a2227c43bc65588b3c51024a6e62ca8ddfa9c034e2245f87c7f7570a66b3bc48272bb4b6dc023d476eb5a32d178f8b6eef6041710405d4006e2350ace3d09ef59d8ca447752fdbadc06656881c818e4376c8aae5339ec625de9265c2a3a9504b1864232e7a9db9a1ab148b1a55ddf78443dcd8cac96ee0acf6bb8aa303fc2cbd33ef51cc0ced3c0b25f6b2b7173a75b36e585ee9ac4481c8552376d527248ce703b64d5fc28ddaec11f8a975e30595ea35d46ac423c48b15c403391f301f3283cf3d47ad14ea6a65385cdbb0f16be917c627945c5a9c85ba5f98373d0e402a7fc0d7d0e03309c24aecf2b118684b63d5fc19f1034c4f861e38d0a6b9c6a5acb40d6916cf95d63fbc7776eb8fcabe93fb4d39c5b3e4f13974dbbc4e5ae6c269add98c64c6002481903f115f4986cda8d57646ff54b44768a211ac69124cca96d1de412bcad9c2a0914b31c7a004d659a2788c054517ba3929d17191f5ecdfb4afc2e5b99e283c6fa780ac4143b801cf6e39afe0dcd784f35962a6d52ea7d453bb8dd1522fda57e17ba873e36d3013d42c878c1e8722bce5c239bb4bf72cc5d4b32e1fda53e191b69a24f1be941da365f9a56cae54807a7f2aebc1f06e6af114e73a6d2b9d0abdd1f9ffe188c4fa42b2711acd28561fc5f313bbf1c835fe8370ed1747054a94dd9d8f9bc6be6ac76fe22b5487c1be0f94e43edbb841f51e687fea7f3afa4a7fc491e1434af2472ee003c8fcabae28f429fc563a5f0c7c4ef14f832cdecb42d7eff0049b3958cad0da4db159c8c16c7ae2b86ae169d577ad1bd8da33527a1b3ff000bf3e211b8b4b85f196acf3da48258bce9b7a6e1d994f0ca7b8af9dcdb86f039ad170ad49599b50c5ca9d4b23e8bf873fb796857d6b0da7c43b293c3da82fcadaa5a2efb398fa951f347f4e6bf8ff8b7c20c561a4ebe59ef2ec7d861f325551eb56df1cbe1f7886dbcfb0f19691710100fcd7211c7a64360d7e335784b39c2bbd5a46b3c45d95eebe20f84a753b3c4fa449ec2f9571fad690e19cda5efaa7a1c92a97776674be37f099241f1269618738fb6231fcf35dffeace6ca3cd2a5a1c0e379f310378bfc392e1d3c45a5a2af466bb403f9d652e1fcc210e7f66ec652b365bb6f16688d2868f5ed2dd17f8fed51e0e7db3cd733c9b1fcbcce9bb1a6913a0d3bc45a3a2393ade9db5b041fb5c7c7fe3d5c32ca71b55fb94d9e845c52b9f127edb7756927c74d2ae2d2ee0ba1268312b7d9e5590021db6e4a93b4e0e706bfadfc24c1d7c351952af1b5cf3f1d18b85cf25f0e5d9b2d6b4cbb918e61bb89890d838dc0139edc135fd35513e43c3a90bc6458f164034cf18ebb60a8516d6fa64e7a91bb20fe20839ace09b8938685e027863c63abf81b5eb6d6b43bd3637f09c16e4acb1e7e68dc77535f379ee4585ce70d2c3d78defd4ed87bacfabbe1ff00ed55e14f1bc4b69af15f0c6b61b69499b304bfed2c9d307d0f22bf8f788bc33c6e5d394f09efc0eb72d0f5682eec353b712d85f5a5dc4dd24827561f8106bf28a99563294bd955a6ee8c245a3665903314c0e843023f9d62b07886edecdfdc544b304117cbb8e7904631c9acff00b3f10ffe5db37a6fde362dd59e50c50104f1c8e73e95c957095fe1e467a14da8cee6bc11b2bc39898467ef1c7507a567470d57da452833d056713e31f87ffb337c48f883ad78ceff0042f13c1a669f69aedd5ba5bb5e4a8c1f76e24aa8c63e6fe75fdd7c258efa9653479d743e7712bde23f8bdf06fe2bfc06f0b2788b59f1f4f3d9cf7496ab059ddca591d95886f9b8dbf291f8d7dfe1333a75e7ca91cf0a116f983f64df887e35f14fc69d32c2e75bbcd474e8a196e2ee09a62ea10210ac777a31ed5f9ff89581962722a928a3ab0908d3abcc7de1f36e042b1047190715fe7bca84d49a7167dc46be84ad13b29c23301e82b37427fcafee34f6e8f34fda3ec9ef7e01f8f2268fe61a6bca378257e5c373f957daf0950a91cce94acf730ab2f69067c23a3fed2daebe9365bf40f0d4b885155bec206405007e82bfd1fc0c1fd5e1e87c3d587becfbf7e39dd0d23c09e04d10300f0d9f9ce8ad9c12a00fd735f379561dbc4ce52478798558ba51499e1bf6b91b6a024f1c738c57d77b2516ac7ca4ab495d5cab2b10003d8e6ba12b1c9290c0e1b9c647a56a898ea4d24b13208de25313021a3650ca463d0d64e9a675a9ce93bc59c65d7c3ad35c996ca7bbd1672c496d32768431f5214e33593c3c1ef147a71ce3154d593193784358931e478e7c46a7a6d7be9318f41f3715c4f2fc3dfe03559e558233efbe11e9979785b56d4757d58e7256e6f19d49f7073ebd2b5860e847ec11fdb756a337f46f0968fe1e4516165140edf333e32d9cfafe02bba14ecb438ead7725b979c12ed83cd6918d99e3395d8d52558a920923b76aea62846d221768c922252a57ae4e684826fde18ae1194f2c47af4a966506320092b3095015230772e6a1a3aa9d4717a1cfea7f0e347bc94cc89358cd9dc1ed27688e7e838fd2b29d084b748f5e19a578752a1f84f617b6129b9d635a90a150a7edac001e879e6b91e129ff002afb8d9e6b5e5d4822f851a05a849a4b6374eadc19e52ff8e0f5ade14631d90e599568c6d067436767058c6238235854764502baf94f2156ab5e57a8cb058a29008c139e6ad1cb377630b031cce7860b903d4d5a06b433b5cd2ad7c51a5bd85e87581800c63628fc1ce411c8ae6ab4a33dd5ce8a753d9bd0e497e0ff00876090c81af5d88da375cb7ca3d05727d4e9bfb2bee3d558e7145297e0ff00876357644be90a82424974ccbd3a6293cba845b959197f6a36ec7b4f8cf48b5f0af83fc1ba158a186d6dec04e118f219d893fd696029c2337638b1155d65738c39f2b3b4f26bdb479f4fdd4445b1bc03c0e9430868ee4134cee5413c0e9561296a24784049fe23cd5589665ebfe17d33c58d00be80c9e402576c85304f5e95c5568467ba3dac362aa51311fe1668018810cfb71828d72c735847074dfd95f71e94b39ab1d0627c27f0ec58fdd4c003908d3311f88cf3f434a582a7177490e79a54a88f4416b0d9781e28a2042b5d0525883caafb7d6baa92e53c2af52551dce7d1cbf240e0d763222c910f5200273818ac99ba661788bc0ba4789aee3bcd420696e634d8a15caa11ee3b9af26be0e86225cd381efe1738c46074833360f82de1ac869609b81850656e3e9cd7155c0e162aca9afb8ee8e7f8f9add9717c0ba378601b8b2478dcfde9256cf02be6b16a147e1563daa38dab8a5691c878dfc4b0716d6b7263718dc64c23ef1c95553c9e30735f2d8badce8f670b4adad8e3d64952e2101e498001b3364e49ea1b272457cece5767a30563ad8bc4969e1df0fb59d81f22eae9b75ccec368940e881bef3283ce3a6454c9dd1d17b9c86b3acdddec0d2477250b70f2c4db030039000aca51d42da1c24938bfbd8a2b1135f3a0fdf44a84807dcf6ad9434338bb31f69e18d474bbd496f7caf20648825e5594f55c0391568d652b9b96d69e14b68bed32d8ce1cf0d144ecd18c7718e7f3aa68cc2d75fd156494c16e00663b56e626207a0dc072df5aa60b422bbba86fd15eeb493e431f95da306373dc03fe3cd4ad0a6ccfbcd4a28b222d3883b4ee2aec5481d30a7818f6a9644f563f4fd4a2bd9bc88276809018f9a0ed07d4e0714d3b03d51d069c66b8567bf48b746d856460ead81f7b90194fe151291762d462092491e10721b0f0c8080df4ac1b0b16ec2c750d12e23d63479a6d3f52b26335b3c6bbc9f5565fe2047047714e6ce882e64686b9712dfcfaa7885bc391c5610b46f7f1d8b80d62cc389942ff00cb32d9e08214939a982b99bf759945a2d4b4bb7961bc5b8b852ccb33a60306392081c71eb57095998495c7e957fb26092c923c71866088df286c7f09ec09ed5daab09534cddd1f599decc8b896e238633bd3f7cd9703b119f9883ebc5744711386c73d4a3747a0f813c57a5f89b4d916f34a4b3d4edd5bcd30cac12451d194312338f4ea6bb2199558ab1e7cb0d767a8eabff083786be12b47a6e9b65a96bfa8e552e2f6dd1e4524e1a4dca30a140381ebdaba708fdbe2539a3e961ecb0582718eecf0d4f08e94ca4ad8c0a724e42633f5afd2a950a2acd457dc7e7dcf3737d88ae7c29a50b9565b1863e3850b9c7b8279aed8d1a37f857dc44aa728a2d16d02c717fab19c0031835e9d3705b1c139f3b3a0d5916efe1ce8170c1f7db6a5756fd72002aac3f31fc85383fdeb3c9db1272332e49c63038aee4cf5376419cede093dbd69dc6b443564da4920d2f2334acee24db2e2d6647c60a93f30e2bcfc442d1699d7465667e947857f637f829af785341bb9fc1d62d3dd69f6f2bba5cb0de4c6a4b7deea4935f8de3e9f3577eefe07b11ad645d7fd82be05994b8f07c69dbf777ae09ffc7eb8bd94d59a87e04baf791e4ffb507ec79f0a3e1ffc10f11ebfa17869ad751b31118e7171236d0d22a9e0b107835ebe0a84711594671fc098564e763e013e17d38499f25530d90dce41f5afd029e53848c5af6689721efe18b26653e51639c063918a8fec5c0bb7353469162ffc225685be44700f53b9bf5e6b1fec6c0a6f96923a954b0b06856da74e5e240a580dc492738e9d79af4b0581861be05638ab54b97c4cb11df90a1486c919e8735ec3ea71cd72c4eabe27c60fc48d72451859e48e719feebc48c0d72c36661847781cab3db0972f2ab2f418e694a71e4b49ea7a56239a04ba731b80f1e4fcacb8047e3d6b9aafb2aaad2b01e8bf070fc2cd363d56dfc7116bf1dcc8f19b03a1cdb115029f337738049231c57ca63722a55d5e8a4dfc872d0ef85ffecfd0c81535af1f46adf316fb4b1031eb8af0e1c29517bd282bfa208cac4af73f00fcd629e2ff008836bb97ef24c486f419cf153feac55d13a6adf20e6f78f3df86be18bdf1e4fe396b7f177886d8e97a7497da6c0b7cfba70acdb4382d91f2edce3bd7454e19c152b4e54d5cd6a56e547116be3bf15a5ac44f8a7587ca8600ea1212bf4e79ab5c23964a4aa2a291d6abfba7b17c11fdaebc4bf053c3b79a45969765aaa5dddb5d49717f2b894bb0e7247de1c67939afa28e4b879c1538e891e7cea36c5f8e9fb5b7883e38f8322f0d6a7a3e996900bd8ee4cf6ace5d76e7e501b8e73c9a7432aa786a9eda05a91c97c01f8a307c17f1dc9e239f4d9355536b25a0b786408c77746dc7b03dabab1f97d3cc297d5ea6ccd148bb77f19e5bfbdb99e2f11f8cad5e476716ff00da08aabb8e70bc1c0f4c9afce27c03962a97a904ceb9632a459abac7c44b9d0ecb48bc93c67e3675d46d8cf105ba8cec01b695dc472c08aaff0050f2c72b4292465431b395439ad1f52f177c61f1c69fe0cb4f1deb96ba56bb32d9b36a32f9876b6770755c641c631f9d6b1e0ecbf05fbe853d51ea2afeed8f7f8bfe0995aa431ac7ff0009e5b7c836e7ec9b73ef8cd774739fabaf6518e88f2a6ef2b9aaf717178e3cc965942af02572c1547a67a0afb6e48df9a27e5eebca7ca9b2095b706182323b56b18b5b9cf5249546885d82a85254e4703764d6860d8c565e011c8efe95499a4049d712a8ce0919c13da9f299c9abee465c00c0900019e80d57294e564022496584b3103701d718146e6539dd04e4a4ae5981ce4a9aae51d1958a8d2ee504f52715705a1b277444d2618a641607ef76150f4672bf8873f72c00c632477aa52b9d4be221e1e7c82ab9182d8ebf5ad2e6135ef1036d32e090028a673c448d8a4df3a0271c73d3df14b42e2f51e00e49e9f5a968d134cd095c43a3c518e55a66393d7007f89acec689a4624cdb9baf7add242724b6633049e7193e954c969eed88c78e4719a844cb715d884c2e3af5157d0dd6c4522a96570dcf422a126c89ae5647e68c6186e61eb5aa896a6ac32288497f1c4ca409582919f5e2b2ab14a9b660ad2677bf1a401e28b3b50c0fd92c6284807bedcd79f81e5b367772da279c4e180001cf39af591c12d08599429c81ef561b15e652b8c0041f4ad1186ec1900c0041f5fad51d3cba08ac50b6d20103bd66d1ab9c90d525be76069a1c5f30e43b88c803b1a89c59aaba34afa458bc2d6615777997729ce7d0007f2a88e861372b98bb00538070c47d6b572358a2c6d25481185c607cbfceb27236b1623872a85cf04f5ae6a952cec8a8454d290cbfd4ed34cb2b89a59e355894b1dc78000cd7898dc5ac32bb67b587a32a925189e29acebfacf8e6ca1d42011691a2a83fbe958b4b70738185fe15c74f5ea4d7e658ccc1d49687e8385c3429239bd174bb7b5d647d9208de5894cd753dc20918a10402f231f954f5fc2bcb94dc95cf6295994ef7c4365617335b69856fa65e16e5d5995867aa963fceb877427a31b6f6977aa03737a5a5b8da5447712eff002c75040e9efb46053a6aecd63a8f5d160de0dccf2cd26df9a28b2911f4e7b9f6a72dcb6f43406ab7ad6d3416b6b05adbe028718404fa8039aa72b2305b9cb5eeab0c32c90c931bdb8556ca40bbf6fb67b53894671d6e4b7b7c48c967bbe5059959beb8ed5b58ce52b1cfea5e2c36d3bc71bb4d260ef6931827d7fcfad68a2652a84563e32920721a18c47d1826ef98fae09c66a5c4d612b9bb0eb965ab224704a2de63f29330e1bdbdb351ca6b6ba3af4b4b048633185132a02590ede71cf3594b4223ab2cc57fa62c718b8898b8607cc041e9ebf95606d7257d6ec639dde30187f09f7f534ac45c9adfc41f658e59e191679070503633cf6a968e983b235bc39e365b1d616f5235b5ba785a0905c47949e2618689c7756f4fa1a9bd84d7332acbe1616cab35906580965410bee54c9ced1d7381d3b8029a46763434dd122b454bb54679d14298d63046718dc41e0fa9a6cad8e43565d663d42ce096edae523914cb1c0143316ef8e98f6155cf20dcdeb49af74bd47c87b6956193216e637c153fdd65ec3dc56d19b21c51b5a26ad1fdbaeec1fe578d959b6fa373b8faf43f9574d0c44e9cb98c671e68bb9db457104b6ccd0cb1caaa76ac9167691ea73c8afb3c367b1a69299e0cf0b2bf2c4a73c6272a8ac198775f7afa3a398c26f73cfad87d0aeb6e64e013915f434abc248f39d2e566adddb98be1d46818b91ae92d81c00d0647ea2baa0ff7a78d5236c49ca4f1153db9e6bb933af9b52ac919232070382715699b4b4640c84679c1ec715a2092d0368c30233c73f4acddb98a868697fc257ac4702470eb3a842a802aac574e8154700000f4f6ae4f61194eed1a39b449ff0009b78850a8fedfd50007e544bb75c7e20e6b474a9455b94ce337b9b7e24f11ea7ac7c3bf09dcdceaba8dea4f35e5b4e26b9764654652a1949c360f4273dab9561d42b73411cf4aabf6b63dfbf62ffd9ffc11f19fc2be29bbf15e9af7d77657d1c1048b3b2058cc7b8ae077c8ce6be633cc6e270d24a91ea5cfa04fec17f08c8f2c68d7e898ed7ee40fccd7cb2cd71924f98de2c8dff607f84c8db934cd4b0aa587fa737271ee4d5d3ccf16e4b999a5cfcd0d5ec4697adeafa71ca25a5ecf6c8646cb6d59195727b90063f0afd2f0d539a09c998495ccd60195d17123152319ebc5775e3aea5b5cd13b4f89799bc436d76cfb5af344b29171d49111527f403f0ae556b348e5c3c79607e8a7c31f1bfc22ff008569e118afb53f0a437cba55bc72c570b12c8ae10060c31d73d7debf35cc3078df6ef913b7a9db2674ade28f834c00fed1f073161c63c9c7d3a57952c263e6ac9322323c4bf6c57f86977f0275a1e1a9bc3a75949ede58174e318989120dc176f27e5c9af5f2ca58c8625735ec5c8fcfc9588f30b48dc83c6707f0f7afd26d2e64e466915d669235cef18520609f98ff008d6bac63608eacf61fd95aeb1f17a2d39c2f93ab6957964ca4756285973ed915e56368a5479d6e618bd0f229ed16cda78b71f3a0b892dda3c7756218e73d322ba295e714d1d719fba44c4e300808b8f94f53f9d74c64e3b12b5659fb40192140e406ac269357894c0189a6232719c80c48c7d2a6c9bd4da0879b8488320c0233b4b7201faf7a9f67aea825cd26751aa4eba87c2ef0fcb96925b1d46ead01ce4056549147e65ab154db9e871d394a350caf0878b2efc09e2dd2fc47a7a84bad32e56e615946f56719dbb8771ed4abd355e2e277ca6d33e8a5ff0082847c4c232d068ec4f3968307ff0042af979e514652bb291d886e132581f5ed5f5163f264ec366993cf508cd20e7702318fa7ad68909cae577b886193639f9fa9c29c815cee74d46d2913cada1448bc9041504e18f048ac157839da322e9c5a2092e54ca59dc02005e5874ec2ba955515ba3370bb19ba3de0b483a67ad38d54faa3574f4258944b21c31c052db94f403bfeb51ed60fed1cd35ee951a4dc32ee3818fbd57f58847ed154f488d1751c7b7322673ea285888772ada8d3324accc658ddb3fc247cb43ad07d47385c24963dcb99793b548ce40e0e4ff2ace35e0baa3af95344524d12cbb55c36456ff58a7dd18ba65392e2269583481547534e55e92fb48c143424fb546f39d9228ea1727058528d7a72fb48950d47298e48b7b3a293f7496c7eb512c453b7c48eaa74d3468dedca0b1b280ba9214b170091924f0715847134eff1a08d357315e654754054eecfe5ed5bac5d05f6d10e9ab913dc28e49da3b678a6f1b41fdb4138d869b817080a02cbeaa09ad1e2a8ff003227946bdc10047920b70b9539a163282fb487ca891a4da0121829e73b4e0fe9597d730f1fb68b941320f3a22bbc3120b6070735b471b877f6d094122f786edd350f1269902392f25d46a01520b7cc09fe5586271d45516a32442824cdff008b1a84175e3ad5d9650f1c6e065413b4aae08e3a722b83018ba2a0d4a48ed6d72d8e0cdd46cca8eec8edc01b5b93f957aab1b8787da5f79c9c8ae248ab12b177e01fe25383ed5a7d7b0f2fb4bef2e74958aaf70ac32aa4a7538526b458da1fcebef308d2d44721570809cf3c2938ab58ca1fcebef3a271561118b2ee20853ea879fa5672cc30dfccbef0e456143aac9e53960ffee9fd6b3fafe1ff00997de5538ab8159436070c1b69e0f143c761daf8d1ab8a668ea2f1c5e1bd201254289e46054e725f19fd2a218dc3dfe3464e95ca30cf6f222e1837a8e6896368ff003afbcd5532c42b1296884aa5c9e133c8ae778da3fcebef3454c962646904658055ea7b57256c6d16aea48de8537cdca715e23bc6bcbf890b476d1c1b99cb300a3b0c8efeb5f9ce6b8df6aec7e8996e17d94549a3cc7c47e2a9f51ba9ac6ca448e32c15ee913680a38661e8307b75af9972b9eeca366731e20d521bd834ef0f69a277b359001676837497730cfef2663c9f50a4ed515715a1bc5d914a0d2adb4fd445926dbed454079521e608d41e4b49c038e981dea631b23393bb2feb7add868c91c16ef14b298c34d74189c1393b7776c74c0e98a22accd52d0d1d2e26bcb5173231b3b3daa10cca0b4b9c12ca01cf4e99c66b9deac252b993aeabc9042b0ca61b101b0f330f324c7a0cf4ad611213392fb70689edec9f00921a3822deee7ebdbf5adf9425ab316e255b5427ec0d300d96599d864fad68918d477464dd6af34aa00b389597ef1d99fa56ba33964db644d7acc4930c4848e42a607e5daa4ec83690d7973b3e5007038e3352cddc8ddd375f8acd4abb48eaf8dc84e578f4ac1c5b26e6b4f7e0c430eb2c6d8227898e013d170791d6b0e51f3902cd7b6487cd75b88c8f94a1276fd47ad6890b72f696979a8791159895ae1dc6c5dbb463d73584d6a6d73af8a1b9b7dd0dec4b75195dde70932e1b3cf1d68e5ba1a9d87aea08368b437114eac7122642e3d0a9e3f4a7cb63553d0e934af122cd2adbeab88c050ad2c0a57701ebdb3f88a868c93bb2d6a3e12b4d45df51d1654d6096f2c4509097248e4fc878e3fdecd4289499045792dade7957ac50291b926c82a475ce6b64ec26ceae4d7f4f8e2f302594acf0ac6d2c7f7f6a9c853d8e3248aa8cac66918cbae69c9024305d7910a3b308f2514b31dc46d1d7249f6ad3da58ca71248f53952591d5a5496270c1612363a9ce320f4e7b575d3c4ce2cc6741345d83c5023564b982582418dc7665003fc591fcabe8a8e71529248f36783b9d8a4e27f00eab6b18632dbdf5bdc8623e570c194f3ea073f8d7dbe0b30857573e331b8594313191c94b03b062a0951d4e2be8a15e32d0949dd14a55290b6780704835d90a86d12af96652b8dded815d09a636c64cae338182df29c77ac9cb956e5f2a22c141c8cf6e95109733dcca487843b811fdd07e95acac351d0debd9fcdf85da3a0e3ecbaf5c4431dc49086fcb2b59ab338f9796b19da4f89b58d0432695abea1a64723892416570d18760300b0079c0f5ac6a46337667ad091b47e29f8c55b27c5bae127ee837f20007a0c1ac9e1e1b591b3648bf177c6ca309e30d763039c2dfbf3f4e6b3a587a4e4ee8b8cb43ed6fd96be087c30f1e7c0bf0eeb9afe81a56a3ad5d99deeeeaf26fdecafe6b02cd939dc715f9fe6d89c451c438537a096acf5093f65bf834ce08f08e8fcae0959003f4077578ef198c9453e666b7ba38df8dff00b387c33d33e12f8ab56d33c31636faae9fa3ced6372262cf0ec52cbb72c7a1af4b0998621e222a4c9a4a373f331e7320899d4312aad82bdf1d6bf50f8a0b433a92b893388c6f78d70fd723238f6a495a36b1111c67768c280360e460743eb5cf3514f43472b95981719254e1b3b88ce6b48c9d8a88b8390cc032e7278e73dab44eeec49dd7c0dd4ffb17e31783b5177d8a3518e125b9f95f2841fae45726295e8b42c5ae6a6657c55d28681f13bc5f63b4a88355b80a481d19b776ff007ab3c1cdce87293427ee1c9a82f924f4eb9aec9fbb13ab789326dc9500286f40300f6e2b040b616175c80fc30ebef49f3772a0ecc6cc18c8063031ce0d5ab1d0f54ceab4765bcf86fe23b41932d9df5bde01b4900303196c8e83a669b48f1e7270ac8e5a503ca60ff7b217eef07deb0566b63beed8d33990e7722fb0ac1a5d8d95cfb44c8172091c7ad6c91f9349d8ad24c1554a1cb039e2ade88c79aecf53f066948de1ab079ada391a75672644dcdcb1c57e13c4b8ea91c5b8c247b54a97323617c3f01c14b4806d3ff3cc1c1f5af8d9e6f8cb5b98eb8e1c626870091c8b7b62c786db129fcf8a239a62e4be36732c3ea325d02de552bf6680bf201112838fcaa7fb531707f1b359d0d0cab8f0e45b4b1b558f1f2ee48c0cfd7da896758d8fda38e5867ca575b03688cb15a41823fe78a83fca859ce365f6898e1da893c3731424092cad8023763c95cfe3c5279be357db33749a6593aad92b2b7d8accf19c8b751fd2859c637f9cb74d93a7886d42806c2dc8cf68171fcbad47f69e27f9d9718b63a6d76d3723ad9c4b9e72214e9f955accf13fcecb74c78f11daa9da96569b89c8536eb8faf4acff00b4f152fb6c8f65a111d7ed4c84bdadb65b81fe8ebc1edda9ff006962a3ff002f19cfecddc7ff00c2436e9b90dac09fdd0d0aa8cfb0c56af1d89b7c6ceaa50d070f16c11a71696fe528f98885703f4ae278fc4a7f1322cd311bc4902a826d62dcc3e5568149fe5d694b1f5ff998941b63a4f12c71f0d6d09c0c902153cfe55a431b5dfda61529bb0e83c4e876916d0003fbb0a83fcab6faf57fe67f78469892f8b636524db42be87ca0013efc7e3512c657fe67f7912a761ede322a1a36861c01905631f30fcab258bc44bedb33576249e27448f70821d9819ff475e3f4a1e33110fb6cd144963f1488b63086146eaae2150723b82071f5a958faea369498d53d08478b048cede4aef504b110a92c7eb8e734febb5af68c98420db1478b8c8d9102160bc930af5fcaa258baf2fb4fef2b93519ff097c4c195a18cb7524c0a727d8e3151f5cc447ed3fbc55134817c5d1c64ff00a342e063e7302e79fc3154f155d7db7f798415d124be2640d8fb34041c6310ae08fca88e32bff3bfbca49b633fe12d8dd5b6429fbbe3688547e5c54cb1388fe67f79696822f8c911dcf948140c3661519f7e9d6aa188c43fb4fef1453b931f14445001696e1739dcd1292dfa53faee212f8d9d1c8c7a78b21946d10c4001ff003c1700fe558fd7f109fc6c9d8917c4f019111ade239049fdca8dbfa7152f195bf9dfde68ee3ffe1218a589c3c56e90823868949619f5c5632c5d65f6dfde5c2e7987c70f8cbe1fd0f426b336d15d5c5ca32469f676880c82320e0671c7d6bdacbebd7adab933e8b03414e699f0c78bbc4c3ec1e516324bb3cb4da72adc7561debe9253be87e8b4e2a3048e1ed52fa6b17b2594a0b8fdede5c4a42aed1d15463a77233d856a8a9891dc456d04f69a607821914b4b71f75e5edcb0e473d00fc6b74ec1d09348fed24d227b3b4b75b6138059e31be42074258f4fe5449d999751b6ba6d858c820b9bc4b899465a242657ce7a9c71532764745f40f18f8d134f8e0b5b76c4a80960406c023a01daa630b92d1e7da8ea775adccc43b1c0dfb09e4e3a91fe15d318d8c9bb1991ddc8242e8c62607f80e307d78ad1931973166d5efaf643147be7c024ae0b7e3f5a96d5812bbb1ad1783f59c0616ff24980598e39c547322f975342cbe1fde4d186964851b278763c0edd3bd1cc5c9a48d46f00d8da2333dc2dc315f94a82a14fe271c55b40caaba0dadb28508acc71d181c9fe958498241676496cef20755e7070c08c7a54a1346da7d921816ee7426dcfca4c401cfd3d69b34816edeed469d18d399a484b166563f301e8b532889332ef2eef74c55b992d6e9492400fc393e80f7a2da14d1a5a3f8cb4c6854de4d2c7907e665c807d38a4cb5b16e7d474e223b9b6b98f538d94ef54664e7e9ea3fa55c617324f533ecbc7506997cc2d4bda93b4149c808debc8ac944d533b5b7f8876d73128bdb782fad146034dcba93d79ea41a251b0364b69058df5aac9a4bd89566dada7dbc6a922fcdf7b2480d91d3be6b1438b2a4f63acff006ade5ce91a45868f6d123319afa6124bb72085c374fc01a6d1122ef850ea7ad5e38b95779521335c88f222553f77a8c824f000ef9ed59294a2c15d9a36917f67ded9c37c6e2d52522392598ef74cf2a18671e95d2e6da34e43a3d12e8786b5c9ac6e6fa3b8d02fdd37ef89848a14f427391f311c8e39aeda38ba987568c8f131985536a47be7853c2ff0af5568adf5096dc5d18f3f677998e482060b29c13cf22b92be778ca4ef191f2b88a5ecee770df03fe134e313f87616b90bc95ba906e1e80135e23e2dcd63b543cf73b0cb9f80df0b02a11e1ac92772ff00a5cb81ff008f552e32cd57fcbc39e55461fd9f7e16ef25f4691491938bd900ff00d0bad69feb966925f194ab95e5fd9d3e174d843a4cecb9f947dba4fe79aba7c639a47ed89d5228ff00677f8608eb2a6997a814602fdb9f60fc2b9e7c679b7fcfc13afaa45a97e007c3a97473a64761742c5ae05d154bd604498c673d7904d10e32cd9ffcbc32a953f78999d27eccff000c5a2c3d96a080747fb7364535c639afb5b7b43b3eb16223fb30fc314f9826a80b7fd445881efcd6d2e33cd554b7b417d688cfecc5f0d1b6a95d63192309a89ce7f1142e32cdf9b9d5436589b22cafecd3e00b783ca82f75c823392113513c13df038abadc5f8da8b9e7ab2a18ad47c7fb377842d4a95d6fc471464648fed26e4fd4f4fceb863c5d8953e568bfad5e258d47e02f86755d1ad34d93c43e20fb25bac8a13ed8199c3364eec8c3631819ed5dd1e28a90973b89c91c437239d93f646f033e47f6eeb5c600659932a3d7eed76478e31ea56377882b49fb21f82e65744f10eb84f24976889c7a83b2abfd7ec6c656b09625102fec83e0d5468d7c51ac8c820b37958ffd079a4f8ef169eb134f6e5693f63df0ac2891ff00c25baab3119194439fc76d74ae3fc4a5f08feb3615bf63cf0cc8a3678b3524e072618c9ce3d319a9871f62bda3f74afac5a24ba57ec99a368da9d8dfc1e2fbd696d2e639d50daa9562ac1803d08e476a4f8f71536e2e2655315cd0b1a1f13ff662d2fe2278e356f10c7e26974bfed095656b75b55740c142b10c4e72719f4e6951e37c552935ca6346bda0732ffb19d9c40f97e3072a7eeacb64b8fc486aea7c778a9c7589e84311ee8c4fd8c925942278bd140e48fb26e047e269ae39aab72962158637ec5c118aa78d55d4752d69d48f5c74a97e204d7d933facea324fd8cdcc488be32b7572d8f30dab6147d3342e3dadfca743c4db435f43fd92eeb47d335cb27f16da4d16a76bf673b6d59194ee0cac4eeec56ba23c7959fd938ab54e6a91661cdfb195d46548f185a924e4836ac40f71c9ada9f1db6be03a957b0c7fd8b7568db6a78b74865ec65b57ddf8f3532e3a77f807f5948f416cafcc41208c75e95fb5267e7753529b2618ed0393d3de896a99c9d4f46b7fda2be1b7836ced346d675a367a95b408b2c7e4b1da719ea060fe15f8a679c3d8dc6625d4a11e647d8e0a2e51d50a9fb577c22ba608be21983004922d1b0dfa57cbff00aaf99b76f6763d550517a827ed47f0919d80f1281b465b75bb607e38ab7c379843fe5d9cf287644337ed51f0a17615f11b6e7255545a485bf41583e1dcc25ff2ecc1b92d2c43ff000d43f0a5721fc4528f98039b5938cf6e0564f863347ff2ecd3961c962adc7ed47f0903171e2091cb1da4a5abb60fd00ad23c2f9aff00cfb397d9c6d62a3fed41f0a258fe7f104815b80cd66ea0fd091571e14ccd2f80e774d113fed23f099031fedb7caf1f35a363eb51feabe6a9fc02704553fb4afc288c16fedb98a9f4b563f8818a4b86335ff9f635464ba0f3fb507c26c943aade12bc97163211cf63c568b85b357ff2ecca71921b27ed4df0a640221acdc1278c1b072c7d86075a5feabe6517ad3172b9751abfb4ff00c284001d4eee3ddc2896c9d0b1f6cd13e17cc9ed4c6a8cbb0d7fda7fe15b11b753bd3cf19b172dfcaba23c358f947480b92a2d2d711ff6a2f84f1384fed7be2e7276ad8be41fa62b9570be6bcda530f632dec48bfb52fc2c6185bad45b670c469cd8069be14cd3f90ecf629104bfb557c2842aef79aa743cff0067b1e9d8e0f15b43857345f60e5a9498eb7fdacfe12ca306ef514201214583b16fc0553e15cd3f90a8c2d113fe1ac7e1446849d435011b1c10f62dcfd6b27c2d9a7f2113a77891c9fb537c27f3902dceadbdb0ca1ac1b1fcf914a3c2f9ac7fe5d9cf2c32b2247fdacbe12264fdbf54048e7fd01c64fb738ad5f0ae6b35fc33a7eae958737ed5df0a99948bdd51b682493a6be31583e16cce30b3a643a32623fed63f0b628862ef56c9195ff897373f4e79a88f08e6bce9fb32d526237ed65f0a6309e65cea8a00e7fe25cfc66b77c279aaff009765aa2d8d7fdacbe11cccb8bdd5318e18e9cc41fd6b07c2d9aaff0097644f0ec61fdacbe1223228bdd559980665162d8c7d335a2e18cca7ff002e8ca5829c4947ed65f09010df6ad5914fdd2ba739c9fce93e16cca3ff002e8d618598c7fdacbe1241f335deab1ae7e673a6b63ebd6b69708e3ded1339e164aa6c3d7f6b3f849302e97fa930c655ce9ac322a570b66b0d153349e1e4dfc2387ed63f08593cc6bfd5100e8069b2609f407a572d4e15cd29bd697e2692a33f91622fdab3e11b32ff00a76a078e47d81b8efd057154e1acca1aaa67552c1aa8ae5a1fb537c269b091ea3a81b66ce4ae9d2739eddf1583c931297ef23a9d51ca71135cfca58bdfda2fe1f8d2daeb4482e352bb5c0486685a04e7b9661cfd07358d3caea41fef1686d4b24ab27ccd58f923e30fc54bff008ade2a692f6da336f6b848a282468d508390a146437fbc4e78e95f4785c2aa48fa9c360fd89e7fa8b5a246669e38a34560aaacdb9e663fc28bdf1c127a0af41c148f6799dac73862bad7353363042db0b61004ddbb8ce473cfd2851b0deacd79e2d23c296e905ede89665259add23f914ff7739f9987e007a9ae88a096c615cf88ee753f33cb274fd3cb6d1b7e57703901ba16f71d294f577314cab77addb68f604c301899d485c281bb3dc9eb8a87ae86c9dce0e6925d46e49d9e64ae73d39aeab58e8b0cb79e4d3ae16742be629ce31dba115473ce269cb630eaac97163080fc196df76327dbbd4b90e10b236bc37692c7a9c76fe5caa98dd2c16e47cbcf56279c562f50b6a759abea304533ac110545048339c8403bfb9cd674d5cbb1c9ea7aa4fb83bde8c70024642a838eb8f4ab51d42c6136a2880a2c9249db2d2120fbd6d257137a95e1d5a7b76dc8ec08fbbcf159729adae8d4d36e55e233ddc4896a582bc840cbfaaa8e868e5d48996359f1c417776bf67b08ad6de2411450c7f7554743eec7a9357c8651958ab0f8ba6b785913956ea00e453b1a266c5bf885f11969dd9420656762c149ec3deb37a0dc6e5e5d4748d5ad8f96ab1cc0619368dac477f51f854496a1b142e74bb892367b6400707e4049c67d7bd69b226e61ebb61201e749b40047dd072076ce6a53d4a27f0a4d29ba5843878d8f2ac7ee8ef9a9abb1699de25bdcc7758b0b9b2b2b270abba45c724e7258741cf7ae5d6c116753a5a5dda8c4a60d4ae03050df6bc96ec146e19e49a9d424ceb2c6f21d2ad0da0ba68448d895edc9644624e5413cb11c8f4eb5490892db42bcf12c60e9b7969159172b35d5db70e57f8506325b0738fd455346861dafd9b49b3bbd1e66d4ae2e22949827d43cb4ddd895552c429c7019b3d286ae6151f43aaf067c46bbf0e5dd9dbdeda59ddd89755995e1c7c991961b06edc3d739ae4af4ee8f1f1185f6a8faff00c2f79e1ef15daafd9b5c8a34400bdb4f721648c1e8183741f426be5aae06a2d60ae7c6e230756150e85ecf4480795fdb36009e015ba5007ebc62bcd961710dfc0ce1a94aa37614e95a62609d6ac98630a3ed51907ff1ee6b4faae223b41934e8490c3a4d830caea56657a6d5bb41fd6b68e1310f78b2e74d8c5d1f4f6f986a36a41caa8fb52609fcf3515305886afca737b29342c1a0d8cbc0d42dcedcb054b94381e99cf15852c26224edcac984249ea492787edda22ad7b0601cedfb42e7f30d5aac356fe566d3a5222ff847d591499e25e319f3971fcea6542b27f0b3154a4393c2c6560c92c6ce4701255248fcfa54ba728fd966ea949131f0b4ab2005d5971f79645c0fd6b39f3c95acfee3454e440fe13963dcc58f23bb03fa1359fb0aaa57e462f672b122784e66f99dc32ede007007d411deba2a42ab8fc2c50a528bd488786ae436d42db07405baff0085453534acd0e51934469e0fb97dc4b9233ebc8f6c77a974a6ddec65084ae39fc257c1d40dcc07078c1c7b5138c9fd93a795d888f85ae81da0487e6c9383d7fad4c68cbf9498c25703e18bc0b93e69c752148fd6a54657d8a942446341bd41905c64f1f29e4fbd74287f74e6719122681a8a82b2176cf2bc5370b3d8a50972d887fb0351590873291d795fe94e72b2b58e88a928d80683a831dc5e46c75cae0e3f0ae64fecd8ce5090efec6d44c99dd332e32c5c73f4e3ad613bdf96c5c1342dce937e632aae001fc454fca3e95d2ac96c69252b8c5d335458c6649194f1c2900fb8cff5a7ed176069d88d34bbe0e012ed95201da6b44f41abc846d2f510c7ae33e95066d48f3b924c920b05da706bfb1f9cf9994534529ef234e4ba8507072d5aa91c728d99464b6b0bbb932cb05bcdb8005a5556c9fc454492b5e08eca58aa94f664371a3e8d23322d859798300b88977007a0cfa538a5095d135b1959ed21bff08d68ae70d616723b0f9b3128c8f7e29cda9174f1955ef2248b41d1ccbc595a1e06310a8e3f2ac363abeb33eac9ee7c2ba3cb6766b269d69e632990811ae15b27fa509184f132e7b5ca91f87349dcc469b65c1c6ef214e7d7eb5ba89a7b7926364d0b4c7c38d3ad579c711ae3f2aa54da5b111c5dc89f40b2908cd8dbc8b9ed0ae7fc288dafaa2a589603c3960ca49b4b6c8e4218d4ff004a56b3216226c8a6d12c264d82cedf03a810a8c7e555b1329cd97b4cf03b6b4e20b4d2239d5465bcb8570bf56c71f9d70d5c453a3f19ece07095f1ced46373abd3fe05acca8d730d85b4879c3c21f1f88e86bcbad8e8d4fe19f7587e10c5ce37a92e534d7e055adbc89207d3e4da7257ecbb437b671fd2b9e38d705ac0e8970657e5bc6aea71be24f004fe1f91a59b4bb616eec7f7c91a145c9e32474af57098ba351eacf87cc326cc300ff7f1d3b9cfff0064da465c18212cdd822e0fe95eb593d91e07b4ab11aba7d944a00b4b70c3a3796b9a76f20fac4fa8e6d3ac14126ced95b1927ca5e69a57e853c45a22476b6c5415b780a8c907ca5e0fe551287902c45e2364d3ed8aaafd92df07e624c2a49fd2b4e4b935310ec8827d3ed9e32059dbae3a1585473f956b15ca39625dd1dffc29d3ad21b0f15dec96d015834c6505a252159b8ce31d7debcdc527169170ad3e5b9e732699689854b7b7c7193e5ae6bd08c6c933ce78ba8a5606b0b6c652d2051d72231d29b699e84311323974eb60414b5b756c637794bfe150e0998d4c5544566b5b69132d044481b40112f4fcaba60a31e838e2e53fb42ae99109377910ecc74112d4cf95f435fac4a3f68747a6db6e6dd690483a8dd18e2b2e4a71da2674f133a931ada5db484e6ca1723bf94bc54e8cb962271996f4ef0b7f69cff66b0d392e6f24055638a10cc4fd3181f538158622a52a70e6a8cea8c7118aa8a1496bd8f54d4b40f01f8125d3aebc737d616970218e28b4c11292582f2ce14127dfb7bf6afcfb198f8569b853d0fd7b24e1f9d182ab8ffb8db1f17bc2b670eed2bc356535ab282b2ac31a232f6206dcd70bcad4e1ed2523dead9f60f0d2f614e27cff00f1bfe31c17f1c7a669da65bda40d963e5a840a4f520e32c73f962be62af2f3f234655f12b111f7158f22d3561b7d0a59ddc2055695df3f3465b2bc1ebb98741ef5cfd0f25a387bdd3e4d41acefe301229dda1850e42c51f5dcc718068827729492259fc4c2c156cb4df9ae1c184cf180379ce3683d79ee4558b7671babc57714cad2b8374a086cf247afe1daaae54f63344f3ddddc4259144480975727181df8f5e2b5e5ba3958fd4a217b1b5c4b22c517f0bc8d82c07651dff0ac62b53583336d15a5574880563d1c9e71e95d3291dad96a7b48ed8c31476c679e4392eac724fa01529dcc9ea75b67e147f0fc25b569a1d3ae6e55268e187067f2fef74fe1c8f5e6958abd9152f7c5c91b4834e84284e0b0259c8ef927ad4d820ae615ea5e6aafbe69cb13f7631f2ed07b5282489b946e7459e124956385c9dc083473582e53934f972182908304f3fcaad48994753456ca2557100798f00657247e559b91d51d8964d0f51b98d2148e578d72f861b5173df27a66a93329ea5593c297b113bf6039c6378abe739dc752cc1e137700bdec119ef9cfcbf534d3b8d685a3a04305b8ddac5a909cfc8492075e47f8567246f166c691a6f86af262d7fa84f6adb030312e4337d074cfaf4a8ea672674cb2f84f4b0d3e99a85cc9721067ed33858cfa9da0119f7cd396c4c55cbba8dbe81e27b259eddd3741b448246059491d481c153db3d2b3ea6cce7a1f08595e493fd85c45a94b13c4aa182c6188c13ea38fc075ad5ec66ca1a5dc5ef84ad7585d59419dc450c492fcf9c365995871c281f506a2491ac51d7f82be24d8c1aa46b293a5dfa83f67bcc0da4f7049e17be3839cf6a8490491b3acf8c21b848d63b81693c8c595d6d4bb1639f98b67e55c64e4509112760d175abdf16cfa4451dd69b7b6f0cad1c6f35be5f7027e5857819639e48f5aa682322a78ff005fd4fc3d35d2c4d6e6d99944c912aa992538c00c39e06781c75aca1ab27e26755e1bb68f5bd023ba882c33155693cd6ea5b241fa9c7e38a75a3a1d3649125ed8dbea57a22ba40a5570c87aa9cf2a79ebdebd0cae14f9bdf573cac4d1e677b1565f0269014b087279e371c7f3afd3a860b0d523a411f255a0e350a33782f4e3f76368ce33957607f3af4e8e5b878ef14657899e3c15a7950815f073cb3b13fceba279661e5f0c50351638f83f4f4552ad3798382c5d871f9f35cef2ac3df97951505168d3f0afc3cb0d6a0d7d19ae44d67a54d7d088a461b99197ef1cf3c3352964d86a2afca8e49b8a662b7862d92464f327db919fdeb678efd7ad43cab071fb28ea94135b12368a637012feed3b8ff497ff00e2aae393e0e7f6510a081b48917246a17cb81c32dd32ff0023512c87076bfb346dc911a2d9e3013fb66ff775dad78d93df8e6b83fb3305076e446f0a3164af6d74ac9247acea98239cddc99cfb61ba57452ca70b535e4412a514cdad3b48bfbcf0bf88b554f116af0dce982dd944779200eaf26c6272dd071e95b4b25c2edc88e5ab18c4cb5d4bc451c040f13eb0ad8f94adeb91d7ae09ae6970de0a2ff865538c5a1abe20f15741e2ed64723e417eff009e33c537c3b82b7c06b18449c78b3c5e8415f18ebd8518cff6849fe35cef86f07fca66a9c47c9e33f1a22a13e37f1010a18a8fb74843febc62a970ee0d7d92e14a2243e3cf1c86c9f1b6b99039dd7adfcbb5672e19cbdfd80f66996ecfe247c43b65022f1eeb91230385fb4b1fcc9ce6b925c2b97bff009764ba313bdd5759f889a77c21d03c6917c45d5e56d46fe5b196d3760c3b01c316e725b02b9df0de01bb721cf4e1173b1c5ffc2dcf88eb1853e37d6598b7fcf65e31f8554b85b00d7c0753a494ac49ff000babe27c7823c77a9364e30bb40fc7033d2b28f08e023ef7b3074a2c957e36fc4d442a3c6f7cca48557214753ee0e2b7ff0055b2f5ef721a468c4993e3c7c4f8f2a9e34bc0d90ac4468fdf9fe1e6b925c25817f60d27423724ff008680f8abf3c23c65285007ccf029239ed8144783f02fec132a31b1abe21f8d7f16fc2baccfa55cf8b564960db8916dd4a36555b39233fc428ff5470097c261429c5a664bfed27f15c3111f8ad635cf4fb221fd6b07c27805f646e92b9f5e9f88972465b4cd2f1fc245b8e07a57de7b3e5f74f80715b1049f111955d9b45d2f38c7100e6b47192563092be88c9d5be32e97a3c63fb434ed0eddb1c2c91956fd0d67526a9fc4cd68e12a55d208c7ff00868cf0c960b2e99a5ac6064349672edfcf6feb511ad49fc5245cf2ac4a7795336f4ff89fa6eb76c66b3d1741bbb53c16b6f9f6fa67d0fd6aa324eea3239e785ad4f568b07c63673850de1cd3188e328a500fc3bd5a4fd9b5208abe8c65c78f74e7037f85b4c73181b58b3638eb9f4a718b92d76391b6a5ee991ac7c66f0ce932225de8fa22330cac7b9b7fb80ab9cd6adc29abdec7a51a152bc7f76aeccfb5f8dfe02bd9044ba16989313c1983a67fe04d8ac7db4396ca6259762796ce3b1d40f1be8134002f8474d9001f2ba4cdb4fe207345e719257d05c8b96cd6a882d3c57a3798517c1da7321e8aaec0fe55539492729743969c2a4e6e3b9dee97a1e8474d6d4354f0c586971850d891c8da3d5b9e3e95f398ac6559be581fab64bc232928e2718ed1ec71ba9fed11e0cf0d5e3d85a2c5751c79c476db803ff01504fe75c92c3b514f112d4fb6a98ec36022e8e0216b1987f68ef09ebd75b1f4e892663844ba9e48727d94e335df42109b51ba3f3fc766d9b4dbaae56469d97c4bd2ecd83c3e1d8d255fbbb2ee461cfe82bd6faac64df33d0f9c867f9952ff00979a9b5a7fc5cd3b58996ceef485b15946cdeee2488e7b303d07bd79588ca3d9c5ce933f47c9f8aa96322b0999ad3b9178c746d03c2f6aba849e11b6b981be59244b864299e7bf635781c4f2feeea3d4f1788b86fd8d578bc22bc1f43cceebe30fc3ab79d90f864ddb2718827924dbed95047eb5e9bc55352e5723f3dfecec64f5847426b2f8a9f0d35afdc2f8663f3fa7d9e5ba789dbe818027a55d2c445cf954ae72d5c0e2a1ef4e269af8abc162331a782e68803907eda4a9fa735de949cac70ae697badd879f13f81ddd4ffc21b7b1fcb8245ef5fc33572f6918da3235507257660defc54f8576d77242da35ca48870ea2f7853dc1ac2755d35cae5a9d1f57c4548fbb1d0f41f0beb9e1093e1e788b55b1f0eddc3a74e628668d2fb77da81c1055b3f2e3be3ad79f52af3d6493307cf15ca8f3ed63c7bf0b74994c5aa689a8d8c81be50b7ff797df1cfe75dd52b7b24b9a7622384ad37cc916f4df18fc2cf105bb5d5968da9dc4718da5a1bbc83ed827ad109fb47783d0ef9d1952f8d0b26b9f0f1a3607c2fae1523208bb41c7d4b715d2dd45ee9e772dd348c01f107e0da3bc2f6daca4dd06cd41403ea01ce33f8d632ab3ba8f3235a383c4c7df9474265f881f080c86316be259420e63b793ce7c7bb2e45615715eca2f9a48f46382c4d4f7a31d0d0f0beabf0a7c59aaa695a645e249af646c2d9b4c125f5cfcd8ff0039ac1e362a2a719233a396e325514147567aac3f05bc1c96924f249ab5b2af2cb2dcafc9ee588c57875b32a936a148fd2f2ee0cad39aa9983e545597e28fc3bf86ba7dda5b4e637485c2cd6f0e434814edcb1392738e95c15f0f89aab9b112d0fad96272dca65c98549ccec3e11fc37f0e9fd8bf58f1d5d69c97be2bd6b44bd9aef55bb512cd2125d540639c28000c2e2be2313594310947639e58eab5a7af53e50d475bf0de93e00d352cf51b89b5696d515a2488058dcae4927fbbd4003d6bdfad8e8ca972a67cf4b0cde2798f08f142c9aa6a716656750006c72719e40f4cf435f3324a47d147dd6677896ec470180111461c3b4680fccd8da005efc71f4ae392d4cb7663eabacdd49616b66f235abc684340142f94bd00f763fa569d0da266d8d9b42ada84a849888484824f38c74f515ab25997addc4967e6064ccac32d93f3203db9a48396e41a269a97f69753310b6e9b496076976cf283fad0e4d339eda91b69035094cb34a184636a4607caa07403d69b93b1d17b234ed3c357b27920da62139c81c6d0719627b1f41498dcae6bdf5c27876e96dec23f32fb1b5a7da71111c02bd8b60d5c5d8a8a31d6cdefa376512c971bb325c4ec5cca7a739ef4d16e5a94bfe11d48930d318893b8b0eaded8ec050d049e83e2d316de4322cd2cfb7a17000fc076ac652b1922f8d6a6428a801555c6c71b87e66b394c1a2adfdc413c9bde31bc8e7031cfb5529dce8e5254bb4088762c4c14003a734731562a5f6b972ebbe25525485639ceef4e2a932a1069146e2f6e6fb09246d1b96fe0046293919ca3a94ae74d9c3ac63ccc3124ee3c67de9c6a2071b912d84b6f2e76b2e0e791d7d45375132e11b13de43f67b5b792342082437a1cf6a2124ca74c82df4f69e269060286e57d294aa72bb0946c5f06e6d6d765a99115895778cf241edec2b38cd5f51326d30dd7da12399a450aa7120e4a8fad12927aa26c77fe27365a3e85e1bd25e61a94b3d80bcbd990665dd23165519e8028e7bf4ad646499c9ea1a5878648560f26d59435b4808dca47f7b3cf3fa71509eb735dccdd25b511702133ed8221f3ee39e3d17d6b44d58ca7ab3afd13c64fa749729a7cb2697691a2869620a64552c03302c300f27f122a13d496b41b69e239f539a5d1f4eb08d2181e348a3b95134d31dd8695d8f1b8839c8e38abbae523689e8ea92683a95edb162cd691da5b4a8c368470a6419c719f9ff2c56327ee9d119fba5ebcbab4bebb77b9611798eacb763398dbb062782a7e95ae1a7ecd935573a3a0b4d2eee53e4416f25ccaa08c5ba9901c77057391ef5fa4e578c5649b3e4b1b4dc5942ef4bbb121cd8de263ef116b2103f1db8afb2a55636bb3c2bf5b14df4abc7fb9657670bbb3f667031f5db5b7b58a7a3b029caa68915991a3425a3957032418d871f88ad5544e3cc6bcea10e5363e1ddcbdaea1ae3a032a49a0dfa32ed61c18f7039c6072bdfd6b1c4568aa7739359688f5eb8fd8e2cb47fd8b754f8cdab7886f350f124ba647a9d858d9b08ad6d95a545dac0e4c8db4b67a727a715f9ed4cf2afd69518ec7d653a5eed8f9d9a58a48602c7cbdd18601b8ce40e9ea2bf44c336e0a523c1a97a2ddc24b9b752a8f2c6a581da0b609fa5744a4adef046716cfa67f621f057ecffe2ff0578adfe2b2e80faf2eaa45b4daade9864101897684f980c06ddcfad7e6b9cd6af0aebd91ef5185d687ca9a7dc5a31bc82dae2278e2bb9d21224dc1e20e42919ea3681cf718afb2cb2ab9d05cc8e1aed466cecfc2216e3c39e3bb2ca167d1ccc09c722299188fc6bd89c95d591e2e26aae6473131f388c6d5cf41b86689d46b4475d19b990ed24f23f1cf269c55b5dcee6925a8e8f2240b8180727239e94a5c8ba9859c46c92330d800c13d48a54d37b96a644c026fc678e791da8bc2f6b12a448082a8e40f954f3e83bd6326a4ed13548f61d3dceabfb25f88725a55d17c4305d46381b239542b1fa127bd79f597b3ab65d4e07171c423c70a3b1cc6c70c4839fd6bd28a5bbe87af523caf986c45d18103237004ff005a9938184271974097326fc01827d7a64f5a9bc25d0d6538a2324a31046dcf7073d2ada8762b9e3d8799bce0232496008c9f53dbf3a87ca672ab1b1d6fc43717d77a26a8f1963a8e9505c73fde50636cfbe56b04d3679d8597bd2470979abfd82628d03c8586edcac31e9e9ed5e6d59fbecf669d2bc6e7d972120856257d71e95e837eef39f9554ba9d8c8b88350d7bc4ba0f857489231ac6b973f66b533b61517f89cff00ba327158e3714a861dd447a19761fdbd7b33dffc65a77c20fd8c74ad2adaf7c389e3bf1dea6a6526e556491ffbf21dd90899e800afc99d7c5e733d67ca8fd46961e1423fbb5628e87fb7868ca608fc57f075f49f0dcf32dbb5ec091cb1c40b6d0ce85071cf4f4ae8ab93568c6eaa5ca4dc9ea27ed8bf023c2de02b2d0be257826ca2d0ee2eef61b5d42d2cbe4b6bc86404ab7963e50c09072074fa54e458caaebb854679f8ca14a507a1e409e7370173918240e9f857ea91a94a51d59f9bd6a0fda35144de17f006bdf13fe22e9de07d11dac66990dcea57d246596d2db190c54f566e8a0d79398e650c0d0f687a180ca655a5fbd563db7c57e28f83ff00b24ea10f84bc2be0a1e3cf1eba092e049b67954e065a495836c27a8551c03dabe1e9cb1f99deb49da07de61709470ead4f73a2f017c68f067c75f1143f0f7e247c2ab7f0c6abaa40f258c5a8411c915d85fbca8c002ac073c7a1af331943150fde5393d0f52a61e545de4b43c23c77f03aebe10fc6f9fc07a55d79de1fd4ed5f55d205e48ccf020387889c12c14e707d057d8e5399d4961b9eaee8f9ec4651fda13e4a0acd9d768fe16b3f00d83ea9aa98e5bc61b62446c8cfa2838c9f7aeaad8dab8c928d1d8fa3cb387b0b91d375f1fac8c8f00f8275bfdabbe225de8af773693e09d108fed5b9b53b6491cf2b6e8c7a161cb37a57918ec47d423a7c4cbaf99cb16d2a5a40eb7c5ffb49fc2afd9c35c6f057c2ff008756be25bfb13e55dddc5b422483865694ab33b71c9e99ae1a197623334ab577647912aea9ee765f0c7e217c37fdb62c75af07f8cfc036ba47892d6113981d559fca270b3413280caca48c8ec71d457263b095b2f9a9d26da1a71adbaba3e4cd7fc217bf09be24789be1edede35fff00634aaf6774e72f25ab8dd1963fdec119afd1f27af2c4e194aa2d4f85cd70b4e8d4e68a12794a42f2332a22296639ed5efaf755ac7851e6ba8adcf64fd9cfe0dcbfb406813f8a3e21df4b07c30d1a475d3f4d329885e18c7cf34ce082624c10173fdeafccb3dc62c3d7f6149fbccfd7b098caf53051a151dcd7d4ff006daf0df846ee5d2be127c201aee8166c611a85bc0b043291c7c8aa8491d796393e95f3f4b075aacb9b112d59d74b0f374df21d8f823c53f0bbf6ded2f50f0cf8bfc107c2be38b4884be4bc622ba54ed34132805941eaa7a771cd6789f6d97b5529cae724e8dd72d547cadaef82759f85ff001035ff0087dabb49a95de92eb25a5fac6435c5abf31b301dfb57e9191e612af454eb33e1b32cadf35e8c4b91785f5abcb491adf49bb90468cc58c45000012739af76ae3b0d08b8f36a634326c5d44a3c963df3f678f07e87a5fec0dac6bda9e8fa7deea13d86a776d77716a8f2ee25c29dc413c1031cf15f9566d88a93ccb9212d0fb6a1877428aa32dce1be10f84b46b5f809a0da5f5d05b2d4552e1a67b811959719d80fb57d34ab568d5fddc4f5a870de02b528d5ab2576765fb1dfc3cf0cddfc79f8a20db5b6bfa7da59d9470bdf225caabb659b692081c8ed5f3b9ee2eb45414f439eae070f83aae9d05a23c07c5be1f9eebe307c4cbbd174831692daf49046b65062342a002aaabf7467dabec325c55386113a8f53e6b36cb6b56b54a51ba23d53e1ef88cf87b56bc4d2a75861b4925669084c2aa927ef1f41e95efd6ccf0ed28a7a9c785e1ccc39955f67ee9f5efc28f81de01f177ec5be14b2f13d858e9fa63e9515eea3a9082349b62b191d8ca5723201cb0e704e2bf1fcc2be22a63e4e948fa874a9c63c925a1e436bfb7af807e1ddd7f60fc2cf8402f341b5cc2b7aaab6de70071b8008cc41ec58e4e6bd9a19362f110f6d524cc556a54df24763da6c746f867fb737c1eb8f1059e86344f10db3490a5dc71ac57fa65ec632bf3ae0b0ced3e841ed5f3d8aa753015f939b46745395e4aa4773e28d2bc5fadf88b4c74d67529ef2eec6692d2425cec668db69207419c66bf5bcae85285053eacf333acd719564a9b9e82e97e2087c03f107c35e31d434287c4da268fe6bdd6972b280fb94804060412bd791dab3ccf0eebd39462cf072ead28d5f7f53f44f55f89da2c7fb331f1a1f0bc6344974d5bb6d0626545f2d88ca02142fbf4c57e19523255e54ba9f731d5d96e7e62fc7df8c9a07c4cf175deb3a1f8561f0be9b6502c6b0c4ca5ee703bed00617b71dcd7437253e53b946fab3c3748d5e7bc66d6eec87421a445c9fbbcf6f5c015d3aa7634906a176a268ae6283293a8654cee281875627fa5612dc5ca6369ba6b5e5dddc6c433a8decd2609e08e493dfff00ad5a7421bb0dd6f0d70b6f1288e2817705ceec3f7638e68bdc1b22f0df83ae75c99ae2f25874bb2765669f532218f1bb8625f19cfa62b548148ea75dd722b2558d757d3eedadfe458f4eb259212bea59b009f7c1fad62e4ae26b53958bc632ef7586d22648c954905b2a29fe793efd6a9c958d5c6e8824f12dc5d392e0ab31c0c7ddcfd2a1c8a844ab63a7dd4b72c5031dcd9396c003d6a39ec68d58d392266b668914b420e4856c173eb5ac64271d4825b086291048bfbec1210316fa7a55b914e3a1613496b88c860d1af20a1ead5c1295d8a312b268ae46e0005038c9c6694b63754c71f0f7ef55c81919e87939ef4948a6856d0096e72c0f4fafd2b3954b0e31b8c5d09d54e14e723860037bd6bce6f15a16a2f0ef98c582b038ca8c63f5aca550cf935271e1f2b9050990f42470454fb43554c43e17cbb46f1b302b9c765268750b54ec4b0784035bb24916e41d07707b1a4ab38a1f28abe090c71100a31ca8efc7f5acbdab932250352c7c04d3ec81004ce481c8e476fcab47330e526bef01086388b1fb3da2b0695d949660392147bff5ab84ee3e438cbaba48754bcb9fb286b9624a4922970a3180aa3b1c0e2ba554b9cce1619e286bdd34436d77e6b3346ad76d2a60ab13b963ce300aa919c56cb524a16f2c29736d7406e52db43e72a40eaac3b1fe95704435a9d74be1d0fe1dd5351b388cb6f6cbba593800c4580ce3b9076e7da95b5267b1a5f04fc1f6b63e243a95f379f1a2829b14b6dc9c918f5e315a4be1327f08abad1f14789756d5c46608e5b8904d1960586085566038fb9b40c7f74d656f74715ee9d669db75bd16d2c2e654786d637811946d6528c5979ea473c1a71474bd0d5f09f8e759f07c77eb65766da72a040c18804f1b830ee3d87d6bd6c2e21d3679789a2aa23d1f4cfda27e20e9d1c4b69aadb44840dd0cf6cb2ed1d4609ff3cd7e9383ad1ad45367c66270deca76b1ecdfb23fc3bbdfdab2dfc75ac78c3c63e22b4b8d33528ed6de0d1ee96da248ca163f2853debe4f3bccabe0eb2545d8f5b0d848a8a6d1e0f69fb4478fbc3326a9a026af6da88d2351b9b08e7d42cd259992390a8dcd819381debedb27aef1b878ba9d4e1ccb09ec276458d17e2d4bf17fe21f863c27f11bc570f84bc0d77f685d46fb4eb78ed371f2d8aabbed3f2b300bcf1d6af33a32a5426e90b030537667e8dcdf0ffe1c6adfb221f090f1199be1aff637d9bfb704c0916ead9f337e3190475c76afc56acaa7b5bbf88fa48bb4ac7e7dfc59d7fc2df00fc6de1fd37e1af8bed7e246817fa74ad771eaab15da5a4aac153632a8da76e78cf6afd2723c562f116a75f63c9c7d04e0d98a3f68dd487cd3783fc21391c963a7fcdfcf8afb9f66e4b53c18519465a9dcfecdff00b3a695fb6deb5e3dd6bc49ab5c7861f48bab7b6b7b0d0ad618e008c8c724329ce36e3debf36ceebcb0f5d247d661e5cb13cc342f88969f0a24d6bc193f80fc3be207d0356bbb15d4afe0d9713aa4a42b3edfe2c715f5994cfeb145599e763a0d4d1762fda134bb5dfb7e14f8622f354a48206642cadf7958e3907d2be82a5374f5723e76a50729b3d03e0cfc2ad7bf6b7b5bbff844bc01e1cf879e1b8a6305df89e485a7924f58ed94e32c31cb741eb5f1198e77f547cb1d59ede1f0b386e7a2ea5ff04fdf829a05e47a46b5f1aaeadfc40df298defeda13b89e9e5ff0f3d89cd7cccb37cd2afbd49687b6a10ea8c5f19fec39a97c18d0aef5fb1d32c3e30785e35f3d954983518500cb3215255d401d01cd7a585e279732a78a8d9f7396bd1495d1e151f8fbe0dea56d1ce7e155cc6ac3eec5aab06ebeedd7ad7dde1aa7b58aa90968794e2d14b52f187c245b1bc5b4f877abf9be4398d9f563b11f076e70724035d156552f7b931d6475fe1dfd887c5dad7ecf5a47c52d2f5d1af5cdfc093c5e1bb6b422590b4de5840fc8e3862481d0e6be0b15c47f51c4384a373dd8d15ca7b5f80ffe09dbe3183c19a9e91e2cf89569e17b5d74c52dce8d610a4e032728acedb77153d4af048ef5e4d5e289549f35285ec2fab294b98e43c79ff04f3f15fc2eb66d6d3506f1df86adc192e60d2d45bdfa460125d73904280490324f6aedc3f14caac942ac2d72ab479a363c716c7e051843ff0069f8bd643cee68558a9cf238079edd2befb0d5255a373c373953e80da67c0d9063fe123f162eeea56d1491ec405ae98a927b1cf2af37d048fc3df049d404f1978a2de3038796c940fa9f9719ad2f2ec546bcdf41e7c09f06a74529f1235945041119d38657ea42d66dcbb16e727d07f8eec7e1ac5e0c81b4ef1bcfa96a1a2d8cb0da5bbda6c6b9cbef0ac71f2e09233f9d725f91361878f2d4675fe16ff826f7c58f1a78774ef10d9ea9e1b86d356823bd851ee25dc11d415ddf275c62be1315994a355a48fa8a2a3c8ae7b79f86d6cc496bf625786528a09fc735edc71d59c9fbba0a7c1546a4eeeaa387f883f0c347f13ea1a5685a64177aff008c65dcda6dad84de5bc447de7775fba9eb923a579f8bcc654a0f9a3a335ff57f0f95be68cee75ba3fec0fad6912dbebde3bf8b106837cca11416595e35fee09656e7a9e00c57c9bce2d6f6302a5534b44d793e06fc01d3af2dadbc51f17351f163dc5c470ad8dbde2949242d855290a9ead5a54c7661569b6e3647372ca2ee8f4dfdb91ec2c7c13f0ebc3ceed6fa75ceb514641639114719c64f5e32b5c1917b49d7b9a479653b54f84f255d6fc35a0c79b2b45b965c283c8391fed37f4afd0fd9556ac7a4eae4396c94959b67a57ec372c7ac5a7c50f88377128b9bad51eda250db8c56f020c203e99af8ccfa5c92a74aa3bd8e4af5e9622a3ab4a364cf93745bdb8f12f88b58f174ce5f54d52fae24f3b277052c4001ba8e06315fa26068d3a5422a08f83cd713530f55284b949fc5f20b81a6dedf6b175a3dfd8ca64b2bf8ee0c72dbbe083b589e320d764f0d45f4d0e7a59ae367f14ae66f8485db6bc75d93c6b7fe28d5e043125e4f77e63c2add402092a0fa77a2387a2b482d0dea67588a5f047964755aef89750be85a7bdbc92e4c31b30f31b3b4019e3eb8ada185a718b70d0e6c5e6b8ccc797db48fa23f667b88fe1cfec3be20f18d901fda17f0dfea6f2f562e4b22f3df6e062bf27ce66ea666a8be87d861d38d047c8de04d362d27c3b6f3292f777c0dc4f39eaccfc9e6bf54c1d3f71422ac8f92cd2b4ef75b0df10fd9ac35186fe3f10dc78635611b4297d6775f677643d5491cb2f1553a7094bdf5a196071f5a9ad35441e15d2a1b79ef7526d766f10de5d30f36f67984ae401d0b724fe35bd054e2ed4f632c5e2a5898b735617e20ddbda78335478880ed16c041c1192067f5aecadee45d8f2b034dd6c4c51f63fed203fe1557ec1fa3683a537930dddbd86972ba70cd1ca0190823b9e79f7afc6a9f362733e69bbea7ea528fb38fb9d0f0cf037c4b9fc13a058e8f6da65a3d9db20402306367c0eadb782c7b9ef5fa0cf288567cd1dc30bc5af05074654ae74bf09fc713f8c3f6b7f8772d8e9ff61992d2f16e5a27dfbe00bc86e33807d6be5339c12c26165cc773cd69667ac236373f6adf15c3e15fdad2d2e6de14b8b83e1a549d0b631fbd2ca4e3be0714b87b0cebd0945e88e6a59852cbea7b59c6e79d78bfe366a8de1ed54d9dadb5b836b2a976dceca0a9048cf19e7b8afadfec8a74d735f52ea719c2b4d538d3b1ee9a6baf86bfe09a0ced8532786e4239c65a463fcf757e63579e59b2ec556abcf2f688f9ea65361f013e1e5a4a8a8cead3b2b7de276819faf35faa6069bf6ed9f9963b152759c549d8f71ff826bd9091fe28ea6001e66a70dbe3bfcb193cff00df55f0bc59cbeda0a2f53edb2e94a5865cc784782fe2fdef87f5bf1c247616f34577e20bc9925663b8132105491c11c0231cd7d1e5b818e2684398f6a1c490caff00733a7cc52f8a1f18fc477be0bd6521960b4865b778d9228c1dca579e5b27a57b3fd9787a0ae7154e30af889fb2a30e547d35f1fee2ebc23ff04e9b4b682430492e8da7da39e84ac85370fc41afcd211e7cd6c8bab2ba7267c65a55845a5e976965011184897240fbcd81927eb5fb25185a89f9f62eb7efac8fb17fe09b719b2f841e3bd408c1935b9d8f3dd2315f90712a4f14923ed303fc28b3e3cf09b0b8b5d467231e7ea5752950d9eb2b57e959769868a3e6f3795aa32e7890087c2faa390aca2071b4f7c8c5698d7cb4ae79b819735548f47fdab7e3d4de18f833f0f7e14e81a8c4a64d1adeeb5bfb2b066442a0a40c79dbb8fccddf0315f88568df15291fa9d386ccf8b7c54f2cba39432c70b5db2ab053828bdf03e959c95ddce81f2c91c1a7c3a45bd9186ead5bfd2a690124e71b57db03248ee4d5f4b12c8e053706412443f72a724b000e067193c54496a5261a342d1e9ed71fbc8e7b82776fe0e00e98f427f954c9e844b53924bb9a3bc92e22004b239605f0df2f41c56b4d5a1a89a175e885d35a24a25b8b864f32492625c2e3855e73818e7f4a1fc26f065fd3bc36aa127ba693ca232c3aee18e8b9f7c66b0d8c9abb3460b18afa091cc4b0a44188da3803a71ef532a963571691521d0d1e5672a060f00f19159ba973aa10b237ce93f66b44720032a86c630547a552327b940e9b0a4c48dcf2b2f083903e8077ad53b235e52ddbf87c5b4ad3b233dc30c0cff003d3f1ac7da5996a2074f712f00024138cff005ae594f5348d318fa217704904e3201e807a815329e875461a17174460b8ff0058739ca8e2b1751a40a99613466ddfeaf9e993d2b9156773454c7a786e5652e06e2bfece33f8d6bedcd3d9972d7c3d3a603a970bce4f414bdadc9f665c4f0d488c84fccbb89e09cd44aa6a6fc85c8fc388242486c85ddd338fad375740f6659b7d15125df80ee17057071d73d0f06b05547ecc78f0f99a467310018ff0ff000fe15a7b40f66743068d1db6987cc895655198cedc7e5de929dcc65495ce675d13ddc69111fb88812171d0f3935d707a04a9ab1cbd969e969aec37421134901de913afca4ff0e460e40382462ba69cdd8e1953327c53a3dff8c2fe28e77322ee24c7bc84dc4e59ce78edd6baa126d9cd28a392d5bc0674a699ed802bb5773b3a8504f45c6735d717739e4751e0cd6d6d56fbc3f290d6cf0a9133a9e49521d067af3d3e9552211af63e1d592ff54d1d259ad6eb4ff2a74952428650ca095201ec083cf06868cb98e5ad6dbfb2a4d42d214711c931467e4ee5538ceeedf4a9bd8d62771690f9252dd4059102ee718c30da0eefa60e39f4a94fde366ae5cd4ad196ef63a3072bb8820838c76f4aa53b4ccdc2e5ed2a031445e2e141da5581041f4afb4cab1fc89c19f3d8dc3b6db3ee4ff825ddeffa27c51b52a43ff695aca015c654c4466bc4cf6779291bc63cb4a27c41e38b03a7fc51f8856a40cc5e22be5e3a7fad27fad7e8dc33ae16279d9b2bc91cc6a502358cc0a2b305241600e38afa9c546f16799857cb23f467e1517d77fe096d7b02842ebe19d42218c004ab4879fcabf0fcc528e35fa9f4d4be247e68e87696b1e936122a012790a091c039515fb0602927422d1e5635fbecd44c2290475f5e95ee38e8cf39a3ee7ff8250dcac7ac7c56b4ce5cbd8ccc3dcac82bf21e245668f7a92fdda3e3bf8cd6ff0067f8f1f152db3865f125e118ec19c9feb5f4bc392bd146598c138a386d59de0b094abf96e40556c7f11381f4eb5f55984bf70ec7150a29c91fa8df1f7c4973fb2a7ec13a4d8f849ce97a9359d96976f750b00f04b38dd34ca4f56fbe73ead5f84cb9abe2f96a773d94bdf3f2cffb2a2b889e4bb692e2799cbcd2cce5ddd89cee6627249eb5fb060b034e145348e5954b368fb53fe09cdfb40eb5a0fc5083e196a97b35ef873598247b04b9937fd9678d776d4cf215941caf4040c57c4f116029c21ed63d0d69cb9a1a9e59fb717c2bb3f845fb49eaf67a5c02d747d76d9358b6b7400223b332ccaa0745dcb9c7bd7670be2e738fb2ec73d7a5eedcf0d900f2dc104a6d3f77a8e2beef111fdd3679f05b1fab7fb1478c8e9bfb0f685acb2090e8f617adb179dde5348c07d7815f87e7305f5bb33dd51d523f3d7e12de6a5fb4afc5bb9bdf1debfaadfdd6a905dde4421ba68d637542c88aa0e02af18518e95f6f80cbb0f4b0bcf638f155fd833eb7ff008263fc5dd77c472f8c7c03adea33ea967a54515d597db1da4789598a489b9b24aeec707a64d7ce67d8550e5ad0378c9548f39f197ed0de18b6f057ed09f12344d3e231595b6af234310fe11200f8007419638afb4e1cc47b6a473e2e8ab2679f80e8e4161b0fde18e73e95f68d1e75482d10d2c923aaa0538eb8e0ae3b114d2466a9882525720290a71b49a96933a631d059de29a2292306192a23c93c1ebcd73ca9271262b959b0bf14bc73a3c105969de28d6e2b38230914516a52aac6a380a067a018af9dab868b93d0f5a35343ea77ba936804b953c92589af515157f23f3a96698bba4e6cf6dfd81ad74ed43c75f12af2eb64be20b39adedd37afcd1da95254afa0241e47a57e71c4eea52f6696cee7dce06a4e5454aa3b9e17fb4cf85fc637ff001cbc49ff0009968de26d4ec1ae99b4a92d2de49ed4db9fba10af0b81ff0002eb58e518ac2d1a6a12d0ecaf26e36a66e7ece1fb2bea3e2bf88fa0f89753f0ec9e12f09e913a5dac9a8af973dfccad9450a4e42e71c9ebe94b33cd30fc8e951d59cf86a75e2ef50f5afdbfefde4f1dfc33d2dbfe3dc9b9b83ecc1401fc857270d294aacdb1e324e345c91e0525f866002920f5ed939eb5fad53a6eeae7e5d56569bf68ee7b87fc13f3c59616737c40f867aa4e906a335e49a959c4cc01b8b79176b94f52a76e475c1cd7e71c4d837cd1ad15b1fa2e5d353c2c5bdcf11f13fc2df1a7c07f12ea9a26a7e12d6758d145dcafa6ea9a4db19925899b72e76e48201c1ce3915ea65b9dd08d051aceccc330cbde31f3763d47f674fd9ab58f1f78f13c67f11bc3cda578334bb690da699ac85dd76ec3fd63a670aaab9fbddf15c79ae7b0a90f67877a9382cb9e175a9b9e3df11357f06f897e3ef886fbe1f69567a4785f4fb65b046d3e2114579202774a1470c323e56f419ef5f4b90c2a3a1cf599e766f888edd486e516ea3789ce15d194fe2315f4d349ae53e5a52578bb9f47fec5faa69df15ff665f14fc23bab95b6d734b5b9b0962601596294b345201d59413827db15f9067d82a986cc3eb10d6e7e9783ad1a945289f385e7c3ef88bf0c6487c39aff0081b59bebdb66304373a5da99e1b8456c2b2b2f001f7c71dabeaf2fcf70ae17ab2e567958fcba78a77a7b9eff00fb36fecd8ba727887e24fc68d12cac34b8accc365a4eb8892adac2a773cd22b65558e0051d704fad7819c66b2c63f638595ce8c0e016157ef773e65b4bbd13c45f11bc6de21f0f6931687e1cbeba0ba7d941188d1625e036c1c02719fc6bedf29a3528e163ed373c4ce1c7550433c61a6ff6a787b51b407124b10f288fef039e7f102be86b6b1e53e770728d3af1773ed0f0ec761fb66fec6b0e85617f143e26b1b68a096376da6dafedc61778ea15b1d7d1abf15c6c2a60330f6d15ee9faa37ed0f931bc21f127c2d751e8badfc37f104fad463cbdd656fe6413303b432b0e369c641afb5c3710e0aa42f39599f235f29ace6e703e9bfd967e07de7c153ae7c5bf8a72daf87255b2305a59dc4c0b585be72ed2374ded80368c9e31df15f259ce62f359aa34363dbc0e1bea90f7d9f2ef8cbe224bf1a7e30f8b3c7aab247a75dccb67a724a30c2da35010e3b67afe35f779260de1a8aa55373c9cdf10b97920cc7f1689e7f0c6ab040332c96ae142f5ce2bea274d7b377e87cce1ea355a3767dbbe15f0cbfc74ff827f58787fc3ee925f5ce80b6b0c65c01f68888fddb1fe124ae39e9915f85631cf0d8f7396c99fa6d3929a573e6bd73c27f1335fd13c31e1ab0f84de244d6348b2368f25c2225bef2402dbc9c6de3ad7dde1f3bc25383a9196a7cb54c9dd6c4b9f43ec6fd95fe0e4bf003e145fd8eab776d77e25b99a4d4b5616cdb96191932b1fa90a00e78ce73d2bf3bcc718f178bf6b6ba3ea28c15382a6ba1f9c1e1bb94b9b1be957a5c6a3753367d4cadfd2bf6aca54bd84343e2f3b729d4d514fe204c0f846fa3e7f79b541cf5cb0e2bd4ae928be64797973fded8fb87f6e355d3ff0062cd0b4ec91e73e956c07a80aa4e7fef9afc632dfde66df367e8d55feecf8fd5d3cc8c1000555191e800afda63a5247e6955f3626c7d87fb0741fd95fb27f896fdc0512de6a73ef3dc05233ff8ed7e2f9fb72c7a5e67e8f875c94627c5bf0f8093c296ac0e59e49189c75cb13fd6bf4fc12b528a3e3b349735566bebc513489839f9588033d09ae5cd27cb424ccb2b85f1163c27c451412ea9773baefdec1dd9b9dca06154fb0c57e3f2d67cc7eab07681c4f88f573149a7cb0ed62c772e4676853dc7a9c54a574522ee9572523b9bb9e591ee2ea5334cd8396623906b1bea26684b37f66e9f753ca3f7a5d230bb780cc0328fc41073e955262b95aeaffcb4618da62b71b48e72cd9c81f4358c901cbda44d7ba861e55cc4a5d940f4ec7f1adaf6817637f4a8525b9fb55c20cc6a4e31c66b152f742e6adb46dac4c43a11028c1c74cff77fcfad734e675429dd9b9696710b1ba5656421556341803af53f415ceddcd670b12d96950a10f215280ee663c8aa8a3a1ab233359bf135d1b785498d0e0aaf527d8f602b5e6b1cb18dd9774ed311271280de6301b718e3ff00af54e5a1d9ca69884c7f2b80e586df726b8dcb5348c081f48dcdc21242e18360ee1e98ef5c929ea68a99a367a019a250a841539002f3c54ca7a1ba8336ec7c2e19b291119e393839f5a9954d0dd44d7b5f0613181b06ee87deb8b9f5365145e8fc1e9021ca92bdc1a973b0dd3124f0c286caa0caf031d3df35ac2a193a603c3d1a8214100f5f5144cb48baba42752995200c015949e86890f3a202bf326413df903fc2b0bb15d16ad7428e27dc62cf1c135d11770ba2b6b1b360c95183d08e7e95b239a49dce5aeadc92cb1911c6172ce7a7a71fe35dd066734cc86862b22a610e676cfce8391f53db35bf324733898da80904ecf2c01095da70704e3b7be6b6a550e6953399bbd5cddccd6e9a6db2c30aeedcd112e70739639c120f438e38ae9a752ece4942c7316fa85b7fc25a1e18f7066767f3392a4a9e41edd722bbd6a8e67a1d85b3be99e2493534604dd411a9523ab2ae3713f4da3f035927731e532b57865b9d7b518ade4458ad250b32b03f33360b104707af1f434a474c0e8b4dbb8d252cedb844a04a1f90cb8e00fc38a88eb23537358d53edf7a9a9897ed534a36edc0015400300f4e300631daa9af782e4975712b491c96cb188dd434801c0dcbf782fbfa57552abeca4da38aac54d33ee2ff82685a0b7b1f1c6a923b31d42ea086150b85c46877163d8e5b1f85638ec4fb7e5463385a08f8c7e2ec2f6df1b7e26412052e9e21bacec185ceefeb9afd5f8665fecd1478d992bc8e36fa3215c001815231ebc57db56d62cf0e0f9667e87fec94bfda5ff0004e2d62dc6656feccd6622a467e6c4981fa8afc233a7c98d7ea7d5d1f891f9a5a1c911d0b4e44757610a86507254f4c1f43ffd6afd7729aea54228f3b190bcd9783107dbeb5f4bd19e5367d9bff04aabc29f163e245ae7fd66976b391ee242b9ff00c7abf1de287668f7293fdda3e74fda62d934dfda8fe2d5b20c7fc4e4cc73e92286fe79af77855f351231c9f2a3cb75888dd69d2a61b270ca47a8208fd457dae2e1cd49a3928bb347e9cfc6fb06fda93fe09f161aa78714ea1a9dad8daea51db5b00ccd716ebb658f1ea06fe3af15f8656a53c363539f73d7a6ef267e645b4d15f462543bfb601c1c8ec4763ec6bf6ac156a75282e5679b28b7367b97ec3fe1ebcf14fed65e0b4b285e54d20cda85e48a0ed86311900b1e9cb1503eb5f07c495e3ec650474d35647a2ffc15175c8af3f68bf0ae9f13ab4ba7f878b4c14e4af9933900fa1c2e7e86b9386232a72735d4789972d23e4af30927072a54e715fa7d5837499e4d37b1fa69ff0004fc71aefec59ac696e7221b8d4ed180ec19377fece6bf11cea9b58cd4fa05ba3e02fd9ab5b1e1af8afe099d83211a81b57c1ecc594e7ebc715fa460610a982b1e0e711bc3991f4d7ec0f6ede12fdb3fc7da0f9a551acaed7631cef613a3f1f8366be573cf7b0dca8eacbea73d13c7ff006ecb37d33f6b6f1baaa11f688ad2e00ce321a1519fcc7e95d7c22dfb2773d2c446f4d33c15a470a4904b139c9f4afd2232be878b28b72258d9721400a5549257a9349a684f423c10acc18e4b64af6a9498d48110c72e5970a464b673fa555b4137a90cf6f3dd32c899542a3031dabcc9c353b63b1f603cfbb0076ea2b5b4ef73f316ada99b11d57c37e2c8bc5be15d66e3c3de25893cb37106592e1400024abd19781c115cb8ac153c746d33d9c1e6bf57d19e927f6d7f8eb656df667d2bc3da848176add842809f52b903f2af94abc2b41caea47d14739c2ad133ccbc5bf11fe2e7c51f1058eafae78b46993e9b3add59d8d8c58b68e55605495ce1b91df3debd0a7c3d82846dcba9cb573d87372c762c78e3e20fc47f8bde23d02e7c7173a55cda68cf2bc1369f088a47665008603aae7b575e1b27a1827780b1799d3ad0b44924916670080a3b63b57bd1bc9687c2557294ee8cad4b4433ea961aa69fa85de8fae58317b5d52c65d92c64f6cf71ed5c95f0d1c4439647bb81c5ce83577a1e9b67fb62fc71f0a5825b492e83e2631a8559eeadda395f1ddb6b019f7af92970c50ab26db3eb7fb630d6f7ce1fe227c73f8cbf1bac8e9fe22d62d3c3fa2b6164b1d258a2ce30721d87cc41cf4cd7a187c830d8777b19e2335a4a3ee331747d0edb43d3a2b3b742a91ae093d49f5afa6a518d25cb147c3622b4ebcae592c554601cf415bdeecc599cb67a8e93e2183c45e1cd56e7c3be23b7c18f50b36da5b1fc2ebd194f420d736230b0c446d247ad86cc1d03d561fdb5fe3d6976cb01b6f0deb1221082e1e131b3fa3300c067e95f215f85b0f51de1a23eae8e71869c17368799fc4df1ff00c54f8f92c76be3bd7962d10302da369dfb9b724746603963f535ea60325c261be15a9c988cda119da96a1a7d85be936896d67088a08d76845c91faf5afa3492d0f9ac6d4e7771d2303ce074ad92b9e44b6b94743b9f10780fc46fe22f05eb971e1ad6245026684ee86e31ff3d23e8d5e5e332fa58a8b4cf7f2fcca741daabd0f55ff0086e0f8f76b6ed04767e19bf70b8fb698195bebb4301fa57c8c384a8ca7cccfad59b619aba3c9be2378bbe267c7bbdb59bc7de2369b4cb76f363d2ac488adb767ef6d03ae3b9cd7bd86c9f09857782d4f1b1d9b539694492d2d92ca08a1822586145daa8bd0015f4518e9647cb54ab52b3bc985c7d7048c123b8ae8717186ace5737195d17be1bf8dbc7bf05f579ee7e1e7887ec505ec9e64fa25faf9b652c8782caa7eeb1f515f398ecaf0d898de6b53e9f099aba31b33d37e20fed81fb45d84d3683259f86b43bb10abbde58279b222bae548dcc543639e95f3b85e1dc345dcf7bfb5a824799f827e36fc69f86da4ebb6ba67892c354935d9deeefee35484cd706465dac55c9e38c6076c715e8cf20c2baa9a473acde92672be1ed3df46d0acacae5c4972373cb228fbcccc589fd6beb2843d92491f3398627eb356e867897493ad68b3592cbe43965912465dc03039191dc66ba2a43daab1c3869fb1ab76741f12fe357c57f8c3a1697e17f155fe8b3f8734f9a2997ec3682291da35daa58e4f4f418af9aa191d0c2d6f6913eaaae71070e44551e4a1c20c295c609ef8afa592bab23e4a5525ed39d1bbe0ef8d3f14fe1b7c3a9fc07e1abfd19bc3b706e158ddda6eb8413125c06ce0fde3838e2be531393d2c4d6f6b3dcfb6c366d4153e57b993e14d2db45d0ecec9c65a05da5f392c4f24d7ab4e31a4bd9bd8f9dc554f6f3bf42af8f2f228b426918361090173c12781d6be7739a8952713d1cae37aaa47cdbaf5edccd657eaaec5e79d100cfdd500e7f363fa57e5d7d5a3f484ec918b7360afaac48f8f3214553cfdd5ebcfa75aa253b9ab6f2c82c5e40998cb614afdec038191ef5cf23534f51431dbc015bcc6694b176fbca0014f7219cfdfec64d41bef82aad83d7017f5ce0d4d82c45e12b38934c590c7fbe986431ebb4f4a7289d4f6345e733ddfd8a250485e838e4715328d919c23a9e8da6e95169f650c2c02c9b41f6248e6bc9a8cf5e9c4b315904576720ab1c819e47d054d3639c75317c41a80d32dc471a6659170a98c93ce071f5ad5e84d597316748f0d0b784dccc732677281d87a7d6a1c8b84343a0b6b52543a4224989c85ce31e993dab9e558eae5d49a2d327f30908ac482df31e83be3d6b9a556e6ce1a1afa6686d3905c041df8fd6b0948de3037edb4a8522208209c72ac4608e878a8e62f94d2d3e26499629518003224dbc37d2b2a932f94e920b68dd321c71fad72f314a235edc9241181eb4d3d0dac30c5120677e001cf152a7664d8cbb845ba50f044e4671b94707fc6ba1d4b99f2dc844b77664ffa04f703fbeb11da295db2b95223b8f127d991bccd2ee3006edfbd4281f42734733b19f2231bfe1606a77b38b6d33c3735f4833ba43708912f3d0b024671cf15ad3d4391104ba26bfa8cc25bef22d973bbcb8db7eccf403a648f5ab264872f838ef632dd3cfb8e4898f1ed803815b44cac5c4d0d205042f2a383d8d74731938dce7bc4b60aac4e00dc72015e9fd6b785430940f3dd75ef16dded03ec8093b951428600700e3ad6b09dd9c928d8e22c2da58af8bbc4192356e100e720803dcf7e6bd7a52d0f32ac6ecd9d6afdad348b690333b2421e12d8cfb67d6a2fa92b4466f86849369f35dbe4caec11989c01b549271f88e6b49150d19b2d7a874673860e1802c996c13c0071db8a56b1a459aba02a5dd8c681c9919cb6d0a76a923ef13e94c24cd8b906d60b2173225b8b80c605dbf7e48db69520676920920f4359fc3231b1eb3f09ff00690d5be19dec3024ada2d9050b32e92818dc107ef48ac769661f296182319e6b48d3e7992cf34f14eb36fe2af1b78af5d8d2441ab6a325d80fceddd80067bf4eb5fab70fae589e063f5306e40e41e78ef5f72bde763c05a33e9dfd8abf6cbd0be01784b50f0278eb4fbb7f0f35d497765a9d9c3e784f33fd6452a0e719c608f53c57e719cf0f4f1153dad3d59efd2a8ac733fb6278fbf67cf1af85b427f849a7c163e268f56125db5869cd6c24b7656de1d88031bb6e07d6b2c93098da15b96b68875249a3e745e1324608f7afd4d5dabb3c396e7bb7ec55f1fbc2dfb3b7c5bf126b9e2e7bd834ad53485b5496cedbce092aca8c0301c8ca86e6bf3bcff2cad8f76a68f530d3d2ccf35f8f1e3bd0fe297c7cf1d78bfc386e24d1b56b88a5b796e62f2dd82c6aad95cf037038af4f21c0cb0b4eccbc5d4525638a4c92013903b57d7cd7346c79f0763debf64ffdae357fd97efee34fbbb4975df01ea13896e6c22399aca43c34b0027073fc4a700f5cd7c066f92fd6f586e76c2a9ef9afd97ec55f1db5397c4f75aca786354be6692ea38a796c19a43f78ba005771ee56be4a183cdb2f7683d0ebe652346d7f6a7fd9c7f648f08ea1a77c21b11e26d76e14826dcb319e41c2f9f3b8ddb4139c283ec2b29e5f8bc5cff788972499f00f8c7c6be20f8a7e36d6fc69e2abb379ad6ab3095ced2a91201848d573f2aa8e00f4afd0f28cbfeab0f78e4c4d54e362a46067805548c118c9f7afa86b9a0d5ce383b58fb8ff00e09e9fb457c3cf861f077c4fe18f1a789ed341bdfed596e238eed597cd8a48d46e52010402a411d735f9467997622ad4bd38dcf794f63e27f0a6b69a5f892df54b27536d6fad096090a93ba313e55b1d79535f6596d270c1f2d43c9cc24a773ebaf873e2cd0be107fc144352d57c41aac1a3689a8d84922ddddb6d8f32c0aca09ed965efeb5f39996165570f28d3272b76a2cf2efdbbfc6de1af1efed4175aaf86355b5d66c64d16d524b9b46dc8641bbe5ddea1715a70d61e7875691ead695e163c1a405b073c28e735fa1753cdbda36198e4903e638e8339a52773090acc52225c614718ef5312628645233162a40046029e2a9ea6a90f3b9f059b9c7f09e2b1703a133eb3925054100814a3cd0d247e67cda1087c1c939157cab7395a4d88d3ef2020f941e98a4e317ab445ecac98c0123660c79272b8ea295a52d0779b561649c460a80483ebd68518f5344ec86b13b236ced23ef022aacba19ae693d045936c649725f76473daa251b6e75a9469ee3a44924059c82460e41cf14e2a282a5aaad0abdc818c9ebef5a38a7b18aba766c9a3932ac8c78f41eb51ac7a1bcef6f7480922452188c55c173a2169b8c91be639fad251698a4d113a92aa539c9e476c55b5632bda3dc5dc63910e47a1cd27696c691a92e656240e76f07ad248e8a92e620327ef70c703d6b648e1bf400c3ccce372838c0ef513496c547922ad211e5519da00239c01cd2517d0e9a2d475440f30967dc06188c1ad20adf123294acf4012e1c924e076ab959ec68e3ceb5185cca540c1cd2e5518eace6968cd2f0dc26f3c43a64400cbdd46ac00ea0b0158576bd9e869ccac6d7c59be179f11f5820642c8215c7a2a818acb09052a57b19b949338e6721ce3b7e55d5c894ae75a7a0c62aecace4641e2aed73272b3b91dc10c410471569d8539a7a95d9541c28ddebf5ad3960a3cccc2c9ea89918e30476ef53a4a3743e693d117a03b5411ce0572ca319c8da9282df734adae4491e1a360d8e093c571cd253f78e84dc77d8e13e2dea30db68b6d1c8c0169b0aa7bb0191f957c36772d6c7d964f4ef1e63c3a475b499e5b805e42015cf214fb8e991e95f9dcbe33ed65f0986991657372d932c932a805b2c412777d78c7d2b49304ac749e1fb1dde1e4b9122e325f696cb3286dbbb1db90719f4ac8a4ca7e26bd315ab5ca60895982e3a018e692d068cefb1c771e17fddb372aa1429e48039e7dab394accdd444bbb87d07468c2068e665cabb8fe103a8ad996b63a4f01f874e4cf21df3ac6afb18728a4f1c7bf279ae5ab52c6d4a3a9e9896e642410148006e3dfe82bc9a8cf5e31b124d6200704ee04649ec3e94a9b0946e731e1ed365d6fc7b77190a56d9418dc8182719c8ff1f5adea4ac8e5e5bb3d006884ea0903ccce814b36c50b83d8679cf35e7ba87a508591a563a5b06c95507bf1d4e7ad61291a72ea685be9015f791990671c76ae6750e9e5d0d1b6d3c16e131ee294a434695a69b800b2eece735171b34acf4c1336d602300e46eaca6c6d9ad6fa5210411f30e8c3a115ce8a4c7cba66d2553e651d322b44f40b8e8b4646e252ac47382300d64b726e3a54b6b75e031ea3084d6d1921462d9953477170088e158549eaec73fa0cd742921b8b326e7c1b05eb24d772b49839111e13d3903afe34db49038b04d3a0d3f11c11471c63a2c6a028fc2b4a52466d315906de7eee71c9e6a39b52ec5792df731e38cd6f191949584b988ac20281ce41fa0aa7233472dad590b804c8320295cfa7a1fc2b7819cd1e6be26f2ad42a80642a368c72d5dd08d8f36a33909a0790b242bba4201718fba71d3238ce3d2bb54eda1c4d5cced784fe48b5910a910aa73d86739ad53bb38e7a31d6852c2c6592474f2e58c8da5be623a106ba1ec53d116b43920925b68e4bb5826bc63024807c881559b271df8dbf88aa92d0499bf657f6f65636d7514534697f0aca216520c2a060f23fbed9209f43508bb9b3abe917ba5e9da66a370556cf51856e2c4ba90cca4e0f3df0df4c67deb2f8a652467d8ca3502c6fa568228f7932c0819908e06e04f4c9c66bba8e93319970c1716f1ec963dbee3041f7047515faae4cad13e7716ee53bb63e592c3a74afb2a5ac8f0a5a19ccc4a90412a7a83d2ba5c20b57a9d10a8d11342981889460e781d7deb05495efb1abacc748eaca485c1e98ae8b5b4672cc8f21f8233c75ac654e71d626b4a762bed0923150ab9eb8ef4e1050958da6dc871c0abfb56329681bce300e2b9dd3b3d48e668ab2dbc370732448cc0e5495048acea52e65ef9d54eab2410c68090a0823b8ae6f6577a21ba97038da3038e800aeb51e45a9cefde14a174273e59073c1a6fdef850a5a244325a5bdd49996356da72030ce0fa8f7ae79536be23b3dadac2b4660b49445c2c6030c0ee39e3df8a5ca9c794e4a92e7523d97f69e54f106a9e06f123a281ac7866d65933f79a45054939f6c57050a517cd139b0555c60d1e28b04514ffba5519e32a31935d54e10a2ed147afed39a5626da307202edc8c76fad76b5757317f15811c856272a7b73d45664c8266dcf9c90719fcbda9951446d319594ab9047751f7854a66890800c9e4f5ee6a877b1f57cac4a6d00063dfd6a373f2be6b8df376a952b9ed5a2443761f0b6c3bb93f87144914be12391589dca3393ce3b7bd248d12f705da198927a0ee3bd49cfba1a0b3c6198719c134ec553dc86450370070474a948d6a3123919460138239aab151d50810b6589ef4d17142954553f31dddf8e3152cb6472a6e39078c6303d6aa0ac88911142a3a923e954b7319e8339519c552329300c7a1a761c751124c9c9c71fa5435a9af3684655a571939cd6f6b2223ab14074c8c7ff005ab1290992c0903ebc55a46c9d86b44532dc918ce71d29c4ca4b51624df924718eb4499bdeeac46d192f9071f4a4dfba44d6a74df0cf4e59fc75a3803e559fcc6efc2827fa572e25fb839c7431fc5d76d75e2ad52e013fbcba91941ea06e38adb07a44c1a328a8cb658820671ef5baf88d62c89516560588550782686444264d80818c74cd5229a2b87280e31bbd6b560d5993c670017233ea6b360cb28e4703ad6361a342d9d8f049c0ae4a85ab9e69f1c23d96ba6b86de4c8cfb0f424a8502bf3acf9dd9fa0e48f9a2798dc2e34a7925460caac3648bb1837420f5e95f087d6256673d6492b451cc0030c311bcdbb720e32aa1bd3e6c5298a46d5edd268fac4fa6287dff00d98b6b315c7caeaa1c938e39663f5ac11514665c432eb5a6bc48511d62665576dab923a67d6a6fa9718ea6ae87a7cbff0008ecf2605c3db5a92703e5e0aee273ed595567772e861c509d7758d3ed4ed04b045c1ca85dd9fe59aed83f74e76b53da3c2c21bc4d43515870b7378d1a1c6372c5f206e9d0e0d79b51fbc7a105647452da3436db4e37960480324e7debcda923d3822aebb20d2743bab9099758f72467f8cf603f9d67096a54b624f8516515c583dea8602e1832875c15c804fe1926ab15332a103b1b3b256bcd4260328f36c8fd76a8033f89c9af3548f4631356cb4af9b73f723f2a89b29234cd808c0039cf6c56324558b1059850380b8a9bea5a45c8add446092055ca5a0345c8916240719c72062b9a52b9562dadc3380021fcb1cd4a6558b11472bf2aa067aee34b74431df647dfcb64fb0ace3b92437168db70ac40ea70706ad68cb5a19d3da3839f3a427d0f35d4e5a0dab94a7572a5589c7614f743667490b9dc7a63a56d4b43164620dc324d475088f583030477e6b68b267a914d1ed2c981d2aae631389f133cd6d0321655c9c9e3d0d74c1848f3b5d22e756ba219f6004ee9029e0e79c0f5af468bba3c8ad1f789aeb431e1cb74911026c05b9e4e7d49efcd74f358871b2386f1258dc79e2e6407cc9d7cc60defd0d694e4704e1a98dae188e9891421a37f282bb38e09ce4e0fd2baef73392d0afa75e5b696fe1c7bc4926846a4ac760c2ed2ac3f9e2ba2d789cee46f5cde3ff6af92accb2bc423642c46e50c31ed8cf38ac69ad4de0cedb52d50ddf846c6279602da45da7941be7cc722baba8f4f9b6b0f7a94accab0b6faadc58df2dfe9262b6beb281669209177c73c45b6c8aca4104953c8e3d4574c1835a1bdad6a704ba6c76919b7d8d0a5f5b228dbfba7183b33d5436415ea083c57e93935656b1f398a472175f3a600c63bfad7dcd17adcf064b5335f76eebf2818af496a67a0d2cac8a07181ce3d6aac522162c4f1d2a24db34be81c9278ed5126c505a91b2103241e4f04d25a9d4b61a48c9248a6ce66f51101639040c7269c5e82154024e7ea2b36f535423862db40c2f77c8ebe98eb59a1a11d470a79c8c67d2b46ae296a030015c6700e0fd6a62817c04284a8601188ce0b63a7e153240f58930c10ca369565c640acfa8a2bdfb9eb5f149bfb5fe007c21d684a6436a6eb479090382a77004ffc04015e750f771125dcf3684b96bc91e3c4a9da413b81c118af41ab1ec46430c4ae0ef66014f0c0f341aee3a4955495c6e01401f877acefa912dc6aa1277b000ededcd6cf635e848a102aac67190725b8ac9ad4d22b42168c963c9e0e2b65b1935a9f59188b498009e3a6ee87debce550fcce14df62390955391d0e315baa882a527d872e3c86208dc7a2e7afd2afda23349f28d59888b0ac1481cb374153ce8d927c80aa642cc809507a8e462a25348c6106c8a42548001fcaa155452834c64ec1594e02e4724f35ba922ab536ba0e003f4c9e3923a557322617b0c0f93b40c80326a14d1a41b103ab370723d7d2a5c91ab4d8d91d1492ef85fe11ea6b452b221a772bcd23280149e7be3b528cf539eaa681959973820819c11d6af99233945881815c1041f5f5aa5511bd28363e388488c08f9bd00e7eb597b4d4d654ac8aff00eaf9c951d338ab955496e2a74df61527de3e50588f958e3bfa9fc2a1cd0a307d842c3700ac571c9c838229aaa86e2fb0d677032bc8cf1ef54a68ae46d0446497900631c9271cd44a6694a9b6f543b71fe15ce738e38a39f4359d377d8ec7e10291e368ee4a165b4b59e565ec3e5c64fd335c78aa9ee1cd59348e275167b8b99a670e199d98b11d726bba849246093657586590f4ebd78ada53499b28b10a10fb644236fa8eb439a2e34c6cc9212084241f4156a439408763820f96de9d28750e595ee4e637de802107b823902b0750dd46e8b1e532be02eec8e0e31cd4ba86aa997e185d554153bbbe0570d5a86ca91e55f14ef5751d592d0a89228979dc768ce7d7b66bf3ccea5ccf43efb28a7ece2795f88357b89ee9ad8e5089950408701536b12c4f7e464fad7c8db43e8e2ef21962e23b4b86b9663046d15b246141de19896c83d71c564c5220449efbc51ad84703ccbb28148c865071f874acdab1d1495cdff0988c7899b4e9e21e62cde5b28c305c82b8e78ea41ac2da9ac56a6f780acbec7a34ba6347f6cd4a49ae74c36e84147ce53e63d00f95793c75acaa9db6d0e27c29a5791e22b8f3a308d65348846707210aedfceba212f70e64b53dd74ed28e93e11d2ade41b25b7b656972463737ccc4fe2c6bc9a93f78f422b436521900e58103a71c9fc6b866cf4628cabb8c6bbe2bb6d2ce4dbdad9c93ce71901dfe48d48fa176fc288ab6a4c893e172795a25c08c308ed6ea4b211b7f0ac676ae3f0ed518977669451e8da458a04c91b46edc47d7bd70743ad1aad088c0653939e98eb5cee7766891228328040e01ebef5b3d50da3462b1924c10bd7b9ae46f5291a56fa6ba747e472723afe14a72d01937f671c9ce3d47159ee17254b7c751fa5558ab8f00464f3815696864c56ca373d40acba920c85933823bd37b96432db6413de89cb42d6a655d5b947e57f3ae884ae86caef6cad9007bf35b465631640d6a818678cd2893b11b5b052481ff00eaabbd896559edc124e39ed55177222735aae8a97a03cc0641e075ae84c5229c3a65bc10b222ee901e0015df42470d58ea72bace9e3c59e29b3d0ad8eff2333df48186d8d5705509fef371c7d6b794ac44a2ac47e2df0c2df4ef14880858768217a80deb8ebfd2a6153538aa40f1ff0018e8896d0b44060646dc75539e457a3099cb389cf6a56ecefa6da08d8a26d9d4700bb06e807a671e9d6bd28bf74e0946c6d6b914d61aa17762d1c91473f98ac06d7653b971ed9a886e38bb1a3a1cc354171e5c0cb7a922b3b30e1942f047af14e4ac6d735b4d4bc5d4e7b7376b0bdc445848e7e50402406fae31f8d66985cd2865964d23454c158e38e58983004a8dfb940ee07ccdf9d7dae5351a691e562e1a115ca00580e9d335fa5d09e87cf386a66cd1b6de4fe55e8c26733a6cac00e4e71ce2b573295319c60fa547b52a312de97a5dceb373f67b589a597696088324803278fa57257c4246d086a67b1f314a8278269d3ab7344b418808071d071cd692a873b85d8d45396c9047f2aa8d5561728ac003919e3d2a3daa6ce88444382ca0fbd537617288e8ce415192074a14cbe4b8d6562700e0904e49c566a664e3ee800c0051f36ee77034a53051bc452c632aa101192481d7231fa7349c8d147667ac074d63f645ba8925c1d07c511ce576f559948233d46377f3af3aa4b93151f33c3e56b14d1e45e5995b70db9249e9c1af42533dc8c452e7ce91002c42ff000d11772b62310ed7d83e6c8c851d726a7662921c51b2a0e1c37040fe11572915d04202c78c3139c7cddeb394cdd6888cc810e3078fee9e2ad48e56f53ec87f027c3d98153abcdcff000aea6d93fae4d7e3cb32c5ff0039e94f2da1cdb13ffc2bbf8731411c4faac917184cdf313f99aa79962ff9cd6597d0e5b5885be1d7c39772cdab4afb480a4ea4704fb7a553cd317fce66b29c2cbec91cfe03f87244a926ad22b364e175527be48c13c547f6a62ff9c7fd97858fd938bf19f84fc2fa769ad3e87addc492a312e9f6df330bfec8ab59a5796f2216558597d93ce11eeac2e92595af2fadcbaee44ba28c5723386c1c1c77e7e95d0b1d59af88d1653858ebca7a643a37c3ebf9a22f61e2054656691ffb65988c0ea00507f0ac56658b8af8cd279761a5f646eade1cf877e1dd2e3d762d4f56fb147288af6caeef991d55f859541c6541c827b77a8a59b62d3f8cc3fb170b2d794dad3b47f835ae424c1e2649430e54eaec1947bf3814e79962eff18a1956157d92e49f0fbe174f911eb6f205ec9ab9031e9915aaccb1697c454b2dc2afb24cbf0afe1b48e93aea7701828e5754c823f91fca93ccf17fce4cf2cc337f0129f851f0d9a4dffdb97de60ff966354e178eb8acff00b5317fce2a995e19af809dbe117c3cc291abddfca3abea6707ea2b1799e33f9c165786b7c029f833f0f1a3e359bf6c1ce3fb58ec1571cd316bed90b2bc35fe118ff077c08e3ca3acddaa9e001a967f239acffb5b177f8cd25965097d91b07c13f00d9a10bafea312672c875252a4fbe6aa799e31af8c71cba843ec8aff0004fc00ce277d72f5881905353e31ea40e292cdf15fcc42cb28dfe11c3e11781324a6bb7ea5936ee4d481fc73ed5a7f6be27f98d3fb3a87f28917c1ef0329e3c4ba84b8e093a92927dfeb5a4734c67f319432ea0ba1653e08fc36bb5c3ebb7ab20e54beaa50fe5d2b5fed5c5ff3171c050bec03e047c3c8dc67c4b771f1819d5813c566f39c52d2e13cbe8be85dd27e14f81b4196496dbc457a0cb19858ff006a29dea7afbd655331c4d4d798e7fecca2dec558fe0778003111788f51895baedd5411f439ad166d8ab7c4754728c2a5f096dfe01f80de1013c4ba9b29f9485d5969bcdb15fcc0b2ac2ff294dfe0078081d89e27d4d0038e354563c7e7834bfb5f1bfcc3596617f9417f67df02c830de2ed5f8fbb8d59013f98c1fcab78e758c5f6889659857f64af2fecebe0ab8dc8be30d530c723fe264871f90eb512cf316651ca30d2fb2598ff671f0a1603fe12dd65bb1cdf2648f738e7f3acd67d8b453ca70d1fb25a83f66ef0fc2bb2dbc53ade09e86f93047b704feb5a4b3cc54fa8ffb328afb257d47f675b6b04696dbc57a8a3a82cde75c97040f519fe55c53ce312b764ff67d15f64f90fe22dccb6fe2f96c21bb6ba82da519b99a32865e324ed249c0c8039af31e2255b73be10f6670ba8d84926a9291337d99554b3118058f039f5e7a564d5b73b13b9ad6d28d4aef49d392dd983df2cd208d4280a5541c1ea46133f53593dc6d1a9e0dd14c178e5b69679de469194f39725413ec081585692d8eac3a1ba7daca93ea57b6f0b5c4f6f39769b07621cee01b1d7238c572ca56474411e85e1cf0e5cf873c5af713dc0d42db5548e7875058c2857c7cd190385ed8f5c75ae59543766378ebc3a741f1cadfa5a32e93a8f951b3a1c8598b05627d09ceee6ba213bc4c671f78f4fd62da3ba8aed203b9a350a006e0e3902bcc9692b9e9d35ee9a569009a34948c823a9ae19fc677525ee989e088249bc57e32ba9802e2ee1b58980c6e8a38c15f6eac6b5af25c8ac636bc8bfa0d9ff67f88fc4366a8c21b9996fa37c7cacceb8751f465fd6b9252ba3aa08f41b2b6d91c6080d81cfbd714d9d5634440672084e87b7a560b42a289a0d38a9322819ec0f7ad79ca91af62d1c63f7b9523b9ac672b99ec68c4e922e50823be393592d42e35c8cff2ad22b429885720f1c7bd4f529108c162a7196e8335a45e8664a01772ec09fc2a6fa8589427c8085273d875ac652d4a21921765c05c67d7b537b16958a4f64f2312417ec013d2b48c86d5c81b4d757381c0ec6a9c887a0c934a2724e456b096866c8dec4be33c606318aa4f53168a73e9ad0a1383c9fd2ae9bb137336f74c33444a80a474cfad753770b5ce0f5a4bf82e7c881fece1c6d3305c900f1f2fbff2ae8a32b1c935ef1d07807c209a25a188444098af9b2b02ceca327963c9249aeb9cae733f8c6f8aace29216318db212d8c0e463a565196a4d44780f8bedd277ba56dc240db48c60838af560f438aa474389b99a65b9b13b4168db192db4ed231f8fff005abd2a6ee8f324ac497b76d2896c4aee9236133b11f3608e9f418cd525a999d0f859ae22f0e5b6a511513dcdc5c59a871b4129821b3fee9e955266bd4d4bf8165d3fcc8d0497902abb985be7396c303d8ae0af7ac2c123abf07fc39f10ebf6245ada799b9cac323b0c124670704e338ebd38af670d8a7867766152374744bfb38f8fafc398a2d3d1c7de8e795a365f6e571f957d353e235056e43ce951bb2ab7ecc5f11d98a94d1fe53deeb9c7d319aef8f137f74cde16e4337ecc1f119304dbe88bc8386bfebf86da25c4dfdd1ac205bfecbff11a5701ecb4558b3cb2df12c07fdf22a63c4aadf08a384b16ecff00678f881a4ea612cec6d65b8563fbe8eeca228c776dbdfdab82a674eabbf29d4b0e919fa9feccbf106d5269e7b0d2e18865b6c77db989f4036e4d7553cff97ec91f5729dafecdbf1066b40c9a5d896600a937ca073ea08047e55acb88bfba3faba18ffb32fc49590b0d3b4b75200db1dfab127b9cf1f9538e7f030fabda638fecd1f1251908d2ace4627a7da931d6b7ff00582996a83e62bcbfb357c4d8d887d0ad5c6ef9592ed79fae7fa5692e20a452c3b11bf670f8938623418f7673b3ed6a31c7ae71f850b8829151a05793f67bf892092de1c2c76f0ab3a73faf5a4f882919fd59c9111f801f124727c3330c005479a83f0fbdc7e351fdbd4d8a18570663eadf0b3c5fa45db5bdee92d6d728a1dc199084cf4190dd4fb66b4fedea4da5d8d7d8dd9d3f87e6d53c3bf08fc5fe1ab8d2a6b8b8d6ee2dde111a16688c672ccc41c006b19e6d4ab54551743cb9e11fb5b9e791f8775e67f28685a8483713f2c2467e9cf35b3ce6923d0745d8d9b3f855e34d4e3fb4d8785b55b9404a864873f875e9571cfa8c49f62c7ffc29bf1fabefff008443560c383b6dd8927d0609abfedca1dc6f0ec897e19f8cd461bc1fac2e060bfd99b04fe7512cee8771c6958865f86fe35f3f60f08eadd0fcde49ff001a3fb6f0fdc73a4da226f87fe2e88ed7f0b6a9bba9ff00457ff0aafedac377391d167e829f82b64c0036df30e84a0381e9c57e62ea2ee7d126df411fe0d58960ad6c198f455008aa5557725a6f420ff8529a7363fd1151b9c3797f9f22b4e65dc12e5ea237c0db078f70b1418639f93692df80cd6129a5d47cbcdd4e67c51f0974ad2ad9de7d3828738da630727d093d33eb5a271ec54528f53c6eff00e17f889750922d274e3a85931cc23702cbeaac46471eb54a468da7d46e9bf0d7c5b73bbecda45ccbb4b2931fca0107047cd8ab9cd77149c48b5af835e2cb9d3e73a9f87ee238186d6699839e7a1c2e49fcab34d7708bf32efc19fd922ff4a82e25d76ca23e74ccf1ab2971b3395ed80714ddae6492ee7b9d8fecfba643122fd8113fdada3f23c62a9b44b8a35a1f80fa704056d86de8c554281edd39a2534ba92a3164adf0334e0a49b28c316ddf7413d3a72322b1f68bb8f962c747f02f4f95007b34f97b6d041fc4d4fb4bf51a8c6c3bfe1476960144b651ce4ab2e47f8d3e75dc8518dc1fe05e9a7730b248d8f42d18007d3b564da4f72d26ba91ff00c28dd2bcec7d9108eec631b6b57515b72b95bea48df0274c452c2d36ba8c82a8081f5acdcd8d087e04e9acfb8da85503efb46037e83142a8ca6869f813a46e502cd5b278554dbf8f4cd6ea48c5dedb120f803a64809365bc0e4655481edc8cd4ba8829c5b7b0c7f803a52ae4d9a2639dbb067f97350eb293d8a927d81be02e99260ad9281d7263047e1c568ea34894bc87bfc03d306d22c971ebb475fc054aa9e4572ab0d3fb3fe9472ef6cc029182aab81f9d0ea791108ab909fd9f74c2e0a5a2aa838dcb18ebeb9a4a7165d92ea20fd9eb4bdc1fc85383d362f3f90cd1cf10b27d46b7ecefa620212d2255639398c2feb8aa7553348a51ea2b7ecf56318602d80ce0e58610fd2b373429252ea0bfb3d6968b830e727392cdfc89a9955b7522fe66478b7e1059f86346baba807901626690b31da38e1bf0c735c72729bdc9b33e02f1d5c7da75db99c0371fbd397070095f4fc306bb29c9225ab9c66a3aa4da84a044eb1c5238872fc00c78033f5e6ae4f984bdd3a685e0d1adc456521bed4645f24ce55952dcb0da597fbc40e87a64e6b15b1ba573b5f006830dd59476f29791557687762cc703ff00ad5e75497bc76d04759f04b4f82fe2447b66305f597fa5075f94dc4733a127eabb7f2ae7ab512475c6074f79a3dc785da7b1bb05f4493e48255523ecee7a066cf00f63eb5cb195c6e273de3ed3af66f0c1b23bc12d1b4738604ab290558374041ad69c82a47544fe08f109d6ad651771baea31becbb40090afb47cc3d43001b8f5ac6b3b1db05ee9dc6936e268d6100e149ea31815e7cdf53ba9fc25cd23c289a4cb77240a42dcc8667ee371ebfcab9e72b91157645ae69a74abfd37510ffb852d0dc2e3aab636b7e0dfa31ac398e88a3b1b2b6f32352307201ae69b3ae2ae6bd8d89c60f73d4f6ac5c8122eae9c464e3a7bf5a49956b8a6cb2072013d73d8d4ca467256225b06520b15619c8e3a7bd38b334895a21927927f9d6b7b23462b445581ead8e6b252d4944b1a1ec33eb53ce32692318542471ce3eb4a32d4ab08065428639cf041aca4f5121cce49271ec4638fc2af9b42c6840dc8047ae077a5090d0f48b3d47039cfad54a467243268d0a803a91935bd3968645178738c0dca3bd6b17a9248da69baf99b804631eb5a27631b588ef7458d6204ab312bc05e315d7177417b18dff086c53aaddcb179f22be555ba2f1e9dfaf5f7ab8cac73cd7bc5bfecf77758d414461823d315bf35ce66bdf30fc4fa4afd95d803b94601a952d472573e71f1f5814ba977121cfcc540e4d7b149dd1cb563a1e7f3ac524bc860500dc08cf535ead13c7a8ac66f8919ee3c637578e486bc854901b1860bb49c7a703f5ae93089a7e0cbcfb468963a46a0c561b1d55efa364182d1bc7b581279c939fc00ace45f536f51b9115c472d83f99f388ff78b9057077647f23491725a1ec7f024b4aad6fa7dd4da7dd4382630a763a1e4311c8e08f4c8abe7bec268fa15346f16df98c0f10cb1315ca8f2d42b83df7119cd529b5d4cf950d9bc23e3730b2aeb370dd7e6da9c7d0e2ba2331a8dcacde0df1a8daafaedd3ae0fcbe5aff322894cbe4189e0ff001a9c15d72e14038070a09f6c62b28b05101e15f1cab6535dba423a821401fe35d54ea7283883786fc6ec3e6d727660392ca841fd2a653b752794a73783bc67239ff89ddcf987827e5017f0c60d65ced94a2243e0ff001cc7854d7ee413f79c85c7e5b6ae33884a2b989e3f0cf8f1373aebd7014f27f72847ea2b4e7438c5730cfec5f1ca838d764959beeee81463f35a9e7b97caac23697f10624217545271f78dba9e7e8451cc6715a942ea2f89310703578d9d47dffb2a82a7f0cff2aa764369459c2f8fbc53f1a3c31a3bddd9dcc1792c4061069caecf9380700e48f5ac5c8d5c53473d6ba56a1aedb4373acddb9d56450d713a42c17cc23e6da0f4c74c554b45cc4422b98d7020d3a248550cb9c0624905bd7902b4a55b929b33705ce630d3229efd655bd9677590f976e599b61fee83fe156a7729c11e83a06a7e3bb48cc36b058ac4398e336c7193ebf35633902a68e8a3f12f8fc0657b6b17c0c7c90951efdf8aa529771b8a241e2cf1bc6c09d3ed1c631cab93f860e2adb7dcc9c11245e38f17451b6fd22cf3d9503b16fa9ed59292ec57226393c73e275500e8d167fd976c569ccbb18ba67d98fa7056c841cfb5793735b80b00171b00527b2814261717fb2630c408c0c8e4fafe35ab90c89f4945c011ab67a8c562d95711b438245224b7574c60a3804568a5625b29c5e0ed32ca5696db4eb6b677e5bcb882ee3ef8a6e409938d060ebe444a49c93b07e14dc8a6c70d0e25e7ca4665f45c54a910c9574b403022118f40b8a5190ee386928adf3246c7ae40cfe75af38ee4e34c56652154718fbbce2b372b9028d31724ec0d83f4c1a94c7711f498a4660d12b0ed914ae098bfd91080008631ec1452b8301a54006dfb32fd02f00fad5730ee3c695130c3460800638a9b85c51a4c4b93e4a2e7b6de293624c53a620cfee9493e829265363869609c3c4bebc8ce2af9886c5fece80103ca56f51b78353cc098a34c40e18c4848e84af3487701a64637130aee2720edaa4c9b87f64264b189771e3381d2973036074a88e09894907fbb4730263bfb2616756f254e3b7ad0e41714695112098571e8062a531a61fd9301503c84653eab9c557314d8dfec58430fdca63a81b78fcaa79886c9934a8719f21739c9f946d3f85437a0ae796fc6cd316f3479ecadac5247923659ae246db0dba95e5e463d54024ed1cfb5441ea51f95be34d02c7ca9058ddfdaedad649fcab910988dc00d82fb4fa90001e95e9242bdcf31fb0f957567111cc8c6475dbf771c0c1f5ae88858dbb3b522ed1d830e8a98f7f5f6ae75b14f53d77e185a0f30829f2b63014601e79fff005d79355da47a3451ed1e1ff07db69d76f3edf2d641bfe5e0038e78af3ab3d4ef46fea1632226f4c18caed753c865f71dea633b213573ce7c6de04966d3eea7d36fe4b36309db6db77c0e47206deab9231c103dab1556c7438dccaf09e89343710dd4f62b69712c6a251101b5980ebc7a5673ab72e31b1e9fa6698b13c5290364a30c00efdab96533a4d768150e1467dab9652348c4a3aa6966eed9e268c18e452aca076ac39ec744113e810cb0c2b1c8b9da36ff00f5eb9e733737d14a11c118e70075aca32ba2ec59404e761c7ae6a79b50b1204c7246eef9a89484d0df207190467dba511919b891980b07001041eb8e2ba64c6c16225813c719a517625122c4c4838201e335ced8c7188e3a77c023ad34ca429b462a36e303da9b29684b6f683a124927d2b26c96c9ce980e085f9b1827daae9ad0ab90bdaba0c1c1c71900d3b6a4dcaf24072727391d315b5ec64d1596061c6d6519e3d2b78c8491b5611a08be65f9874e6b5b98b45b3602e49c2606335bc6462d10369c63dca0639e322b58b32657fb0ae3730c103b0e7354d98b4616bd688f1b909c630013fceb56c51563e76f8b7a0f06758fe704e08e8467a115e8d1958cea2b9e27149b669ada50acd21c2c8a30548ec7d47eb5efd077478b5a36656d4ec8c9a9c72eef95a1683cb5e70f952b8fc8fe75d6ce5653d2c8d1f573380ae5a175392c0ab2b03b7d3eeb107f0ac5abb345b1b5e173e6c7744796df6765970d9dcaa49195cf5f43dfbd68d683833e87fd9b869979e29b6b9bb66b3fb615b09e58080f036730cc3b609f94f1c86e68b590da3f41348f87f2a22daea220bc89171e6ed0add382a00e0fd2b979b533b1b23c216b870d131238539e08ff001ab53210c3e0eb2cf313e7afdeeb8f6aa73364c8dfc1561300c626627904b722a79c9b89ff00083d81e90edc721b03f2aa530b8d3e0ab1c0cc4c08e33c5439b1dc43e0cb30721189230083cfd3a50a6cab89ff0008369ca8316ca1987241e69ca7a8ae44de09b26e0232700ee0467157cfa140de0bb111aaed62a79218039fd2a253627218fe08b293828157fbaaa3f2e6aa35182209be1fe9f80db33907fc9ad255583772bffc2bbb06c14894363925464ff9cd65ed594990c9f0d34c6259a3f9ba61540156eab636447e17e96ebf35bc7919e7cb5e7eb50aa312284bf07b4a7984ab6b6aaebcef6886e1ee38ab55594d9713e1ad9845009e0ff7050ea93724ff0085696801db2ec247fcf31fce9c6ab29837c39808e260e7a61d4f4fc3ad5fb5664d0c6f86b6c0fce540fe124126b15559af4233f0ded90fdf5e79e41ff0a7ed99cef73d6fece339acac4086dc2ae304e7d6b448077903b66a49b911b5da381cfd7ad1628905b7cbc8a4d12d89f65c9c0006460e79e284098d167b4631c63b8a19571cb6bedb7f1eb5204bf66181c629c0cee28b40c18e3a724d39157105b90dc8047d284171c6d881ca81f4aa6896c4fb3e4e48f6a8b026396d4e703033df19aab163cc05415cf5a5622e392d46dc6326a5a0b91bdb8270067d31cd5b424c5fb373820f1d8d4a453649f66523819a2c26c05b039241e054d813156d831c8238ea29a1dc77d987a54b64dc3ecf86c8e94ec4b62fd9ce48185c8eb8cd1604c708028ce39ef8a9636c0443390013d79aa486982db8e723f2a2c2e60306571d6a6c2b8155846583e0f002aee39fc286b413763c5bf6a7d49ad3e186a72b2b456915bb968c9da666c81b47e04d4d35a9ac59f941e24d4a6d46e3cd7090c0b1e12141cb30c9cb1f7e074af49bb1491c5dadbc977771cee499554330e06327903152a4127634ed6397cf776704c6fb71f5e7229c76083bb3de3e16d933c56ee541dca1891d07b5787887691ed505a1ef5a45a85b700a8248cd78f39dd9d8d1763b3dbf7c0643c726a5bd0a4859b4bb768f698958673d3f4ac1cec6e9149f448d250161001190300607e158399aa892269802ec0182f503d2b9273352e47a6055c819e3a9acd4ae6f14598eca3dbca838eb9a893344ac3934c48e42c8806e1c9c75ae4933444d1da0dc460d14cbb9325933b1017a77a6d6a1727fecf1d08cfae2a2485711acf0a00007d07f5ace3b85c8fec65c6325475e95d37148516438000207af5350e5625025ae7031ee2925711723b22d825463a918aab58a44f1d8ed1d3e527f2a454890da24670410c7bd5725cc6e539982b819c9f4ad608ab91b20638ce3033cd3e5d49b95da105b6e060fb74a524293156d00393c569033e62c42817803d8d6b606cdbb281562dca40738e7d2b789cf264ed651c80919693b93fcf1549d8c4c8bfb528491f9019aa4ee4d8e73518838604753839adee36ac79878ef4217d61709b0062323db1d2baa9cac676b9f2d78ab44365ab3150146ec91ef5f418699e562636650b4848be5b96e14306cb1e15c7dd6fae6bb5c8f2d98f7ab2c5ac5eda3a6d50c5880782f9f98fe3c5545dd8e2f436f4a448eca0963ce4b10640300f62bef8e6ba24f4127667be7eccfa959e99e2b9d35511c56b751797b5d0b17c0242ae3f8b0491f4c566de86f73f507c1d129f0c582a5ca5d46b0af9532b643c7fc3d79e07ad70b5a9cf266b3db83d4679ab4884c8c5a83ce2a9a2ae0d6c003c67e94728ee33ecc49c004926a6c1718d6febebe949b44a61e40030429a132ae37ecf8073938ed4496a3016c87f802e4f200abb6855c60b607384e01c7d6a6e6621b3db9c8c67b7a555d17190dfb20c74a2e098c36c070a3150d957b0d163824b36eee4605526989c869b34563807ae286d02903598ea3e5f7a94c7711ad46ec119c0eb57744dc3ec8187000cd4a669717ec600201ebc7e157744b620b41bb079c0a2c86a5a00b320601e2b3662d9bab1e73c56ec57144273d339f5a113717c938c1eb524dc410600079aab9571e23ca1c807d334a42b8df2c2f40381c5110b8e30f98bc8ebd8d21730be4851d00ed814ac171cd0e00e3a1a6881de50c118a96804301000fd6a922ae0b0f1d38a1a0b8e30f23140ae2a41b7b75aa417058081927bf349e82b8e318278ce2a770b879431c814da15c618be6c81c50915724540a734ac4dc558c01803834582e22c417240009e334215c5f2f3c91d39acda0b8aa0ef1c641ef5aa26e2ac417202601f4e952c2e215278e2a520b8a22c13c0cd505c50800c54b0b808b07a715482e017823bd24b406ee78d7ed43a35bdffc3cb9babe5c2db612dc37dd527ef3edfe238e003c53a5b9b40fc92f1058cb2dc5c4c80604a5430e51724e3047b0ce2ba6a1aa3968e726f6489599914601618ce3ef11eb59b224896dee11afc1042aeece48ebfe3571d225d35767d39f0834fdd650cce4b093939e702bc0c4bbb3dda0ac8f6db585420007cbd0578d3dcea65f4b60c48c703047bd0de85204b42e7041c1cd72cd9d1144dfd9c6165ddd0af4ae66ce9484fb28538c0271deb0926343c5bee4c0e39ac76364c7b5b8c630073cd0b545a65a86142a063007a565cadb2d93fd9517032bcd31264d1c69b8024703f0359c994d923c38dc481cf4039a872b826557c150082067e86a5206462356c906b7453229131c8e081532218f89818c3e0649c734d4ac5245c8ae91010c715a5ee0c81aed0b719ebeb56a37327221d42e590290723d7deb454d99dcca96f0ab02a3a9e6b78d3239c81bc43042d8970a338cb1c735afb221cc8e5f13d9321093287fee81d6b4742e672a97284be2cb68783300d9e30dd7f0a150215414f8c6000812052063a82335afb1760e72de9df10220ea8d228cf009e47ff5a8853b31733675da6f8862bf52d0bab0ced22b39a1162e544c41ddf29fe1c63f5ac6f6039bd56d8c64938e4d7545e869277394d6ac85c21c8072307eb5ac1ea7333e79f89fe15293493c7133b8048c0e40c57ad427a9cf5e174793e86e93bcf68ff2ca013b7a1241e47eb5edc754783523666778a02db6b497731579658d776cce49c64e4552dcc60c6e9eeeb6525b827612648491900b7a7af5ae87aa376ae7d1bfb2d68cbe29d7e338679f4e81a5923e46d40caa5d4630c416c63d0d4cb6296c7e98f827449b47d2ecd1c39448b0aa46e45c9c8da7a8e3b1e95c92d4e46f53ac5264c86428dd71daad11717ca240c0e49e954c2e0d0900820834985c43095c1e9422ae35a138ce38a963b8d1027528095e87d2a905c68889507181ce0e6a98ee02124fb54a0b879472076eb498ae33c8c600f5a92ae344606463b55a0b8d31007200a1a2ae2797bb2280b0df2413c520b0a2218c139edea285b0988f0860476fa62a56e521a21e98aa0b8be4e724d30b818403c0140ae288411c8a80b9acb100a46339add1cb714420631c50c9b8e0bce31598ee35a204f0302a877136e38038c502b8a8a1b918c63ad0171db40c63b5310817e6cfad3b00ed81b823f0cd242b808c139029b4171db0938a11371db063d4d0c2e2346491c76a92ae0a8703d2a905c714383c669489b8df2cee38c9fe95310b8a50e3dead8ae010f719a115717cbc71524dc779631d3b50171360c7029a42b8e11fcbc8a1a0b86c039c5343b8aabc600a96170f2c63278aa4857144631c54b0b86dc75c0fad4b26e298fa71f95520b8c30654f63db1425a0ee7ceff00b5dadfea7e03bed334d4166d1a86babc76cf931b10085f56607181c8e6a69e923b29ea8fcbcf18cf1dac0cb0c7e5db12d127182083cb1c773ebf857454dcd11c56987edbaa5bb3a28b75f95b8e808c927f2a9e82687e98c6e7570c8a40dd855c741db1da9cf489ad05767d95f0e34b167a1d92800332672a31c1e6be6ebbbc8fa0a6ac8f4ab384f031cfb9af36a146bc3110149db91fcab26f4348930f254120807a8c9ac5ab97164536ae9182242a42f39c8c7d6a5533a548c49f5d819d8f9aa8b9c0248c7e152e9dc5cc46dadc58c09319ed9a9f621cf611b5c8836379e7b0352a8d90fda932ebf1c477b3844c60166ebffd7a954cd6352e581afc0fb4ef241ea00c7eb593a65c645b1aaa8dbc15ee3b7e35cb381b5cb4753df8208236f4cf358b88e2c635e87239f9c0cf355145b2137615fa83eb4ca647f6c0f919e7b8a97a90c805f98dce08c039acd95733753f13259a17601bb019c575d3573393394baf88ef1bec8543396dbf31e147ad7a50a673b33e5f8d105bb35b5d2096407ac23a7e6719fc6bb6344c253b15350f8911dc440dbce70dd98fcffa5691c39cded8e0bc49f11654658de62c09ca8dd90a73dfd3a574ac399caba300f8dfcf95f65db212bdc6e03e98ae958730f6d722ff0084b670c0bdc09874fbd9c7b8abfab20f6c6b695e2b6c98e5ba51bce70c474f6ef43a4ac5baa5e9b56bbb4912489d8e082467b7a83d2b9bd9a4cd6152e7a2f817c64f188cb3fc9bbe603d6b9ea525634b9eb961e2182e62043761d462bcc942c58ebc68e784963d3a62aa2f41dce62fa2241c9c715717a994b43cf7c61a1fdb01daabf535e8d2766677e647cb5e26b09747f14c8e14210cc148f4cfafe15efd195d1e5578d8c6f12db4972f6770542e173907a8cf5fd4d753dcf352b16dd0ac3e1797785f36392398c4bc8dac47d33b48adfa1699f52fec3f6ba8c1e2fb5bb82093ed726f781dbeebc4b232b21e30790a4f3c54cca7b1fa85a74c6ead1270aa9bf9f93d4704fb5628f364f5242096390339a105c6941919e7154d05c455209c2e727b9e94985c1d3b91422ae2041b32054b15c698401d2a905c68425b95031dea9977031e0e0d485c19704607b1a8624c42aa7807a5495723f2b3c90735712ae47b03124ae306b468ab8a62e4003ae2b30b82dbe39ebf5a02e30c3cee05be83a50b61390be59c03b4027d692dca5210c39f4a617030feb405c43116238cfae4e2a6e171fe501c6d1f9d215cd12a09feb5d08e6b8be567079e9d68648edb81c7a7a5405c464db8391d318a02e26d18cd5a0b804cf18271ed434171de563d6a50ae0b17af5ed54c2e200a5b68233e942d8a6c91502f00726a56e66c50877039c50171590f04004679e7181541714c6335282e013b56882e0b19e7d735217029e9405c46841c134315c77960ae1ba75007ad082e0500e714b7005425ba718fc68b1371eaa318e95360b88500cf22b5417011e4631c54582e22263be47a5160b8e2833c8a2c2e61421000c5160e61db47a73430e61bb727a11f5a90e610a9c641c7349c46d9e13fb4df84b5bf1de8f069da5dd4505acec5a48994991d55492e0f45507d7ad4a763ba933f307e31f86e6b6f16b69d09dd6f6e56d6163f2f98547cf237a12c5ab7b9acb73cda15586f6ea38870accaa38ced1c0fc6a824eeac4de02d2cddeb96f06e76766190470067ad73d77ee9d5875a9f707866c459e9f04441fddaaaf4f6af9b93bb3da8bd0ebed4023247b66b8272b8d15f57d762d36dd9d9d1768e0138fce9c2372d33ce356f8a51ace608253238fbcdb8fc99f5ae974ee116737a87c44992e021ba0415fba4f00fd2b58d02dc8c49be21cd1b300c393d4f3cfae2ad61cc555b142ebe22cf1480b4f851c2b1380c3d056ab0e652ab72b27c5a7662df683c019da7a8acde1c9f6e4a9f1865e142bf071856193fe1583c3b378d535ac3e29bcd2f905dbcb71f749e9f5ac5d13ae13d0e96c7c79205086562321439391f4af3ead1773a2123a6d23c5f23fdf70c47182d8ae29d268e84ce8ad75c598820f3d0f3d2a631b1ba277d4c6e3f30c74e959c8d084df80d90723be4d4a8dc86ca377a99504873827ee93de88c09385f14eb8ea8ceaec4827e527f5aeda30d4c64cf309bc4334665de4962ccdbba6149e303db35ebaa67254958e07c51e2fbddf2223bb5ba8ced0029e3d715e85256386a4ce365f1fdfdb36eb49995d79dbd3f5aed54ce1532acde3bbcb9999e672391b50b643d6ea065377268bc4b392196709f37cacaa72a3d307bd39025a17b4dd52fdd76c7b99036598af26b9653b1a422ee76da458cb3947973f742edd9d07a0ac65511d6a2761a6bcb0128eb2ec03237290028f6ac7da2345136218a4b19567858ed619da4e3f4ac6f735b58ee7c3fe289da1452db5b182735cb52172d1e85a278856e9044eff31e41f5c56128858d3bb0258f3818f6a5156336735aa5a798a5700e7d6bb62f4333e60f8e9a2a693af473a293e672cc1781ff00d6af4f092d4e4aeae8f3a8425e5b341238663b546e6c60e7b1af6923c56ec2493c89650c5b7315bde18d777f0b1ea077c1dbd7dab4409dcfd03fd833c2b26ab696f24adf64361752b5b3c40384493e7319cff09604e7d45653dca9cad13ef6803c6a11d0ee1fc4bf74ff00855743cb93bb17cacb67a1f4ed42d02e26c3838193d6a9ab9570dbc74a961719b3700738a90b8c09818cd558ab8153d0d30b8d2a4719e69c877136938ce39f4accab8d9179eb4ec3b808b278ea7bd4d82e1e50424039c7e55282e3563c6413d6a9957058c727af3c54b420c1e8454a011500cf7356c00a02dc83c7438a940232024003eb4ee0288c0ce060d4dc045504e28014a73d4d55c0b98c8e0735b239c7a260e30050c9b8b8eb839f6a760b88149ea28b05c511fd05241702b83c1abb05c50a71ce3f0a86171c57d40a94170d841ad12d02e28424e4f2454adc4047cd834805380bcf4ad2c00c319f6acba80d11beec82001db15a201e3ad493714839c633405c423be2a98ee1d0d66171700e0d11d418b8155224307ad02b8e2b9c7d6a82e017d69582e1b79e94582e2ede4f145842e3da8b00d23ad0876140ebc54b0b09803b7154f50b18fa8e98337778dd7c93be463c90a095518e801ebeb5cf247442563f28ff006978e4d1bc53aabddc39b83d3e65e0b36e51c72320e69459e8f2dd5cf9ba1987f694ae17961db903f1f5aec891057675bf06e0373f106da0c16608cc770edb8715e7e25da276d1dcfb6f4fb5f2d7207ca3eed7cddeecf513d07deea71e9f0b33b600ebcfe95cca3766a78c78efc4f3ea772122669177636a9c2fb5775388ae709776ed0a9965b93196396de2bb9450d1ca6b1ada69c09470598fcccdd4d6eac8c6726717a8f899e5dce1dc02c32c323a7bfa56cac71cdb461df788ae2565041381cbeecafe75aab1cf76ca33788a539e768c00483914d4532eccb5a7f89a6b7249e4704e4718fad6738a348b68eaf42d6e67983bab795ce1d5ba0f423fad723823ba9c8ebe1f104881fc994aa372467201fa5705482b9d70933b3f0e6b8f2bb932330ebf31c62b86b52d0eca72b9e89a76b3b946d7e4ff002af39c6c76a37edf502c010406c6339e2b9dc47cc366bd9b924023d71c53482d732aff005231824b74fd2b482b899c4f8a2f818e4048c9ef5d34d6a73cd9e67acca76a9072464027ae335ec4753cdab2d4e2f54d3bed9236f661cf507afd2baa28e76ae8c51e0d133be5e4650df715719fc4f35dbcc73aa65cd3fc14f2364464a83901d79fa0a3da14e0775a1fc36798a3b4408249e406c0ae7751b358d3d0f40d13e1fc56f9062183c9cae0570d49b3a29d33a4b6f08d9c4dc44a8c3a1c563cccdb94babe1a895bef671d3358ca4cb512c8d02129962a001d003551626887fb28dafcd094ee002706adb211a7a4bcb6f346189524e3af4a728e851e83a5ca2e2df96cf18f7ae592b18488efed433060327d6b48bd0ce5b9e19fb46f8744de1f176170d0380c7d8d75e1a569194d5d1f36d9db812f9ac061181008ea474eb5f589687cfcd6a741a1aa5ecd6d09859a69b5189a442b9182d8047fdf469d8503f5d3f676f8776de1af05e8f73a7a797e4a02b6ca15029272c8475eb93839e4d73cf722a3d0f77386c10301be6c11cd6ab63ceea26067e9430b8c2993df1f5c5522808c0e7a54b1dc695c9e062a505c8b1c1e3bd68d0ee29520018eff009d66c2e359327238e808a6cab88c841ea7f2a9431847ad50ee37660e3ae6a6e171dc64d4a0b8d0a33d2a9a285c0e9fa50d0ee20033d2a505c6ed27a03cfa5361714f5ef5282e047a52b05c6e09a9b05c07271fad558a03d7afe95205fd801c67b56ece6b8e0a303068448040a73556002a739c9a2c00ca42fcbcb7a5500bb3db2680159463a5215c0f5201a90b80cf7e2ac91c318e3b52b00813e6e6a40763248154023543402e3b509006d22aac021c671458036eee0536026c3ef59b40380c62b48a00dbe99fafbd2900ec7b9a2c48b8dc08c9aa001f28e4d002e0139a9b00640356805a1808467a1c1f5a400a0e39249f7a9b006d154053d4ec2def2ce6171134f10524c59203639c6075aca48b8b3f37ff006c0f860745bad5afb5830beadab4f1cb67676c0fcadd563c7560b1afccdd371005636b33d6849347c61e4a2cb2ca55410c546d3efc8fa576c5e852dced3f67fb46bcf8b76e17e641692bb7afde51fa66bccc63f70eaa07db37118b5b40e06154679af9db687a113ca3c61aebbcd2c684e1b3bb9a8a68dec708f089662f9f9875f522bb2f63451b94af2d669f2362375501d33fce875197c9639bb9f87771abcc4c91021ba1ce050a6cc25133ee3e0c4716e7dc51ce071df1ebd8d0eacae3951ba39fd47e1398481e590a093943d4d6eaa4ac73ba3639dbcf020b5650c9b88f4efee6bae353422702b7fc2228b264820e07cca4839a14aeccd42c68d8e9c6ddbcb04aa91bb39c93f8d67295ced50b2362ce0c30e791dbfbd5c92d4de9a3b0f0fb0824c9049c6dc31cf159d55a1d549d8ee74ed54478031907d6bca9ad4ed6ee767a05e35d3101783db15c938d8a376e2d1960214e49ed5921dce5b598dca3294208e0fad6b4c2e70dad40ce1949c8ea4819aea83d4e5a8703abdb957c0e99c9e39af4fa9c7285c860d24b05778c1c8c8c8e6ba7a0940996d604c00e09dc7af1cd68a57337134e0bed3b4e8ccb71222051bb2c474fa1a77d0394e834cf8816063c594135eba90a7ca8cb05fa91c7eb5cd19498ec9337ad7c61a9ba6e4d0a7643fc6ecaa0fd077ac5f3366f7562bcbe35d476191f499a31e89876fc81fe54b964c2e90d8fe21c71283730dc5b8cf59a1655fe55cf252895746de93e35d3f5103ca9e27f550724ff005ada1e64b674492c3728a500248c8c56ae37d886c9e055e0e324738a4c675fa14a0c4a0f5c735c5367348d1b801c803923daa60f41d8e0fe2ee8c352f03ea4bb031588c801e876f3fd2bb293d4c648f902cec8dcdd9802ecf95998918070a5abea294ef03c6ad1e53b8f851e1c1aff008b742b099dd16fee84084601ded92a727b295ae94bdd39a1a1fb1ff0f2c2d4689677eb19b6babdb789ae143655a40a149f4ce57b7ad72ad59cd519d8b72307a1add1c9b8cfd7d6ac4285c7426801a5739e48a40348da38352d1571a50919f7ab188461480464702a240204e339a00424e4d051195c9a0ab863bd485c6953938a2c310ae7a139a2c0215c83c91f4145805c1fad1601a541ec734d808013907820f00f7a92ae039e083f88c52b084c0eb8a2c5a62e30d9f51430b89835160b9a3d48ae86725c061b80471c1f6a113703b830c00477aa2ae1f51405c70071914ec17118648a2c17140c8e94342131ce0f5a901d400a07a0c56960148e78a9b009337971ee18c0ea36e49aa4805c67ad43448a474c0a12010f4a0a0039cfe9400bb481c9a762436f24e68b006c39ebdb18a401838c9a4028e0734c05aa01719e848fa5001820548098e69a01dd3d286034e7b5226e2e38cd3b05c00e29a06273838a968699f3bfed21f0767f8857526a91c264d5a0885b6928ae54248ccbbe66e0e70a180fa8ae7a9a33d0a32d0fcdbf8b5e01d47c1daf4ba6dee9d1d9bdbb340fb194ab49bcff0010e18e0f3d2b44f43ae259fd96b4d49fe29dfe073058b86caf0bb997bffc06bcfc5bf70eda07d77afdab1d2d8ee0011c73cfd2bc44bdd3b51e5f3f877ed57059d4b03cf233f9d115635b98d7f611594ad1988a91d4d533aa9bb942492ce004fcbb875cd26d2366cc5baf1ae97673794d731f9c3fe5929dcf8f5c0e6a39bb0945318be299ef8b7d9746beb8017702d0ec523eac40ae7f7ee5b68cfd4353d65e123fe11ab9e49c379919c63be3766bb62a7639e4d1c6ea1aeadacce9a869977038192ed0b6c19e9f300457446f621c5332a3d734dbec98a4560df283807f03571664e2847b6b770db5d71e9bb903d29b46c9e824168230af95c0e33e959b4694cec3c3fa7fda7e76238e3d7f4ae5ab23a69a3adb3d14330118f7e062bc99cb53b2c7a1784fc3ef942cac173c138c66b9aa4cd394ee6e7470968378208e703b571b993638bd6f4f00c85b1d4e38ad69d4b9691c1ead629e5b63ae7afa576c1ea73d489c06ab6a012cc323240e3ad7a4a5a9cf6306ef536832a1805e4f4c118f4aed4f4033ed6c353f10c9b2d8429192774b29da07b8f53ed5a415ce39c8b91e8da1681e5cb7d27f6a5fc6777992b0289f45c60574344290b7ff0015ed2c6e7c982dca1daa46d8f031db01783554a31673546d3093e3d4f6c8aa2295d8f04b44383e9835d51a517d0c9d46432fc7a13ca8ad133367041551b7d73ffeba99c231e853a8ce8b4df8b569732469208e75e30addff000ae36a322a336cea5f47d07c73034b69b34dd4c306596d084724763ea2b29d253f84d554b993a2f8ab56f06eb09a76b71161236d8ae517e471efe8debd056697b2dcd93b9ea761a925cc61d086079e2a651d0a4ee763a25c02320f5e9ef5e7d44538dcd727ee91c64f3594492aebb64ba9691796e472f0b2e08f51835bd37a98c91f1b416082f754e155e08659541e4e501e3f1afa8c3af70f1b17a33bbf05f8675cf1143e18d574e81e29d668ef2e85ab66780632c5401c30c1603b838aea94ed13051f76e7ea77c0ad7aef5ef07dafdb2d24b29edd042f131dcb201ca4ca7d194e48ec7e958435382b2b1e943247423eb5d5638530c1abb00a171da8b00847d6900c2a41efcd22853d071d29a1f30d238a1a0e61bd7a52b008cb9073c7e3458a23da03923d31416017b01520017039ef400854f6c7bd0001466801a09e868002475f4e38a4c063f2c715250814f5eb40001c9c903de8188393b81c71d314d8808c5401a7e51c86c76e6ba6c72804273bb18edf4aab00ef2fd05090ee214e3a734341714a71c5210797ed556017650d006c150e201b064f1d4e69d85700807205160b8a4639c5160b8329f4aab05c0ae3b5160b86d3c714582e26dc0c1a8e50b815f6a760b8ec1aae50b82823ad1ca1714814388b9842bdea794398307a51ca170db8aae50b87228e510bb7079354a20038e868e515c31ef47285c02fa9a3942e18ed8e29f285c00e318a5ca20dbc63b51ca55ca5ad433cda74c968425eb2958652a094278ddcfa75fc2b9ea44da32d4fceefdbafc0f69e1ab7d0e1b2b7967b95b9792ff5008cfbddd4e77374196e7eb58c743d6a2ee8f23fd9574c07c6de27ba40563fb1c3b46738cb3641f4e4570629e87a34753e97d52506ce40c070b904f6af194b53aac71de4aaac8c060e33cd6b7d0773cabe24f8d6df41899c8df3160ab1a0dceec4e15540e49271531d59bc65638f9bc3d733b2def886fe4b48f05bec71b18f70f46239e2b6587e65a9a3ac999d75f10bc37e13458eca24518c9dab9cfe27935a429a86c66ea18773fb44bc0c63b5b556cb6dc852719add5272672ba8b9f7319be36ebf78b3ce9683ecf13052cd8c027f0ae8749d899d45cc5783e2a5eea97696f2d8ce2739658d7043e31ce07269cd72a34a752e4c1b44d4a5517b64d633303f346be5b827e9c7e95e74e4ae744753457c193b859f4cb88b50b60b9dca7e70de8cbd4fd4715a4629a358b6cb761a74f14b89a36e3f871deb925370674d38a6765a14691b8080939e73d6b86bcee745347a268b1a3329c7cc463a579552475f29e89a332a221c0031c7b571ce66ee26cdcdc030e091e9cf5ae4732544e335f50eacc327d335ad07734b1c06a8a70c057ad4d5ce792397bcd2cdfc6c8a70c7d057a10d8e15b9c578860b1f0f4912de8dd2b37cb1804b37b0aeca73b112463497dacf88161161652c5662431066421411d40e3ae4f35ac2a4d98b8247b1fc30fd9b61bf0753f115c2dce5008ad4a96e7a9663d0fa01db19ac255e71660e4d1e45fb42e96fe0af198b4b4b386dd1155a1ca008c3e9dfb57ab83aaa671554ec78b59f886f57566b9b858ae0ab99424a994c9c8e57a100718af7e363ca9dee5bf0dc31ea1a9ca974a51e5f9940e84e6b2c4462916db3e8bf0b7ece716b3a2595d9965b47955645645c8ebc1e7bfd2bc7714d9d9464d197e27f0deb7f0caf628cba3b962cb35b1c1db9e1881f74f1ef593f73e13b23ef23a0d13c7707886cdecf58b343383b5a5c64b77049ec7e9493e6f8817baceb7472b0ed588feec7403a5635343ad6a77ba2c8cb8049c01c5797391ba4751165c64d73df421965602e8ca472415e7dc56d49ea73b3e43f10e9474cf17dfc68a258f3345f39e39c82a7e99fe55f558697ba7915f591ef5fb26e7c25e247d5e382496dd9e37c630ac369565e7d07afae2b3a953de1fb2bc4fd09f86169a545a54536833bcfa6b9640aed9f28827e503f8403daba68ea78b898b8bb33b765c0c62bbe313ce100c552417140ed8a4d05c423da8b0ae26dce720d16284d9c54587710464f269a4171be591c62931dc4284f1482e2088f5a761dc6f96412724e6a5a2ae37cb3dc62958770da68b05c43191d01e94582e31a33d874a2c3b86c38e07e9492d02e218bdaa52d4ab814c0f4cd160b91b29ce31541711c10703767d71c54d8770f2dbd28b05cd9c638aecb1c37020fb55582e183e942455c081df1434170c62a2c4dc08aa0b8a066aac171a579e94b942e2e0e48a5615c0ae78cf357ca170c1e3ad2682e15217171cf06af942e26d27ae28e50b8bb697285c08c53e50b89b413cd5582e2803dcd160b811ed4728836f39a9e50108ef4f9405da3eb55ca0017bd1ca3b8e0a7a55f285c36f6a5ca20d8477a3940304f634f940365572806df73f952e5401b7bd1ca85710a83c64f3513a68bd99f337edbbe14b9d5fe1bea37b656ed733c1e594880c2e431c9c0e5988638fa579952363d3a133e30fd93adc47e22f1def4746805ac6a0e4752c4f06bc8c5bd0f730da9ef3a9b80ae3190335e0736a7a1638ed4af85bc328240da0e0d75a7a19b4788e8da64575e2cbff16f8a4341a6696196cade4604cf2918570a3938ed5db4609984a763cefc55ae6abe32d78db58da4f7d23a96582d90b9451cee6da3ee815d32f7968257373c2ff04a582cae2e35f8bccb868cb2dba12029dbc02ddfb5654d723d4773c0f56377a3eab7769e5ac68094601b2473dbbe6bd3c3cd48f3a77e722b3f115ed8e95776292af9174bb25dc809e0e783dabb7464be6e63bcf835a65deb3e38d002446e8c73069085e15075cfb74af3313515b43be9a3eaff0088ff0009748d5f4f867821b782fa4caaa22805c7afeb5f3f28b933d1a48f25b8f84c9a47881ed20f10ae8ff6785996742678de5dbb829504704fcb9ec6b55270476c5268d6d2a4b8b8d0e07d46c8417a47cdb4719f7f5ae29d64d9ac29b6c9b4db6114a7048cf6cd71d4773b231b1dff0087ed5ca27078ae0a9b1d713bcd3613b1430271fa579936692342508140c127d0d72363823035a87744c47183cd76e1d9323cfb568d848c48cfafa57b14e460cc68e3c3938c5765295ce4946c596f87275bbeb7d4ad6f92cb50871e5caf08729cf504f1fa1ad79acce691b367a6eb3a0cd2ec1a76b70c8599ada4936aac87fe5a0e54e4fb56ea728a32b33a2d1bc73aee8f0c6750d00450b7dd92d95bcb6c0e704e467f1ae56e5365f22383fda03c2d63f16b49b6d42d9c596a96d9091c8461c63a31fe473d6bd1c349d3392a534cf922ff00e1bebd05d346fa75c4271862abbd41fa8e2bdb8622e79b2a27a2fc2df83735e6a90de6b6e6deca221b66e019fbf3e8beb58ceb398468dcfa76e3c6b61a269c96ba7037d3edc288c308d46381bba579b3aae2cf421873cdf50d325f14eb22ef502659ff00e59c71b10aa3d09ef570a96dcd5d3e445b9fe1ef9c23960815195b732a8c6efad39cb9b63992bb37b49d1de1650c8c36faf6ae6ab5343b146c7a26916cbe52e1702bcb94ae69737a0b71b00eb8e456698997a08c10d8e0fd2ba693d4e791f297c4db3363e3bd751c1118ba4b888a1e4a91b587ea4d7d2e19fba793517bc7a6f833c590f867409dc4b1db410aee776e7803f4ed58557ef1e8d385e27d95fb13ebcde2ef87fabeb42491e0b8d41e28fcc183f2000f03a75fc6bd7c22e647ce668b96763e8923b0af5794f09bd04d9c669344dc4000e7268482e3b1c669584263bd162ae205e6a2c55c0818ce29d82e26cf9b2697285c5da293885c4201f6a02e26dc8a9b0ee37cae4f34ac3b8797cf38a2c170f28628b0ee35a21e94ac34c4f2f1425a0ae06218ff000a94b52ae218b3c8fd68b0ee34438aab05c0c20f415360b82c231d05160b96f9ce735d5639d8e3c0e28b1222f5a68ab81c76a185c5c034ec48601ed45804e9c0ed5402f3fe34ec0007bd3b00bc0ab48000cf4a96804c76ed53ca02918ad394036f19a39404c0a0000c5003b000aab006d039c516013b7d6980bb4d4d80368aa00c01400bc0149200c9f4ab00ebeb4c0322800c9f4a002800a004c1e9400847ad66f61dee70df17f57b2d1fc13a9dddd466e0c10c8eb1a26ff9ca32a96ec002475af36b3d4eba1b9f9dff00b3ac0f1eb5e3212bf9970df66677c7ccc486c927f4af0b1af43ea682d0f53d572a930c756e0e6be66fa9e8c51e6de2891e252a818f3cd764244389e6be28f0edef8aa486d200c2d81cbe57248f41e9f5af4a0ce49a1341d1e7f875ac4575a767006d9a3c7ce7f13f781cf4ad653e542a70e667a1af8aecb5b8f1b4c133101a361c6715c52aae4767b1b1e2bf19fe07c7e25ba6d57486482f19409adda4d8b2107ef291f74f6231cd7651aaa072d4a2e278ed97c17f11cd73e4269d2b3ee254e72bd78ae89e31ad8c6349b67d11f08be165c78013edbe5aa6a52af96f713ae3ca1e983d3ea715e5d6abcc77d3a763b3d5f443a9a817dabff684ea73e5c4c4a83fef0c0fd2b82c77c63626b4f0ed9dadbe4c080aaee048ce4e7a5672958da28ced6202dc222ede9c7f0d73395cde9c4ada66965e4dc47e9d6a1b37477fa2da18f62ed038038ae1a9a9b1d9db59e23c85e4d79f5b63644a202ec00da3d6bcfea69d0a7a9e9e248d8003dcd77d0563191e7fabe9c04cea066bd5a7a9848c65d2f6b64003f0adf9ac63b9d0e896f25b32b1202b60671ef4d5433942e6ede68aba8c1911094e092700607a8ae984f439b96c723a9e8d7f671b8b5b89114fde546254fa023b574d3d4b4cc6b7d4750d1d6449632125e598a871fe15b73f222654d320fb44571f7e3b76cf768873550af739dd0191c1f31643081d7f778c0ff0acdd52a34ec3ad6cccd2b190b31279e2b373b9d1637b4dd3034aa02fd49eb5aa9a3396a765a6682f3a82471d3deb39c8e7e535dbc3311c1d8063be335cf295cbb1241a69b6380802838e9d7deb1b8cbf141b533800741597506b525863c1c8ee6bb29e867511f2dfc77864b4f8817a8a30308df28eaac3bfe22be8f07f09e4547a9cceaf35ccba38b06cb8243bf3f7940e063dab1aabde3d3a0ae8fd2afd81eda3b4fd9d745488706699989ea496ce4fbe315ede05de99f339c2b563e8cf5fad7b297ba7cf7513029f2883f8854d803a673dea5806684000006a6c0232eee391f438a2c02818a92ae2606338a02e2548d8b8e334102639e2a0b02076a006eee48cf2286242e3d73528a42607ad21863d2a461c6477a006e3af154018e695803f034580946338ae9336079141203a1a100871bb068603bbd580b400dee69a014038e39abb00a3f9552003d686ec02ad36c04a901719e6b4b80a7a628b80dc719a9b00bc631458051eb55600ebc516010f5fc2980b520273dba555805a2c022d301680004d0014005001400023ae280027bd0021c9fc696e0968735f10bc32be2af05eb5a5e4a35cdb48aa500ddbc0caf5f702bcfad03a294accfcdffd9ea37875ff001609f3f6c5589271e8cacea463b722be6316f43eb683d0f52d5c7ee9867a9c135f36f73d183391d574817830a0b719357091a35728c3a11b101bcbc63a67ad7a50a861285cc3f105925c393801cf5c8eb5b4aa290525cace4a7b76b594ed01801d4572c9a47a0bde42a12d172f143cf47cd429b88e54d4c9e1d5668d44504ed2311b7f76bb6b3957467ec544d382c351d5a302e24936139318ea7ea4560a6d9b2a68ebf44f089da320468abd875f7a1d4b17cb62c6a7a7091c24670abd4f5fc6b8aa5437843439ed434d11b100e7b74a707cc5c7464da6586d5e3d7b8ace4cd11d668f07cd192b953d38eb5cf2d4a6756b0bed030047b7fc8ae39ab9aa60c1108e40007e5587b3364579d8c91360609ec456d0563291c56af6d99db8e4f5c77af4a8ea61232e3b40e401d41cf3573d0144d9d36dfcb2148c83dc5735c1a3a2b4b57b78d5e35671fc5eb4a155a30711d269e9724f453db0073f5af4e8553171332e3c2f1c8a728a79e700106bab9f9912ae65cbe05b69189102807aec5db8fc0572fb4e563645ff00081c01b006de3d073fa54f30cb317845221855071d80e4d1cf61a5737349f0b0cef1190c3b114a354cec75361a598f682073dbb8a72a8ccde85e3a72a82338cf63d6a54ae4d8a72d86c391953ec69b622a3c5b0153c807344424c48a2dd82073daba626151e87cd7f1b90dc7c455881f9c2a96523a8e403f8e0d7d1e0fe13cdaab530bc55a4b590b45239914b29ce4b00403f964561565ef1ec6161747e83fec21293f0725b72ecfe45f3a8c8c6dc8538fc2bd8cb9de07cbe78ad591f47819afa24bdd3e5fa8118a2e021ea2a6e007a54b4036848056a9b00a3a51600cf2456600781400da9017750021f5a82c41d39a0046c9e09343121474a9452169d8486e319c9eb52688011eb52203d6a8031ce28013f0c7e340122818ae88988a466aa5a80638c516003d7345805a6021e940084123ad500a3a56802e7b5200a6028e324d160128b00e1c55008bd680148cd002631cd0028e94009fc554800f23e94900b54014005001d6800a002800a000f4e2800e3bd00140050023520628e41159d4d8a8ee7e79c1a343a07ed29f15ec20b616769398e6b78d5701be625881e9b99abe43188fadc23bc51b3a882f95070071823bd7cd496a7a94f70b3b156c13d02ff93591d43eeb45132e4005bb7d2b58c8868e5b55f07b9663d41ce475ad1cc16e73f2780de62c490807a8c573caa1d8b6123f8796ea4195de43e848db51ce6bb9ab69e16b6b6c2244aabecb594a6572dcd9b2b082d1b3b064fa2d67ed6c524682c2ec3622ed56eca78c543aa68915af2c52388e07cd8c1ac24ee5dac73573681dcfad7452d119b160b60182024fd2b393291d46956a2089379018738cf35836688d47b9122641e01a866b1294a0f27701ed9ac9ea75c18c901280f248ebcd3b18c91957967e7ee001f5c935d94e56395bb18861f224c11c9e95d0f515cd3b28892086f7ae69c4ab9d469cc4c232339ebfd2b992b0da27960209602b6a75394cf96e428af1be48e0f5cf6ae9554c796c4cb3230c30e7d2873b8ac38c48ec180ce471815129087c56de5e091d3a6456572d46e5cb7914b0dbc11dbd0d0a40e26adaa990600ce06703afd6af9ae6128048fc1073c7f7ab68c8cec5495d58152493d7dab5339233ae17ebd79ad20f413120538c1ae882bb39ea9f327c76bb1178d45cb1da62d8d91c70a491f8649afa5c2ab40e291ade3bb7fb4f86747bf441969bcb0bdc065ddffb2d79d5a5ef9ec611591f627ec177824f056bd679c795731be3bfcc98ff00d96be832bd99f379fc7de83f53ea3c76cd7d13dd1f20f611b9e29884a960371ebdaa004a0071e9490084f6a9602fad2453108eb4982107bd6630c7a8a6481e462a64084ff1a94501e9431213926840c5ce7b53042639c54b290118152ca051eb55101154924fe54ee02e4d4812018aea31027b0aa003d45160139c7345803dc53b00bd7eb458007a1aab000e945c05a1007f0f5ad100a391400a0628003ed4000e2800fe2fc2800a00423d28016a9007f0fe3490055005001400500140050014009dbafe9400b40050014009ff00eba960c50de94a7b148f81fe20ea0d6dfb55eab63709e5ddcda6c84f4f9c0972b8fc0d7cae2a3a33e9f06ee8bd7e8924c581c2e76918e735f2f51599ed4372d5a2852800ea0d7249d8ec469c0abb46719cd65cd60685ba82075c6c2187a1e2a7da0f975312eed10499c01c741cd7254a963b147428cb659c918c7506a155b96a257780a6477e9c5372b9aa1d0dae1f2727038cf634a53b0ec694481109c824d65ce689142fc165e73e86ae2ee4c8e6aed3f7981d6bb23a2312c58db953bd80c9a96868dbd3ed5ef6e12304f3dfa015ced1a2674074616f165f1c7518a991aa77294f00691488feef1f28c715923b20363b4491b18c11e839ad20ae4c8a7a9d8a5b8431b12ac0f2477ee3f0adb94e392302fec14c6ae832727f0aec82b98c9d88ac720818c6d38a99c451674b60c78c0c93d31dab89ad0e86cd7f277a1272412339ae5d98221920dac71cafb8a69b0b0c8d423640f6cd69ce43896208c3311d33d8d5364b8d89594b0c004fa7352997144480c6c411d0f4ef52f402dc77010839c11c820d688ce487b5e1618c8e79ad20cc248a93cc492770c13c0f6aed46162291b7478fcfdab54ac8c98d4384c60ed3c123b5765239ea9f2c7ed0485bc70c0217c5b2965e09c027fc6bdfc3bf70e49fc48ed34fb73aa783a6b79482f1468eaa3d5718fd2bcbabaccf728ab46e7d57fb0946f1da78c030c0125b01ff007cb57d1e55b33e5f3f77703eaaafa5ea8f8c634f5a06152c06b54d805238a2c0264fa54a0027352c04c90692298a4f5a1821a7a8acc42d4b6505122508739e0d4a2831ef54c4838ebe942062118a4085e473edf9d4b29084e467152ca0ce05544001c5201a4f35204d915da6218f5a00383f853b0099e9458000cd0c03f8a9c405fe21556000314580523b835480077aa0141a0018f1f5a77014734802800a7700a2e0148008a0041c62ac05a004231c8a94028fe543013838aa016800a002800a002800a002800f4a0041d7ad672433e1bfda83441a0fed73e0ed5846638b58d325b4f33760191727f9015e0e295a2cfa1c0cae873dab96c31e720f1c8af8eabf133e829ee5a5c202704e3a1ea2bce93b1e8447c73939033803d3f9572499ad86fda55c1c1e719aca4cd144a93336e240ce79e6b964ee742446b1348b93d2a23b0d0f6b23b0823ad5c5ea68812ccc78240c8eb8a1ee5242c91ed38c632289229a33eed060f3d3f4ade3a99b399bdc1b923a63dabba1b1cecb91c84c6b1f56c67f0a18d9d7f866d845189d8fca4608ef9ac645235af1c4b21049c1e01ae46ee6f1d0aada74af83b18a7404293f9e2b451b9ab995c5b1899838c8279ff0aa8e8352ba27bfb682f2c5c3a8f3146e520e39f5e2ba51cef738b9addc48e3078cf15adec6137a99b0ee4bbc11804f19e2a1cc9674fa726f8d581e17ae2b959d1736a3e57ef0ae79219224609240e00ed59b45a649f642c32a3701d4918fff005d081ea352cc8390769eddeb58ec2b8b3c0615de4e7040200e7eb59b7a85cab346d9241ef572d592562ce1f049fc6b4b684b60642aa401c91423298c9653b7a0e001ff00d7aefa6723636194bb63767d726baec64590e02609e0706bb299cb367ccdf1bed9a7f89d00445915ad549f6c310722bbb9ac8e74b9a474fe11cf962273b59d0a1ffbe6b865ab3dd8c6d13ecefd8d3433a6781f56bc7186babb0b9eb908a07f326beaf2a8da99f179ecef522bb1f42039afa34f43e5fed5c6d0c02a404ef59b016a900df6f5a9600d902b36521303834818a49e940213a9c7a524310f3c54c8008c71499285a945098c9155700c7145c03af3e94800ae4f7a0a421c76a863133d684028eb4980dc9f4a404c79205769889c919a005e829dc06d1701c38fc686018e734e202f4e6ac03af3400aa7229a006eb540267d2930039c548013c62ac070e0734009fc54ae02e7145c0298050027ad580639f6a005ce6a5008b43016a80318ed4008b400b4005000dd7f0a002800a0038cf4a4027a50d01f36fed8fe1a86edfe1ef887c96fb4e99acaa9947458dd4821bf1e9ef5e26395a27b397cb5b1e76e85ee24191b3248fcebe267f133ea2030007006303a62bcba9a1e844631c72738191ed5c2dea74a1a3e71f2807dc7ad4cd1b17ed6c03005f18239c8c9ac521dcb1f61880c28dbf4acd6c5a1ad6e0e41e475e959a7a9a2236839e01c0eb577d4d1bb114f6e18641e9c73e95b35a12998fa9c4123ea01c7cbcd6d044b399b3b196fe766c9eb8cfb5772d0e766d7f6745691e49e71d73421b2e5aea090c680960a09e878ac668a468da6af6ee30ce09ff7b9ae5b1b9a83c53e5db2c50347b4373b8039feb5b47626c52d53c422eb2d29018724b63a0f4a496a5c36324ea6aa400e7079ebcd74c51949d98c061b87ddc649c6714e673c9ea56d4f448dc7989c31190076ac52b8fa09a2b88f6ac8036d38193dfd6a2c5a67491052e098fe56e013d3359c91a16bc8556c0555cf5da6b2920b92642ae08efd2a0b434b739031e829ad108591863240c9ec0f4a85ab029cc1642411f4c1ab5ab02b496c0b024e71ef5d4d686526559f6a9393c76155ca4cca170f84ce793dfdabae9c4e1911dbcbba553b811e95db631b9a96f928c5b079fd2ba699cd33c1fe2659249e3b32ec21d6dd5416e8c37139fceb69e88e8c342e4ba2bbacd0b29c12c067eb5cb07767aaf447e807eccfa6be9df087486933e64ef34cc48c672ec01fc80afb5cb55a99f9c66b2e6af2f23d532315ed2d8f156aae07de9300a94037f8aa580a7bfd2a9000f5acd80d627a62b3652173c53061c75140202bc8229218953200c5364a0a945099153700cf3924517001d4d30168290d3d6a18c451939c9a100a7ad260281c74a40387deaef66207a0a901474a0046eb400a7f953b0076aa001d2a805ec4d55c02a62805fe2ab60277c54b0003b50800fd450c03ad520147535202d50077c50000f6a000f4a002800ab00a002800cf38a002800a002800a00280006800a002803cabf69bd024d77e12ea2f1677d84b15ef1d76a302dff8e93f957998c85e0cf43052e59a3e7f62928f362e55c6e5f70457c35585a4cfb2524d22bee20900f4eb5e455477c18e40aeaaa3181cd703474a2d448a304f53eb5322d16d5b0401f8573cb434485794c7918ebeb595b435484dc3391d3f9d671dca687150e31df9c81deb748a654b993ca46e07d3154c11ca6b57048624640e001dabaa8a224374bb84b5b16662013ce48aed713031f59f11c08a774ab851c7ae6b68d2771ab2472575e3446908493200c641eb5a54a0da21495cb765e267921cabf5f5ae295168eb53b9a517899161cf9b8603f235972346cac53b9f17339c3c859474c9ed5b2a6d99ca4922bbf8bc28c33f03a1dd8c576c28b382a54d4d5d17c656f39e6650c0f43eb59d5a2d3214cea6d3c4105de516553c63aff2ac1c5a46e9dcab7938b6bb8ca1c2b5472e838ee6ee91a886c06c74c707f9d723dcdcdbb7903b310723dab390993926420f031d71deb1b14864a02b7396edc50f628acec58920e33efc5443702b4d2155037727b8ed5b4558948609f3904e76f1c9e6ba13b194d1917ee48207009e48eb5ac51948a6f2f0149fa66bba3a1c9224b72778241e3d2ba21b1c899a50301048493823a8ada0b5339a3c83e21a09bc5813259d62453e841c9c55621de28ebc2bb40d1d3b447b6b649c8f95802808ef9a8a2bde4774e5ee1fa25f0db4d5d1fc03a05a282a23b38c608c1e5413fcebf40c1c796363f2fc64b9ebc99d2576decce27b5843d287a884e7159bd0050734300c9e9fca840213ce3ad4300db9ef52007814ca6267079a96c10bcf1cd2448952ca414861400d39ce01a180b8e28402727a106800ebc0a9003c8ea291620f978eb498013f3639a602e4d00487a576b3113b0fad480b9c8c77a0046eb400a0e6ac03b55000e9520397ad50001db156004739a005c739a180dcf18a100a075a1800f4a9402d5007f11aa00a90109e33400b400639cd0021f5a770023d3bd17016a80280117a0a005a002800a002800a0028013b63de802b6a9610ea9a6dd594e81e0b889a2752320ab0c11fad73d65789a52972c8f90aff004e7d1ee27b174daf69234193cf00e3f90af86c52b499f69879734518d3c8c1f2715e25547ad0645f6911b800f2deb5c0d1d299622bb3b40c8c7d326b0b1d08b49764100fdec720f6ae699ba429b9dc4927e51d8565ba344855bade7a0c019fc6a22b529a2c24e1464f4208ae964b28decc4a96030a06001dea920471daeca00201239e95dd49112381f11f8b1ec6dcc68e76a8391debd051b981e21e3bf8be74bdc8a924f3303b401807f1ed5e95286a652bd8e0341f8fc63d4962d42c644b766da6547de579ea571fcabd29504e272a4ee7b3689e30b4d56d16eed6e56581b80e8d9ff3cf15e74e8a3a2332e4fe2a4b7392ea01ec39cd733a08e98d4332efc5a645621c6dc6e24b00140f7aa8d249984ea5ce0bc5ff00170e912bc163692dfdd2a867dbc2027b6e3d78f4af469d25d4e66ae60697f1bb5d91437f666d193f293823d0fe7457847b9373d73c09f14ee2f8a6f2e1c8c61ba67be0d79538a3ba9ea7af689af36a12c59276f5c939c1ae371b219dadab3280d8c1e95e5cf73789bda75f0552189638e0ee20e6a19ab46dc772193e6073c1c7ff005ea5225114939dc7827008c566f628aad201d3ea05630dc0ab212ce5c8e7eb5d1164b630804120e09e73eb5b18b651ba4001e77293cfb574d34612653688025801c7e62bb3639e408c570064fb56b4ce6b58be8e16dd813c106bb608e79b380b2f0cff006ef8d279dcb7930aab3285e1b03b1aceaaba474507681b82492f7c46ba4a8cc6268a253c000b300056f461efa36a92f719fa196b1082d628c0c6c40bfa62bef69fbaac7e67525cd55b2538e38adda325adc4fc28402d672d006b75a96c031ce284029e949800e5aa404e86ad9484ac58301f5a6890a96520a431a0823d45002f5a1802d08000f7aa2509d0f5cd49a20da0026a061c7bd26027534c05e94012d76dcc42a4068cf6a76017a9354027a54d8007a5500a0629d8051d7156807679c5300c8ce280133938a004c735203aa804279c8a00072280168011a80170318a002a980678cd480d24e314005003a9a00aa00a002800e08a002800a002801bc8393400b9efda8016a27aa1ad19f2dfc52b56b7f1cebaa48552cb2003b646735f159847de3eb700fdd3ce6691c385cee19c83e95f3d247b90192b156047615c3511d6864571b49c9e82b96c74a2c2cfb875c9c75ae6a87421df6b283009cf63e958c763543d2ec2ae14e7d4834e2acee0c9d6f18aa8438603bd6fb810dc5c10a773124d52d00e63564f35587723b7635db49d8991e41e3ab6387054e39c1f5af4a9c91ced1e2bae688ba9ca544409dc777382457ab09268e49a649a5fc3cd2a470cd6a1dd80f948040edfe7bd6b168e6bb46adc7858690aa2c008634e760fbb9fa52b9aa7a146659d18a386f3178c678fad2d0952d422d1ef75b8fc88498a26383db27bfe15cad6a2723a4d2fe1e25ac5b6e638ee875f987238ad9d4d0c9ea4cde1ad11626096881c039c8ce38ace55a0cd390a963a7416970896c85db380703a573549c2c7653563d77c0f672bed9190c6171d475fa579d391bee7a958a0784e7270383ef5c2f566d1179f3b2a0fcb83915124685e82f3008c8c77c9c565b01335d038c107dc718a99009f6a19c9ce3d01c5656015a6057918cfbe6b44ccda2bb4a4027af35d305739e443291b090793eb5d1031914d8e3703f5aeb460d8c886e6048cfaf6ada08e76cd09e30d0105f6823d7a5774744724ce26cb53bd83c44f668e442b2ed3b79c8c67358cddac75d15781dffc2cd04eb1f15343f310959afbce70548c045dc3f90aefc3252aa8e6c5cdc30ecfb9dabeea9ad0f806c438fca9a7a92039aa480683ce2b3900a3838a80109c73400b9e335002639e9400703bd53634071dab3286e48a9b00b45804c718a4018c0a0001cd001f8d001914900003a9a18085b3c62a122c00c9e455001e0f152802981260577d8c44db53601474a602d50054808073540382e0e6aac01d0e6980b8e6801a450003ad480ee954027f85000071400b5560101c7068b00673c5480b906800aa60235480b8f5a004c1ce7de80169a00aa00a002800a002800a002801b8fca80170074ed48001cf3e953ba07b9f377c75b1369e379dd4315bab657638e370e38fc2be53308ea7d3e5f2d0f2390969dc12703815f31247d04191cc70b807a9ae1a88ee895e46f900039ef5cb63a108b71b3192483efd2b9a68dd0d7bb1bb00d66a3666a8745769bc8248c7e95a4e3640cb6b76bc64807d68417219a62db86eedc553417294c9bd4671f88ade0ec80e575dd056ea37528a475e4707e95d74e466cf2cd7be1f219249044c9cf6623f415dd49b39a524cc6b6d21ec2751002493c93c9aeb8b6734ac6fdae886f51967e1f1d2b5b9937645e8bc0b15dc4a4050475e30695cca0f52f5a78762d3f206ddfd09ef5927757349223986cb8f2961241e49c75ac53bbb131336f743b4bcf9a4855db39c6314b96274291d1e81e198102ec85463073b7f4ac6b28a46c99dce99611db85d89b715e6c8ea89b913955c1200f5acac6a38caa0823031c641eb59a771dc635d719241a6e217017254e01073d875ac5941f6ae790460f7a9b012adc86c608e292440ef3f18c9c83c7b0aec8184873c80c7d2ba207249956720127d2bad239db0b6277824f03a57642273b6691844b1306008dbf9d6ef430919165a129bf69d01dd9e99cfeb5c953568efa2ad03d13c03ad43e13f1869fa84e8668a2dc242a7e640c304e3be0574e1ea725547362e973e1d9f5b595f43a8da45736d22cd04aa1d244390c0f435f7142af323e0270b68c9ebad6a6484ce052b886f7cd66d80eea298098cf14007f09a800271d6801320f1523420ea682853d8f7a09b8501703c0cd405c6efddc00680b8bd3934140791f5a006fd4d422ac29ee050d8584c11c9c531864d300a84014c097a75ed5e818877a900f5a6900b4c0318a002930173c5680213400a4fbd2b0003da8b00b8c5170131cfd6a8006738a180a31da84014c0298060feb4ae0145c02a4009c5520101e295800668b00a7a5200aa402107145c031de8b80bc01490055005002139e9dea58074e3d2a805a0000cf22a6fd07caad73c6be3f690ac963a997f9d49816303a8392493f5c015f3b98479353ddcba5ccec7cf576862b8738e3bfb57c8d5dcfa6814e661d01e9cd70cd1dd165092600e339e2b95e88e88bb9565b9cb609381cf4ae74b999d9143565dfc81f8818a76b1699346e430cb1c7bfa5325b2ca4982307a1ce0fa54422c398901e39ce2ba2c4b910c9200a46f073d322adc74173191a85cf9111c90703927fad3a717732948e3356bf9ae61658b041ea33c7eb5df08b471b673f040d290db76104823ad76a4432e5c5bbdb08c862eac092ddff0a952339c4bda75f4b6f26c8d499587caecbb9547bd5b57223a129713dd4af213c1ec303deb368d62ee41738675238c1e0f7a10490f9658e22a84658904b01902b38203534ed4c24a039e0e00db93cd15237344cec6c645788367693dab8a74cde332e1240fbbc572b8b474a90336d3c06f4c62b08c4dae53b893cb1904f07b56d621b21fb4b6edc4f207e3595486a689920bd1b4e4fd2a797429b1d1de1c821864f5143892c9967ce063afbd6d089cf2762613f6c8aea8c4e29904b3091f009ebdabb228e665db304b015d0b43165bbcbafb2db1762064e3e63c5697307b95b48d4b7c8b22900139c570559ea7b1456874f15f89e21292abd413819359a99a38dcf7ff00801ad49a87872eacdf95b3907967d158138fcc1fcebec3299b9d33e2739a3ecaadd753d47b7d2be95688f9c7bf2887150c4254b014738aa4007d6a64000714900373c54b2908bc75a18ac18ef482c14db282a4910f4a928063a8a000fa62860260d4a0101cd22c5e4d00048ef400038a000f5a004a0094f2735dd2310e87eb4a202d580520039cf3da98087a0fad2603bf86b40128001c9a760148e38a2c02038ef5980e078cd5201a4f7143000706840388cd300c0aa001d2a4008cd00235002ff10aa4014c03f88d0007a5400018aa40152021e07d280169a0139edd2a805a004c0eb52c03dea805e3f2a006b308d599885503249e80544e4a31e6049b7ca7ce3f16fe220f14ebd6763620ff00645abb8798e009e4e80aff00b239faf5af86c7637da4f94fadc0611d38f3b3cbf564018b64e7dba115e35467af139bba9c893af06b8e6ceb8b33e49cab9c1c9f4ac5c6e8e9a6862fdee4f5ed5cabdd6765ec4bfea9412d9e7a55312637ce0e48ce323f2aa8ab92d92db5d208c924e01c66ba95321c870d5234c8015860f01b2c3f0ed5a2a664e466de6b01368c2aa39c12dc915aa85d0b98c1d6ef0c4cc496646c0da3ae3e878ab843525c8e6848b773ed42dfbb3c67bfb7bd754a3632b5cd15526488344bb8b6199571c562e762f94d0b8d3090c0b7eec0e38c902b0e71ca267a5a5c16008054924b6e0187b7ad68aa90a038db2c99f3980e79da6a7dadcd69d32dc9a1e10c81c9e00f7357195c2a46c66cda7c90be0a904738cd5a7639984ac445841861d71d73577b9172dd96b92c0b8772b9c6464919149c6e69768deb4d7d0289583348300024735cf2a65aa96349759331460769c7dd273fa5732a67446a5c7cb7427524f18ee453702f9ae67bdc00dc8c7191cf6ac6aa358b1c9296c0e083593d8d6e491c8549c9f6e2a5937b96629495c12462ba6073cc9c4c554fa0f515dd089c926456f72259f20724d74dac733376263190703e94ae62ccad7af0456b22383b082c081d0e2b68c6e47519e1bb846b48c93caa8cf3c9f5cfe35e65786a7b149e86adef8820b58cb17450a327e6c63dcd108368dd33eacfd9f744b8d2fc0915e5dc661b8d45bed01186182745ce7db9fc6bedb2ca3ece99f039c57f6d5b95743d38722bdf8bba3e7b77cc35bfad66c02a580b8c0aa40254c805cf1490074cd4b2909fc228630a40148913391c54942647a5002e7d7bd0019c0cd0cab0641e2a50586d218a4e0e2801280038eb4000e4934005004b83fad76d8c44279a2f601d54c017ef55200ef9a4003ae28017a0cd5809db3400bec28003cf4a00422a500a338aa01703bd2402018aa402fa8a401d05001400119a004c0e95601ba8016800fe2352c0290080e680173ce2800a000f349005680152c042c3148030783400b4d01cafc50ba9ac7e1eebf35be4cc96ac46339c743d3db35cb89fe1b675e122a559459f1d3dfbde34136fc244c180eb9c7a57e5f8892955699fa5460e34d2e85fd57fd6027037af02ba3b1e77538fb8662dc8239cd72c96c744194d990cccd8cf1c0cd67247553081be505ce08edeb5ccd1d77096e032b608c0ef4dab92888ce51093e879f6ad60ac6736634bafa4618e19225ef8c1cfad7a315739e4ccf9b5f4b99375b9cb0e0b630456ea998dcce9ae6596f231e6746dcc7e9d2af96c8b45cd5ae25bc2a231b89c0c8f5ac6f6668d93697e1a90fcf2808339dcc718ab93b827736a2d4b43d3654596759c8e0841924d656378c4d23e20d12ebe52ed02f62c9dff0003594e3a1ab88c8f49d375090fd9afe28c119f9c95c1fc6b082d41441f44d1b48226bdd56220e784f9b34a9d3b31a442de30f0b34c208a6b8ddd03491154cfd6bb140ce5125922b0be1bed6e2190af3c3027349332e5b1c86aba5dcd848d2c7f70b1f7aadce79a39b9f527762df32e3a103a9ada2c96410ebf3bc98048dbc16ce2aa4ae665d1e2468e45c12cea464e7835972969d8e8135e796dd492392380d8c0f6349c0d948bd15f19514b105b1f98ac2a434378c8b50dd1e001d3ad70b81d716598ae082c48c7a54386a4b2cc4fc7f8d74a8591cf365932f9911524927a67b0aec83b1c921da6425643ce7924fb7b56cd9cd237810c40231eb8a495e6632d080f82758f88f05f68be1d585b5992d5e487ed0c510608c8623a647f3af56953739591cd52b2a7ab32744fd9c7e35453ada49e19b6b705b6b5c3ea5198d47f7b8f98fe0294b0336fde476c732c3463b9ef9f09ff006467d2eeedb54f1cea716ab750b8923d32cd4adb2b0e46f66e5f07b600fad7a986c0d38af78f13179b377548fa5d5428daa02803000ec3d2bd78c79763e66526ddd8ee82b5e6b19887a0a2e02566f50157d29ad00338e287a800c521d84c1c67d680b00e82a4a0a960271d6900771de800efd28107e14156109eb9e9536284381d0516017a76a40359031048ce0e47d6800041e2a980b5200381d6800a9025ea2bd1b9886051601067d39a9602af1cd5201698050029e9d6ac00e31400809c9e6a6e01d3bd17003c9cd0805dd54019c9a4800e2a900a3f5a402138a0001f7aab0064fa5160169808719a00527140080f6a9602d20139073400b8e73400500140063b934ee027d6a800819159b002401c52602e722a90105e5ac57d6b35ace824866431ba9fe204608fc8d675573c5c4b849c249a3e10f8cfe03d7be0e6ad22bd95d5ef872563f66d5214691554f3b652a3e423d4f040eb5f018dcba509f323f44c16651c4d2f64f72d699a9c5e22f0b699a9c4cae25452db1b700c3e5233f8547b3b58ca4f5d0e6b512c5df07e61c004f415c5256b1ad366413b4104e4fa5652477418a92e172493d8573c91bb6413ce218cee3ffd6fad5c2171a285e4e92c2cfe7b44a3b2360935bf258c26ce4eeaed2e99d9dcec5fba5989af423039e4ce657592b71e446e7cb90972fd7767bd7528d8e7b9d168b7293485c310a380cdc66b2674459b13788ec7474dee43f7c93dfdaa214eecd1b399d4bc7d36a9298addcc51e3e63db1e94d42e25a0cb37f9449bb7fa7a569eccd14ec5c6d5b6fca171ff02ac654f4378cee4d16b85b0cfb7200038159c28dd97cc57d435c6b9c8cfcbd31daa9d1b13cc64cf2073b9ce0f6ab8d23394c86db55b9d2a4f320908c64b00719c50e958cb9ce974af8886ec2c574a1778fbad823f0a9e416e5abd36ba82ee5650a39e0f5aa51b11230e4b558cb153f2763eb5a7299a326eae8c0c5576927914f94ce4ec6ae992497168b2aca3e53ca633fcaa5c47191d3e9974c6050fb777b75ae6a88de12d4d78a4dfc639f5cd70b47a306588d8f4248cd6696a365fb72597a0c01ce4e0d74b5a1cb365a8b05b007b62ad23091a16118576f4fd6b648e691a01c8c90726b7847de39e7f11ecdfb3158fdafc57a8de3056fb3db6c560bd0b30ff0af6f02af33c5cc5f2c6c7d2c40af6dc5b3e6f45f131318e78a6a290ee9fc21c815648b91498841c9349b014fae685a9a24260751de9bd09b07a6450b50b0805218bdbad002548087a7152c03040f7a44a01d7a506886b1208017239c9cf4a0487672338e682868209ebcfd680178c75a004a800a00690460002a980e3c0cd48011dba5002ab702a40901cd7a373116a93010f4a86c001c7e142602d3401d29806702aae01cf7a2e0152014009de9201c718ad100833f8d4005003874cd5806334008b400b8f7354026302a4051eb9a004ebce680140f4a0033ce2800233400679c5360152c00f1cd30109e2a400b0edcd5a013939a97a80526014200cfa5340326863b985e2963596370432380ca41ea083d45652499a4676d51f3dfc78f06d9787a4b3b8d2f4eb7d3eca7859196d2258d7cc0d9076a803241eb8ed5f3f8da2a3a9efe06bb7a33e76d508124814f4af9ba91e63e862dee61c8e14127d79ac394eb8b1a652d18da7682466b3703a5321d424f2d0823703f28a51d195d0e435bd48c7134718e30460739aee7aa3199c1ea7adbdbdb48a59572a7f8ba7e15bd24724f638d9bc6315bdd26532cad919e4fe75dc72c56a6abfc453e58585d5f233906a1c11d28c5bbf14cf7736f91cbe010a09e2924914d92db6ace7040dcacb93838c528c920836ce874bd72776505888c92064f3f8d44e491ac60db2fdd5f4ae5720920eee0f6ac5544cefa749d8ae354da151e51bb70048e31cd253d4de3441b5229d5cb283c106aa534cc654da2bb6a65f92db941c904f35719a39a5168a571af06ca02539e72dd456f1b338aa277293eaa930233807a03db144a0869b4896cbc59369ef22a4a5a3e3f76c739fa7a564a0ae4b65f8fc785a0621d637c1c293c575382b13732e7f15457d3f33ec2400428e83dcd435632a8759a0eb08e886224678240e2b9a4ae6b1d8ecb48be2b3a82473ebd2b964b43a6074f0b02c0feb8af39ee7a307a16d1c9c73c5525764365fb73841cf278ad67b1cecbb6e87728cf07b8aa48c5b35ed171c633c9e6ba22734d8e91c024ef65e7b575d35a1cedfbe7d31fb2be9be5786755bfe76cf7011411d957ff00b2af73051b3b9f39984ef3b1edb915eb1e2085bb629006720f140094980671422c38e0e6a877026a5883a9e47d684007918a4261818239a0910f1d4e6834b8374a9621718a2424340193cd4234178e9540271ef4009c6695c02a402800041a00427038a48009e39a6005a921b019c5531128e39cd7518864f15401cd260041cf5a100a0e6ad00039a602d001d680038cf140050014000f4cd30018ef4803ad0000f6c8a680756880403068002715202d00267d2800248340013c50028eb55600a2c01c6734980848152c042d9ef4804cf19a96c04ce39a8e6014377aa4c009eb4d800e9420141e7ad340b50efc50c1a38af8bfe1b6f12f812fe288e2e6d945cc5f2e725792b8f71915e6e269f3c4edc354f67347c43e26222bb90a06c3608f6e2be4a74f9647d953a9cd1472371a81326c3e9826b16ac76c5935bca26f9431cf4c560d9d31649a830588670428f5ae5d99af4380d72711bb9ca819ff0038af461aa3199e73e252fa8b116c03e410597b57653472cb631c7c2cbd974f371737223955432c41725bebe95bb3182d4aba1fc38bebcba589eee38970496607a75c669455ca676d65f05a491416bf5941e9b54a91f9d138b25334e3f82130c089a7b995b92118607d4d79f52e99df452b132fc13915b7c82643c82771e39f6e2b1acda47a34d23461f85df6621089650401825ba76ae5854763ba10562dff00c2a182560b35a4a01e4312d9fc3355ceee68ac894fc218812a86542380ac4e1beb8a2ed99b699467f838f3b02d1b839c290c45526cc9c1333a5f8312a860904a771273b8ff003af420d9c3560ae56b8f83532c3bf74a9b881b4b038addb67249248cab9f82972af95d4517193f38edf853b6a72cd1c5789be1aeab67ba2b6bc89c9c0528a735d0f6324ae508be186bf05a34f25cc6e570546d23f5e86a24c5522741e1bbebbd35d22b84318040d8470df8d63b9ac63a1e95a35e1bb02488a93d08cd6325a1bc4ee6d2406254208603a9af265b9d9165d889ce060d6d4d5d1122fc726028c8c0f4aa5ab39db34ed149c1c8018d6b6306cd784ac6a7279ee6b689cd365396f12390a19363bf0a719fad75c16a61276d4fb5be09689fd85f0d3478dc159674372fb8724b1c827f0c57d161e36573e4b152e6a8cee6bb0e303ed408519fd680038c74a4c00e02ee3da8650d072011dea491686580f4a1009ea295c4c5cf6a2e48550054b28424e78a4c10d19acd3340c9cd6801919a004cfa9e2a004241ee6a40447049e723e98aa0147dec659beb8c0a00188c5243b099c8c13d69858013ebfa5240c5aa62250c1ba74f5eb5bb6656177638a130b06e18ab10bdaa900da2e03874aab80a324524802a980bfc34ee0190295c04c863c52bdc051c73557b00949a01074a1201c00c55ad005ea29b601d7a5500500211fad4b6019fd2a6e02120d1700c678a2e029c7406aee029e0668b8108666fbc368fae4d64d801524825db8ed9e2a407638a4029e94c046edc54009c8fa50038f0334360267da84c07556c0276a2e043793dbdb5a4b35d4b1c36c885a492660a8ab8e4b13c018ee6a1c1ce252972c8fcf9f883a968d71acea8da1ea10ea9a72dd4820b8b66dcacb9cfcaddf1d2be631141c2573ebb0955548a3cbeeae879e483f4af36713da896ec6f403c6777a9ae5674a760d4afb119cf7ef9ae571d4d91c1ebb29941d9cf3920576d2562268a9a569fe58620664739e40c1aeb72b1c8d5ceaadec9248d549e7bfa54399bc205987408525dc140fa75a232b19ca26adbe6dc023a2fb55b9dccb636b4ed71212036029eb9ea2b966f959dd48e9ec755b1ba560fe593ef584df323d1a66943a85832a16553b06318c8ae64ac74f3685a3a9da4969e4a220f9b70908e41cf4ab2232d4af35c5bbb6102293f7b8fca9a571730e3a95a98d50aae138271cb1fad689587cc63ea7ac5b6d2005e7b8ae88c8e5ac73379a82b8640c0e4f515adce592d0c8bb512ae06724633569d8e59231a4d160320790e0e73c77a1d4b1118952f01815a311968c9ce71d2946ab2a7038ed5b47325c314555e0e0ff9eb4d4ee4c558bde1c436320de08f5c7435137a1ac4eeacaef70565395ae05b9bdcd38270cc70735bc76226cb714df301d8738aa8ee652d8dcb16e41c1e067ad6c7232edddd8891401966ed548e765cf0be8fff00093f8834dd34472192e674870a7076b30ce48e82bba8abc8e4ad2e5833f406da04b3b586de31b638915147b01815f4705689f2337ef5c909e455a2409c1aa6028342015bb0a18099046066b26020e79c62a900018a4805c639cd51007939a0b101073400b52c9109e0d4b284241e693042566cd10d2306a9085208c734c042d8079e940ec34377cf5a02c1df39a9180f514ec004e684026718a1801703927145c910118a2e513b47d704af18e2ba5a320084a8079fad0900a1005c0181ed56407cca001b9aaba00ee319acee01919e78aa017760139a6dd80648ce31b4027fda27fa54a900c679d002a91bfafcc451701a924e65c3c40211c32b6403fcea6e04afbfaa005bb063815a45dc068918101d40f52a72334e4c0941f7a69dc0327d286c06ee6de780171d7de9395805249e01233494c06c6aca492ecc3d08157cc03f773e94730064e319a570027154020e3a77acee000e3be451701851c9ff005ae01ed81fe155ccc068b601f7179246ff0069b81f414733026c0c60567700c74aab8076e29001e83eb540040f4a9016801303a54b01303d68402e38c53bdcab1e5bf1b3f68df06fc0ad3449ae5e1bad524e20d26cf0f7129f523a28c776c7b66bae9d2723372b1f9c5f1fff006c0f14fc5f57b7d4ef1b41f0f9638d0ecdb0922f3832b7573d3e53c67b57a31a0a272d495cf2bf85df13217babdd304ae901fdec51b3700f0180f4e80d7858fa0b73ddcb6abd99e83fdac1e62db8903a37a57ca56858fb5a6ee5c8353dbc07fad79fcba9d571b3dff9f11193efef59389a464654b197970075eb5a455824cd0b2c7dd28a73dfd2a9b3348d58264878638f4ac8e84ec5a8ef540ce48c0e6ad9124386a483200c8a1332e51fbede7c297087b8cd125cccde1a22cc303c003c72abaf4a4e9d91d509d8b62f9ed86e90fca780c1b8a874ac6fcda16a2f112c716d272a78c93cd47211196a13f88e311e508da40ef8ad23017315dbc4d1e3697c76c678fceb4701f308d74b7809057d720d52858caac8864f2914924003a9a992b1837a1565b851800fbd2329146eeec6d24119e98eb51208a2a44f2ca7273c73d3ad4a958d37286a51a3af2986ce3815709ab994958cf8621bc0040c706b592ba3389ab6570632141efcfa62b9a31d4dee6edadd8c29c8cfa574463a19cdea5f8ae32720e4d5c63a99c9e86ee9f720a8c1e7bd5b89cb22d4f749b773b00179cfa55c6260cf62fd96b424f1378e4ea0fbdedf4c89a4c843b1a46202e5bd472715e95187bc7918c9d9591f6037279af6af647ce496a2633d6a9001c60d53005c83d6a500a714301bdfb564c05dd5480319e29a0149ef542149ce281894008dd2a58087ad4b284fa0a4c04009273d2b36302335480339cf14c0660e381cd0509b7ebf8d000a085c1eb4ac03b1ce6980841c60536ac035813deb3010fde14c05c7d6824b6475aed46770038ef430b8638c52244c7ad5006de083ce6a580d0810703152047209d9818ca051d7775354c07a331cef001f639a8b00f00100f14ac589c01c9a2c028e3eb5aa20300fd687a80d6520e41eddfa53b011892652328ac0ff0074e31f9f5a9b9488ae6f6641886d1e527824e001fe352d8d2258e495803220563d71da84c1a26e0d6ac80601860f7f7a9011542a8033f89cd2403bebdaa98067afb54dc045a2e02e39cd2002702aae01ed4008c48e2a58003dbd6a5001edd2b4bdc038fca96c001bd4d4b601927a524ec812b95754d52cb43d3ae2ff0050bb86c6ca052f2dc5c3844451d4963c0ad210e765dac7c37fb45ffc141ee564b9f0f7c2ab3371261a297c43708460e3fe5846796ff7db8f41debd4a3856673a8a08fcf3f1678cefa7d4aef54d56e66bdd5ae24669ae2e646795c93c8cb1240ebf857a6a1c88e25fbd67037faecfab4a4067001395dc48fad29caec76d4a7a7eacfa25e43731481658a40dd39201e47e22bc9c6439d1ead0763dfb41f16a6b1a5433c44a89532aa4f35f2588a67d7e1aa5d1d0586acc42f987961dabc9e4b1e9295cb8f7ac632c9d719009c66b1e52e2c22d4ca3fef30aa0609cf534ec0d9a115e298c989c9e7381d07d6a248b4ee685bdf8922c100923efe2b0b9d30572b9bd64931bb2a3a0c551322acdad2c0bf306031c6055a8dc8919771e2b4b500eef99880013fd6b58ab99f358aede3d727e47e01c654f435d2a98d55b19da87c44bbb650636328f726a5c5952ac503f15afb7102dfe400632c7ad4a8b31faca4366f8c12c9030168cb229dac58e00f7ade3063589432dfe284d71186042b678e7814a51078946d5b7c48f26dc79923239e807afa1a5cb61baf735a0f88e2e2343b4927924138fcab9e4253b9b10788dafc232aed5e8460835cf2772d6e5c560cf96cf5e2a123796c5d9a6211767159c90e28a6f70ce3e70463b9c55c5133463de49b1f20e0b1ae84bde39996ede52880f2bef54d7bc0997adef4ae3939fad5a56136695b5f658027f1a6d13736a3bd36f0021b93ce3daad2b9cecaf75e2146b7948fbf1aeedb9cee039ae98c0e49b3ee9fd92fc253f873e11595fdf4422d4358737b20e85636e2353e842ff00335ecd1a76573e73193e6a963da3bff5adba9e7bf88327d055459360c902aa4c2c1d2a02e191405c0f009140c41dbda80141ef83f8d52061d471dea5820cf14009d7073d2801c4e281d8674279a90b08783d693280b0f4a9402d002670077aa01b91400724f3da82840dd738f6a005c8c67f4a0045cf7a526007d0d4a6034f5ce718aab0ac2d20b168f4aed46003a50c05a6037fc2a405e82a805a9b00dda29806defde95803a75a2c3b8a464d160b8b4842039ab4004e2a6e0348e99a9290ee71d6a921263481ef4340d8a00ec2ad885f7a90108c77a4f4002722a5300041e28002734003138c0a4d8095370024f635572ac04e4738a96c2c1430b09c9a698585ce78a9720b00049e07d2ad26c0f18f8e5fb56782fe075a4d0ddce75bf10283e5e91a790ee1b1c091ba20e7bf38ed5df4f0ce628b3f35be3c7ed55e37f8e37ef0eaf2ad9e8b1bef874b8149b78b9f94b1fe3619eadfa57ad4707ca6156ad8f12d4fc79169b68f6ef6569733487e69955a393fdedca4723b678aef5681cbcaea1c75ccedaccaf2bca5e024168a43995573d57d45297bc5d383818d7d2ad9ae227f3a11b823ec08c46780d8ef8ae571b2293bb394bfbcf3e627254310320e3f3af3ea3b9e8533b4f857e334d3ef5ac2776d9336119cf0adfddc7a1af13114d58f7f0b33daed351c8259db61c8055b1cfd6bc3942c7b7095cd7b7bd3220576f947030727f1ae271364cbd04aa17e43e693c107b0a562ef7346ca3da9904827b13d2b19a3446b464ac6483c11dab95c753b20c8e4064c02318ee0d54b426450b9883294ce79c673d288c8896c636a7a3b5c26f0bca83ed9ade2cc648e3aeec2e2d65cc64313c0c9c8191db15db191c72b94eeacae00588a48c0ae0b9fe123d6a9c919b6d9563b46b6b5652649242010e73b40ef8f7a95246128b655b8b170659a542c6e0ee2bdc00319c568a68766848f4298cc85030451c906a25510d266edbe8d24a884af98c1b2c54fdea8948dd26755a4682cac0b860a0f3e95ccf53a6099d6d94023daa80003d2b9da3a16e6b20c8073f8566dd8de5b0f3721005c6081eb42571b762bcd2963803af7ad92316cab3398f712accb8e428c935bc57bc61260b7030003803a645535ef19dc724caee70c72076ad5c48b97adee3632939a4912d8fd535d315b9d85776dc7cc781ef5505a93267227c5ecb677f732b2082da1691a46ee40e80fb9e2bd2a71386a3373e0bffc1533e247c38363a4789ac74ff16e8908110594182e628c1f942ca3e56c2e06197f1af7610d0f9cc4afde1f7cfc19fdbf7e107c63861b75d7e3f0b6b52019d335c7588e4f65933b1867dc1f6aaf657399ad4fa321992e214962916585c02b246c19083d0823822b9dc2c4d876467a66b261617af5fa501ca21eb40728127273d281d801e78a02c0091c7140314727a9a9608424639cd50585278a02c379c62a4634306e7b74a96c008e6931d84c8a9416173d6a8630b1c8e78cf3c75aa01771c74e00aab123a8b1437209e952029e4e28013a71d6934035b819c03fa55288084e064f1f5a004f9bd7f4a9b0170f3f8576dce717a1fad1700c8e95280302a90087a0a4000e2a580a4e7d2a98001eb4260273d2a1b1585245520b08738e693341010464d0800e79a44d8770719a02c19fd6aae201d28b809b80e29390d3b8a1854f3035711bd28d47613939e68b3276006a6e558071d29dd0584e73c526d00b529263133ebd2876013a0a952013209eb55afda0719c23643c29cf19ad1464fe1426ed1f339ff1bf8f3c3ff0dbc3d73aef8a356b5d174ab7059ee2ea40a33e8b9ea4fa0aa54657d494eecfcfbf8f3ff052b5f131b9d23e1fde49a2696aadbf53550d733a03cb275112fbfdef715ed51a0ada955363e3b9fc47078aaee69ccf1b86625a533334923139258b1c9635eed2844f13155a5429f3239fd775f6b0436d6970508e4aee040fad75bd16879d869cebae799c73c42ea577b92c8e79dc3f8bdfe95cdeccf51cd44ad74d2c0e3687dbfc2e1ba63b5449584aab7b195a9de2be4b6379043f1804f635c750eca48e52f2701ce075e08c715e5d43d58220b4b87b69c4a8d87520823b1ec6bcf9ab9df4dd99ee1e04f17a6ad611a330f350057078c1f503debcbaf4ee7b3467a1de5a6a414a0c000f7c1fd6bc99c6ccecbdce8ece65214a9539c0c11d6b296c6f037ed17746031ddbba8c5714b736468a26576819c74a4d6874264c9092c0043cf5f6ac5bd0d223dac0b827038fcab994acc24509ec486c15381ce6ba29cac4b8942e3c3d1dca80a8413c0c0e9cd6f1a864e028f06995ca105c11860470453f6864e9968781d6ec283682258c00a00c743de9f3d88f646de9bf0c6c2e98492a0f349e18fddfa62a235341fb12f0f85f691bef45c9c60ec1c1fc2b9e557517b2231e038ed432c5174e4f19269ba9734e5294da208db1b0295e959399bc6228d25e3c32819f4a74d9a728e113c670c07d475a1eac4f42bdcc7b9b2a718ea2b4892d9198ca8dc49c57498b958ad752a44b92e1723eb9aea82392a3d4ceb9bd5038dd2639609d7daad43533b915aea23cd0ac0aeeee6b44b42997eeaf4c56e4a9ed4a2b521c8e335cd7658e1118b80f2b7451f29e4fe9c56a9184e776733f143526d1fc091db966537f280300a9c2e1b07db38aefc3c6ece2c43b23c25ee51cb6e39ce79afaba70d0f9aa93d48566c1211d58024ec75c8cd68a9073e87d0ffb3dfedddf133f67731d9d86aadad787d7e66d135773240003c88dce5a31f4acead1ba093573f59bf665fdb1bc0dfb4de93e5e93769a578aa040d75a05d483cd4e065a33c7989cf0473d72057935293453d8f77f331df181deb955c1b1cac18673914310bebe95201dc60d000b827354029c76a4c04ce7a5500364f46239f4a570109e2a593613803e9d28450cc92dc671eb414267a63d6a40775c807daa8041db20fe3400ee0555c00f5eb5202638a6f50139dc318c63f1a168014804c8e9549801ea38a1b00c9ec4e2a40b6303f1aeab9ce275e945c05ebf8d0800fad520019c734804ea322a580840f5a180e1d284036a18001cd5200a4cb02284023120e053014f23140000718a08149cd00183d7ad4b57d86958cdd6bc45a5786ed5ae756d4ad74d8146e2f7332a0c7e279ada149c81bb1e707f6b0f83e9a8b584bf10746b7bb56da629e631f3f56007eb5abc2d4438bb9d3da7c67f006a16d1cf6de35d0268a442eac9a94272a3bfdecd62e854b94d156dfe3c7c39b9bf4b287c6fa1c975236d48d6f53249e80738adde1a56d8cf98eb26d7b4cb7b7f3e5d4ece38719f31a750b8f5ce715cee8c82e52d2fc71e1cd6affec561af69b7b785770b7b7ba47723d42839a25424909336c9e70473f4ae569a34055278c50a0e4071ff00103e2df843e15598b8f14ebd69a5eeff0057048fba694fa2a0cb37e55d50c34a426d2d59f2d7c60ff82818d3de5b4f05d8a471c4332ea5a921e07fb2bd01fae6bea70b91cf7aa7cde333685397251d59f2feb3fb6578dbc6977722ebc6377a7da468d3301742147da33e5a950a3737415edfd5285056b1c143158aad3f7f547c97f163e3ff008bbe287fa26b7abddcfa6c53f9f0e9d2ced2244c0150771396383cf6cf6af9ead18a7a1f5d87d56a7999d52ee32cf14cf1b32ed6dac4647a1f51ed58dda5a1d0d5dd85b2f146a5a5bcdf64bb687ce50af800e40208e4f2391d460d5fb795330a9423523c92d8e8b43f15db5f5c05d45cab95f908190cdee7f3aefa58a52dce79d08d2a7cb1dceaa7649c1dc16405463041e2bd48d9a3e7a6e49ea62ea4be5a32090c8879563d41ae6a875d09a392bf94ab9898f6c71d6bcc91edd3462ddb9f3318da31c73d7debccaba33d28a20527f0ae27a9ba7666d78775d9745bf8e4470109f9b3e958d48dcec84ec7b9683af2df59acc671b1b05493f2fd057955a9d8f4e33b9d7e9bab46268b247cbd064f35e74e27546476fa55d45344086c15e483c1c5724a3a9d717736ac09946f50304e00ae7a8ec8ea8ad0d7860c90ac00f7c57227746b12cc1044ec533c8e9c75ac6da8e44f268a97111600e47502b45a2296a245a584656da576e38f5a716ee29234a0b558beee49232463153193219a76b05be544a465b9d87a9aa948122c2bc56f31d803601014718f6ace2f417293dc6a290c48caa199802467a6474fad73cb72394ae75189d4062a8c7b11d6b486a0ccbbc8a39e56247ce7249078ad5a344ec6648883251836deb8a9a6c1c8ce9e58402bb46f07ad6f1d593729c9199d0b28da41e9eb5aa891232af2e8db46e1909c1e56baa11b9c72672fa96aab33e042c00e8e0e00fcabd2842c8e5a8c86de75e4199881c838db83e99f5a12d48e615aed1c83193f2f04ff009ef551469cc54d63c5b0d8da618879181555033c81ea29256666ce7746b37bbb94ba9ce1cb06c29ca9fc286ec6695d98df1e2ed19746b37e5963697b03927193ed815eae0f5679d8d7ca8f107404c800e09e87a57d552d8f9aaa8877850000001c015d317a9cce56342221a255703a704d6fca9a2b9f534fc2de25d4bc1dadda6ada56a3369d7d6b2092def6ddd9248587dd65208381e9d0d79d56923b14b43f5e7f62ff00dbdedbe31c161e10f1f490e97e331185b6d44155b7d4f1c631fc32ff00b3d0f515e6d4c3f2947da582a718c11d462bcc9ab329007dc081dab3b85850707a668b85870e98cfd2a908318aa005e39f5a601598084f6a180c6e9c134228683b7b5480c524fde1f91aa01e08f7fc680173c8e4d0035490cd92319e2a6e0487a55008304669ad400b0ec687a008791d69b01a720e73c5480b919c6280169d80b5d4574b30b0838eb52985851c1c5697107ad201a7d39a82c5e31f4aa4c04240f519a180a0e2a4043d6a44c0f228242800c9038aadca418dc6935cbb0697b3303c65e3ef0f78034f17de21d5adb4bb7270be737cd21f4551cb1fa0ae9a746a55d91156a4292bcd9f3cf89bfe0a0de06d235b7d374ed2f50d55d5b6f980ac6adeb8c927f3c57b74b28ab516a704b1708eaba9bda67edc5f0fb50b52f241aac374aa59ada3816420f61b83639a8964d5e9bd0c566542f6bec799fc59fdb8a7d42c67d3fc2569269019097beb839b8518c90aa32aa7dc935d743297157670623398464a14f63e3a8bc79a86b1a85e6a77b732ea7a8331956e35095a56c96edb8f18e3a57ab4706a2f623198c74e2a703c6bc737373ae6bf7171205121930c46141159e2a858f67015b9a8ab91e89e09d675ad7b4dd32cf4cb8796e66003db2065503e762581c0f9549e6bcf548f56e99d069575078e7c7d676b15adb917d7e9012aa06220d86607a8c2a9af725050a3b1e76f54ccf17f8e60d67c5daccba6472e9da5adc3c36c96b74e13621da1873805b6e48af160d3be875545a91e81f1035bd1b52b5b9d37579d2e2070d13c8cdbd18742181cd74c2319ae538eab715cc7ddbe10ff00828feabe1ff0a5a5a6bf6367aa6a102056d4a776432e07de655079f7efd6bcfad95453e626862b9f4396f881fb7578bfc436970b178a74fd252e141b7b6d294a6d53dccac7730fc457761f2aa71573cfab9bfb295a513e7f6d5ecb50d6e6d7754f121d7b599c971b1ccd21ff00641390a3d39af6f0f84a71678798674e51b4626078d2ea5b9f0ccf68eead3bc866240e796c853f406bd797bb03e630b79d4bb3c23c51a9b911da42d88e2c9638e4bf43f857cbd6a9ef33f4ac1d14a0728f6cec4b38c81d4e7d6bcc944f4e12b0cbfc4370e8d8455f954631dab1d8e9bdccd775c9c0c919e2b9e6869dc8049c823e420e463b573a7629c6e751e16f17b5a379172c6489be55cff09c7f2af4f0d89e8cf1ebe1b9d5ce82f18c90e410164c1048ce057a69fb43cea51e59599cb5e44412ed83838e6bcea9a1ee5077312e4ee7c81803d2bc5aaf53d2868322e5f00139ec2b1368bb0b22b46e548c1eb8359c8bb9d4784b5d7d3a50849319e4a678e6b86a46ecf428cb43d7744d7a2b95531a06e30ae7b1f6f5ae2a94ae8ec8c8edf49d5834f1c6e448410b9e9b87ff005ab8270b1d70a87a5691724dba97da3681d0f4f4af3aa23d484ae8da87531b72e0103b815c915a1b2d012e634904824553bba77a94b525c8d9b4d48042ec542e704e7afe15335a9711e3508d8b630c71c73c0a57b21c95caa7573693ef605828dbf29ec7bd38ad08b58b0d7cc0c770f9247cc01e78acecee5dec4526b6eb68ed8da589e4f714d45a05b0b67a925d5be7ccdc7d01e98a1a32bea4c2fd668b0c090a7697c67154a22b90dddf79419a2384ce39079aa7117318979aa4704a9246dc1fbc00e288c75334c80dd1bc73b06e1d735d325a14d9567bd30c7b036d72091bb8071d6b486a44e5639ed675e4b7457203654f7dc09f4e2bd1846e705691c97f69096e25de4a67059477cff3fe95df6b2386f7259e641b4a3048f838279f6ae7b5d9462ea9a99b72c43b0451cfcd819feb551562f9ae6641a7dc6ad225cc8ec76e76a63391eb8a9923448eff0045d37688c91f2121986dc018ff00f556727736842c78d7ed0064bbf14c7e53fc90c2ab9cf7eb5ee60227859823cb4dcbc518330249e39afa3a723e7a624720b87c0552739c1c0aeba6ce168b119f2f20738e86ba53336f53434bd86728c037f4f6a971b9d0a564757a4ea2d63751792f87dc1924e8772f423d08a89d3ba36e7d0fd25fd8f7fe0a0e9756763e0ef8a577b2552b0d8f88cab156e70a971c9c1ec1ba1ef5e356c339174a7ad8fd008a649a28e48a44963914323a3065607a1047515e34e9381d0ac3d5bb0fc7359a1343f231d7bf1ef56807d2601dbad20118903a50033df07f0a07b8d639ef498588c800e07ad22ac3d581e95216024f7efdaa84009e00a68a40b961938a910e07b6315a5c419c739fa52402647534300c827ad02421eb9f4ef523178c7e14009b88ed5405bdd5d0cc8339ef5280419aa1587039aa0b09eb434311ba76a901401818aa6018279f4a90038c8f7a91313a50481f7a521b01cf4eb4e3a88f15f8e9fb51f863e0f4335843730eabe292ac22d363725626c706523ee8e9f2f539edd6bd3c2e12551fbc73e22aaa717286e7e78f8fbc7facfc46f143ebfafdedddfea6c582976c41103d1628c70aa063dce326bf40c1e1a9528d9a3f3fc5e3aad69f2d5d11e4dacf87ee6de613c723b4cadb95fab67d735e8fb2b3d04f191e5e4b9d0689ae7dbec06496962f95f9fe21debb5462a3ef1f3f564e94b9efb962e755db6772ef962236fc7e535cb28a4898d453a91b1c3e8dac2a19c088e000b83ce0e6b929af78fa8c6297b38f2987ac5d2cda85c1c0da5883cf06b8f12933dbc04daa28d6f06de59782ecb5ed56cb596d3f586b2369a6c4e1b6f9b2101db2bc280a08e7aeeaf3f94f7e15077c32b83a2ddeabafea76372ada5584af6d3c51fcb24f28312671f291f313c73debaabcaf4ec8d2946f3b9c4d9697a45b59795f6cbe322f1b16d4b83ce4fcdf89ed5e328d91bcbe22cd8e8d617b7c16c7563bd416315ec623e7d98123afd2baa841dee70e2e492b16356b2d48cb0c3258dca1501976c6cc1b3d815ce78f4aebacdcf43870d0e4bc8ec2db42d5c5b2a41a3ea170981f76d59c13db9c57a746294753e5f17efcfde342db46d4f4a837dde89a8db0c9c3c96ac153df001aeea4e299e5d7a178fba61789356b5d2bc3fa9c923f9d33404424a9521cf0b80402793fa5655e7ee334c061fdf4781c9ba5986e2401ea735f293f7a67e814af08d889d4c2247c8381918e99ed59cb63a23b9cfdcccf248c5c10c4f3935e7ce5667645155a4246718c706b9e73b971447bbb62b9ae6c33a1f4cd4a6e1b18bd343aff000a5e2ddd94f64e409946f886092c4751ed5efe0aaf32b1e4e2e1ecbde21d69d5b68407ee8dfbbfbddea311a236c1bb981292ecb81d4600af0e5ab3d843e3628b84e187561d73e94ec3192a9490ee259ff8b3d8d73c8b45cb105c140bcf5cd73ccf4a91d67873567b248e3033b49c363201ae59b3a11e8da36bef0ca844adf301820020823f5ae6a91d0a8caccf44d0fc433bb66524c4b80c436319e9c77af3654ae7a74aa1d7d96b22e729e60500f393d3d2b0746c8ede72ddd6a090924e08e003dcd73286a67291369fabc9756d9c14863ce4be0127b0a73a772a350b76be24366ce488c3a80433024006b09d3d0dd4ee50d435af3a5b860df3e03100e07e15af26827216efc546ce2895e404b0006474151186a66e430f88c344c669331152570bc102b7953482152e8963d552cdc081b691d4e7824d63c82e6d4b96be2f783745b02bb1e98ea31d4d351093b0b0eb924b6f77048f8f9771f5e7d2ab9485239cb6bb8e4de4481541dadbdbbf6c1a6a1a987396f44d5d2d8c9e6391b5806673ebc0c7d6b59434358cae51f106be96d73292708abb998af50476ad69c0c2b4ac79edd6a69721c40b88db382a723eb5e9d38a3ce9cee558ef5e290075dfe5a9041e847a1ad67a2324c86eb5c436e009db9604a15017f3ef59535766a8af6a1b56b81238f94103cac0c63d4d26ec694d5cedb45d1599908210b020a03c562d9df181d232ad9c24292c4000e3ad4455cd2d647cc3f17753924f1a6a67cd2a89b506e1edcd7d0e0b43e731ece3fed082208e41271cf6af7299f3d3435ecd644f95c6eebb401cd75d3dce4686c72bc3b4329209ef5ab763350bb34e29fca8010000dc923ae29a995523646d695711c8ac252dcfdd53dcfad74a772764751a36a0649bc89d846303681c64035bfb252464a6e2cfb33f65dfdb675af836f0f87fc4b349ae784c3011a49f34f6e0ff00cf3627a67f84f18f4af1eae0b9ae6d1afa9fa65e0af1c687f117448b59f0f6a116a3612000b44c37c6d8fbacbd5587a1af9daf8774d9df4e7cc8e80723f4ae72c51c52602839ed4806939ea68010f4c6681a2166381827ae29334487741595c0683c8193cfa76a603989c74f7ab1586e738209e7d69a283715c7e478c52068969198c27f0c5360296e011c8c508047701413dfdab4b090abd323a76a9187079c127a0e6a4030178c55016ba1ae87a18b17fc6a4108383eb4ec3173cd1701323342015bfc69801e38c55b0109c76acee019c545f98360ea7fa5349315da393f1afc55f09fc3e8f3afeb96b61211b8405f74adf451935d7470b5713a4119ce74e31bc99f1dfc63fdb4f59f17dedfe91e083368ba2424c2da93616e2e8e39643fc0a3a71c9f515f5183ca1c35aa7cee619b42853f75ea7c71e22f0eea126a777a89ba92e5e793cd7df299257623ae7249afa6faaf2ec8f170b9bc711a5625d1bc477174fe45dac56c06155b761b3d3e6cf7aeaa6b94e1c7a53d626b5c9080ef2ae98e4fbfad7a118f31f2552a38cce73f756f7d94f91643b5801d4d6b6e53a67539e043aabbc305c9e02f92f8c119fbbe95cd5d5a273e0ddeb23ceb4cd44add38c1daca1b9eb5e5529da4cfbdc547f7488af650d72d8ee739f7ae7ab2b9e8e1a36a4845b910412b950eaaacc41e9c0ae74ae7a4e56b1e8bf146197c01e18d2b40f0fdd5dc306a70da6b9a8233ef52c62f95541e815b2d4941c91b53c424ec7983f8ff00c42783aedea376d92ed1f9018af2e6da958f4f993573474cf13dff00d8659f52997550ce76a5f2eeda00e70c3047e75e85156d4f1b1735cdca83c39ac587f6dc730b8d4f46922ccbe6dac81c1ec14292180c67be68a69caadcbab2f6544efdbc43a54d1ee1e29d7f6b825a216ced93f8c9c1af663a1f24f733efb56d22da3536dadead0b939df2425173d39dae49ad6e4f2f39e69f15b579af25b381b587d5628e32e8599b0858fddf9803d07eb5e6e2a5747ab97d2513ce816d86527a1c735e325667d02b115cce2388c64a48739665e7f2ac24ceba51306525d99b0793dfad79d50dd2d48d5413cf03b9ac1b469d4499a3dd88410bd32f824d67a152d8809ee6b2627b1d4fc369d22f175a472bec49c3c25f19d9b94807f038af47012e5ada9e7e3e1cf48b7e24d267d26ee58ae771756237118079ed5e86261733c14afa1873a08208c22133b12c49ec0f4fc6bc9946c7b16b14d8792c73d73d739c56496822376de73cd724b7378a36b4481d94b01c36462b8aa3d4f429ec6fdac1b0647040c7bd73d4d8e88ea6a5add7d86584ee620740a338cd1d0d39743afb4f131b78088dd73804a9393f8feb58daec519599d669bae8b881241194761b8a939e4761eb52e96875c6b1b4fe2b4768d4100100e5b939ef5c7ececcd79ee27fc2428ec0000b96ce33d067da938dd8b9ac2dd7889c92b2b31daf8e79cfbe6b39d3d0d155487ff6e7ef17cb62c188241e8d8ed50a0cbf683b51937857dfb181200ce41279aaf644f35c6dbdca3d9f905d9650c7383c7e06aa6ae8853b127dae3470242d9c6411df1db3eb5872b1f397ff00b5a2566640ccaca0170c0367dbf955ba6294ee675d6bf2fc8f1311b7244a18838f4c77aa8c0953293f8891512131090ab1618fe2fc6ab90c94c48fc4e9196c9c4672093ce01ec4d5aa7a0dd5b199737e2eedcb090b2124ae491b483d39ad68d3e546539dcc96ba589f7871b9880703f5ae9846cce6bdca17daba440824b16fba0f526aaa3048afa7d85c6a52472b828a586011d81f4ac6323547a0e85a2ab8f3b0432b7240c023b7e55cb376674d28ea769696eb6f18770391d33dbd6b394f43d38a296a770605674e4f3f88a7197bc4cdda27ca5f112d6e65f115dccc8cd1bca5b7f55c13ebd2bea70b0e689f2989a9ef339b2ca24c603293918e73f4af552713c7bdc2379126ca1231debae1239e71b972dee05c318dd5723dbbd7439730a3018ac6191b206d07a1e847a562f41ca26ce992f9a778393c015d749d8e771b1d258c84c6aa641bd738c9e71df26bd14ee8f3ea68ce9b4ad6936f952ab3860304f6ad631e6dce3a93b1edbf047e3c78bbe08eb89aa6817a4d9b605cd94abbe39d7fba5723fc735cb5b0519ee5d2c5729fa8bf01bf694f0bfc78d1d24d3a5fb0eb4aa7ced2e7e1f23ef1427ef0fd6be47138395367d053aaa67af0c8e3183e95e5caf1d0e9684c803a567b00c39ce68dca10938e71edcd363430904e315250106a6c0216dbdb393d051601c78cff002aa4026edddbe954c05ce793daa407027071f850676139e9e9ea28341c7af4a09633d2a842e0edc02680141c30cf43ed49006dc7a5302d918ae8918b10e4f4a95a82038db9ab180e7a5660154805079eb4c038cd36007159808d80a492028192c7a015518f32d095a3d4f97ff695fdaae0f0ce973681e04d461b9d7656f2ae3568c8782c463901ba349e98ce3eb5ef65f96baeeecf371d8e8e1e3a1f09eab36a5af3dd4f3deccf7374c5e7beb872f2cc73cf5e6bf41a384a74d7ba8fceb1f9a4e53d1e840b11b3b45894b6c5180c4f3f8d7a10a6ae7cad7ab52acef2641e690b9420e7dfad7438a614aa72bf7b73175cd3e2ba8fcc4012719390386fad73ca99ed53af2968de867e91ac48ead69338f394fcab8e48ee2a235394cf1d87e5f790bab447cb59704321dc31c1cd7427cc79945df4664eadaa49269f388e1666f28e5b191902b9abbf74f570343f7c8f39d3eeccd70e24061740accbfd3e9c5781195a4cfbdc5c2d4902dcf9aee4809f313e958d491d7463fba4364ba4367282480cac3007af159c667438ec7b1fc65962b3f88573007212d74fb18155cfdd0b6ca7f91af5f0b69c19e7caf199e4fe3df073f85758b41332c50ea16516a10ae48d8b20380d9e878af0ebc57b43dc52b5322be7b7b4d1913cf08c00033df279aed51e5a773e753752bea2785f467b802489e391df381b80381f5e2b6c253bea6d98622cb90dd9cbdb4792a415e0e2bba5a1e0465ccce72f276bcbc86000e19fe62c7000faf6ac798ec8ab1c878b7511a96b323a9c2eee076da3818fcabc8c44eecf77074ac8c1b9b8c6705485f9461ba9ae3a8ecae7a3ecddcca695ee25db1fdd5e319e3deb8a52b9d94b41b7769b3cac49bcbf18f43e95cee373a1321bad3a5b6b9785cae50e1b69c807f0ae574d8dee2ae9fbb0a258f39c7defe75a4688a6ec88ae2c64b76c36d207756045655616b096b11da55dbe9fa8dbdd272d138603d714e8be4aa88a91e781eabab6a163e20d092e1f6b4a9f2b479c39e9823d715f4935ccae79145fb3958e175380db44f33801e463b1474503bd7975e163dd4ef1305d8b139fd2bcb72d0d010118ec47ad71b3789d6e8c33a672029ebb8fdefa570cdfbc7a149686bd9c224881249239cd455d8eb8c4b50401b05c1c83f2f1cd61cda1a2d8b12e9f246be742491dd718c5107a9ced6a476fadcf6d27ce59a3cf4ddd3d40ada32ba0e568dab1d78c8ea50a8c9c02cdd3e953caae52958d98b5ab610b3afcf363a03c8f7ac5c2ccb72207d69a7954b4a57680570c3afa52945588bb265d7dedc0532073c9193f74fa9a71a68a7368b569e2179dc412104e3796cf038aa701c6a32fc5e205803ac7229dc3a71fceb1e44c6e44f2f88625b79632479c182a639c9c73fcea7d992a6638f114b6d2287e7f84af43927ae6b594109cc5935c0e363b00db77291d339e949409e7b19f2eb81ee439214018381c74c55f213195caafabbc80a27cdbc8cae3eef35b460ac29b167ba7c7cf3151d4afaf1fa564fdd264ca573a94af36d88075c001b9e0d2e6b0e3a9a3a2e8d35f5c6f9019083f739f5cf158559e86e95cf40d1b4131dba99142a96f9477fad71aa8754699d8e9962b6c84b2fddfef74ac6a4f53ae9c6c5b9a40130401c640acef73a8e775e98c90301804e7241ebc1aeaa4b9a4615be167cc9a84daa6957f712e08b791982861e644c0fa8e87f9d7d9e17dd89f1589f88824b6d275b50558e937b82cca4eeb763e83232bf8922bd9a50e73cf6ec646ada35de91708973115de03a3039565f50470456b3a7cbb19f315d0ed3b81da49eb5cae5ca74a45b84898842075ade1ef994f42ea44d66f98d8b0ef81d2baadca73b77342def5656d8e36b7057d339ade1338aa42e6e4776d6a41392540cb0e95db4ea1c1529dcea343f12caa104b3ed8c9ea3ae2b76db389d1699da787fc4ba8f86754b6d5b43d5ae2cef2270f1bc4e46c208f98004735cf5682aa77d3aae07e86fecbff00b7b5a78e6fed7c29e3c234fd5c8db06ae70b1cc41c012e38563ebd3d7d6be631982e4d8f62956e647d98463d0e46411d08af9d9ab1da260679359a2c8dd4b360b7cbedc1fce9b1a1028c9ea31c66a5144990319a008c8cf205002b1c8c93cd08045c7d0d5300c751d2a4055185dbe940067b83400678cd04b141e40e9f850203e86800c9001cd340024e3bfe74c0b87920574b32133f2e6a512c01cf7e94ca007bf7a0033bbea6801718228010fb529596c231bc5be2ed1fc07a05d6b5aedf47a7e9b6ca59e691b1f40a3b93d80ad29d295421cd44f81fe3d7ed67abfc4b86eac74d926f0f783dc95448db65ddf7aef20e554ff7548e3ad7d86072d50d59f398cc7bb348f0c86681361bb23c88c130c0bc2203ce594753f5afada54d53d8f84c562e526cad71e20b3693994b678502335df0d8f09de4ca526a76ae30253c9e856b48bd4b953d0aef708c7113ab7b01cd6a67ecf4e628dcdc82083f2d44a49154d39338af125fc56ee9259ce1e6cfcccbdbdebcb94ddee7d4e0a9aa8bd9d435b4bd4e6f10d936f68a395140750c003ee01f5f4ab8d6e87162b00a84ef0d8c8bfb99cda5dc625c2ac5200071ced38acf112bc6e7561a0d4a279fe937923dd48253e6c613e5007eb5e1a96a7dc62e17a3133aec86bc952273190c4156ec73d2b9eacac7561e37a64572932dbcb28941d8acc31db8e7f4ac1cae8ea8c343d4be3837da7e205fced3b146b1b298b1ca921ad90e31eb8fe75ec60e5ee3382bd3b4caff00b406a767ae7c4ed48c418da5a4367671824e116381381ec189af36acbf7874b5681c16bfe518e111676eecf273f2e2badbbb4783878b55dc99b3a7431c16114680a900739af6282b23ccc74ef3626a1772c31ed89c91e8c78abab1b9c544c8b9bc3a6e937ba84e14cd229b681460e491f3315ce400bdfd4d79b566a9a3dba10e6679adcea676bb637330c0f615e054aba9f51461ca8a0e65b965dc46c5e3038ae49de674a3aab730e996318b49e09924399629e3f41c64918fc8d7459246307a98dab079e5f3d638e2c7f0c2a157eb8ae59d8ef8a2b48caf20999368620900f53f5ac92b035666969ec8ecee2d202a0670ebbb1fd6bb28ab9cf88968675c4915e6e0d02c19e018c1000fa56338f30425dccc4848770187cbcfd6b91527736bd8eaf43b947b55b39c1b7bb0c1a2de08deb8e95ead2abcafd9b3cea94f95dcbbe20d104ba325c0955a6de50c2a0ee0319ddf4ad6bd3bab1d10a9a58e2fc9da8723046735f3f38f29db0771a80920570c8ef8a3b1d32202c9507208e38ef5e7d45ef1dd13774a857e604f6c54d4d51d91468bda496f1a4ea0903d3d2b8aacae592efdb19058b6e239c7f3a54c9e4b90ea7a009a3f35094655e00fe2fad69cc4b472cef2da3980827d76f6f4fad74c59934587bb96240439cb74e3a0a5b9172cdadfbc5012c4fcbcfad1c831cba84ccf903703cf27ad68b43364ef7970aa1e3043120f3fad37a9717613edd302871d5b6f3efdeb270d4972b9706a139daec0e50e4283d4e2a9c34291596f25951f79241208f51fe4d12192fdaa7765763904608a84ac4b05710c837bab863ca839aae6d498122dd4e9293120dbb490d8c9cfa62b493d02d763f4fb1bab8b825d8f3d9ab926cb92bb3aad17c2eeee24c7719f97233ed5cf39d91b2868773a6e991dbed3183b7bc98c135c73a97475c699d25b4115ba8da0e71d4f5ae24eecee842c59f38b2f2c3f9668668a2433dc6011bf03d00ebfe154b42da39bd6577c3337072a5411db8aeda3a3386b3f759f34ea7777de1dd526b678d7c82fbd61986e46193ce3fc2beb70b51247cad6a6f99b23f3ac7576cc256d2ec9e6227f76c7fd927a0cfa9afa1a328db43caa9168965d46eb4bdf637f109e307e7b79979e7ba9eabea08aed4ee73a4625c08e3b87f20b988f2a24fbc01e95e5d58f2b3ae3a09129c63273eb9ab83339c6e6a595f6dfdd3b7cadc1ff00ebd76c65739a712cdcd9965df09048e735aa81caf40b1bc78188986e881000269c67c8115734ecae3ccb98845233938da54f20fa575c67cc454a573d3bc2c881111e296e65e32910e56bba99e4d5763ad974982ee61ba2834fb90331c925f6d61fec955e39f73deb2ad4b982956e53ed3fd903f6d06d3e3d3bc13e369e49ad40f26d75379048f6e070ab213c94c606ec1c7d2be531b826b53dda589e73ef34962b848e68a55962750c8e8772b83c8208e08af9b9c1d33d253761e79c0ac5eacd043f5fc2aa4ac17b0d249619e98a650fc01c5480c63905483cfeb400d0d8fad34020248c91d286039480bcf5ef42010a16e0e4f39e2860296c1c11f5aa401bbe6c06238ed52c00312326a4033918069a01319039ed5405ff005ae95a9ce27b76a97a12c3e50c68b94291cf6a2e01c014c04a00e67e227c44d0fe16f862e35cd7ae85bdac5c24791be67ecaa3b935bd2a2e72224ec8fcccf8ebf1f753f8cfadc9abeaf34b6ba059bb0b1d2a0270476c8e8ccddcfa1afb0c2612315a9e0e2710e3a1e6fa7ddb5e4a3519ed7121502285c1c44bd718f5afa3a10713e531151326bbd65fac50aa75043ae6bb54ac783349b32a4d4df8de576f538502ae32d098d1572bcd7304cc4bdb479fef2f5c51196a6f38248a0de52a93048300f08e4e7f3ad1d4b1cb3a4d42c8c0d735e9e6ba7d3e096325c055571f364819c11ef91f4ae39d63d7c365f6a3ed1942c6d97458a57d461591c9ce48ddb7e94aeb96e7a7ed9d46a34f739d8f55369acadcd8418566f961c17dc33d31d6bcbf6bef1efc28ca74f967b9dc5ee893cf6b25c5e5b5de9b6cf0b38b992065463b490aac460938c575397340f0634b9248e4fc3fa1e8571a848b1ebf2dabeccb1bbb42c83d4650939fc2bcca51bccfa7c54ad46273fe2ad066b4d4667b778b508558625b66dd9cf7da791f9715cf8a8f29d582d699cfcb3016b364b05da770e87a735e6a91e9a4ac7a6fc6801fc7f789293bd74db256e4e187d9d483e9d315ee60be06726223ef191f12239af3c71792dba3c90dd5859dd294524b8681016f53ca9ae0a8bdf29c3dd39113c970f0c6e5818d95186391ce7915d345f348f2aad25493923ae8be5418e07603a57d2535689f17527ed24ca5768d24c8a324b1ac6acb94d29ad6c711e32bf4b8bae18148c794a1471ea49af9ac6546cfabc0c34d4e67c96b88c6405e79f7af3630e767af29f2a257b71008c0c90d8078e86ba254f950a9cee4f7113bf968149cf43eb5134ec5535a905c5a4b1e3792148ec78cd61c973b5cac5c5b503c2fbdfeff00dbb62ffbbb33f8569187319ca7763edece7d334ab7d52321ed6e2792d594f5560a09fd1bf4aa5ee326aae64269da57dbec2468f0c54e092315d14e09a38abcfd9b31af2d1ed660afc31f6ed58ce1cace984f9e373a8d4f4db1b9f0369baf45ac412eaf1dc35b5ce944112c69fc1203dd4e07e75c7d7da1d2d73c4a3a6ea7212a5ce4e470fc8aef5579d5ce1e5e5958afe28d3e0b0be9c594e971680fcb3467e57c819c7e24fe55e5e25591e8d177322142f227704f35e2b67ab14769618f2d428c0da3827a57155677451b5a7e52e368e475dbd6b9632b9d316759a6c6264242028783ea3f0ae499ba454bfd34dac800f994b7071c0a707635b05a06f2b0e4305e01069993454d434686f6367507cd00e2b58ccc5a39db9d3594286049c761deb5848c2c578ac9fcbdc17e53c6339addc8ae52586da58db206718db91c9a89cac47296249a6cb1298703900671442570b02dc3b7062c81dc91c5373d42302c099d936796a38ddbbd6a9cf435e5b0ab134b1f420818185e9ef597319d877d90bb223b67800e4e01fad1cc5729621d1cc7228081d40249f4f6ac5cb51a8686c699a2bcbf315db9c7cccbce289d5b208c353aad2745f9d7112b0c7de3dab9e7335e4d4ea2df4d55525dba10768c004fad725496874f2e86820dbd7a28e0572dee8ec844996519232727d4d42dcdf6059c9ea31ebef5a581104afb9801c03d4e0e2a5e8449997a849b86c8c6141e4e38aeca4f5382aea79978c3c3316b36f2c4e83ce562c8e06315eed29d91c15696973c6ef2da4d36f248d81564f94ae3a8f6f6af6f0f51a3c1ad4ec695b6ba9359b59dec22e164202ce0932c58e81493d3d8d7b14ebd8e15125b716bac38b7b8916d66550b0cedc237a2bfa0f7ad6ded183d0a57ba6dce9b398aea268651d15bbaf6607b83d8d13a7ca8cf982de1df2f230b83cfbd4d314b52ea5e9b50aaa3764f22bb39ec724a25d7fb3dddb84420b2fdee3906a2dcc4c158bfa7e9d2da3452931a8e195c67e51efeff00fd6aeba6b942723a8d3357613f976e5e28b015893f79bb9cd7a303c6c4c6cae769a45a1b87568a3625875e82bb12b9f3d3a8e2cd797c392dcc3b90c515cc673149e700411ea7b7359d7c2a9a34a39872b3ea6fd937f6c6d5fe1f5edbf84bc6464bef0ee0a2cccc1e5b66cf55fef2f5c81f80af94c5e5dd4fadc2e395447e8c68fadd8f88f4ab6d4f4aba8afb4eb950f0dc42db9581fe47dabe4eb53f66cf7612522d92769e7ad63f11ab429e141a80119c0c103a9a9b80ac46480726a808893dbad3400781902aa45584c93c9a80b0edfc7269364831e4f1d2a9008870d9e7f3a100e2d9c76fad5008cd9c8ce0e319159a01ea463bd3b817b3835d699ce2139a9602902980d2334980b9e2a1b21eace57e257c49d1be15785ae75cd66609144a7cb814fcf3be38551fd7a0aeda345d41cb447e627c5bf8bbe20fda23c68d7faadc9b3d2ad4936d609974817d80eacdeff9d7d46170ca2cf27175f921638fd1b449755befed2bad124fec8833f658af6416e1c8e0b11bb7649c915f4f4e9d91f2988c57246e6fb430431c324565a3c11b10de54d785d88cff0016e6c8aec8c6c7cecebf3b16e6c65d4656d9a57876f509e3cab85e187a1de05393358aba2b4da21e62b9f87b2dd98d73bf499a5dcca3a9015994fe552d8a1a1cf5d69fe1ab9b89764da8e8456307c8be0b32ab7fb454061f955459a2d59c3f8b6caef46b013dbb43a945212125b47de00ee4af057a8ea2b96bd4e87a797d1854a964705a5e9b35c5c8d459d8056241e40627209ff3deb992bea7bf8b4a953f6702a7887c4b3334965339962c6338f981fc2b9eb57e5d08cbf2ee4fde331edf5092ca7592191976fccaeac55d4fa83d88ae355398fa1953e55cc77ba378aedf51d06686fb5fbdb8bc505d74f996492373d06e666c0279ed5d54ea5e0cf1aad2f78c8d13c469f6c9a29f46b29d1547cf8647273c80ca4018f5f73461f5933a7171b50453d6b50d06e3586956caf3489f2b8b886659f60c63032149fc4d72626ded0ebc02bd12849676d7f6b7ac6d9f508d622bf68b322271f29c3143c9e9ce0727bd79f38a6cf520accf49f8bba7685378e45c8d6ee2049f45d3a58d67b3672c7c80b8254f0381ce3d6bddc146d45a39312af220f1ca8d22f7c18da16af2eebff0cdab4d7b3308823233a150d8e1463ad73555ef34696bc4f3ebb37326ab7125fb452ce8b8f3e22089c0ee587de3efe95a505cacf3f12b9958d1b690345c9c0c715ee4269a3e12a41c2b3313c4dab4761a748a8dbae25f936e705411c9af371754f5b0785752a739e79047e7b649240e157debc3945d43ea1b51d0d4b5d3472f8fafa574d3a76396a54ba20d478655248dac3181f9d55489a61e432e772b28f309c7cdeb5c7511d54b7238d0cececf291dc71d2b08c4de7237a7b2b98bc21a77dd68279e6940650ad81b5467b9ce0d77421b1cb29da655bcb9923f06dada10b86bf967031cf2aa0f3f8572d556b1bf35e450d12796ceec9191138dac09e0fa1ad2868ccb114f9e3cc59d7ac9a4884ac85633ce715d75e9f32d0f370f56d2e5672f24fe5921411cfaf15e1545cacfa383ba35f44b49351b9448995ae18e121270d21f45f53ed5d3057473c959976fac4bb3dbce1a1603715652197eb4ab526d174a76285be9d2dbce8258248d586e52ebf787f787b578356934cf5a84d33a6b650b1ae0649e0e3ad7875ef13d78aba35602222ae17e6c7209e6b9e0f98a868ce9b4960e8ae0b06c62b9aaee7740de8ede3994ac8b9046323ae3b9acfa1d0d19777a4bd8b3363744e7e56c63f3abe6ba33641c0393f771d6a22f530688e5b486e036f18246001dab48cd9972941f471106646650dceccf15bfb41f295d2d9629099c153d39fe9473915225a4b48e49015c720649154a7622c3ff00b2114b0206d033c0eb9a4e7a9bc5589bfb353215906474cd4cea684c89a3b550704841d40ed9153ce4462c962d39266216032484e785c919ef53ed0e85035acbc3f34d3a02045f5ed8f5a9e62f90dad2f476b16944ae2689d81556fe1e3a0fe758ca435037214ce305571ebc560ea5c718d8bd0aae320ee3d3a718fad60ddcea511fc65b23047419cd64cde2ac37a15ca9383e9574c244d1401db2e488c72706b6721bd08afa6445c211e83fc6b37a98b661cb8720050d938dd5d74f4472cb732b5ab20586300e33d38aeba733392ba3ca3e206841c0ba4401d3ef053cb57ab46a6a7975e96879fc4c629839040041391d6bdfa52b9e035cacddfecf8b5481ee74e56f94132da6d2cf1fab03fc4bfcabdba2d4f56734d96f47d561ba822b2d611eeac172b1ca8079b0023aa93c903fba4e2bb6dcda338dcac1ad7872e34145b96912eb4d933f66bd87ee4a0f620f2ac3b83f866b8a70e4d8da9cae61b80916f627731385ff001ac14efb9a72f331fa4c4f24cd207d8230187d6941d98721d143ae03882e5c862c0bcce78cf638af4b9f438ea40e9ac2d10bc53001893f714f07eb5ec5249c4f0f16e49599ded8aba2a4d713c56b076258000fa735d8e718c6c7ca54536fdd44bff095787ed176cda85b48f920fef738fcb8353ede2610c2e265f0a286afe38f084b6aecd7d1c77310dca62525b239c71d6bcfc4d58b3e870187c4537efa3ddbf658fdbb2dbe116a4f6b7b7934de1d72a6782527632f39641ce1c7b707b8af0abe1a15e3747d2d1854a53e58ec7ea3fc3af897e1af8b5e12b4f13f857538358d1ae0656585b2c8ddd58750c0f622be4ab52f672b1f417d2d13a5f3490383c722b93a008ce3839e3358f500673c9045691d0761913066e783dc6735770b0fce720fe94db0b0c93819048e2b3d8a1a84e7924d0c6c9030279a1085032d8ab6401eb83eb8a100d2d86e98a458e04e3a7e66820d2ebf956e99ce255301723d298099c9c66930327c51e27d3fc19a0ddeb1a9cbe5da5b2166c72cc71c2a8ee4d6f4a973b27a9f975fb46fed0777f197c6812198cb651feeedade190f92a3ae01fe23ea7d7e95f5f83c2a5138f1153951e3bad6b33cf243e1bb2bb6b6b5389af042db439523ef30e4e38c0ce3a57b1470ed33e7711554e9b65e72ef282257748d42f209ff3f5af769c6c8f8aaf579fdd2148a776c20213ae594f3f855b471c5ea457493aa90228ca8ea180e2b16ae77fb54915a2f15df691731fd92e6e74e68cf0d6f3321fd0d0e16338c8bd75f14ae85a6dd5258357b58c16637f1297ff00bec0047e75849f29e961e1ceec71f1dc68de32f12896dee67f0d5cbb7c859ccd6e540eec065413f5eb5e5c9b94ec7d4d3c3470547da2dce8fc73f66d074d862d5ac45d45729b6df53d2e640d8eed9030d8f4600d6d5a5ece070e1a157155b99ec78e6b3e1ab86b57bfb2946ab60bfeb2783efc633c191472bf5e95e14db99f674a1c8b94e625b8fb3c0ce46f08b9c7af1c73deb14dc4bbddf29e9df12a36f07c3e1af0a470a5ac7a769d15dcfb76b4af753aef94bb0ff64a803b57a9878de279d5a2948c0d0ec1e795e642a50a9e14d74e1e3ef9c59954e5a2647896d644d4e40636185539c70723ad7062e0d5436cb2a5e899280a866c90fb48ca92bc77ae0717cc7b4a7a9e8be22d7e5d5f43f0a6b33422f4ad88d2269e45c3196027ae3fd965c57b5839e8d18d6ee5df895a8da6abe12f858d69a7259b1d1ae2dddc48599ca5c360f3d07278ae79bbd6685096879e6a77674bb76b772acacdcfcb9f9bd41ed5d135c8707373cac59d2673169f0c92b6f45f97ef64923b56b4aa5d1f3f8ca294ee72bad5b8d4f5092746901662591ba039e029f4c572565cecf570d254e99259e9b1c30f20176017763ad694e9248556ab4b98d08ed9946092549f4e735d1cb63879db30b57da92e0e376ec8c7a571d43d1c30cba50c540e1b19fc2b964ae765296a470831c872791ea2b3b1b49dced3c47188b47f0adb22a961a525c373c6e91d89fe42bd2a11bd8f3eb3b4cc3d7ed523f0f68a361494acd2cb9f52f85c7b60572e221b1a53a9ef1cda178d8827257a73d2b8a2ed23bdcfdcb1d9db4c9a8e92b91821407e739fa7e35eed34a713e62ab74aa5ce0356805ade67185c9ce6bc0c5c3959f4d86a9cd12fe973c0ec89b7c87dc02dc862420cf2c547271450773699da5e6b9aadf694b60d776ba85ae3868950b951dd9b1bbf035e97229238dcf94e7e4b69a38d1d6419c9555771838e48049fd2bcbaf451d987afa9a708963553342606e0323a95707e87f9d7c96268dcfaaa33ba34a3018a027a77ee6bc7b721d4b73a2d2d802a0f427ad73d43a6074da7b1db92771f4ace5b1dd1574682c514f13a3286c9dbb5802304739ac23225c4c7d4342923889b4c3a96e6224e71ec7d284f521c0c812658a15652bc1c8efe95ba919c624f180701b049e001cd0e60e2366b74924da429047193cf1549994d5c6a69891f23f77818f94f1f5a1bb072122599db82e481dfaf159b93b9b389616c958a92e5830e7d4529c8c9c4d1d3f4b467c98c9c6482c6a5b358c0deb3b548d41da015f4159391adac5b71b8838e7d4f4aa5209a25f2d49dc08031d718a8948b822c44002b9c1e781eb5ca572d8b0a382c3919ce338fc2acde289a3b56930028c95cfcbdbfc6a196588acc4601958f0d803b7d4d5533321bd9da30c80f0bf2e57a7ff5e87b952312e650fd013ec456d05738e4c92d6dcbba9c76048eb5bbd1183dc8fc4768a5954a9621403818edfad4c24ee5b5a1e7fafd90b847880c86186271dabd3a527732ab04e278feaf662d6e648d8ff11da33ce2bea70baa3e56bc6d229d9df5c585d453db4f25adc44db9258c95653fe7b57ab4db4ee8e0944ecf4d8acfc6cacb14b158f888b0616ec76417b9ea549fbac3fbbd0e6bd78cd5456479d52243a6eaf75e19d5e6826b149ade0936dd585e29d929c6086f4233c11c834356dcba6ac3af7c2d6be2966bbf0ab493c4bccba43296b9b55192c41e9220ebb8738ea38ae3a94f9de87541982eeb630f968a4bb0c82463f1ac796cce942453442de2692326e37160cc032e3e9deb6bbb1cb5123b3f06c374b708cc4790c4093713d091c8fe75e9e1e6cf031b4e3395a2707e3cd52e4f89f53b417b25c5b5bdcc91464392a555b008fcabc9c462a51a8d5cf4f09858420ae8e71ae3706ce73fa573fd6a573bdc60be1428918e080437a8a73ab2922d4236d513dadc4b6ac8c5b0bb8363358c673a6ecc86d2f751f7dffc12a3e36ddf85fe3abf82de4d9a178a2dd87d9598955bb8d770751d0161907d78ae4c5d3bae63a6368c6cb73f5f7702e47a122bc1e855842709d3bd66b70b0c520822ade831a58c6738e4d0980a8e19782284cab01e783c8228648dd8a9f746066a96a3648a47a74a1e821c09dd9a4c561cc7ae684162238ce719a63143e28034fa1cd741ca263af5a09b8b9e38a56bea3dce37e297c58d03e10f879357d7a6748e495618618865dd9881f40a3392c7802baa85275dd82f63f373f69bfdb4b56f895afdfe97069b268da2e9ced041199b3248c78673818c329e3af06be9f0984e56734e49cb991e1ba5eb3a669fa10d54db4ad7b8d850b6e45cb63e5007181fcabe9e14d247cc6371ce55fd922e785e7b2b884dedb4f034f3924a4aa54af3d093c75e6bd0a503e5331c43854f666e9fed104b84840ec030c576f2d8f124f5b14c4b729292e11b27a7a7e554a3746719588a7d425552ad6e5c6339cd118ea3955b14167b3bb526ead00238dc49e295486a109ec717e39d3ed24b13f64942cb236d00b7503af1e95e5e2b447d665cb9ea291cbf871b50d123779222d6e5b0ae30738f4ae1a31b2bb3e8f1b5124a9b2387c757d67a94973637460392ad0361d1c770ead907f2ae0c454bbb1df81a3eca1cc8d2b2d5acb5aba4b9d31edfc37aebb6d118936dadc13db247c8cdfdd3953d38ae2be87b2d1d77c34f87765e24d4354f10eb56d1690be1a0b71369f72a16db53bac9f2add5ba2966192bd08f4aba54f9d8af63839ee353f167c42173a9d84971ac5e6a4b24b65b48fdeee1b508ea154003d302bd670f63038ea24d8ed0acaf86bdae41335bc33d9cf2c4f0da481e10c24e4230e4a8ce07b54e05f34d9e2672d42898baededcc3ab48b200f8555c30e318f6a8c646ccd72869d0336e75362de59895147538c735e649687b37b48ef84d6927c03d06e1832cf2f89ae954742556150481e99c56d83d6a955f58585d3ed1bc49a3785c654dbe8f7b7566cec4640923f3101f6dc1bf1af4274ef5ae70e22a7b3a16388f19c4f75aa18a22af1c440047049f5acb111e67a1cb81bca9dd8dd2c911881c28c0e589c0c53a71b22711153441a85818ae0327cf183c807a7a1fa569639a12e8356405012a48e9c0ad2d6267a920792442151b0a7b83551d5182d2273fabb2fdb155d0ab67a7715e6d57cacf6b0f1fdddc597aa85e176f39eb59335a6364ca02492d80491dea0dd1ddf8eadfecd73a0c0806e4d0eccb63b654b01f91af470db5ce2aeb533bc77088e2f0ddab208da1d1e176523ef6f677047e06b9f12ee14d3b5ce024da5895049cf3db15e4c4ed4ce9bc2f76111adce096e7ae715ee61647858f8df533fc61620664007fb5ef5863a8de06f9755e873762e219a3cfdd2c0647d6bc9a10e599ef4d5f53bfd3ded6d65591a058d88c670013f53e95efc62ac7858993e5490f821b6d4b47bcb09154cc18c911ce4a9cf0d9fc6b0ab4d31466e138b39f8359ba85cdb5c666894e017e594fb31e71ed5f2988a4cfb4c356563aad3da3961564393e82be6b130699eed27cc6d5903b108e3dbd2b8248ec823a6d2d8b0001073839ae6923b9337e388050140241ce7fbc3d6b82fa9b45dd13ec0c318e07502b494b4128dd94b52d2e2bd669658403b7ef21c374ad94933270b18973a1cf1441a31e700b90a4fe950f521a28b3cc8c91c911465c1208fe1fad6adb32e52cc532364641c9039a4a4cae52e40a33b4e403c0aa9334b6869dbd9c44ee2472369f4ae7722231d4d08e34540420e071b79a4e773a230d09da755523a003a573b90d475104af9200072319e947b42a51b9660b59265195f941e0939e6b394d8463636ed34df3305d86dee50fe94499d162f25ac10e3081b3d3776a23225a09ee42001471d38c01f5ada424b433ae2e093d5b3d893c565d495b99777705ba1ce2ae3b99ca45782dda571939dd93c8c574bd8e692b9b16107cc984240f534ef744c6241af81238daaa38ea2a63b9b721e75ae295925c1190081b8f1f8d7a50958c66b98f29f125aa7dad9948639e08fe239afadcbd73a3e571dee339efb3b19829c29272327ad7bd1a763cae688f9942a850a339e08e307d47a5293e53096bb1d5d9f8a22d76d2cf46d7c93e5e161d61577ce99fe193bb2e71ea456b0ab72942c684de1fd43c0935bea19963cb1305fda3128cdd955870091d4751d0d75ad46dd8b0bf63f88f7134bab91a4f88154b1d42da12d6f778e009635c6d73fde5e0e39aa54bda3f78c9d7e54759a57c1fb4b9f0f3ea9a16acbab6a7671896ef49bb8c412c510fbd24273fbc55ea475c76a2ad3f66bdd39d54e7671f7fe32b3d05708eac4a86503a939e98ae69578c6267528b9cae7936a376da9ea175758da6576908f72735e1d45ed65a1ec528f246c5758f7119e33dcf7ada34c99c89feccf0853b739e783daba234888ccd332c5259849211e76ee180e40ad248ce0ee7befec39a89d07f6a2f8773bc71babea0a9be56da13702370278ce3d78ac2ad26e274c2763f7b590c8c644daf11390ebc83ef915f2ae2efef22f98819c1e87a1e6a397f94d13007193ed4492656a4133963c1208f4a8bf2949b08b29c6771eb4089cb00327afd2b44c91339519fca9142ae47414123c3639a9900d772c4f3c5110100dc7f4e2a8040db06379a00d6e86ba0e3614122f5391f4a6bb148f88bf6dad7346f1a78c2cf454bb768f48b39edf5178df09199769653db2157f0240afa9cba8a8fbc7156a963e17f10fd8bc45e2496ee389add65090c31b36e608abb573ef81935f474a1a9e555c43841b1d7d6d6164b6ba2220796ec732bf507bfd077af4d40f92a557dacdd77d0b29e17b5d36d561b690b82db99ce339efc0e95e95347ce63b10ab55e723105ce9855d26250ff0939c8fa76ae967039f33b929d41ee1546e11b83ce3b8f4a70d8d121d25f49085120dca4150ddd7e956b4673cf7125782f17180ca060861d7eb594ddd9b2d11e73e3c8125d492181bcb11282401c924e7f962bc5c61f6593bfddf332e436f268da0627559a38d32c08c939e99fcc55429a54ee562710f1188491e57776a5a57961015c9c904e6be6eac6f367dce1a5ece9a8b22b359ee6e62b48a2325e4ce22863ce03bb300a33dbe622b8e5a1dca573e85f881e2bb4d1b47f0ff00c27d599a4d2f40844d73ab5ba8f3535190166627fe5a22642e3a8c1c57af828a7a9cd526d15a4b43e07f0649e29d565d9e2ad4d4da7857548ce239622a4493364602e38527904e2af1b574b22a106d9e61f0d6292d3c4ba8db5d022416adb8120b6e2c3e6f7cf5cfbd3cb16b73e6388d38d2d0afe2b1ff0013a9557030a083f4ed5be356a19254bd0473d76548c971cf5c8e95e672dd1f477f78ec7529223f0d7c056ca0905b50b9628780cd2aafe1c2d75e0a95aadccab54b3b1d0e89a74569f062e7537711c975afb428a7a911c04023fefafd2bb2b7bb56c79f897ceac79e5be9b2dbb19ca99114904b0cf3f4a50a1ccaec89e26387872a21b9b7594ef8c6e04ee642318aca54da661ed79a372332b140493f21e00f4ff0a9899a44d15c9739445c8ea08c8fc8d68ca6384f2a30445488f5200c7f3a5f0c4c5f6397d55bed9aa31946f01860a75381d79af22a3e667d25156a2432cbe4ce00532295ce4751f5ac65233a4125c1922942213f2b638f6a8e63ae28ee3e2342eb369d2c921129d0ac48407a0f2411cfad7af86f81b31ab0d487e2dc36f63e35fb109a44fb3e99609f38ce1bc956238f76af2ead4bcd97086870aeb26e390b21c920a8e715ce8ae52de8974b6b7513ec650cdb5b775e7fa57a786958f37154f9933a3f14d8196dd980e1876af4f134ef4cf070751c6763ce0421250a01043722be6a5171a9a1f689fb973a9dad2e22043b85ca907e63f857af1bd8f29ae75725b3696ce54994b12463e5ee3d2b4e56ce45252762dea9a41d4626d42c959815ccd0ae49047565f6f6ae4af8656b9df4312d3d4340ba900d80027a807815f238dc3d8fb6c055e7475da5918c0dc46010bdabe5a7a1eec4e8ac0942e48000e001d38ae29b37b9d169ec182b80177738cf2b5c4d6a75436349231b72467279c0a999b4371cb09fbd8e73d2b25309a2bbdbee8f2411cf002fe95a739872dcacf60245c346181c06f9413ec33daba1d441ca33fe11cb771fbb431f1d178a4a685ca5bb6f0a2cc0159b0c31f78f359ca65a5a17ed7c1b291f2ba360f52ddffc2b1bdcb844bcbe169224c3153ec5b8fd29266915a0e4d0846e4bb81ea473594c8ea584d3adc0180eddced1dfde8521969628bf8011b7aeee6a5b1a2724e32063031ed50e46a472cc3660139ea38ab83158a33cc70723247bd74364db433a4601c9381bba93df02a5192dc8110b96e1b0393c1a9e6d4cf94b691101492c00f5eb5d1cda136352c3e588b924f181c6302aa0ee812b197a8bee0c38c0c9c83c8ad2268dd8f3ef10308d9c81bb272403d6bbe9ae6671cf43cbb5c9cc77c980486c920f6afb7cb572a3e3b3277d88e5b017c89b15a49981da8ab9dc47afb57d1dae7c946534f531a4b6944ad114264246140e7938ae6a903d4a72ee6bd8e99f634c480bcbb48d8a33b7dbeb59469d8ea72b9bfa25e6a1a7dbc96cf71b34f600ada336515baee0bfdef7aee83b1cd3d4d3b6f89d67e18d265b1bab0b5bdb49b73a128a268a4e79565e40ee41e38e95acf10a8fc47272391e69abfc47d52e8c89697325b2124078d886da460ae47407d0579589c773e913a2951e5391925699f73b166ee49ce6bc67394ceb4922d5840b712057628bd588f4aefc352bee5735f434e0b6492419c0887dd24735e972a31a9a1a7f6750140cb6ee0fcbdabaa9c0e0954e521bdb3512ab20c311f373513a7666946773b7f84be245f0fdebb4f6d05c44aad896542cf1b1e01461cab7279e73dc52e548d9b773eacf0f7ed41e2ef85163e1a974cf16df4769a8da1be8e2998ba2a6f652acade854fe15d13a1466bde457358fa2fc19ff000515d6eca2d3dbc4da4e9dac595dc2b34573037d9a5901246e039520904738e95e157cba949fb868aa58fa67e16fed31e02f8c33bd8693a9ad96b8aa0b6937e44530f75e70c3e95e456c0ba47446b267a7347b386e1bd2bcc71b3d4ea5342e71494462f53f850d5890edc5201ca485a09108cd002ae4f5c7f5a0023cae402003ce29b402edc1231de901a9ce715d2958e413381cd4b6079cfc74f8af07c29f064b728e1f59bd060b084ff7f1f348de8aa3927d703bd7a981c37b59dcce73f6713f33be2878a716aba74b2bb5fea04ddddc8e4ef78d892a189ea58fcc7fe035f5f4e9aa4cf0673e691e6fa34da55af9d7f712179222c61b68f977603f2efc678af570dac6e7859954e4828f730ecfc6365e20d79ef1ad1a2b88d772217caa81f2f6c574d395e56396ae1fd8d05e65d93c58b0094c56aabb9b9f281249f7af4533e5ea51b896de345988496d95b190723fa0ad398c23872c945d40ee88346c4f009c0ab848da9a76b102b5ee948cd340d343b8e4ae580ff3ed4efa9cd3a5764f1dfda5ec6cf0b8565fbc338da7dc76a873b4587b2929248f3fdaf7faf0695cb7ef89ce3f841e2bc86b9e47dd538fd5f0d76749e299922d22744c6e9485183d141c9fe55b55d2363c0c15375b13ce79bdf5bc332ede720e438e00f506bc270f7ae7e84a7aa467e9f0dfd86bba4ddd93e2fa1bd80dab840fb65de369da78386c706b92b4353ba9cceae4d1effc53f1365f0e5e6a467d5ee3536b5b9d4265e4b162cedb7d86ec0ed8c66bb68feee8b2fe361e3ff8a8be2ad77fb3600c3c21a643fd99a4dab3736f12311e70ce70ccc0b11dc1c5796e773aed6b10f826f4c7e29b6b5b8916e248ed6486199483b90ed60a4f7c638cf4e457b197bd4f99e21ff74641e388d135a729c363904f6aebc5ab1e3645fc2395995c92785007535e3dcfaa8b3a8d5b6dbfc3ff00031030a45f440af5244c09c9fc6bd1c24ada1855776745af02bf0f7e1a69a818c9731deea6c3ae4bcc1147bf0879addfbf5ae7354f723ccc6dc690b630ac2802151cf724f7af5a9c2c8f8fc4e2bdacb4398d434e3b4bc44a9079e38c57254a7766f42bb4b5312ed364caeaa540c0280f07deb8a503dca12e746b41a699cc6d6ee8b24832b13301bb9c6013c67eb596c6938d99b07c07e229e3c9d0af58aafca4424f6e0f1d47bd5b5ee1cb7b5448e36ebc1bad5ade3bdc68d7d0ae0b163092a07ae4678af2a14ef367d24dfb3a499ceea39b5b9de846e51caf5fcc57156d263c2fbc86bdeb35b49b42a651b2141e4e2b9f9fde3d18c753bbf8ac8cd3f87c82db64f0ce9aec5ba67cbda7f415ed61257a4cc2a2fde1a1f18fc25abeb9f147c4274cd2aeaf12dd6dd18c70fca316e9ce4e062bc7946f73a52d4f2dd4749bfd16609776b3d9bb7189576e4fb763f85676e826bdd16f2e47d80061fbf0548603904735dae5cb048f3d2bb773d02374d57c370ca46d0f182c7a907bd7d1466aad2b9f17513a18ab799e756f69b75560406507381d2be7d479ea58fb0f6bfbb4cd39257b5d403a028500c32f15bca5ef114bde834742900bbb58ae10078e451b48001c0e3fcf7af629da513c4a92f633770b68e7d3675962624038653dc7d694a9fba66aaa9ab32cdf5847727edd6815496f9d40e14fd3b578398e1f9e9e87d264f8ff00653e496c6969b210503a857fe207ad7e7388a4e9dcfd1e9d54d7ba753a7baaa9901568f001e39073d2bc66eecea8272dce8ecd15cab01d78181dfd2b9e47743446ac00c6b92304f1c8ac0e882d4b915a998020123fd9e0d725eccd144b49a6aca1490719278ed5a37a12e240fa630c6dc301d78e6b44ec82c316d5572c542138ce7be29467a93ca4cb1904609c8a872068d0b77310e403df00e6b33445869d5f21be518a4dd988ac002b82431edc5549dc9b1387c2e110ee1d791545b88e65562724966e49cd45c8b0d78f79524703a106b9a6f51d8a922630403f788ce6b58968ad38f94f3823d6badb319141be76c6411dfdab34cccb56d108d41e318e48ea29bdc1b2cc56cd230232a01efcd6cf444a2d4b858c81c01c734e3a03566606a4dbb2a01da0127d0fe35d74a1cc126ec79e78b2e9adc1744c9c82147522bdfc261a4d9e3e26aa844f18be9eeeeb578c3a1491a40a8ac719627007eb5f5b4a3ecd1f1f567ed246bac37161a83c20aacf0ca52608db9588f43d31f4af4e13699e6d487bc747696d673db2aacaab20192edf2ede79519eb5dcfde47139384c7df436da35a35c867915b015d88c67dbff00ad50e0a074d29f31ca6a5e27b4b5d36e8012cbaab4cbe43023cb58f9ddb97a924e315c35aaa81db08dce5bc4f7769a8de25dd982826456962393b1c0c100f71dff001af2313539de86d63179c62b8dbd07603cb7029adc468589c38278c0c66bd8a5b10cbb039f3b0b93ea07a576d3227b1d3d918d2df820cbd48c74af461eea3c4aef9994645325d1c776c511573483b23a0b2b177b0492df9323105231f3145fe223b8ce791e95855563d1a2ee765e2d69eefc2ff0fd002f21d2a708304722e5c01f8e71554a4dc51bca3a9d9fc57d14685e22b1d234b96748b48d2ad2c7f7e8549664f31fd890ce7d3a573ab733b8dd3d0a9e1df1c186eac9ae671a66b76402c376bb952403b31072a48e3d0fb5538c2a591c8fdcd4fadfe1d7edefaefc36b0b21acdc47e23d1c6d8dac2f18fdaa2c1e4c72e0ee001cfcd9ce3a8ac317974276b1d346ba91eb7ab7fc14b7c240489a3786ee2e2e0282a351b8581189ff6b047eb5e7ff634af7b953c4a8abb27f077fc142f4ed4350897c47e1c8ecf4c972a97da5dcfda36b0fe1dbfc5f507f0a75326935a339e9e62aa3e547d3be05f881e1bf897a0aeafe18d5e0d5ecb76d7784e1e26feeba9f9948f422be7ab61e545f2d43d2a7554ce84643720e315c725c9ab3792b08bc9048aa648fda08c0a940371b69942f1e9fad401a80f35d4d9c436495218de4918247182cccc701401924fe15718f3303f3bfe30fc4b3f18be278b93318b429af1f4db272d8d967082d3c983d37b0fbde98afb9c061bd9d2e63cac44dc9d8f917e22f8c1fc5fe29d5354963fb20ba99992056ff57183845cfb281d2ba25791c9ececae43e10d3923d12eaf1118349213b9f91f28c67db9af6b091fddb3e4732973e2230287843c3b0dceaf733dd4c91a6d2046bf2f53cf3e9f85452d6a1ede61ae1a2757716fa558a32a0590f18c2ee38feb5eb58f8c9a30efa6b7727ca83273c12a01a051462cdaa5cdab92808079ce2a54ac8ea84131b078eee639364d23641e00e33597b57713c2f3324b9bcb4d5949f305b5c3291bd0727ea3b8a4df321d3a2a9c9366468d0cba6eaaeb74e8c02e23954e7783df1dab920f965a9eb632a7b6a0a3122f155f096ebc985d5d63e4f1d09e7f3a3133b06574bd9eacc25915b21d36e0f5ec7debcfbf53e96d6773acf83da345ae7c55f0fc53a06d3ed663a85d9db9022814c84b76032a067deb39abd8da12b46e739fdabaa45e393e27d0004d52eaeee1a159177b812975181ddb0c307b1af4ab51e5a48ac3d5bc8f3ebdb67b395d1d4a98dda3619c9dc1b0c09f5cd7cc4e363d155399a377c15a8983c4fa4e73bd270176e3a90460fb577e067691e5e794f9b0723a2f1c3a4dae1c0c381bbea0ff00857b38dd8f9bc93f84ce76440ca416c71c0c578a95cfa78b37fc42c63f849e0a383bd2f35451b7b0dc879fceba30cfde68ba90f72e75f730addf88bc2f6f0b87b7d27c2f6b9271f79cb3e31d89dd9af530b1e79b67cf66d5bd950b8fd463f3031c80c7ae4d7bc9591f0919f5312e6d0a29e0b0ef8ae7e5bb3ae955bb30351b34926720642f51ede95c35207d161ab72942dae05ab1ca89216c6e47ea3e87b1f7af3a4accf5a12e73a492eb4c9f48df1788e481a41b3ecd7114a8ea7a72ca48c7bf151297ba11a5cf5131fe15d22e6512dc69fe22b086556d8124bc78d88fef648c11cf426b1c36b2773bb1b3f7144e63c650deddea12c9abc705f22cbb5afec995db18c7de53839c7f10cd79d898fbe7a1825ee9867c37a5b5acadff00093c098566311b295a4000385c8f949fc6bcfe4bc8f46f667ab78eedb4db0d5b44b4b7b26d6efa0f0ee9aa649c6638e4f27728541c1e08fbc71cd7b78385a93392a3fde127ed0324d77f16f5ff00edcd63fb3cf9766df65b72651ff1ee831b6320291cf5af376b9d6b73ca7579ed36476d15e4b7fa7c840956e536bc273f791727a77c1ce2b05acec0f639af1040d6b3fd9890c57eeb0e0303d08fa8a75ee9d8e6a71bb37bc2dadac7a15cd8c992e6404396e00e7231f5c57ad87a8d4794f0f178453adce88a5b06b799657237373f29ed8ad79141f30dbfb0562c1a491f9c312467a015e74dd9dceea6b974373c1b1cf3497512a968e28c4accbd1496c0fc0d7a385ab7d0f2731a5a5d1b575118c32b82091dfbfb57b2b581e0424de857406ca6f3d0131b0dae839dc3d2b92a53538d8ea8d47f0a7a97980b1b9b76794c96f21630ce57823a90ddf23a57c76618156763ee728ccdb6a333a5d3ae8c243e701b9cb0ce6bf3daf4bd9b67e8f4aa2aaae8ea34c951b6c88b820e4ae7839f6af264cecd99d25aac722ed2060f506b33aed646adbe9e57050e57af5ea2b866f535469c764170718f4149c8a6385886c8dbc0aa94f426c4126925f9500906b9e12d476293d84819b208191db38ad54ae4342c762d8c8c83ed5b2d4121c6d58609f5c6319acaa3b31d816d1870037038069a7744d8788188e782476eb59fb4b96c9a3b6239393db9f4ab4ee66c734406071cfad6335a8ec675d958c30cfcdd88f4ae98474033af66ce40076faf4fc6ba648c643608cbb2f1b474ce3158d8ccbb0c601c1395030bc63bd5f5068bd00c64e38ad6452562aea1700290462bba14f9f444f99ca6a3ad58405a379599ce73e58071f9f15f5180cadd4d4f9dc7e62a868717ae2e9da9abab4ef10cfc8d23ec05bd081c37b66bec6960a34cf929e39d738fbff02c2b2add3ea51471fde05c0057e9c9cfe02b7744e58caccccb8d22eb5996336d1f916f02eddc06dde49c966fc6bad50b9955ae94892db4b71241696aff0069bb964daa4f2a1b3c814e6b90e54d549153e246ac20bcb7d22d2668adec611097503e763cb1f6c12464573e21bbd8eca34ec709a8e9cf0471ca1848b264efcf535e3e2e0ed73b13b15208bcd7084ed527938ce07ad79f18b91bd88caf35338d82c478efef52b73335ed5629140e3006e24f73e95ee525a11224b276178849e4b01d2baa97c46351fba747aac62dd4088e7ae401835e8d5f76278d15cd2d4768b64ad7114b23660c82d8e6aa92ba2a6ec761acdac767736923492405a1dd0ca0642af3c7ae335857d0f4308ee6addf8822d6f51f87b62f3c92428df6291dc6146eb80c4afb60e0fb9a9a524e276cb72ffc45f15ddc9f113c57726f5487d4a5896191f7aec460abf2fa61473c735c324f999af36864cbe23b5bb9b7c76c923261c34ac485c1eddcf19eb5d745c5d8f3eb6b06cd8f1ace25b7d3e1db18170ffbb2abce3827e9c0e95edd5845d8f128d671932ba0964b850d9393839fd2b4e48db638de25bbc51bda35edb345716b34e2cda3c343293f216048652474e39cfad4c2316cc92717cccf52f86bf173c65f023c47a7f8c3c3ac2fadc304d42d639375bdfc3c655ca9c6e039563cd7979860e189574b53d9c2e25a67ea6fc14f8d9e16f8fde0bb5f11785af9260ca3ed560cc04f6727f123af51839c1e86be0713869537696c7d353a9ce8eef807e95c2cd8524f5cf152806fdecf34ca067e7ae680357ee82490a0024927803d6b7b5f43892b23e52fda6bf6a6d253c13afe87e0dd461bcbad860bdd455c08e052db0aa9fe224fcb91c57b584a16b36677bb3e16f18f8f22f09e85a19b01f686d67c3d716368e17885bcdf2e49189ead8dd8e3be6bece12f70f3eb42f2b9e67671e916aaa7509ae279368fddda6dc818e0176c807f035cc9fbc74285e2743a85edbb78584769657b696de49705e6196079c92147f2af6e8e903e0719172c62386f03eb16515e5c207c120646f0587bfd2b0a2fdf3dfcc63fb848eda4d4ac9d143487e6c2a9009c93d3a57b2d9f18e1a94d1a2965db124a7bfcd0b01f9e3159dcd5c7422bcb2650d90bb4f2491823f3a5cd70a68e6b52d204b92877123391fcf8ae7923b0e7ae526d3983b5c08f839c839359b93474722948a379e298da18a5f36362ad838386c7d0e3f5ae2ad5ac7a14285e5665a9678b58b77bcd3df6ca98f36de490331cff00103c67e9dab353f688e9951f6322ac170b2651c92c78c1ebc7a567f0b3b97bd13d33e1f94f0efc29f1c789f7ecbabf78bc3ba791d40621a76c67fba00ae8c3c7da564ce7acecac55f8596b6d71e38b3bbbb0bfd9fa4452ea5721baf9712923ff001e2bfa57b58db463632c33b33c5ae9c49a8cf3b9db1deca6521b9dacc4b13fad7c9d58e87af4e56658f0ea1b1d660bab855090ccaca41fbdc8e71dbf1abc2c2cee658f9f361dc4eb3c6c53fe122ce08f903640fbc0f61fa57ab8995cf9cc921cb17730ae42b1cb60003af7af37a1effdb3a3d6f4e693e0c7846505955b55d515481c90761c01df915d5854b537ab2b591d3f85a717f797f7810a096dace1d8d8caec840c7e06bd8c226ae7c1e7f5eed44d1b8b5df200074ef9af652ba3e3d4ec882e34d291339c007a7158f2ea6b4eb599cfdcd9a2166032c6b9aa44f5a9d677398d42d02b3391f51d88af32b40fa3c357d0c5d46e36db20552149e59bbfd4d79159eb63e870ed4a573a1d1ed843a309dcaa008cd8278f51cd6b18b51b9c58a9dea58e39afee6de57681fca2c7071f77af71debc6ab2773e930eac9169b5f79a39526d334e9279f6c659e0c67240046d20679f4acaeceb4d5cf54f8d1e2bbcd4fe256a1a6c13ad9d8442cb4f5b7b2511ab058e3521b1d4e4d7b787bc69367155b3aa729f1aa16ff85bfe31508abb2ffcb18ff65157f3e2bc57ef546cebba479dea21a30180dac0e1491c9f61536e5626f41d6708d6124b67256f123df6e5870d81c2127a1eb835d76e7473a9599996d9b4bd0086192032a9c1ebc8e6a693e49d85369c6e779e28d3e07b7b636e4c6d2ae41cf61ed5eb56f76173c0c33752bc91cb917001062120ce095e33f8579527a33dee4573a3d2ac6ea2f87de2bd4c32c119b9b4b484953bd9b7176da47a01cd4d37ef32e74935a97f43d563d62cb32e05caf0ebe9db3f8d7d150a89ab33e331986745dd1a2d6c02e0019c62bae513c952b9561d903bc170e56090f0473b0f622bcbad4935ca7a742ab4d491b9a4dc1b0bc3a75f904820249907823239ee08c7d2be1f32c072de47ead95e62a7689db43a54b6ee1e36241afcdeb41c1ea7dd534e5a9b36771244aab839fef62b2e6d0e971b9d26937a5982b80b819cfb57132923a2819276521b19e338ac59563523d3470590904e370ff0a5d01227fec8611960ea463a639ae5bea6a8a7368b22ff00086cd384b521ad48bfb11c11f26d047ad75f30e4b423fec77eac01c1c74ae794eec56192e9bb5c823f2ad6f744d88e3b12ac3238278e2b2484d0e6b20d9c039f7e95d08948a9789b0630b91c7ad28a0473d76e0b300338ce315d51d0c66515525be62371c7cbdb15af3e8668bf6b672cc0120281c85f53ea6b152d4d122fc562080cc30c074ec2b6a7efcaec184f2089723a63a577d2a12ad3e58994a71b7ef0e33556d435a918dbecb6b5279694e188ff0064761f5afd272bc8d692aa7e7d9b71353c25e3435665dcf84640a717d6a64033867071e80915f650c2fb3f7608fcdaa7107d66ee6b539ebdd1ed2176b7b9d46d92560311a86724fa8c0adbeafa8e9663a5e5131350f05dd3ee5b770d103feb9c6dc0f604e6b49614d239c526ed25618f632595b2c0582a1f94b1e063be6aea4152898d1c47b7a97456f0d5e0d3f45d6b5e19f2ed50d95882b9fdf1eadf82f23ea2bc694aecfa5a4acee796ea37934c6217199998fdf71863ee6b86a3d4ee8947546549bcb463246bdfdfe95e7e25e874532cc76507d86da78e450eecde62938da06303f1a884158dd6852d62148ae7cd88a18661bd761c8507f84e3a115c95a2ae436568215b86da0ed38a718464c92c26eb72631c8240048af463ee20b1afa3db11771cac014048c1e33f4af42947a9e7d67734aea42d72e01dc849c0f4f415d92f7b43893b1d3685e1d92eeca364f977b659c761df1ef5df4a9fb963cbad88e47725f132ca2d6dec892c6d7312f987270793cfd6bccc5c7ddb1ed602afb45739eb867b6d1b4b9d1bcbbab79a65620e767cc194ff003fc8573d08dcf4e6ce83c6fa7e750b0d66dd898f59b6f3d9f39db70b85941f72df37e34abd2338cec3347b648a3cb1c315ea4719adf0f0e46725595db47405e7d7afa131480c36b1c714249ce588cb903ebc67d2bdf82e73e7b109514d9afad4c966b69668dbaea4e59fa6d3ff00d7f4ada6ae78f0935a9126972a4613693b8609f5cf7ac545a3a2156e69684753f0f4c64b4b89adcb00af0ab652451d994f07f9d438f7348d56b63bef875e3fd6be1f78ced3c5fe16b85d0b5cb57cdd59c526db7d4231c90ca78c91907f31cd7858dc3c66ae7d2e0714b691facbf04fe367873e3bf83e0d7740b98ccaaa12fac0b624b5987de56079c7a1f4c57c3e230ce07d1c256477fb57181d0f2715c1156dca8bd417e5cf19a690c4ff003d29145cd574f5d5b4bbdb17728b7503c2cc072a194ae7f5aeaa7f19c2de87e21f8d34f9fe1b7c5bd5747bebe5b8d36dee9b4dbe2c8591edc31f982e78607918e86bec68c5282220ae5dff00843ef2d9fc45e14d494b6a290cb7ba5a9ce6d648e3f35980ee248f6e477c035ecc61685cc1ea79569dabddf855edafd131a832f9b10994318c1e8d8390091d8f3ed5e7f35a474435563bcbff008afacea7e1d33cba8bdc4de48c870adb7f0c6077af5e954f70f8dc4525f5a4ccaf06f8b26bab9b8bc58b4ff376796c5ad13382792303af1461dde6766692e5a491d24de2bf11428a61b98e08b90ac96c830a7b648af6da3e279eec81bc77afc69e4ff6a4de59fbcaa17630f4231c8acec766e841f133c4b6f06cfb4dbc8aac4ab4d6713fcbd87ddfd6b08954e231be27dcdcba0d4f44d26f62230de541e431e7a82a78a2474b5a16b45f1c7869eee513595fe8a482639e231dc22b76cab0ce2b29b4552bdccbf1ee8773e34d2e6bcd22ff0043d65a38cafd9a2863b5bb271d594801bd739fceb8ab45347a1839bf6b63cc7c1fe29d43c33a85cc6fa1693a80ddb9a1bfb42aca5781f32e0ff4ae2a1a1eee312ba2f78a35fd13c4378f75a7692da15e901a4b58a40f6eef8392a0e1909e38e456b3d58508e875fe32d46db48f01781bc2892096eace09753bd9107c827b83911820f3b5700fbd7a7804949b38714acc7da4c9e14f82de25d52451f6ff00114d1e8b62ae307ca56124ee3db851fa53c6d5e69a4451563c62f544a19b1b4f60074af16acb53baf668b56f0a5ce88ceb2a2dec6431c9c0241ced39e39fe75d14f45731aeef3e565cf16eaeedadacb2a12e224f948e17e5e98febde8c448e6cba1cb7315f548dc82f950a3a9e49ae1e73d9f67adcf53f186db0f853f0db46665f3fec973ab4a0821d7cf9485fcd56bd2c0c5ddb39abaf7ac5bf87b2093c2f031ebbdd793f37071cd7d1611a499f9ae77775ec74257731c77eb5e8c5e87cbb7a0971fea8291918ef59bdc98377306ea050a48196e80d6333d6a73394d5ed482493804f715c5551f45869e872b7b6f347788224f3549c9438af0eac3df3ea3093b46e765abde5a5b685e508396017040c1e79fa7b576d4872d33cd52f6b8838fbc1612a9c42c85bfba319af9fa91573ebe13b23a5f853e17b2f14fc4bf0d696d74238a4bc49a42c31fbb8be7707d06d5342822bda9d1e97a3d978efe30d9e2e7cd4d63c42ac1e25c0f2ccdbb3cf60ab5eefb1e4c336798ebb95539ef88b7d16b7f103c57a95b5b1916e754b8756e483872a31f828af02942f767a53aa715a94b2346c25b701074caf7fad54a176446add14ede28a1b9922695956450d04a3f81b1dfd89efdab551e5465cf7d875f580d4e7333c8b0df03f307380edeb9e993eb49d3bcb985ed36892dccf3a2dbc13e448aa55be99e0e68c4d4bc7949c3d1e4aae45711f9997de4e38014f00579ee57b1ebf21d3ebc93693f0bfc236c48dfaa4d73ab9c8c9f2c3794809edc2b7e75b61d73598e49a4615d4ff00f08de93e1fbe50035e89a69978cec0c15463d3e5241addd674e763cfc4619568b3afd3b508afe1de0829b72187420f4afa1a553da44f86af8774a4cad7e866384ce4f4c75ace516f52e94928a446b24d756e905ca324f19fdd487a37b1fe95e6e228aaeaccfa3c1e23d8cd347a7f817c42b7d02585d165bb8d782c79703bfd457e699ae0552bb47eb794e62b10accee0e9a8f19393923a8ef5f0937caec7d45afa8b6d6a6ddcaa9e4f390315c8e451bfa6cf245b482411c64562e451d258dd924004ef3dcf4fcaa39f41d8dcb5b92495915707b818cd7273ea51394881c73c9cf154a416d46b08cb64ae07f3ad54c725a03450153b4fb6315cee4ee162a49020e00c67f5aeb52d09b1565544e31cf4aa5221232ee5b20b1183d38add12d18da830552493cf1d79ae88a3346279427251635c13f311ebf855b3199a3a7689bc8908ea3f8bad73dcce26d1d3042a0ed3923826ae99d11454ba096f13162727f0c576515ed2a72a26ab51773cb3c5de3b5fb79b0b2569a45c8628723d0f4f4afd3f26cb941a9491f099e63ed16a0cc98b40bdd676dc5e5eb468320c71376f41d81fcebf46a345cb53f13c5e3bd9b7d5970f8734a55556329941fb88dc9faf15eac20ad647ce471f5526ec4a9e1db4b66cc16ad1b7425c738fc6a3d9ea724f1d527bb2a5fc5f6704c806067e95b4a364694aa3aaec99e75e31d698efb78942f1c67a8e71dabe7f1556eec7dee5942d0e6627c49b51a0699a3787923dd25ac29737651b3ba6604e4f7c80c140f415e5b56573e954ac8f2ebd9435f20724606578c7d2bcea8f53ba1b19b2978e4723d7ad70621bb1d94c92def9e38de2c0649073f28c83ea2a29c9b2e4ec0d6b204cf0622704fbfa539d37232b95846627c1386ed58aa7283342edc3868d1482b28c927f9577295d01a9a5df078e357c02bc93df15e9d196870d489b1a440b7d31766d91a65d895cf1e95d74bde67973d0dad22ea7bfd6736ccd121907eed5beeaf02bd3a52bcac7958a8a54eecb7e330936b338c10870a707af039af3f17acac7ab963e5a571ba6e830df782b5d9f1b64b0b8b797272731bee5381dceec71538789eb49dcd0bab91ff08069b6a03168f559d63623854112ee18f7620e7daab10923029330860084e243f2f2b914e0ae72d57eef31dbf84f4e82d2d1af300411a9c00b81f51f8d7bd878d91f23986279a4a28974dd167be95aea542ed212d8232541e9f4e2bab92e7993ae9237ff00b336db10e180db81b49cfe749d348ce9622ecc9bbd79fc3cdb2ed14c0df75b68249faf63edd6b86a791ec51a33aff098379af6a7acdf98a04d91120c6634da3e991c93f5af3ead1733dec3f26157ef59e93f093c71e2df847e238fc43e16bf96df5684afda22dc7c9b98f3cabaf7e3be335e6e2302a68d23995e5647e9f7c07fdaebc1ff0019f4cb4b7bbbeb2d03c4cea15ec2e6e1556661c1f2c93c9f6f7af91c4e0fd9b3e96857e78dcf7611ed033d0f420e41fcabc992b1d7718539ebfa564599bf133c609e02f00eb9af3e33676ccd183fc521f9547fdf4457a18683a9511e7d4d207e3e689a759fc44f89325eeb3719b49dee352ba9e45672b14437b64752c71803debee392d0512213e581a9a978b123f0df8dbc711c07edd2a7f63daddccfe64b24f7036b301fc21225c0efdb35dd5a7ece9281cf17a9f3c49135db0258ccd8c6e392491c0c93d6bce6afa9d699d1f82fc37a84f0ddabc463b50405debb5738e793d6bd2c342f03e4730abc95922bf8474fb4d1b5fbb492e566891987ca7ef63a63f1ade8c5466679a375a92675f2f8d44076c49b463e50f83fa57b717747c6726a529fc49f6b930f688a00c1289b73efef4d4753ab99450234573c282a48c006972132afc836e34696601bca2c08c023bd2e51c714a463dd69ad1b6d642ad8ce08358ba5cc76c7111d0e6bc490182c667cb07046d6dc415e71c579d88a6e28f5b09539ab2b10f847c553d82df0bdb587528258c28372b9922233f323f51f4e95e761be23dfc73f7119b1c1a6df6a4d0b6a434e9e4936afda88119c8c83bb3c7e358e22694cebc35dc1172ead0f86ee2e6cf549c8963883dac903092394e791b81c018cf3db15d785abca89af4ae747f16351b2d3e2f0ef86f4e65ba8b47b112cf78a772cf733e1dd94e71b42955fa835556777738a14dc773cea66f31490fb58f20570cb5469d55ca57cb711d8cb2c780a5c0cff00748e95d56b5309b84aa6a6f6bba9c1753a4a4f586350c39e028ebf8d735697bc46129f2b39f7b25bf904101025b8658131dd998281fad72b7791ec33d3fe3abc96df106ef4c864060d26dad74a85739dbe54403007d3713f8d7b785f76079d557348d7f0779563a3db40ec7ce55f9b238cd7bb41fba7e6f9c42f883a0f37710637e9d40aeb8c8f9ba94b527e5932483dbe635af3184a9b450bbb4f35588c0f6158c9dcde94b538fd52272cd9e82b92ac743e868c924730009b588415e55ba67835e45af23eaa0f930e747ac4eae2d610808552cc48ebcd74567eed8f3f04fdf6ccb11060c360e4f1b866bc870bb3e8fda1e91f04f458f4eff84dfc532a228d1b4668209187fcbc5c9f2d71f45ddf9d6f4e9f35546756b591b3f0334eb7d3bc5f3eac1634fec0d16fb52576e8b2245b11bfefa6af6b308f2d0513cdc3d4e6aa792b4aad029739771bdb3c12c7927f335e146368247b539ddd8c5bc87ce9b6952c0f03bf5f5a8e5bc8c39b95366578b74c6b79a39480991f311c0247b55e229da26783adccda22d3ae2c351d34c17a1e2b98f1b2ed72c187a32f5e3d4735cb4d9e93d352add69f736811cb89edf3859233b9483d307b7d0d73563a683d49a4b3b98ad5dfecd3a90858110b60f1c738c1ae371f70f54ef7e35e88343bcf09e8aaad13e9fe19b21246e30caf20691b8ea396af570746d0b98c9d8f3bf1aa168fc391143b61d263073c124bbb138edd6bccc5ab5434514e251f0b6bc74cbd582563f6791b862dc293c7e55d184c57b39599e56370aaa42e8f57856dd6d090016238c756fc6bea2325247e7d3738cac616ad13c8e850ecc608da483c5724d6a7b58695d6a2d9ea977e7a5da304b98304919048fef0e79f7af9dc761955834cfabcbb14e9494d6e7bf780bc456fe2ad2a395498a641b6588ff0b7d7b83eb5f8f667829519b6cfd6f018c58c82b1d5ff0067057539078e0ad7ce3f86c7af605b7313720e0f4acf48c4a48d6d308c804640f7ace2f9917246edb00720e08f4cf35cb27cac228bc1094001ebdbd28a73b22ec21b766fba5881c70327e955cda90d0896c00c16da7b822845f4246823da30483dc574c59cfd4cdb950ac7038ef9a6989b30ef5f0588cf26baa0cc598d340f349b79e79addb0b1a1a6e881f07a804738ab8c8c647471d9450e0e0648e98ac9d8515a58835143144a4f19e47ad6f0572e0b94f2ff00899ac5c5943f62b690c3753a91e6000f963b9c7735f6592e5cebd453b1f3f9be611c161db6f56715a2e9d67610b8860219c62499b966ef924f4ebdabf63c261942c8fe7bcc71b2ab37aee69dbe9b1b82cb2bcad8fb99c27d71d6bdca6ac7cae2252d9172defa2864f2963114a3a6063f5aeae648f26509ad5b1d7b789040cd2cc2351f78c840507dcd473a6634684eb4ac91c1f887c40f785e2b1cbc6a0b35d11f229ff67d4d79d5abf29f7181cb65149ccc9f07785ac754b6d4bc57acc85740d25910c5bb6bdddc9c9445f6e3737b0c57cfcdf3cae7ddd2a7eca91e7fe20d62f355beb9bcb87066958bb80d939cff002a555da2107747297fba5994b26063935e6d6d91e8d2692235b433a8200dd9c649a974dcda3a94d0d166d1b0278f538f5a89d0b317b55236b44b285efe08250df65ba7113907715dc71b803dc715b469e84b636fbc332dbc9aa6f0449a7498918f0061b6fe79c5456a518a2a3239e13bcd726418573ce3d2bca8bf78ea8ea5d8d0cb99a3fe11cfd7e95ebc4e79ec75108687445940d86e98a313df1d71f98af569688f16a3d4eb7c0fa5adac32df498ddb7e56cf000e7f9d7a74e3cb1b9e36267cf25131b55b86966791c3664766c7a64d7915a57933ddc146d1377c297ebff08878c2d0125e686d59081c8db382dfa1ad284ad13b27ab2cea713ea1e01f0ec96b11644d4afa191c74f30ac65573dfe504fe15389f8a2371ba2be91a74dadeab03344efe5fca5037df20639f5c0af430b1e63c7c64fd9c6c7a659849523b4951504670ea060601e057bd4e3ca7c0e25b9cae747656d6b1212083dd883818aa94944f2652729fb35b9977de2240f2c364867933b43f1b47d33d6b172e63ba8539d295aa6c5092c34f8d376b33adcb3f3e4923e53f506b354ee7b30c54e8c7f761a5dfdadb29834bb245841c2850589f7279cd3748f2e788a9565fbc22165aacde2db5bc30491c26331392db54e477f5ff001acdd3e647a31c5725368c5b0f0d5edc6b13491b44e6d2e84bf782ba85932197273d01e95e255c1a93d4fabc1e3ef4923ed4fd9fff006b1d4be18f88d74cd47549fc41e0ebed5994dbcedbe5d3ede41959558fcc42b1c32f3c127ad7858bcbd28b68fa4a5563dcfd155c4a8b242cd2c2e0323a1e1948c83f957c74f0ed4ac76f39e0bfb7178b8e85f0c2cf4c47c49a85c9675ce37471a9623fefa2bf957b99553bd5386bcacac7e6cf8799e1d03c4b7aa102b5b2e9b6f316c1df230793007fb0a457d828f354b1cf376a645f15aea0f0cbd8f842c2d8df5adaa477f73b78dd772c4acdb88e9b54aa81f5acaa7ef6a3445ec79c5c47a94ed886d62b65e81157fc73f9d4d28f33712bdadb73b0d2f42d56dbc360dfcc637cb49877e4823818fc3a57b74697240f87c757f6b89b238df0e783e7baf1002d2931286793071d7dbeb8ace8d37299d78caca1863b49fc2e96e9952ae41ea4735ee538687c1cf13cac64fa1c488840e40e6b4e5b32bdbb9a2226288aa281bbd68b18d4a8e45cb7bb609b490a075359b0836859904aaa1c074ee33d056aa2912ea4a0739e27f0ddaeb16af1248f6840dcac00752c3b1cf38fa57262297323dbcbf1f6ac933cf6c74a9a37ba8a39159c91b0c6339c75ff0038af9ca34bde67dee2eb2704721afdbabdf48082b19620923ee9af33154daa87af839a7044fe16d6e1d135fd327d42d9754b4b2984c2ce462a93ede8ac79e338cfae2b084dc5d8f42ca46eea274fb8d26e75196e8c7a94976043648329e5306666240e369c2afa8af49bba38ea53b18a1d54ed2064f4ace2ae7054f86e6b6a36c2d7c3ce8e0ef93241c0e091f29af4271b533c64e4ebaec73cd21754018901029cf1c8af06b4bdf3ea2842c8ddf855a5ff006ffc52f0b5a92a105fa5c306191b62cbb67f05a507791d5ca5ad47c4373e2bf19ddea37129964bbbe9af18c8461943336338e9b40af614f9636479f0579b3d1b4ab15b9d3e2218ef0b9603f3fcabdcc3cbdc3f38ccf5aec9a4b29e190188eecf246718ff001aed89e44e285fb4dcdb8191b941e47bfd6ad99ba69a2c0d4e3db97564661d53181595f525e1793530f5b64589a44c6d1963cd675a5647a187a6db471da3c026bf7939c2e483ee6bcca3aea7d4627dca562ecd70b25e49bd89c7caa076e2a6b333c1434b8e8c162005c11dab9d2d0ef89eada5db8d3ff679233b6e3c47e2166e99dd05a2803e9f337e95e860e3cd58e4c4c9a44da3429a27c13f88dabfcad717f2d96830331c15566f36500fbaa8ad7339ed133c247deb9e333c81812a724720e3b5790de88f5efef1169aa6eef1101c166193f41451d66655dda9b633c7500c42840248e33e9fd2bb31914a279594c9ca6ce3ad23681de3001c7535e44158fa672b95e49e6b2b90626642a72003c0ae3aeec74d0dcedbc2be26f106b17ba7e951de4eeb797905b0409b8296755c0e3be6b96e7b3d0f45fda03c693eb5f16fc64d796f657eb05c9b388cf080db60411a8041cf1b6bea70b14a95ce1aaeccf31f8ada75a2f89c5a5bc4f606dac2d2230ccc5fe730866e49ce32d5f3b8a5cd33aa9df90f3bbdd366839f2cedebbc0ca9fc6bcde46a7a156e68ea777e14f10493d92452c80c8830771e597a03f99afa5c3d66d58f91c6e1a317748ea447e640c36862c3bf7af42d73c98be431df48bb8ae62369048c79015159b07d49edf89ae4a945d4773d7a75b97546ff0080b5fb9f0deb62e9a2616e084bd8d0f0abebe991d47e35f239b65f1c4c6c91f6595667f57926b63e95d3ae63bcb78ae22759209143a3a9e1948c835f8de330ee8d4e53f5684f9d268b92a09780707dabcb9a77b1d09896f1ba4980391d4d4af751b9b565216c02761ae497bcc2c6a44c791906b07eeb35b128620f040ad5ec66d12a395c0003119c703ad689896c57bb7214138049c7e35d5031b6a65dd4f853f4abb1ced9872e6566c0e09ae88a24b767a6965dcc076a2e09dcd986dc0c0000cfe14f98a71b8e919208db79193d011cd69cad995b530755d456de29259096017e515ece170fced232af53d9c5b3c924d1afbc4dabdcce5d5dcb1290961b9bd00cf18afdbf27c0ac3d24cfc0789334962abd93d115aead9f4d622e6192d1978659976118faf6afa4f855cf8b9c7db7bc8c3d43c63a75931952e4b4c8718897728fafad5aa961472eaf5b4922a5d78b6e7c4c13ec1682d645eb2be4e47a8cf02a275ac75d3ca561ddf11b191733c16accfaaea4faace87e6b645f9578ee47031f9d71cf13ca8f6f0f8484d7eea3626d1b43d4fc7e2f2589a2d23c3fa72acd77792b325b5a06c0058f466232554724f61d6bcf9557367b586c2469bf7b5399f88de35b0bf4b1d03c3cb22f86749e60f3f896ee53f7e793d18e480bce062b25a6a7554b38d8e066959e4c02727f1e287ef9828f2c6e2ad89b8201033efd2abd8f3a443abca4f2e8ef64158a1f2cf7c71f4ae89515068215db2eeab630d9d841707f88e1573dcf358621241879b9330a1d5a5b1ba8aea28918c6c19049ca654e471df9af3dccf5ed74453dddeeadf6896e2e99fce94cb2aee20331c9c91df9358f2ca6b515edb1945441390cd9c0e0f4ae1947919d50917ec5d048884e0487bf4cd775295d99d5d8ea2283ed1782d10058a26000ea00ee7f3af6e92b9e0d47a9dacd01b0d23c985838753b954f0057ab37681e2a5cd3393be9918805bb67d2be7252bc8faba31e5897fc3d771c5a6ebd196db10b31b8f4c8322818fc4d542563a14743b2d0273a87c18f13c0a3f79a35e5b6a48b9c7c92130b63f35ae8c4af84203bc3b0ff0066d82dd3b3091f3819e83a67f1af670ab951f2799ceeec7436720cf9a5e4dcc32de95e9399f352a772af8835d8a5436d6578c8eab8705c804fa7a1af2ead777b1dd82cb2319fb5a847a35c0b6b156768ee3509d463c9625107be4649aecc37be4e3dc54cd5b4d2a2594497445d4c0e71d147b11debd18c4f165294763521d463824610445368c010ae31533691cd2527ab13ed1ab4eced1594781cabc83241f7e6b14ee8ea8ae6d05bcd2f5cbb82416f15ac6cc9f2bc5f2386ee739e6b8e74db67ad8793828a336c6d7574d2cc3b7cbd722c95dc0248df3763ec2b82a52735a9ee52c449491ed1e17fdb73e28783b4a4d321d4199623ff002d39607001fbc723a671ef5f3d53049cb63eae9d75ca8f7aff00828c78d4ff00c2490e9114a7365648840e36b4ac58fd72a00ae7ca23cb4b530c53bc923e31f085f4f7fe10f19da345204b5860bf1b392a15cc4768fef30615eee1ddab15885eea63be2a6a329f18b6a30b1365aa5adbdddbb918f93cb54656ff006959581155287b3a923914f9da33fc2da8c575ac5a0954142c4f4cf2071f8556195e470e3e6d50958ddf1bea3e74b058460a2152cc738dc4f4fc2bdd93b2b1f1797d3739b6c5f0fe9ffd9f62b24b162693e62c7a91db1f85698786b739734ad74e0694b70b28014723ae2bd05a1f2b7b95e408cc01000c73c52674c1d8a777610491efc943db031550d8994acca1269afb3e572540ce4f159b5a9d719e854594da4b824f239aa444bde2beb530b8d22e0c44642124670738e2b3acfdd3ab090b574709e1c825b7d45a7242bc4bf2807d4735f3d423cb367dce6126a92453f184765aadd15312c172a3e6953ab7a061d0fe59ae4c6414a677e552692b9c4dde9af6e4e416c72b228e07b578d3a7d4faaa73573d075bbfb383e1d782ecaef4c867bf9b46b80b3cb9592356b83e53e00e7001db93d0d7461d37a1759ab1c568f1acd7ab0c98661d9bbff8d74528f23d4f2b13fc22ff00892efcd960817f8465b8ee7b575d5f791e4e1e36d58b178275db8b14bb8b4e96e6da452c924255f3ec541c8e9e95e14e1ef9f5147e02dfc3977f0e5f78975b785849a768d3ac25b831cf332c2bf8e19be95a52a77996dda254d10ae93e13d5b513b4cb32ae956824e7323905c8fa203cf6c8aeeacf96691c7156526779a2ddaa5ba952c080141cf3d2bdfa0ef0b1f9ee2a1cd55b354eab2a0dd9ce38e6bba2f43cd9d3d49a3d697665e3c67b0e7f1a952d4ce54f42fb4765730170d187e83270f8fa54f36a6166a37386f1a09ec150404b87cb15c7615c389a97563e8729a6ea3bb28f87767d959dd5a3724b3065c0207a7eb59525cb4ee7a38d8aa92d08617df3c8c46373122b1724d1b52a7cb0342dc186d65776dc514b7bf4ac5bf78a8de47b37c46b6fec6d27c07e195753268ba0c6f75b5b3b67b86323291ebb4ae4f5e95eee02369731e562e4d3b19de3eb91a3fc05f0169c0fcfae6ab7dac48318dca804284fd39af371d24eb33bf070bc0f169cb658839c0e9f8d70a7cc91e94a2944b7a04426d4e170701416200ea71debaf0b0bccf371d3f6787b10f8d02bdec6adc1118da4f41935b6275958e3ca93e47239f6b158ef8c72a8462b9054f1d3fad70ca0d1ebc2a5d193aad9344db8a9eb9cd71568dceaa33b48ebbe04f88efec7e2c782ade09c0886b56f33215041d8fb8673e98af32a7447bf4df323b3d6be256afe37f155ff00da62b3bf8b56d55d8c135a4796df3e000c06467dbd6be9e0f9708998cd5d9cff00c70d574ed6be2b78c0359369ed1ea06dd7ca60e882355403079fe1ed5f2d19f3dcec513ce1f4f9916692d275bb8d3ef283b4e0ff00b2793f851b3354849664b5314a10d9c80825547cbf8835d8a5c8ee7993829cac7a77c3e7d3b5d8d67d52ea4b6b2ea4404798e47555e0e39ee457b587a8a68f96c5d07099dfebfe3389f487d23c3d611691a748a63976fcf3ce3d5989241fa57738e879ea7cacf31d43c33acf86ee1ae6e44ba75bde821d67e3ce41d54a9ebdbad7915e9731ec61f116b1eb1f08fc4b2c318d12e65de445f6ab6dce0fee98e300fb7403ad7e5b9fe075f688fd8321c72a90f66d9eb16d36e6049eb5f9ece9595cfb08ab4ac6847827705f626b8668dd1662187c609e7f5ac5229b3520c900a8c63ad72548dd9a26580bebd49fc315518b48963d72806d3f5ab6f52195ae8e5739add3d0cd99173f3e06719ae889ced0db6b1f3086202807bd6fb1162f16545da3a0e0564f565455891ae42c39006d03a9ed56d1a330eeef9a4924df9da3ee9cf6af4614d2644972ad3a1c3f8c7564458a2258a31058671951d457dee4382e79f333e1f88f1ae851f670de47257de26f0f4aab6f61737116b45b0b6acbba36e0f01c77ce3835fad5376563f148602a625b9d5d0a17a7c457f39b4f1d1783478810b016559114f5657e838e79c8cd6ee48df92952f761ab28eb5a2f87fc3766daa684c35ed2598299b6ee7b76feecc7a67be40c735c952a24ce9a54f135b738ed47c5d7977f24612d20e81507cc38f5ff000ae4ad5743d7a3964a5acd8ff05f82351f195ec905a836da642be76a3a9b90b0dac5fc4eccdc6ef451924e38af254f98f563439109f17be29c3af58d9f83fc316e74ef06e90fba150087bf9b1f35ccc7ab31fe107815d11462dd99e5ea8d3920104b724918e6b64ae8e49caecbd67a63485411cf5049f5ade953d4e4ab57951d1d868c11c87030467279af5e9c1286a78d56b7322b78b67874db344009666f97be47b7e75c389a8a313af02a755d99ca6ad7973a859dbc9221484e5634c7070704e7b9af0f115aecfa3a54794cedcae02b8239e73d0fd2b06ee8e95a17ac200559be51b4fcbcd75535689c957e2326e603e66401c9c9cd79f38de475c34896e0b61e74441cae32c7d3fc2b7846cc537a1d868b6e8798d9b93b89fef1afa1c34743c0c44acce9258e47b79d7cd10ed42ca73ce718fc2b7a8f7386946f513384bb7505487f3580c139e9fe35f37395ae7d74616699d2fc3dd35f5d9f5b8142871a6cb2b073c6d565271ea475e3d2aa83f7cdb96c8f47f0841a45bf843c59616d732cf77a9e9a96f145344116593ce4230727181b8f3ef5f47528f3c533cda957d9dcbfe24b3b8be9f4ad01e1b2d2e6b55f39a70a119948e1598706bbd42d63e36757da37263f50f0d6a16d64b0586a3a65ddfcc0ac50addaa923b91bb033fd6b0af52cac2c1d0957abaec798db5ac6e35092e43192de36db1b3631272371f6cf1ee6bcca6b9d367d3e29aa69451b5e03bf13426ddcac5c6e33b9f9dc67a0ff0001e95e8e0eada5667cce674652b348ee21bb82246f2819587039c67eb5eace5ccf43c7749a34747d3f53d559cd958cb27382b1a138fc4f151277466e95cdbff842b544320bd7b7b348f97fb4dda2103f13cd449d8eaa702d47a0cd03aa26aba48040e3ed45b1e9c804562e46ed588af7c28f7681e3bfd3e6bb89b31c96b7aac430ec475c7a83596e75519a4cc1bbb9bcb698c5ac6802eef5400674b88c89076390715838ea7bf1a8ac7b47ed71e35b6f1afc47f125ec2c66b27bf11dab6eddbc228e47a0e722bc2c352f634ac6b2abcd56c7997c37d2dbfe118f889767e58df4fb5b157cfcde635c2ba8ff00be558d7a383873553b3113fdd5ccbf1f6900fc37f086a1b5848d7da8d9a0cf062574607e80b11f89aecc5c6d519cb4248e1349b89349d42094f055c1001e08ef5c345f23446252a94a46ff008d2e5fcfb598125586c247a76fa57b155dac7cae5a92ab289dbe99acc577a4dab100b18d5411c8e063afaf1f9d77d07a1f3d9ac1c6b32616ab22875e01f9aba1b3c3842e4335b6d70ccc06307ad4a6747258ab78c03295f9831ae986c7254566549ce411d31daa5ad4d60f433ee6dc4a72a303a66a06e56b1cdf88a46b5d2ee00236b7c8c71db35cb5de87bb97ae7ac8c3f0aa99aea50e18054ea78079ed5e3d14e533ea333925048e73c49632e97a8c9bcee0cc583fa82738f6ae2c5a7199e9e5f25c89999bccd0396650a41dca7d3a5703d627b709ea763aff0087f50d5fc19e1cf13ea1a846b25c16d3b4db0d837b5a4231e6823f877161d093d735d781a5cccaad55d8c4f13787adb46f0ff857518e78d755be92e3cd801cfeed1d42315072323775f4ad3111e47a112fde40c782d9e7bc9e667030a42a9e771a983e64704e3eca243a55cea811dade6b911a9eb0bb04183ce40e2bcbaafdf3dcc33bd33463d6037847c4d6f78ad35c4b35a28999b0ea14bb918ef9207e55b50f88aa8ed12d78bec6cf4d6f09e8d6f7092bc10adedfc782a7ed130dc47fc05760e3deaa4f9f10918547cb49b3a5b28ca43b41c11dc57d0537c92b1f9fce776d97378230704fd6ba62f43071bb16357c8247e5537d4538e8582b222b39385c54397bb739553e68d8e36e750b9d4fc40c2276642c102b1f940fff005578f39f34ac7db60f0cb0d87e734bc4f25aff0064ac2d0ac0e005dea31b8d76d47cb48f3b0e9d59dcc0d3daf920550ab3c6add73cafa73d6bcb854b9eebc3da2769e05d32ff00c4de37f0de8915a2e2ff00508207e727696058fd3686a7297531a746d166dfc62f1d4dacfc46f18ea71db88e092fa58a1d8bc6d8ff007683eb8515f4b85972d2e63caa987f693347f68dd622d335cf06f86006ff008917866d217dc47cb2ca0caf903a1f9857c94ebbab5a573dea384f674cf1e92ee39c6e47c8e879ea4538ced6339d36e274fe0984dddd5c48e3e40aa8a40e093cd7b78277773e533697baa251d5a35bdd7000bf22b84f5c907f95555d6a1d7825ecf0d728f88ac08916541b483806aab41239f0b59caf7306676446593e6ff68fa579f38dcf5a12d4bbf0aaea2d37e2bf85ee4fcab1df0627dcab003f3fe75e45685a513e8b0b26e2763f0534bfedbf8ade0db2da5bcdd56391875c0562edf96daf6710f97068d60ef2382f16ea0753f15ebb7afcb5cea3732b13cf2d2b66be5e0b94eeb98b25a800321240e783dfd2b45ab25c89f5295df4c0931f3557041651b80f63d6bb671bc6e79f19fef2c3bc3b791582978eecc6ec30632bc13d8f15a60ea5a5639f1b49499edfe0ff001ce9de1fb313d85a477daab4400babb50c911efb57b37bd7d039e87c8d5c3b83b9ca78c2e6f7c4534b797b7125e5cc8db9a494e79f6f4fc2b16b9874a7cba1ccf87b599fc3dabc373131fb45a3995500e5c00772e0f5c8cd7cb6658755a0e2cfb3cb3152c3d44cfac740d5a1d4ec2def2dd818278d644c90480c01c1c77e6bf1cc6d1f6751c0fd9f0d5bdad2550e86090020835e1548d8ed8b352dd4e036003ec6b8af629b34613c6307f0ac7765c59324986c11c56cd68364a58153803f1ace4b51b2a4e724e01e95a5b439d95160e72475e9ed5d14cc87b315520b66b66558af31c104e2b38eac3633351bf2a319c2839fa8aec71d00e6358d7a2b18a59e6902468b9e4f51f4afa3c1e0e5552670d6aea8fbccf15f11f8b2efc5f7cf058136f6c090d313c81ec6bf53cab0bec29dcfc9b36c4fb7acea3d9149759d23c30be5dad9473de81f34a1896cfa96cfe3815eefb4b1f32a35b10edb21bff0b0eef518c5beb605f694548863638685bb32e3d3d0d734f10d1e943014a9abc756374eb2d4e07fb4e808daac37040686c95a47eb8daf181fae3f1ac6a556d9e851a3ca77b3fc14d1bc39a11f15fc45d6a2f075bdb28957c39bd5b52d479e162519d80f1d6b92bd47ca7628aee792fc4bf8e97be3fb38f40d2ac22f0b7846027c8d22c80066c1c879d872eddcf6cd7161a4e4c751a5138291a59892b824f240e82bde8ad0f0e52d49f4ed366b89029201cf439e9f957542072d6a8a074f69a4bc099623039c06e83f1aea82b1e4d5adcda106afe29874d2d143896703819c853fed7f935cf5713c90d0e8c365eebbe6666788d4cbe09d06eee8b49777734f331ce3e5560aa00f4f4c57972aaeac753e8e9d0851d228c0d52223ec908919845082031c852c4b11fad70558ea76c762aa87d85588db9e943d1028dcd9b3b4806986490b46c73c8af4e2ad13cfa9ac8ce86d5925f36402e2153861d323ebf4ae24af23a368935d416a6e18daa489093c2ccc1980f4c8eb5b28ea44de87aafc30b6bc8ace4b983468b55b40aab2acf09900f7e082bd0f22be8b0ab43e6f152f78ccd42291eef511140ea8f39545cfcaab81f8f078aca7adcde31e48a670b3d9911e641e5609dabfc4c3e9d6be6aa2dcfa8a4f9a099e87f0167b597e29e8d657b12cb6b7e93e9cf0ee2a08962640723be71534e5cb346d235a5bd305e5b69eb6515b4d6c5e294c2c70c15b69ce4fa8ed5f6b4a5cd047cde3fdd373c3fac69d6f7b70750d23fb4483b51c5c346401ee07d6ba1cbdd3e51d26e5a1c8f8c3c4fa0eaf3de4965a7ea1a65f2b2c70a34ab344ca09ce5ba83e84d7cf57ab7958fb1c3508d2a4a4b730f45792f9fec9732e564932881b9208c609faf6f735142a5bdd23174dd948d99fc2d7cb73f68b10ab6d080cd2b82a9176393d319fc6bb2decfde471b9c6a3516765e1ef19f876c6c648934e6d6b57864d8d34cc56d7207de551c923df8aeea1579ce1ad86515734352f1e6bbaadb886e75496dad14e52d6d711228c63185eb5dab5678d34a2c7689e257b197cdb6b5b69670a5167be845c151dfef71fa529a262ec74317c4ff1344b86d52d911460469a7c2140f4c04ace30b84e44ebf112eeed57ccb4b0bd6507992ca3427ebb547eb446262a766433eb96378c24b9f0ae9f34a460ba87507f00714dc0ef8e22c8bbe35bc2d246b211f3966c91d066bcc9c6d1b1d7809bbdd9af6db343f851a6c76e1669b5abc7bd9163e5d962fdd420fd4eee2b5c042cdccf5f1152eac57f8dca345d4747f06c2ca61f0ed8ac732a2e14dccdfbc949cf71955fc2b0ab539e42a4f951e457362243c12180fcab068b7aa674be2bb0dfe1c81df0b2831b311c8195e6bd7abf023e3f06f971afcc6f82e4693c3e891169a45764381d39ce07e75bd09fb8639bd2e5a874f6fa55e5c2a992468e11c60b62b772773caa51b22bbd8a2c9b92f381eeb8fc8f5a252762251bb18b6ee833e679a33c36307f2ada152c7255a2d88f1b927e53edef5b3a873c29b4ca37a5d7e404aae33c53561cd72c8e3bc63388ec618c31f9a4cb73d40edf9d79b8b91f4d94439a641e10b6105a4d2e4fef18f53d31d87b563858da2d9ea6693bda26378a1d27bd91410db701bbe08af3710af33d6cb34a467f867c296be20f12585a6a1782d74a9a60d793b36c11c0a0b360f7242e07b9af3dc2ecf696c6cf8dbc4d73f123c651c9a7d9968d8a58691a6db2e161b71f2c48a3b647ccd9ee4fa57b7876a9c4cb761f112ef49f0d68f65e11b592db56d62d6e1aeb54d5d4642dc15dbf6681ba9551f798f56e95e457abceceba71f74f3e87522048a4053d54f7359d2a9ca638985e01a178b755d2259a1b2bf96de1662446a01527e84735e754a8f9cf470d1b532e47a9beaf657b15d645c5cdc40a24083e6258862dd94007afbd552aad48ba8bdd245bb6d43e245cdf9c5d402e6458d9978651f2a8207006056b425cd88b9c78b76c39ed7a7ea3e18d46344bff000d3d94e176b5ce9d724e4ff78a3700fb0c8afaab5e573f3ea8bdf2f49e03d0b53225d23c46467fe5dafe0f2dc1ec370e31ef5a450efa19faa781f5bd0e1792e2c59e318c4d6ac274607a1057271f8529c4c94f5392d66e9ec2c6e724893691b58118278efd2b8eb3f674ac7660e97b4aa8e5bc3501fb634a402138c1f53debcba2afa9f598b9a8d2e422f16de99ef52dc3122104e40e371ed9fc29e2aadf433cba8b50b942daf1edc30572148c9c1af354cf79c3dd3d37f675d4ae6d3e23dc789259b75a787749bbd51bcdc6dde10a460fa12cc31570a9efa89cf2a768943e1cdb1f1d78e3c37a35e9797fb4b50885d10b9c296df213e8300f35f595aa2a54933ca50f78cff8b9e2483e207c4ef146bb6a375bdddf3884a0e91467627d7e551f9d7c647de6d9ec2f82c70af6d3c1217ca9c9eaa395ada31b331946d13bfd02f25d1f43432c7c9064dc410738e335f4543f7503e2f1f08d6aea28cef0f5c457faeef941e034a7fbbb88e0fe7dab1a4f9e6766269aa14123535db3dd1108b9f5af4e6b43e6f0f26a47231c58728503e010438c8fcab8248fa0f68ed74626e3a36b7637ca31f67ba8e52bdb01b27f4af2f114d687d060ea5e27b7fece715aafc5ab1bb8c144b386fef576b72c16ddcae7d8122bb7116785513ae32bc8f203a725c40b2efdd2be598b7a9e4ff3af0610563b5bd0adf6170c0001d077ce315a469ea632a9635f5ed1960d2b061390a0671debd6af4ed48f0e9e294b11639182c4984b22e5d5bf3e3a579587a4d6a7a588aab9b536745d527b09540395230cbd3a57ab0b9e4578a9a3b78e617f082a3191cf7af4608f026b919cfeb7a6b599fb6203be3c92180f9877af3b1741497333d7c16239dd99eb7f037c4693f8723d3a3c30b550d190739424e067be0e47d2bf23cef0b67cf13f67c8f12a747d8d43d8b4f98ba331ce036315f115227d7c7637ace505471915c1520117a9ab03a82013815c8958b44fd892e39ed8e6a99b0062bd3f3eb4a5b90d91b9c6723922b6b6862c809000000ad608cda2bcb280a7d6b56ca5a197a9ea1e5260707b569463cfab13f7ddd9c9ea9abf968ecc48500e6bd5a34a555da28c6b54495a478a78bbc4975e21d4ded2ddbcbb64c866233d7b8afd4b2bc27b28aba3f32cf332bc9d3a4cc6bed6e282cd2c6c373322ed924da371fc7bfd6be994b94f86a346b559fb6a8ce5e51e5caccc4939ce73c0ae6a93b9f431566761e0af875378b34c9b5dd42fe2f0ef856dd82dc6b778bf21278db103feb1b3c71c0ae3dd9d9cba1e9be10f8d177f06f40f12defc33d31343d1ed6d8dbdcf8a6e156e6ef509d86d45c367cb07ef61718033515db83d0a86c7ce579e241e26b992e7c4524f797f26e66d4198bc9b8f249ce491c9e2a2ee71d43a95ff00e11a9edaf97cbdb776c7e659a3391b71dc7635d984a0a2ce3c5cff007572bdd4260bff00210925c0fc09ed5dbcce33e53c94ef4f98dab4b2fb14485dcee1c1c9e9f4af46092d51e6d66e721b71e23335d7d9d1e4483056596350580f607bd7257c435a23ab0d82e69733295c7829e5512e95763538994bb2b2ec9531d432e7f515e44d5e27d2d38f2a2ef883499ee6d7c19a62c46498d983e5282ec5e498f181df18aba74fdd264f51753d3a4b6d6b5056f0e99ad2098c444bbb7a2a7ca7e653d78a9de66bf60c7d474fb3ba546d31668c9e648263b9906782add4a8e9cf3438f34c57e585d916a9308ece38411b988000393815d355fb3f74f369fbd3e61349b19ef6168a3c9404b312400001ea69d3a765cc69526948268d2066119134a090188ca8e31f8e2b45a333e6bb3bcf076a375a55989a29e488823fd5b11c7e15ec61f447cde25dea9a5a66bb35bd9ea4f6dacc769f69ba33b403779bb9b3b8a8c703f1c5609de6cf42b47f7713cd353ba61792200cce58e2490fcc41af02bfc4cf7b0cff00731367c02d3e9be28d23518c49e65bdf5bca197270a245c9e3db3530a7d4e9e64a48f6af1be896d69f163c7281d638a0d4a6f26241c0dcdbb03f0615f5f8556a67c86675d392387d5ef4e9164ee81fcc90ec8c2a92c58f4c0abacda83383097ad54e004c2de3de4092766c9e78033d2be52751c667da4237d455998c9bcb0400e723b7d2ae33b32e70e7763aab0d4b50d734bf284b24563ca995870ce07f773963d39f7af529be647ce6229fb3a852f06c33ff006943696f0b3011b2bc99c85618fcb9cd18476a9637c6c92a099e9765a42c6c1a793cd6618383c0fcebe912d4f88ab56ecbd1c16825da66ca0e3e539c1fc2a9ab9cf3a962ddb5ad823645acb2b81d771c13fe7b55a818caae85db5cab218f4ac863cb3be0a8f6150a3608d5d0dc838886ed3a553e9ba918bac6078b6e43ccc2360561836824f1bb924fe75e149f35cfb5c0d3b23d78786639be24f87f44951174bd0b4db49ae53a23471422e276c0e3712d8c7726bae9cbd9619c8ed946f23c37c5be341e23f126adaecf2319350ba92e42498deaacc76a903bedc0af21e8ae75461a989617175a9de5badb404991946187424f7aba2f9d935ff7713a6f1c4701b4862d46efca42c088d7ae4743c76cd7a75a5ee1f2997d3f698a6fb1b1a2cc342d22dad2d1163650cc64c02c77124fea6aa83f74c7339fb4ade851d4afae65777967770783938cfe1d2bb5c8e38d3b2329e50c39e98271efeb44a5a02a776661bc920980476c63b13c54fb4b0e54d32c43e24bbb36deb33320ea1f9e7d6abda8470c99341e28174ffe9101f4678c8c67e86b48d4b9c95b0979185e2a4177224b1cebe563688f3b7f9d70e2a47b994d170993e9e0db68aa411b150b0f6205694256806611bd748e12ee733485c1cee3b8b1ea6bc7ab3bccfa2c2c392290c8ae0c9b501271d3039ac9be567a4f63bcd12f57c1be088fc4fa70906bfa81bbd32d5f3f25b94dbbe51fed6d7c0f435df07cd1338ee79034617119259727773cfbfe24d78351599e8c74893269ed727622751f2927a56d4e3cc73e225681836ec6def9c3121831e0f635e6d4f8ceec3caf4cd7826db6b71265976c91b796572186493f4c0a517691b495e259f873736f06bcf3dd40d716e8416855b6f7ec6bab03acae797986946c7bfd9278435311082feeb469dd794ba88c91e71d372f4e78afaf86d73e1aaad6e68cbf0df5bb9b396eb4b7b6d6608d4330b19d5df9edb49ce7daa9333b6866d86adaef85272229aef4c990e583332007f1e2af73250bb32fc61f139754861b0d7744b0d66d4485db7a98e463e819707af3935e5e35dd58fa1caa1efdd96745f0bf81f50d19258f52bff0e5ec8ad218aed45c5b2e7a29600151d3934a9d251a572f17579abf29e77aefc37d7e1b59f538a2b7d52d4313e669b309b838c71c107db9af0b11ab3eaf094d4699ca5cc0f0ee8e6478e41c346e0a91f81ae3d523adec7a678720ff008467e066ab7cc47dafc5da90b085f241fb25b0ded8fac981f856f8487b4ad739e72bc4b5f0baf478534bf1b78dde5f2a5d1f4a6b4b262b9cdddd028807b85c9f6af4f33acd52e5473d1a7cd23c6c31b78915989c2e1b93cb773f9d7891972a3a9c6c8d4d21cdc5cc506d0c1980ce71c77aeea1efb39717eed23bdf11ea76cfa5bdb9215e52142e3eea8e7ffad5ecd595a363e17094675711cccafe18d16278249c1652e7686ce78fa7d6ab06b5b9a66b59f37b32e6a1a6cd6f13b236e1db07fa57ab2478709a5238fbb063b9c952ac7ef026b82a687bb4df32395f114844a8148f94fcd93eb5e3e21b72b1f41838fba7a5fc11b8fb35d78b6713146b7f0b5fec7c9eacaabd7f1a2a49b8729db0f88e32db4bb986d556352ec9180c33c600ae68c19b4a625b9b98e78c90ceacc3e52338fa62b6a74ddce49cb46755e27ba6fec58ddc0c97550578c9c135eb629fb963e67089bc4dcc2d27ca96d0971cee3f97b573d082e53d8c6cb964482d224949490678cd75fb3b1e6ba8cbda75da697bde690790a32cc7b7a9fca9b9729c93a6eabb4772665d57c73762d344d34dd47229099fbccbc7cc064051eed5e062b12ea7b88f5f058096112954dcbbe18b3bdf841aba7f6ac535b5aabf973beef3163ddc64ede8075e2be431b4f9972b3eff002daaa9d54e5b1f48e95324d123a36e5650c08e41046411ebc57e71888f2b3f4aa73bad0dfb4cc6324f02bcb99d1046d40eae1428c678c1f5ae168b5b939048dc0671ef528d47a390180e9d0d0f7316c8e76249c8c003ad742d89293cd8cf35b451267dedd844383824647b574461725bb1caea5a89fe22493c57a1428fb47ca8cea4edef23cbbc7de27334e749b06f32edc7ce7380a3bf3ec2bf45cab2c8c2d291f059d66fc8b962ce0b53bc16101d362204ce3134ddfdc7e35f70e9aa6b43f3ba8e355f3a7a983736e6d19c40429078c31231ec4d72cd9eba8af66ac7a07c36f05e93fd92be27f1dc57074195cc7a7e9d6e596e75694024a28182b1f1f3374ebcd7333a2d6679f7c55f897aafc4bd6ca5d795a6e8b644c561a35a656dad23070005e85b81963d6b268e94f41be20d7efbc23a2e9fe1cb699e31137db35186419f3a665f9432f42029007a1a8ad2517a950d8c513586ae4820d85d631fde4738ea3d3e956ad35a10f735b4192efc310c976fba166c10ae328ebdbeb9af628c7969f39e2579f33e4332f359b38ee26d50a85ba39296eabf26e3fc44f61ed5cd39d9738e9c2eb90a9617b79aedd97794c71290420fe13ed5a61e72946e555a518c6e5d9b5622e64b7bfb55ba8b761bf81f23ab6e1ce47a5725597bda9d9858da372bea674878a11657d75bddb0f0b458dabecca79e7d8573ad62772d8e87c071b6abf12b4c16892a4164e648cb4a58848a32c727df69e9eb5db08be439deace4ad359bd8e7fb743773c32bbb4bb95c82d962486f5ebd2b829cbde376bdd3534a967d6b5986e614df796e77b089465b8e0e071f5aeba3ad4393153e4a62f889b4bd5eee392d639e2bcced9624da6262339238057e9f5aaaabdacee614572d3e61f1fcb6aa84a845e16251819ee4fbfbd7a2a3ee58e49cb98cc99e59d1211885379c201d724739ea6b9adef58b5b33d26c34c30698aea158aae5b6f5e95efc29da173e56a4ff007f6393d3a70971346e84975e0e30739af269bfde33e92a6b422cc1bc851b5398bab15046d19c120d7054a77933b70b2fdda3aad02e67b592d0c56b847b88d707079dc00cfad6d08e8152a599e9be259d354f11f8a3570d207935295829e72376dfe4b9afa5a31b523e0f1955cead8e0755f135dc7e22b1b9b63f639f4f61716d215ce1f3c36d3d40c77e3ad70ceb27a1f4d95e1f969f3b2cf8eed74df16787edfc5fa4c31d85ec4c20d774d8b0122959be49e250385739c8e80e6bc1c4c55ee7d4528e9638132275247943851bbe6247ad713766397bb3b1d7784f7dcd93098b2c2af9555f5ef5ef6117323e6b3295a62689a8de5beb812db31c41de3215410c391f31f5e9d3bd552f76b1957fde618f4cb2d2d9581b895949fbd86ff1afa848f80adeeccda82c2da26ca20e3deaec70ce6db342dff760ba0d99e08f5ade24b77468452bbac64e06073ed59c909376250d120da666523b66b2b1c8e4ee737e06d324f13f8a74cb21836a665b9b969232c8208c8790b1c600daa473ea2be529fbe948fd9e1455347677fe2cb9bbd33e2178ba6924d32dafad9b4bb5707832dc4830a07b44bfd2bb7193508281d34e8a6ee788ff0068da58337d9a0594f237cc4b67df1dabc8a92ba3a153bc8d7f08dd5ddeea86450a9122e76a9c00491c835d5845a9e3668f96362af8e9dee75eb781b121002f1cf04e79adeb3bc8e1cbe8f252723d3bc3fe1dd475f8231a7dab5d246144b3021638fdd98f03f3aeea4ad13c2c4479aab66b5d780f47b4531eabe28b58ee07fac82c207b9db9e9f370a4fae335d0e4458e7a7f05695248eb63e29b577180b15fdac96c0fafce415fe556ddd15ca625ff00c3fd7f4d591df4f17302e7fd22c655b94600f5ca125473dc0ac2fa12e37399b8814ef01d493c6de847e1d6a22f53a69c6c5428d0704f6a77d4ae556392f145e16922888e172d9dddf38ae0c655d0f732ea0af736b40d5ae2c746c4844aac581ce092bed5b509fb870e61454b10ac72f717c1e7977201f31e14579b39de67bb0a56821b14b198d99092155a4727e52a00c91ef50ea24da294743d67e24e963c39f0efe1ae808365eff664dacdd36d0087b87ca83ff0155c67ae2bd7c1479a326733766791788de39f5eb9910009210df2a85c92064e3eb9af27111f7ac765297b868e8962638bcf70bb4e76f3c95f53e95db4a1cb1b9e363f1175ca8e32e608a4d6e57fbd1bb641ec0ff5af22ac14aa5cf730b36a9d89ee6068ad6e8abe492b839edcd633a6a2ee77c24ed625f00a6e9e663b989c039e871ffebaeac0c353caccf447a85bca5621c100f715f52f447c7bb162de636f70b3c25a29d0e564462aca7d88ae74ee5417344ea2cfe276b904461bc96df57b766dcc9a8c2b363d81ea056bcd6465183e6391baf107857c5faf2add6872e95bd828934897e4551d498db8ed9e2bc5954f6933eb30f4bd9526d9d17883c270ae8ee740d6acf54126d55b566f2ee00032db95805c63b024d75d6adc90b1e551a6ebd63ce253ab78464701ee74a75ea50141cf4c1e95f3cdbb9f711f7524695bf8ee7d4628acf55d22d35e4959620d22049d8b10a30c31ce48ab72d0ce72b1ebbf1c7c3de104d7346f07787b514d0bfe117d362b29ad3500de49b990079d964e71f31c126bd5c050f779cf3e72e66731f163c0fabfc34f82de17d32685253e22bc935dba9ecdbcd88c0988e0f980e416dc7eb5e6e33f793e53d2a1a44f072be62efc71e9ef5e77bdcc366d7849185ff9a412a0771dcd7ad848da5767919954fddf296fc4179f68d4f6443e789029418cb1adab4ef23cec053e44e4ce9f4e8ce9f6b1c5139185078e304f27f5af5283b44f0f1d1e6aad9a906b12a61278d664c71ea3f1ef5db19dcf1553f7ce57c4eb1b1699090cc0e57dab0aaae7ab86f8acce1187f684aedb4145396c75c7bd78b6bbb9f5117ece0753e0bbd36b1f8aede11f34fe1f9e2f4ff968878fc01aceb3be875517cd1b8b6b334d10241083804f7e2ae1b994b46cb7a2c606a96d9db80e369ef9cd76e1a17a879f8ba9c941dcd7f18aa496d6d1143f365c7b91c7f5aeac6475b1e0e4b2f7e52336c74066b285c2282c093fdec678fc2b6c3d176b9b633169cda346d7c36c5c07e06327bd76fb3b9e54b14ae636bd6969a66a1a70ba5616325dc62704f0c80e594fd718af1b18fd99f4194cbdb4eecfa77c2f049e0efd9f754f1e43e55b78975c81aea1b828a446ecc55005e81557040c638cd7c74e7a9f4b535678a78cbc776d069b6ba7218f58d66e210d70efcc6808c166f5627240ae0ad3b9ea61d591dd7c07d5e6bef87fa64771b8cb6af2dab3367e608d85e7bf040fc2be0735a5695cfd1b2ea9cd4123d92ce2dca0b0241e462be6aaeb13d88bd6e69411ef381c01eb5c72563a205975e00039358a2e446c4a9c1fc6b6898bdcaf7329d9c1adec12d8cab8b808b920935d10442398d5b5020b00ff005e6bb6942e6326723e25f118d32c64b80033a0f9558f05bb0ffeb57d3e5f8773ac9451e6e3eaba349c9bd0f1eb97b8d2216d42e4b1bbbe62cb13f503d3dbd7d39afd5f0f49528a4cfc471b7c5d7e58bdcc4c87769d836edac5b7738f523de9ce7cc74a872687a0f81bc15a559f87ae3e2178e646b2f075a864b1b365c4dabdc819548d4ff0038dcdd0f35c729a89df495d1caeb3e35d53c7106a9e34d69e28e5ba4fecbd22cedb2b15a4417f78235cf002e173dc926b9e09ce5ce7437c872fe0bd0d0ea12ea17b009748d2e06bbb966fbad8c796beb966c0fcebbaafbb1e62a32e6395d42fe7d4efeeaf2e0937173234b26493f3139efdabcde5e666e9a16d201367cc244672a4a9e7f0aeca740e0ad5944db9eeae350823b66958411e02425890b818c8cd7a4e2e6ec78ce6b729dd690b1dbb93261bfdaef9ed515a972c4de8d4e666cf86346315a3cea4491eec1c707206700f6eb5786872c4cb1b52d25139dd4e591a777676dcc59b27ae335e462f591ed619de288b40b1fb45ecd2cbbbc9822691b1dcf403f3c5185a4da3a673b23d57e10e91696f2f89353919964d37c3d79711b13b4072a1413d8fde35ec545ece99c5197348f3cb7d24359a796c37040304fb578fc9eedcecbea268535cd85f35c40d2412c7f30923382a41eb9ae9a2ad138714ee37fb42f35dd51e6ba944b33b7ccc155723e838c9ad28eac9aaf929a3622b095c7cabb0727e6f415eb286878b2a9699561d3cc9a8442424e4818f7f6ac614ef3359d5e44d9ea16ba51b3d28181cbc800da1b9ddf5afa38c2d03e3a7594abdce212f5e3d49808943ae4671c1233d2be79afde1f5f26de1d3285ad9acdaa4b2ba039e5b8fbb8ef5855f88eca153dc4740257b2b9b5285188916450cbd3073938efe95708f3b48ca72e6bccd7f146a72e81e10965605e699b6a807ef124fcd9fd6bdaaf5151a163e630b4feb18a4bb191e1dd7ec7c61e158bc3bad7970ebb60acda1eac13923259ad6623ef2b73b4e3209af928d7bccfd0e95351890e9f26a9e18f07f8a0c7146f6d7f6d1d8ea201cf92aefba2661d9b2a40f4c9f515b62b589b425667156b64f3b021311f41904e7debcf8d36ddcc672f7ee77be07b104dd452824c5b5972719cfb7e55f4d828dd58f91cdddddc9aebcbb0d72472b854657c018e801fe62b494392a0f0af9b0f63d1bed31ce3cc00307c3281d0e79af760ef147c2e2bdd9d8bb110bb25c8cfdd23d056f2471a2ea4e0678c8ed53176068b11dd85c0652a09ce6b46ee448bab7f6ac07dde38a8b1cf63af4d1effe21ebfe208bc3faaeab676f6e92dc5fcacb1c76115ba31519006719c055ee0d7c727ece9247ee3257763cdfe25eb305978274df08595fb6af32ea32ea97d7696ec913b15091c4aa796daa09cfbd7154acebbbf63b230e54709a4f82756d6636916d52dedb00b5cdecab6d18527a82d8cfaf1e942572a3a1e97e07f08786f44b0925d57c48f34ed26e58749b6697701d3e66dabc8fc2bd3c3ab1f31993e77630ecb52f094de2f3269fe1abed6ae3cef3049ab5e165183b81f2220071fdd63f9d677e699db18aa784b9eab70de24f1adbcbe74b0e93a4281e6ab20b680027a6d18ddd0d7b095a27c6737336cca927f0868f0c693adf6bd731e78b46fb3c24f61b8f38a734677332f7c5be129a39229bc217104722952f0ea6cee87d4065c1a2fa1d50d4cbb5bad0e39249b42d72fbc3b3bf2a9a88f29598b018f3a32464fb8a98ad04b534b5ef126bda698d3c4ba558f88ec99762cf756eae18639c4e8324e3be6928ea6db1ce5d278435b6730c175e1a914654330b8b76f4504fccbf5354a3a19bbdcf32f883e04d5b49d519d234d42c597297760c264fa10bca9fa8af0f16aeec7d5e015a372b490b69da480c4a98e30324609c8c74fc6ba22b96079b5939e20e245d18e639dc4838258d78ee7ef9f4b0a778a36bc25e0ed43e20f886d745d241173724b4d74cc4456b081f3cd23745555cf53c9e28d6552c250b23acf899e3eb0f10f8c6f65b02efa5d95b43a65acf20c349140bb15cf6c31c91ed8afa2c2bf6748f36a47532fe2969834ad5343d34220bbb7d2606bb2800dd2b02dce3b85602bc9ab2bd4344b962cccbbbf369a32f18661b5457a72d29a3e715375abd99cc6a56a61b68654e5c30dd8ea01f4af2abc1c5731efd1a894f94af3ab9d3af243f2ec655c3a90dce7a7e55c356ee173d88b5736fe1e59992ce47037649ef8c67d3f2af5b2d8dd1e066d52c8ee522915327705f4231cd7bed5cf8f55351eac40249271d8561181bc276455d5ef5acec1dc60646dce3a66b9eb4ac8efc2c7da48c2f0b46a6f0cbb73b01556f427afe95e6e1e1adcfa2c64fd94795078baedfedb0c2191d5137641f99589fd38c573e365d032da4a2f9c769ff0010359d32d9adbedad756ecc09b7b95f314fa75e98ae07347d0ad4f56fd9edfc21e36f8a1a4cbe23d24e9da7687149ad5f5d594ac11521195dc8473972b57ce9e86338945fc297df14fc525b43d46df5cbff105fb15559313665738dcadcf0a7f215f534a4a9513cd8c6f22b7c70f1cea365f17352b2b2ba92dec74548b48b38999b679702842554f0033863c57cace7fbd6cf5a0ac8e067d5b4cd65f7dfd998a762034f6a368cf72cbfe14dca2ccdfc563b0f0e7c3d9a5d186a3a45fdbead0cbb984699499554e3e656f53d3d6bdba34ff0077cc7cae6151ba9ca70f0e9f7a75b55bdb596de4662c4cd1953c718c915c518b94cf423fbba173ac552b918e9c6474af6d42d13e52a4f9a4d9204c8193d46455c558c1457318be21b50d090325b1c71deaa4b43a28bb4ce434eb38e1f34ca708724fa035e5b8d9367bb39b9248cfb2bd7b2d519b9d92a490b81fc4acb823f3c5795397bd63d6a0ad4cdbd26e0b46a8c182f181e98ade0f522ac7de3a9f0bc6b36a8991bc052dcaf4af5b06ef50f98ce25c940b7e34631dd471852c638cb607539e6ba316fdf471e4d1f72523a1b5b58e2d3edb9cb0894e40e47cb9c7eb5eb52495347ce6226dd79215a54b7fde103a60e3ad6bb19413948f39f8913b6a16c11773a23170a39c1c633f966be7f1f1e73ecf277ece7a9e9f63f132cbc7ff00b3d5a786ae2e4dadce8768b673c5b82bbfcff2b01d594a900e3a62be3250d753ed392eee7995fa691a1e9acef31775c00091bdc8e028ff00135cf528a677c172a3d4ff0067ed59bfe11130b906437524e47706439c7e95f279cd2495cfafc9e578347be595d19e18b2483e82bf3f9ef63ea692f75b37b4f23183939239ef5c7519bc4b373f2be41f9477a982b9a3653964dc09279f5ef5ac519bdccd9e4193824d745825b1ce6aba8988103a835d70465238dd6350f2d19f2739c924f4af5b0b4f99d91cb3925ab3cf75dd552d9d6fe7cc814ed82075f9589fe220f1f9d7eb19365f1c3d2f693dcfcab3dcd3eb95fead49e88e2b56b99353b837776e1e423e539fb99ec3d3b57ad52efdf478f0c3c60f47a9dff877c09a4781f423e39f89d0dc268bb82e91e1e43b27d666dbb8160795847193c66b8dcac7472731e57f12be24f887e32eb96971aabc49e5edb5d3b4ab35d96d6684e1638d077e465b926b964f999bc7dc1fe3bb21a2de5ae816970d2da690821638e1673ccc3dc6ee335ea50a5656339cb98935dd48f87bc0d61a2347fe95ab482faed94e0f92bc431b03cf277311f4acebeaf90a8fba72b6da5b5e4f9da546013c6315ad3c31c35717c88deb4d0962870ca028eac7b57ab4f0f647815714e6c9df4f8970888570064e7ad6ea972b23db3712a6ada406823192b960739edfe358e255d1d582a8e53342c61974cd15a4859a45219b681dfa67f4a4a3cb027132e7c5f29c65fddb34651a2e7b301d2be72b6b23eae8ae589def80bc38971f0b3c4dabb2b163a9595975e3043b1c7e9f957a9818a7a18626764751e1eb6834df86df13af94ec074db6d3d07525a59c703ea14e6bab345c94d58e5c34b999e64b64b223b8728e17e56ce08c579895e99dd29d98584040be133ecb889483dc31c6473f88ae9a50f70e2c44f623f0d2468b3380cf29380319a5868ea463676823759ee05b9c0da0f000eb9ed5ecb564783292e643746b57b8d620248254f1ce4628a31bc87889bf62d9eaf0c0b6f1a02bb938ca9ee2be8249281f0cea353b9e5f7c8f75aeb465b21657da07450cc78af9a70fde1fa242aa7854c96cacc25f5c82e54872b8518ce3d4f715cb5a3ef1ae1ea271b0eb5996e75a8a023242962338f9411fe34e87f16c3c43f6745b1df16e775d3743814009991f38e7a018cfa74ad3339696383238a75a72657f8697b7fe0ef10699e22b0b417babd93fdb2deca45f315c460b3165ebb42e49c72057cd5287be7ddad2258b99f5ebaf0fdf78aa5da9a57882ea4d3ae63857f76eeb8941da7a01bbe53d457a9888da28e1e6f78e71665272b9c636e3dc77159412e53194b4b9d1f83a754d4c820932c65473d7073fd2bd7c04bdeb1e06671bd3b9a1e29016f6de6dbfba746e3d4fbfe15d58bf764736552e784933acf0fde79ba65a3b01e62a6d24742477af4283bc0f9dcc28db10d1af14e858bb925bd074aecbdcf1e51b162d26767284003a83dea6c6658204929124991e80f5aa6c2ac6c5f852211801463e94731ce89eef58bb93c17e2e892e2484de69b6d726288901d52e00917683920e4e7e95f12df3c11fbab8f2b3ca6c353d4ace659ecee658a65fbaead923e99e95ce97bacd9cb42fcbac69da8ca26d767d4ef6fc30558e7daea589c6773138e4f61deb4a516d9c72a9ca9b67a0def8d2dbc3de1e64d33c3da6c13087ca59aef75c38723ef618edcfe18af51fbb13e69cdd7af630fc13f117c4cf7737917b169f6eaa79b3b78a26c9239caae7b7ad67415ddcf571d2e4a3ca5dd5b5cbdd56ed9ef6ea6bc9253b99e672fb8fe3c57b16bab1f11cdca5761b7863c8e702a9c2e73ce76654b8c34990db948e9dc5354ec6d1aeac509d5e2c82bb9076c7068b59042ba6c4d2bc4ba9f86a40b617af690eedcf6c007864ff007908c1ac96e76464a46e3f8a344d7404d574a1a24e412da8e920b2b8ec1a0638e7b9523d85272358c6ed1c159e8da8a6bb25e7877518f52b7698b3bd94844aa9bb8dd09f9ba76c1af0e5ef553eae9ae4a3737bc55ade99acd95c26b1a6359df332aaea366023ae3fbf19186fd2bbf1114a163cfc32e6ab7383d5fe1fdcc1a6ff0069e957116b5a72f2f35b91bd7d7726723f0cd7cdc2369b3ea36d09bc31e21ff846fe1c7886d6d1d46a1acdec76f724b10df6454ddb411d017e08ee2bae8bb4f52651d0d1f06e8da643677fe2cd5c0b8d234531a5bd9eddcb7b76dfeaa161fdc53866f618af5b11562a079fc9a9e797fafddea7addddfea5319afaee6334d29e9b8f651d9463007602bc384b95dce870e68962569b539a36da04118e013f78ff8d7af4e5ce78ce1ecdb17508d5ace356420b301c1f7a9ad1b1cf41de7733f53611e817c4b03bae1142e79caa927dff88578d58fa883bc4ddf00c8f1d826d518619e4e33f4af7b00bdc3e4f397799dfc1710b28594601eb939af7145729f23cbef13a4569382170bea572326a1ab2b839352397f1a412298a081b7701ca1e33d71fd6bc6c43bbb1f5395c5a97331748b76d334c0664d85b2c7bf1ffeaade8c7921a862f12aa55f648e42eee0dc48cc412ec4e4b1e719e3f4af02bfc67d561a2a14d448d9caaf2a39180c0f22b0504fde3b9b3d43c26afe16f813e27d6d2455bcf145f47a2dab747fb34637cecbdca96001ed5386a7cf5426f42cfc11b9b7f0d6bdad78d1cf969e15d265bc49158a30b861e5c0a08efb98903dabd9c64ad4ec72524707a778e75211e752f2b575972f30bb00b966396218f39c9eb5e0c27656676c34268e1d135eb88d2d59b4b99f0be5b9df1163dcb7040aeba715268e6ad269b91daebde15d5fc35a019ade07b88f70892f2c8978cb6339dc3d857d04eca8f29f1f193af8913e1d78e2fed1eee5bd8edf568c8f23c9d462f300f5c67907deb2c2c798ebcc2b7246c74f753783b5b68d6e74ab9d1653cb49a53911823d54e49cfd2bd4f6763e569d5bb243f0eb4fba651a5f8a2d6e0b64a417836487fe05c0a4a1a9a4e7639df11780b5dd32cde6bbd2a716cbf29b98d77c7edf32d6938682855b33c935e56b42d102305b240ec7e95e162158fa4c3be630ae464ab01827d2bc596e7d0d38d91a3a1ce58e013907a56f4a5a0a6b53d13c12f8b9b82470140ce3ae7b57d060a3adcf8bcf57ba33c4739b8f11302001848f93c92060d3c47f108cb55b06ce81d840a224380a36f3cd7b10fe19f35515e6d94ddda652839278cd55efa1714a2ee55bbd0d3ecade70279c926b8eac2e7652c45aa687946bfa4496daa31b3dca71f334676f7f6af8fc4e19ca5a1f7f82c55e9ea66cda6dd178da7919c9f986f6ce2bccaf41a48f669d45289f407c0f736f14b0953fc2c0f439ff000af0f3ba76a07d364556f2944fa2346522052e727eb5f9555d19f7b4b6b1d458711139c1032057048d58b77290c00390473ed4444999cd3121890460e066b76b53396e646a975e5eec1238c0f6adaf6454b6390d4af4b8209e39af528439b539dced2bb389f126ab6905bcb25c31d89d23006e91bb015fa264f9772af6f2dcf82e20cc7d9c7d8537ab3cbf57d51f50b979e7765da42ac6c3202f6007ff005abee39eeac7e7d4a93a6eef73d1f49b2d17f67ef0f5af8c7c65a6dbeade32be8fcdf0ff00852e5b8b70795baba5e800e0aa9e6b86acf94f660ae78578cfc77af7c47f124badf88f529f54d4a5fe395be5897b246bd1547602b960afef1adac74bf08ac12df5cbef10ddc63ec7a159b5d233ae55e763b2251db3962df8576d285df31cb52467e9924336a4d7bac132da2cad3dd1c0cccd92c140ec59b02bd58ae589c6e4d4883516baf13eb571aadf9579a76dcccab80a00c2a81ec062a29d1e795cc6be32cac6be9f6091270324e324f39af5a14ec7cd56adcc5a9a3dd851d7d3b574a56396321d1da990a83803f9d68e372a55396252f1047e4a464e0ecc9c74cfa0af3b10afa1dd97caf2b9635b5161e1f545001651904f5245692d2831d3f7f1479c3b35dde302000091c1cf1e95f2bcb79367dbc1da99ee7e1ec59fecf77b6f146775cf89a207b70b06ee0fb57ad808fef4f3abc8a7a81161f04fc500fcad7fab585b2927fba1dcd6f9b2bb48e5c13b55679b58bf5076f3ebdabcc81db524ecc9d6c5aea1becae36280483cf4af4230ba382b546a5113c2b6fb609f0193e60001fc581eb4b071d43309de26edf44c8992076c57ab247ce527765af07db4975a84bc29c0dc4f7c7bd561b56698b9f2d23d1ee311400f0307a938fd6bd696c7c77c5551e5ba2422ff00c417929248121238f562383f9d78908dea1f755e5ec7068d2d671697d3a95daa7ee81d71deb8f191b48acbe7cf0b98be1e64935fb9246edb6f8001e72587f2c56785f88eecc1f2d02ffc4378ee34ed2e594fc96064918633bb380abf98a5996a8c3217ef3303c1f75aeb6b777e25d3dd80d2009a6b8000489643b0291dc3670474c578f86579a3ecab4b951d7f876e2e756f855f1034b445bab5b316dab29562ad0389763b7b6558f4ec2bd5c72b40f361ef33820acac063e45f6ebef5e7c1e86ae3791a7a3df7d9353b79958aed639f60463fad77e1a5667978c87353674de2b90be9d6ecc7055cedec0f1c8fcabd2c63fdda3e7b2d7cb368d1f0adea49a5aa467fd5b95209e73c1fcb9adf072d118668af3b9d1a4e9164b9009e9ce335e8bdcf9c69b2517664660878fbbc1ad1ec0a0cb10dacd395225218ff00798600f7cd64e563674f435468ad18c1bd19ebf290c3f3a87332f6268695a43de5d7802c267dd36a7672fda00cafeee76668c1efd369c1af99c2c39a9367ec3567667926db8b0b892cd0032c4e6370c70415241c7e55cd256d0232ba3abf0f786a6d4e5fb5cb2c4d1444158d970777ae7d6bbf0d4eecf9accf19ec65ca57f1502644b4695623f7b6b1ebe86b7c4c7951cb96cd4a7cccd2f08f870dae966e1eee14f3989db9390074ae8c2d2d2e6599e37f79ca6dff0062c230cf7e848eeaa08aeb8ad6c7cdd4ada92496b6103f9a6e5646002b0ec07d473fa568a2ce3c44ec4897b680e550c80f401401fad69cacce136d142ff538830df6fb97b2820e2a6d743849a673b7f1595dc8c238da076fc89fa564a1a9eb52a9a18ba9413d82b3b8f323553c804e063bf6ae3a9a1e8d1a9792470fa35c6dd6125899a29436e574628e0fa823a578b17fbd3ec6abe5c3a3b4f1878fd35a82d6cb5bb617c54122f615097283a105870dc7af35be2a4ed630cb61cd3b9816da01958de6857ef788992401b278f1fde5f7e3a67e95e428eb73e864ad2347c2f047f133c5da66817f12d9dddfbb42b7b143b5f70566cc8a3008caf2460d6d4ea27213d8c5f19b4fa3783bc25a507023792f2f6e046c407943f94adf4dabc7d6b6c5c1b8a660a3a9c5ddc627db20259d78f5c8ed5c56b8e4f951b362f8b1b777fbccc703a75ed8fc2bd4a1a1e2621dcadac4c1042b8254b72476ad311231c2c3a943c404af87e12325a7bb97040e0aaaa8ebf53fa578951dd9ef53676be09b4dba640a470aa073f4afa9c0d2b533e2f389fef0ebdb4c5b854032840ec3ad7abcad44f9a53f78af3d83d9f20e4e33c54cbe135ba9491c74dab4f77ab670d2a349b0e4630b9af01fbd33ed68da8d0e63a6d575d8ad2c1908da241b467a63ff00d55d9525cb1b1f3f86a2eb627da1c73a5a5df2acadb720804035e054d59f7904d3452b9d39e48ca42fb9d8e150839249c0008ee491584938c6c75dcf5df8d501f0c49e15f03c481d3c31a5c71cce8319ba940794fd46541cfa57b582c2da1ce7256a96461ea930f0cfc0b82de29956ff00c5bab1ba6503e63696c36a8cfa1933ed5e66326dcf94d68bd0f319c84725c93ec39e7d2bcd71b33a39ac5fd02059f558d0a7ca393dba0aefc345b99c389a89419d36a9e2ad474445834ed4ae2d413bda357253b01f29e3f4aecad36a5ca78f82a694dcd9dcf87fc7e93e996f6fe21d22df510aac45cc23c89b2deb8186af4b0dee9e2e652727635ede1f066a51ff00a3eb379a448e09116a30870a70780c0f435e8f3dcf223071409e0bbaf985a5dd8eae02eeff004597823afdd6c1ad1bd42a22573e2bf09324b126a3a680b904ab796c3e872ac29c9dd1946279d7c51bc83c476afa9cd6d1daea68c0ca6d610b14fc72d80786cf27d79af23150d0fa4cbe776797cb10312b8073d4e7bd7cfb8ea7d7ad8b96d672e98da7dcb8531df42d2a28383b436d0c7ea41fcaa57bac89bd4f46f042f970ce4f24950c49cfaf4afaac1e91b9f119ebd0a97d0a5df8964270abe680854f3c10467d7a5635b5a869845cb83674f35aa81b8f43c8fa66bd98fc08f9173f799258c2887385e33d7d69ad0973bc8cef105f086d58290493c8f6ac2723b30f4f9a77670ad66677b8998e18007191c83c715c0e9a96a7d6d39aa74ec8ced6156231155033cf4e2bc0c62499ef602f5627aa7c1acbcd70013ca2b28f4c1e6be4f3cfe01f5590bb625c4fa434750618c9246540c0e84d7e475fe23f4d8e8d23a3894c50f231e95e748a9321701c3124e4741eb44484cceb99331b370081c0f435d96bb07b9c86b1a910cc78fa66b6702a4f4387d7f58fb35b48ecc225404b3fa0f615f619560fdb495cf0331c62c3d3e65f133c6f52d73edd746ee79311807607e368ebcfa57e974ff74925b1f99558ba9539a6ef73d4bc11a5e99f08f41b6f881e29b55d47c417c4c9e17d0ae0065181ff001fb303fc2bd5437703ad6f1d0e2acfdeb23e7ff1c78bb55f1c78ab50d6f57bb3a85f5db9696e186371f6f41e83a018ae4acb999e8d3d22634010305d923bb10a1631b8927a00055525a728e4cf44d7b51b7f0a7832d3c2714be6ea97538d435401b708885db1439c632a32c40e84d76d3767c8724d7539bb2b29ae252cec581e801ea6bd48ae63c7ab5958ea2d6d06d555538c0277718fc3b57a54e0a27815aadd9a11a6c0001d0d75b4703770f2f79fc69242bd8bb696e231961c1ad56a8c252e6d0c2f11e6eb568e04fba0a82179eb5e3d677958f6f04b92372a78faec8b4081c062c08e3d296225cb4ac7665f0e7aed9cb68d6a5b73e0e091953d0fbd7854f5d4fa9a8f9558f670df67f80fe1c8d707edde22bc9c63fbb1c28bcfe2d5ece023efdcf3abbf78c9f164853e0a69c08c35ef8859867b08a1208fcd856799caf2b0616169dcf3cb1442c4c88194fbf35e5c1d8eda96e566de9567b6c75558be66f29881ce5b0320fb9af5a93f74f171324aa4515bc24aed05ca800edc310c70471faf35584271cfdd2dea0ec24da41e057a136795422b73a3f015b989659011b181e9d7f3adb0b13873395a3646ff008835136f60f1e719563f4e0d76569591e3e1a9f3cd3393f8748649f519186ff955813d3ef76af230d3bd43eaf3456c2a447e309d3fb5ee5d1d87ca31918e48ed5cb984ad235ca21fba4cc5f09132eb734858902265008f70726b9f0723d3cc21cd4ac54f1d5e3ce0c4095555dd83d0f381fe7eb4b18efa19e4f0f67a9aff0005e49dbc437da3a2efb6d674abbb5b985b80d8859d0afb86518fa9af3697bb347d2557cc8def8331477d7fad68e1832eade1ebeb458c8e269163de8bf5dcb9af63151f694ce3a6f959e756f33bdb4658e0328ef9cf15e341e87427ab2c59b3457084209092005eb9e6ba28cbde39651e68b3a2f11dc98ac90cceadb883b01fb99af5f16ff768f9bc352e5aa5ef0a4b2be9e638a2f2c339f9ca905876ebcfad6b857648c33386a755a5e91777f32c515bcf792b1c2ac285b27d32381f89af4252d4f15523a76f0ccda5c98d4ee2d34e007ca9e72bbbf1cfcab9231ef8ad94b4074ec4b05f6876b133cb6b79a8b72155a55b75e3bf463584a46f18684ebe2cd1c0c2783ad665fefcba94bb8fd70b8ac6e4b823aef0eeaa3c71f1662d5edac24d2b45d3d5aed9666c0b5b5822291a93cf390a31eb5c6ad87a7c87dbcaa7b591e14eaf0cd2cf2b869e7999d9df92c49c9233f5af3796da9d8e5cb13d1fc0a2393457f31c03bc9c9f41ff00eaaf6b0cb43e073a9735548e3fc436c358d441da250edb40233919a2b2e6676e09fb2a29b3b1b2d16ce18a24024da8bb473c62bd0a70b23e5f1b89e6ab72e49a25b6d18566e32c4b1c7e55a28ea79f52bdddc60d32da3c01191c0fe2ea7f1aae531ab5ae588b4f8617426042c06093c9c55a818fb6d075c2c67ac6800ff6452e517b66d987aadb40e496880c8e08e31f952e53be359a4739a9acb0da4c213e645b096079e31cd70548fbacf6f0952f34713e19b2b0bdd5424a04136d2c377ddfcebc78c3de3eeb132b61d14bc5da6341a818f2a005072a73927dfbd618b8345e55553473f13cb6d3abc13b5bca8770789b6b823b835e33ba3e92f767b27c0cf185a3788fc43ae7896d1ef4e89a05cdc41776caab2c52c8446ae7180d80cdefc9a7465ccec435647156de118759b5bfb64b913db5b6973ea105dc7928853070c47ddddbbdb9af76b2bd2b18c65a9c0d9da4b16a31432e3636091ebee0d7974e3691159da2cd2ba0c3558e1180b0b377cafe1ebd6bb20fdf3c36f9a0cccd59c0bb447605724f07a5655dddd8df0b1f703c636ff63b1f0d5916eb646e9ffbdba5919b9f7da16bcc9475b1eac7e1b9e81e0f8fc8b340cec42e00dddfdebec708ac8fcfb3495ea1d624c5a3c2b6dfa0ea2bd53e65bb48afac5dadbd84a49d92321504f624718ae5ad3508b47a397c1d6a9ca73de1db28eeae9e493ee469b4003bf1cd79b429f34ae7d2e3aafb287b22af8ae04bab848a21b4469c9c670c4e73f95618bdc32a4d6ace6174c9e352632b26780c3e5c7e75e34e07d7a773b9f813a41d63e28e99fda2b8d2b4957d5ef8c8a4a086052e73f560a2b3b394d229cac883c43e2c9fc63aeea9abdc82750d5aecbab28243191be51ed8054735f5d45ba54b53cd93e691b5f1f96ca2f1ddae8362f1a58f87f4bb6d282a1e3cd0bbe563e87731fc6be52acbda367a10563cba4d3e60e42a8646e4b679ac394d24cdcf0a40d1452cb27cbb8e153dbd735ec61a1cb03e7f31c57b38f2146e14ea9ac600126e70a07b67a5734a3cd33af0ed470fcccec1a30b1e0f6af6211b1f315a49b046f95487c9079ad1b328244a9334723c832921182c876b11f51cd52612573a8d03c77af696f198f5399e145c79131de8476c86ff001ad548e7a9019e26d5346f1968f790dfd945a66b0519a2bdb5f961948048464e8a4f40deb58d695cedc27bb347846836577ac6b369a6431859eee610a863f77d49f6001af949ff0014fb88c9389a7e34d62d6f7c4cdf60c1b0b155b1b7c2e3e58c60b7fc09b2df8d6d53445c95d9dbf821cb5831c72cd923d78afa1c1cbf767c2678b9aac51513373e2a001011a660a7b0c64ff4a96ef54eaa6b9306ce9aeee1810b9e8318af593b1f1d18f336cabf6d0a87248e31d6af9b434f66db316fae7749b701b3d09ed5c6f567a94a164446cc3c6c490a5791c553674465739ad7936ba0c8c03dc738af9bc72d4faccade87a87c177097c40009f2864f6e0d7c7e75feee7d6e4abfda8fa3b469d582f4240fd6bf1fadf19fa7f53a0b998672080ad823dab8a5a97d0acd2846ceec8e84d6b081315a9ccebb7a904acb139c577d38d824aece2b56be5892591ced455c9626bdac361bda3392b62152478ef8b7c4afa95c955908b507e50072e7b13eded5fa360a8fd5e3a1f9a63abbad50ea7c31e09d27c11e17b6f887e3eb77956793fe29ff000b4ab893519073e7cca7eec2a7900e338fc2bd682e77ef1e65d27a9e5df11fe246ade3cd66e355d52eccd7d71f29c711c4833b511470aa01c715a739cf385e57382964088d93ce3b735cd391dcbe13434bbcf2105c404acab92addd4fa835d14f6b9c1525efd86e816c975a8bc8483f297c64f39ebfad5e162dd5b9588972c0ecad2187001600018031820d7d1d3563e52acae6dda48b9c0605b1ebd6bbd3b9e54e2db2743c7241c9ed5a27739a49dcb30c450e4f46ada2918cdbb17e2db95ce300d10563955db38ed42413f8827752301f1f51d33f9578737fbe3eb2946d46e6078c27325c411ab65f078ea71918ae4c5caeec7a7962b26c7d9c623814918dc01cff9e95ca9687ab29fbc7a9788365bfc3af86b649fba1fd99777cc31c334b72c377e4a2bdacb74b9c788d5993f11985bfc2af87b00231757da9dd15ed95288083f81af373077aa766156879d5ae52538008efcd70416a6f3dac767a3c24dadcb05ff0059195ce7ae476af768c6f13e4f1b2e5ac8c5d02278aeae50101580c1e8481eb58d156676633dea4992df39131272d9e84f6f6af41a3cda6b63a2f0bea714167b58fcc09270bc0ade84bdd38b1b4f9992f882e8dc5acc554ed008cf4c7bfe75a625de99c782a5cb50c9f025d4b1cd3a2441984415873f2aeec8fc6bc5c2cdf39f4d98c14f0f666678b6fddb58b98cc6a4a6de73d78e6b1c74f9a4756534d53a1a18fe16d4268af9860972ae071d383fe15cf859b8c8f43150528195af6a535d6a526e3b94280476ce73c7a0e6b3af51f39ae02925037fe10b799f137c368f3adbab6a117ef1ce0007231f89207e35c907ef9d950dff0006dcdef85fe25e922de12753b2d5161fb3160159cb946524f18219bf4afa38abc0e239cf17dac7a678a75fb6813c8b7b7d46e11636f94a2891be500fa5780bdc6ceb845ca251d1a29ef6f105b1c30cb0763d31cfe3558557aa6557dc81d46afe1db9b886279062d19373dddc305424119007a838e07ad7af8a4ac797828fb493677ba3eaba169da640eb0c9aedf88f0df69063b60dd3180431f7e82b7a324e99e6e3e1fbcb16a6f1beb3a8a347f6a5b381800d6d648224c018e8393f89aeb8c9b3cad886dec65d9909b79dd9dbcd75c6e73ce68bb1da4f347f2a1e0e32456528ea255055b1b803a015aaa7a0731e99e36d6b4ef873e1db9f066957516a1e22bb11af88753b71fba8947cc2d222796c1c6e61dc62bc3c44b9ea58faca2ada9e37a9471cff00326491ce186304f5ae79bd6c744e6de8743e15b896dfc3378080aaa5b93db8cff215ec61dfb87c4e67ae22288bc3108bfbd95d0e440a1b711d73d2aa9fbd23ab152f63868d8e8629cc526cea057ac9591f1f51f3ab9791cb91838feb4e0724d7bb71ce13025cf2a7bd38ee63cb744314c1d99c9da4fbd7458c231d495023eec6587bd67246d057667ea7123ae39001a491d72d11ccebd1f93a5dd94c86f2880475c771f9570d6d22cf5b092f7d1c5685a79b8b9704287540011e9ee6bc9a6bde3edf1951ac3a323c4cf3dbeaa5186e8d531d73dfd3b5638c474653b188f602e501b771e637f0f7af0a513e994f5377479a7d0be19ea533b807c4d30b555fe2482ddf2c08edb9c8fc01acf090fde1aca5a1a5a3eb1ff085fc38d5bce955478c552c6089bef0b68d83492afa7cc028cf5e6bd2af52cf94ce0aece2b2bf6916ce01743fba7cfdee3a7d2b993f78aab1e68b26b68c099f38673f30cf502bb28abccf0e71e5a6cce5d325d6bc4f69616d19796ea55815475cb9c67db0326b96aafded8e8c2fc01f122e92fbc7b770db2e6d6ddd6ce001b3fbb8c05183f81ae6ff0097b63d04fdc6775e1a252d94118c002bebf0eac8fce330d6a33aa80029827040aee47cfcd5a4739e2abd2f756f18257682481dc6315e462e7ef58faac9a8a52e734b41b416ba700e555ce5c9271c1e457452f723739b1f3f6b89b1ca4f74d77772380dfbc6e01e315e3e265767d3e1692846e3507ef49e01cff0f4ae66b43d0854d4f59f025aaf85fe08f8a7c4ae425c788af1340b12bf31f250efb8cfa06e067daaf0d152aa875ea5915fe0df87f4eb9f883697fa888a1d2743825d62e9988555585728a73c7ccfb457bd8f92a54b438294f9a478e6b335e6b1a95eea976e66babeb892e9dfa312ec5b9c7d40af92846cae7b716568eee685c839201e8460fe75749734853dae7776f35b5968203858cec2cb9ea18f419af71479607c66293af5d232bc35a3c377a87da37906353839e327be3bd73d18734cf571553d850513a4b8d35847853bf1d70306bd5e53e4e552e56f21a10414e0f4c8a392e690ab62b4b2386385dc28e42bda5d972de57552586ddc071e953ca69277457d4233244d8c1c0cf35938dcba52b4cc1d1a283498359d72550ba8716960bbb6e4b83e64800e7e55e01f535e054a7fbd3ed683bc11c85f68cf0d85edeaab08ad9a35f9bf88b1c019efd0d67885647645dd33b2f035edc5ae95e6e7f8cb062b9e3d3df18af5f06ff747c7e690e6af13334ad6256d596572ac15998000838e7a7a56509dea9e9ca8afaad8df93599a46c90013c6476af69cac7ca470ea37125d5728414f980c1391473685aa2ae50b79659a52e17e5e4fd6a63ab3a2515146817959338015b864c75f7cd5b461148e6fc5113c1e4b2b36e6078f4af9cc73d4fa9cb5d8f42f8232b8bd72c7910804139af90ce15f0e7dc64ebfda4fa3f453fe8ea48e71c1ef5f91578fbc7e92b7359a4f942827239da4d714637668b6285fdfec888271c735d295820b538bbebb32cad970dcf15e9e1e8b9b30ab3e547967c45f119793fb3a06690ef08d1a025a463f750639c938e0735f6f80c358f8bcc712ef64ce820d3fc39fb3fe9963acf8bec2dfc49f10ae02cb65e149b26db4e43cacb76c3abf0085edd2beae0f93467cacdde478c78d7c79adfc4ef12c977a84c6f758be9764691f114596e1113a2a8ed5d0de9a1c9ecdce6705700c770f03b006362a7073923af35cc99dfecd0d31c78dc58658e00a8919bd0ddb5b045d3320f3b7279e99f5af5a9c2d4ae78d565fbdb1a9e19b2b755958c8090006c73cf5c57460a3d431f271a68de8adaddfe61201dfad7b0b43e6657b17174d0d1e7cc009e46483fa56e99cec961b29f80ac0e39e2b48b395c937b13a5cdd4326640180e995ad39ec2a914d16a2d562d99987959c9e791f9d52999468ae7563878af52e75291cbae371230d92464d787277aacfae9d3e5a08ced4ae15b520f265d5070a07241af3ebc9b91d38285a217774bf6791a27da55490a4671c7a566e76476a85e573d9be2e5bae99ad689a4c2710e9da0d8c187c6159a2f31b1e9966ce2be8303a2396babb399f8b2ad6be1df867a73f0d16872dde33c0334e4e7f25af1b1aef58f430ebdd380b53b1b71c6d18e08cf35cd17a8a7ab3bdf0c8fb569b2124160db54fb62be830cef13e4f358daaa673704c6cf53703190acbed5cc9da6764bdea0ae2de392b9c03b8641cf5af426fdd3920b53574e9a2b48d484dc48c11452d2273d78f348d9babf074db9644e5a327a0c038ad2bcbf76735083554c8f03eaa4de5d82a154c792c0ffb5c715e46124aecfa2cca9b95056337c55710dd78aaede350239250caa47ddf94023e99ac6b352a9a9d597c3d96195ce7f4cd5228efe40e8f185de3701fafd0ff005ae384ed50edad16e0625e959cbce410ece7383f9515ed7b9ae1972c6c2d95c9d3dd264244d1112a32b6082a72307a83915c49da46f347a878c565d73e22412dd22e857baa0b6b996477cc285e352260cbfc2700e7ae6be929cfdc39d44e23c41a85ff008b7c4d7b77a848b25f5c4a55fca5c090a8dbb828f5c67dc9cd78527799e8c23cb034f44361e19bfb79afe237b7f14aa059ab1102ae792c41c938c703deb5a2f92673d6a7cf0373c6fad5f788ac83ddcabe5c2e59218c6d890375014703b7e55e9e21370b9e160e5ecaa72957c213bcb6f241d4060d939e00cfe14f0b16e273e67eecae767a75e2da30630ee607018d7bd4e28f95a952c6ec1a95cb0c9551c719e71f4aebd11c2e772dc1f6d9063ed1b73d0018c56728ea11a83db4b973ff001f679e7a56c8dbda988e374832433f05bdcf735f25524dbb9f78a0dc445b50d92073d707b8ac64dc8aa90b53b9d659e8c20f0f5c820a07859f04e31f2f1f9d7b349b8d33e37137ab5d58c4f873765ad2f632a46182b64609a5869be73ab32a52741237a64d9216560416db93c73e95ea4aa33e529d265c81b7ae54866039c1a519b2aa516c64b2393b338c8e95d2a4714e8b191908db49181db15aa99cdc8e248970b1a1031d68e6ba29c79086e10c919247048c528ee34f98e57c552345a74e846048b807d066b8f15b1ea609de68e77c196c7cebb7e0ed5551ce78e6b8284753ebb309da09199e2db54935db82c0e580c91c015c98a5ef9d795cff00767332e9b24526602c4a9049c607d6bc770f78fa053d0ed6fad2cef3e0c78426bfb7115fcfaaea0b1dc853b921053e507a7dec9ce2bab050fde1a56a9ee9cef8f343bfd4bc3da47885419b4bb58e3d114a61bcb96352dca8e8181ce71d735c78ba6d573aa8cd381c9e970b4ed6f392c5616018819da7aa83e99c63f03534b4415a7eed8b97b7c6ff005e6b88d1628b76e0a80851c7415d5474773cca9ac2c743e0ddba56adac7899f6c634cb2630bc8b956b893e48c003a91927f0a26b5b93877eed8f31b556975b442c580639627a1ea4fe75e6475ac7a8d5a9dcf5af0f8c4680b1603af18e6bed696a91f9ce39fbece9165f2a367c8da17a1aeb6ec8f192e6958e42697fb4f591856fbfc7fbb8eb5e0d6f7ea5cfb5c247eab41dce875ab836fa538080efc22e3b5775495a9d8f0e847db57723938988e483b80cf22bc09eacfb186c25c48123124516e99970a8b9258f41f4e71528a8ad4f6cf8cf6f0f8547843c0d684ac5e1fd22392ec3364b5edc62494b63be081ea057a996d2b2e6671e265ad8cdbfdbe17f80da95ef02f3c59a947a6c471c8b483e794e7b066007e158e3eafb57ca5e1e36573c772125e1f91d39e95e725a1e8d393b9634eb55bebf45705c161b8f7c77ae8c3d2bc8e6c5d7e446df8aacd52d11222dfbce029c741fe15e9627b1f3980a8eb55e71be1ad2a7d3f4d12b01b9ce49427047e35787a7a5cacd313ed1f29b916a2fbf0e85b03af435e9729f3f645b5bf81b0ae085f46a56b1376882782d9e5de8ea83fbbb85534529b238acc4edb4b807b1159d8af68d0ebad22611155db2b30e88327f2acdaea3c357e6aba9e55ae5cbcb792c5e633dbc1212b8fba0f463fa63f0af9dc46952e7e8b8456a45bf1b45ff0008ff0084b40d38b13757ea756ba0cd9d8adf2c23ebb4337e35e4622773d1847434bc21aa245a02472b310431db9e057d0615da89f2b985272ae8a1a3dda1b894b465884c649c724f5aca93bbb9df895cb865136e39a1753938dac149af4f9b43e7b9342bdc5ec08b8555e4f3c7269b91b2816b4fbf0a9b8c44a91d00ed5ac6472d48bd8b77374aca005c13d08155299cf460d48e6bc492b19220533b94839ee2be771aee7d665ebdf3b9f835397d4a44c280210428f453815f219bbbd03ee729d3127d23a54c86d10b12188e83a66bf2bacbde3f478bd496eae0c1c9718233c7a5611a569dcb7a1cf6a5a907046ee3b0ad9c39a56434ee71fab5cea173736da768d672ea3acdf37976b6b02e5d8fafa003a9278afa8cb30ce72d8f9fc7623d9c1dcbd79acf86be096872dd4496de26f8cd6f2ec13c6be6699a4023e625b389665078c743e95fa0d3a1ece27e753aded26d9f3c5aea337897c577d7badcb34e890cf797135c31df3c8071963eac7a74c0ad69c2f2426f421f8516889e2fb4bc939b7b2867bc76eea52366527fe058aea9c79223a5ab386959ae2569594a9918b9f5c9393fcebc981d13d45890a90a065b3819f5ab49b66725a1d64d1f91a53a801485181f435edd44a348f021ef6249740c2d8ccdb724b8031c0f7cd69829248db1e9731ab0aac6c4904b018c01c0af56f73c0946e49bc040f8243704e7bd689d8e7702e5a5cba2643ba80780188201ef8a2333074b5268b5575dcb265f3d3239c569ce673a2ec4f2dcd8cd6b2b4859008d890073c038e9ef8a1cd2832685171a8ae70da4db417174cca7e6501997be4f5af1a94b9aa33eb310bf748b57e2d21d46505036d0382739fc6b97112fde1a60e3ee15edf4b1addfdbdb5b00ad752a42a3923e660a071cf7ac9bbc8f5ad689e8df1cdaee1f895e2431b993ca912d933ce3ca8d5028fa1535f4387d29b3c8a8af2297c7f83caf1768ba640cd8d3fc3da7db480f557653232fe6ff00cabe76a3bd467a74d5a279e4567398c738c67a1e73e9c545b4263b9d7f8452e2deca601d9e4329200e8aa1467f1afa4c1ff0cf9dcd237aa8e6eeee6e17549037dd323052570546781ee6bce727ed4e9e4fdca1e92bb4eb118c90e42939c607ad7a2e6dbb1cca163a8d2ed55e1e03280dc7727f135bc3e1382aa3435305349b8d99c943c51595e99950fe3a39ff0008baadc4ca0939501b1d86735e3e19d9b3e9f3056a28c8d46432eb970a46406fcc54557fbc270ef969231e3649351be557c288647c91c9c74007b9e2bcebdaa36775457822a46c64d38b13c2484649e8315b49f340749f2c88edd05d5c471a06762db708327fc8ae58fc474ee8f53f87c34df10f8d6c742d5266d574fbcb5fec6b3bdbb5c1b4694623703d15dbbf6e95eda8de0651dce3b535b9f035dea1a4298ffb56d277b4b9ba53b8a9462aca87b038ce7ad788a5cada677dac8c2b2125cdc808ecd2b13b49e4e4f5ad29ae6918ce56477d79632c5a3095e4532615645c70077cfa9e95f453a76a563e5633ff00692df83e60f0cb6c460ab165c0e99c67f955e074d0c334d5f31d7416aa530c321bad7b9147c8d79b34170a14038e9cfa56f638f999a96d2889918ca08dbd3af359dcce321e3508b9dc4673458ae633fed9e1ab18819b589af5c9c85b4b6201f60588cd7c63a89a3f62852f745d3bc53a15cea7041069b772e1b7133c8aaac0738217279a74a6a4ce7c4ae5a66ef8cfe2294d1e482db44b3b759582fc92b9c0eb8c9f6af5aacd4627cee1682a952ecc1f01f8ef50b2bbbd963d3f4d91658c46ab716dbc21cf55e7afbd6386a9a9e96654a2a9a47573fc49d5cc651ad748e576f16238f5ea7f957a8a773e57eae91453e224c488eeb44d32f62539fb8d13afb82a7ad6aa68ce54517dbc6da04db167d16f230c79960997e4fc0f515a7319470ea43df56f0a48acc354bbb676240596d4c81476f996a39ce7ab8357d07c5a4da6a32e2c35bb0bdda71b518a37e2093574e7a1cd570774177a25f59ac864b69366002cbf3ae0671c8aa752cce3fab389c0f8e1646b02011b4b63904e40eb5cd889e87a39752f795cc8f0705b6b799880373027df8ae5c3cf53d9cd344919de25bb13eb72042be5a9c1e7a607435c9889fbe7a59642d4cce8ee9231b4950a5b820e41fad70a7791eb3ba47716b689e2cf82d045b9626f09ea922c859805923bb2597049c96565e9ef5dd825fbd2ea7c065788165b3f81ba7a584ab25bc3e2494dea6413be48418cfd0286a9c7c2d56e6d859de363cde1b9db1de45131844ea3cc55e3732e4ab11df1935e3ecceaa8bdeb11cf08b3b5b65ca811c2a32070fd6bae1a238a7acac4dabebab0f8622d3958665945c4c98e4e015519ed8258fe22a672d1954a9f29c6f87f326ac0e3b9c026bccc33e6ac77e21f2523d7f428d951378c646715f7144fcd716ef26cd3d42530e9d330209da5767ae78a55aa72a30c1d3e7aa60f85edda4d49a6970c91ab20539ea5700fe15e7e1d73ea7d46613e5a1644fe22bd1e72db127e41b88048e4ff003c53af2e87265d4ed0e631c966185918fae7a9af299f4b18d91defc07f0dc5e26f8b7e1cb6ba84fd86d253aadeb3af0b040a5d89f62401f5a495dd84b4641e34f11ddf8d3c4daceb6c0bdcea976f3a863d0336235fa05da2be969c7d9523cac4bf7cd9fda0e74d1fc47a3783eda5135a785f4b86c9989c6eb87fde4ad81c672c067af15f35357a8d9e853d2173c99898a4048041193b7d69db43a69c91d17842dbcdbb9ae0c5fba8d42e4e3a9e7eb5ea6095d9f359a56e5d06eb521bcd53ca8465815403f9d4d7779d8cf2f4a950723adb2b7115ac69800a80a476af62945281f358baee536c93cb00119ce7b62b78ea712a8d15e5d321986e66208e883bd1289d31ad628cba6c8d90982bdaa1a378cd3190c72c728ce463818a868d1d8b936b6fa45a4b72e31246372f6e474ebdf358d77c94ee5d2a1cf38f29c169fa5e9dad6a709bd91a1b36904974e319dbbb7374ebdc57ce545ce9b3f43a6feaf08a671de37f10b789bc45a86a183143249b6de32bfeae05f963503b6140af9b9c9b67b2b636bc333ecd125c8190a4a9c7735f474a56a278b898295644fe1e89e5495802cc5b03238cd6b84575739f1eed1512dcb6d333e15033e3232718aecea79096b61d69a74cf8795493d705734d14dae86ddbb0861fba002380a3915b239e6ae3f3e5dbb10b9c8c904726b3bdcce2ad239ef14c0df678a427686623af22bc7c623e832f7ef9d3fc189c26af206600f95b473d3e604d7c7e67ad03ee72bd3127d176f7a238413d08cd7e69597bc7e8117a94af356dca518e5472a33510836ce84f98cdd2ac6f3c4ba9c561a745e7dc484900b0544551966663c2a81d49af4b0986752a1c35aafb24cc9d57e38d9782ec75bf0a782dedaeae75387ecfa9f8a6483748a4120c568c4fcaa0120b6324f20d7e9396e0d53d4fce734ccb9ef147cf3af35c5ac22d2cc36cce5ca756ee09e7393d49af6712d4763c1c3a7377373c15abde69bf0efc6f2c8229a2bcfb25830b95dc07ce5db1dc3617f5ac683bc8f4a71b22ff0080c68d73a6f8cb50bcb09edfec9a24db24b3900e5d910707b73d2bbf1b1e58914773834d0f4799d561d565462a0edba80afe1b8715e4538dd1b49ea5fb7f024b3bc4d6baa69d23160364b210c7e831cd76d2a49b33a93b23a9f13fc3dd72d34b4992c249e16655f32df0e0f1d060f4aefc552b42c7818677af732b45d02fedec584b67709f33107ca63fc853c2d1b4078e9fbe5ab7d1b5169862cae65dc718f2c8fd315e8c51e2f35c9a6d03539572b6374e8bfdc858e0fa715a5896c9bfb26ee25ccb657514aca09dd03703f2a4a045f520f2c23b092408003957183fad53817268a1a8458b19f2480170c4920e08f5ae6aaed0674e1e0a551185e13b7637970ea9bc600383d39ebf9d79f845cd367b18f5c94d224f1222c5aacea1403c647e1cd71e2b4a86f825781a5f0b11ae3e247856024ec7d56dc9dbce42b8623f4ae652f78f5651f74d9f196bb75e20f1debd72803cf79ab4e634ec374c428c7e5f8d7d35295a933cc943de1ff1f6ee46f8cbe278b3816f2c36c07a6c85011f9d7cb4e7fbc67a7187ba711fda322c9b805523aede722a94f431e5b33a5d0350960d2de53f7048c49e849c0e3f2c57d0e16a5a99f3b98afdea39e875679b525dea198396c66b86134ea1e8f225411b5696cfaa5f1c155c7cc59c80831cf27fc9af529b5291e4cdd99e97a5e85e1e8a38fedfaedd5cc9b416fb2c0422938ca82c32d83df1d2bd38c3dd3c6ad50d1d6345f024ba2dcecd6b56b7c42ec924b6e1b2d83f7970381f5a8aead4c8c2caf5d1c9f857c1fa15cbdcbd878a6299c2c6a12f6068f239dccb8cf038e4fa8af170d1f78faacca5fba89c7db7872eb51f11dfac061263908676995118671904f5e94a704e6c5076a28e7f57d1ee745babf699adc70d1e6270e0648c0af3671b499e927cd0450b071796734720550a3f8401d7b9f5a549de2d19dad244be1ad5ae7c2be20b4d46c8892f2190318946432e7e6539fef0c8fc6b9de923be31d0edfe2359e9be13f113db68d7c2eacefa28ef96e2320f921be630823f894e57d6bdc84bdd33e5b333be2e6988fe349752b3656d3f59863d520900ece00653fed06073f5af2674ef5ac74b96873f6c22b47528c448bf3640ce2ba210e4a8705491df40e359d1d4e4e244c86f7033f8d7bf07ed158f91c449d3ac9995e1ebe4d3f5a412330dd98f047527a67d3a572d397b3ab63bf171f6b4548ed92e669d4040557b63bd7d0419f175dab97ade295c057cfcdd4d745ce5d2c5f8e1665da0e76d6473589069fe68dc0f5f7ab0b186de18d36d622f77ad5ac9b410a96ca5f68edc9e6bf3e4d5ac7ee6934ac5ef08d86843547b9f3aea511a15550db633ee0763f8d7761e36773c4ccaafb9ca4be33d5bc3b1431406c6ebed2ac5d5da7ca8c8c72a3dabaabcf97439f2da7657659f08ea3a641a648eba42b966f95a59482e077e0f4ad30cfa918f7ccec6c5d7883419d0093424565c65967639aeee63c7501cda8f859d039d3afe262b8631ceaebf5087bfd4d69ce675624b3e95e1ab9800835896d5986e3f6b83a0f4c2934f9918c533324f06b5c990586a9a7de21601079851dbd7a8a346538b6636a1e15d4748b8cddda14556da255f9908f50c3b5694f62671d0dbd2bc67aee84c0daea122aaf1b1c0746fc181aa7ab39a31d4b3abfc45b7d4b4bbc4d6f44b3be32a9633c69b1d5f180c318c1cfa1c573d57689d382a69cce47c310daf88276834cbf855c02c6dee098dd71e83f887d2b828d4f78f5330c2de299c8ebfa45e68daa4915f28472c4861d1fdc13dab0c4e8cdf03b58c6bc7da84648039fc2b8548f45c6ece9f56d45bc39f0c347d236ec9b5c94ebb732360958d774702e3b60066fc6bd3c25dcb98735a58abe20d63fe112f045bf87d83b6a5ac4f16ad70ac411040aacb1291d43b67711d971eb5862f11cf3e51e1e1adcf3d96e84f32aae77312ac3d735e63d59ded59172f2412edb6889511a80704633e82bae3b1e73d24636a65c217707eee3a74158d6d2277d25719e155ff004b67033db06b0c0693b863f5a27aee844340ac0f006315f6507647e69898b6f94a5e28bb1e6c7123ed5032d83debcfc4d4e6d0f572ca56f7d9aba2442cf4c477c991c1666c0ce31919c7b574e1972537731c754f6b53951cb6a323dcddcf2800166ce71d2bcb9caf519efe129f2d3486aaa85e1b2c7af6ae6d9dcefbea7aff00c2fbb3e11f82fe3df138263bed66487c39a7395c9604979d95ba8f9720f6adb0d1e6a9733aaec8abf06347b6d6be2269cf7855347d2125d5af4b0e0456ebbc29cfab6d15ede32a7253b1e6bf7a479aeb9addc78a75dd4f59bc24dc6a37525d393c1cb312075e806057cda93923d16bddb192cc14804631d33551d5d8d69aea771e1f8d6c7488e771f7d4cacaa791ec7f0afa0a31e58dcf8acc5fb4ad64646899bed716575f9636f348eb823a0ae2a6b9a773d7ac951c258ecc3ee90fa13935ef46378d8f85a8f9992fcb83d3815a47432b58ac0966edf5f4a6d968465c74359dee6e98db7c6fc919c9ea073536224ca3e3178c6991dae5419886638dcc141fe59eb5e6e33e1b1efe5907524a5d8e67fb085ae9124f126e2e4961cfddf515e7aa4dd3b9f4788c6275551ec79bdf5adac9348209595f76d6471951f43d4d7cd4e1fbc3eb29fc299da69de09d7a3f0a35d4560d35bdc45fbb741b94e7a018ea6bdd704a99e55777ae917fc27e17d60dacb2bd998d030539755e71e84e71ef8ad70305638b3093366cbc2d7d7536d8a4b45910e099e6c6e04f40477af55d3bb3c3f6965a9d0c3f0cb5db8fde4674ed9d14c97a88c7e83ad2e4318d445a3f08f5f7843b4fa34201fbb26a2bb8fd063ad1629d54509fc197963b924b8b2254fcd89f27f9535495cc3da5d9c8f8c34523c9479e1193b766eea7d73d3b7ad7938fa4ac7d0e54ff00785ff87169f60d555cc91312858ac6d92003d4fa57c66670ff00673f40cb9fefcf663a922c4086c1c63af15f9ace1ef9f790f84ce4b86bcb958225324b236d554e4b13d315db430ce6d45173a8a94798e53e2cfc431a5696fe10d0a6786391c36ab7f0e55ee1c73e42b6722353c3631b8fd2befb2fc0aa2946c7c16659939b714793ceeb67a734a50150385007e55f48e3ec699f04e5edeb1cb2ea3730ced2c73b46e496241cf3ebcfa579152a7323e928d2e53d4ed3c592dafc138e2bcb4b7befed2f11197cc9a30ae7cb840c8231903dfd6bb30aeece8a91b2357c3579e1b8fe1378fae6e34cb9b2699acacbcdb5977160d2ee6054919e87deba71d2bab18d25a9c1c9a57876e2e505aebb3daa96c1fb65ae1003d33826b86314a25d45a9a9a7fc3bb9bdbd8069f7da7ea61dc1020b95040079e0f39c5756192e638713a44d6f15691e21f0ec71acf6b7f66ac70ac8182103be4706bd4c5f4478b81fe3b1749d775cb7d1e109aa5f066273bdc8e33d80c76adb0cad139b1b2bd46593ab6bb2ef74babd91c8c070cdb80f63d45751e6c55a458835ad7916378efefd3239c33027df9ad50497bc68d9f8bfc4b6519dbabde79a4f0f3fcc7e9861cd6aa244a28b71f8ff0054625354b3d2f545704b47756db3713c67729cfe55a5b433ba2978a64f065d6937720d1351d267915107d82e95e11c8c9dadc8fcebccafc8a9b3d5c1bbd6462f83bc3de10b8373326b97fa6465006fb4db070a4f2071d47d2b930518eacf43347a2472be33f0f18750bab9b2bd8355b5f30aacb0e5580e9f329e466bccc6d252a8ec7a181fe0a35be02d9b49f18bc2fbd41f2a69a6da57ba42ec0fe633f8579e938491eb2454f06452eb9e34d1606dbe6ddea70b1c719ccc09afa6e66b0ece47fc42a7c56be1aafc53f185e0dcc24d5ee3041ce42b6d1ffa0d7ca453726cf46c7339c484e3031ce69d9d8e792d4e974e25749c973e516395cf4cf19c57bd45b548f9ccc17355472d6583aa2920b727ee9ef835c149de47a3256a4773e13d356f652f3cf0db405b0d24cc40623d2be868ad0f9daf2d0f4fb2d37c296d1a2c9e229e6651831c364cb83df96e08fa57a2a566783524d91eb7a4785aef43d49edfc5a218d62603ed3a7b02bc6704679fa8ac7152f74df089fb7471de11f0fc6d2dd1b5d46c6e9f6212124c3124e78523a71debcbc15accfa7cc95d44e1342d3a7d53c4b776eb034a5a42005049c827a7b66b08a4e4ce84ad4919be25d1af748bdb86bab5783cc7280cab82c415dd5c335ef9dd0fe199d1c4d199a600f921391bbb9e178a851f7821b0915c7d991e4419b875215bfb83d7ebdab9a5a33d36ba9dce9f0699af5a78305edef950c4c6cf51f288f3a3432960e33c1c86239e462bd9c2c79a27256657f1c787a5f0678bb51d12676962b29185b485b72bc2e77230c703729078ef9ae7a9fbb7a9849dcc45641c100f7031d6aa2f9b53148e9bc377af731b5a6d3c0dc0818c0f4af570af99d8f9fcd69f2c79866a9a73c5ac4ae800958025bb73df14ab4792a1a6165ed70f63d0f4f85cc103823694073ebc75af668c9347c862e9b550d0171142a4c8f8c0cf15d37389a771f15f3bb218a16653fc478cd63cc53a6cbbbaee5e56dc01e981fe34f983d99e53234ae48076138c935f04a0f98fda9cd5ec763e1ab1fb169e8ce4a92c5dbf1ef5ee52a4d46e7c6e3eaf354e5395d5651a96b324a4ac9139dabfed8e82b8eab73958f730c94295ce9e126c2dc2c44848d7185fd715ebd2a7cb13e6b115b9aa343512764dfd7383cd6fca65cda881e687924ee278c1a5622ab1cf2c91e3e6209209acdc5a34859893dc452050db50020f071cfafe75514ce9b2b16b4cf13de6992486dee654ce414790b27fdf278aa53b239a51d0d93e3482fe08d353d32d2f101cb3a2f9441fef0c7a7a74a71a9767172ea65788a2d1b54d38c761a81b4b991b6986e976ae073c377e7dab9ebcfdd3bf0149a99cae89a2dde997b24b3464227cab3c6d94c9ef91d38ae2c32bc8f7f1f1f709359f14baeb0a9751aea36f1a286598e490739507ad462e46180a5a191aa69d65abd896d2ae5a272c00b7bb621d3270486e720727f2ae18ea7b1ecf53a0f11358f893e21e87a75adcadd69b01b1d3633b71bd230a1ce080796ddf9d7b3869a8e8715756679ef8b2f24d4fc47aaddb399a696ea4259ba9018851ec000001ed5e4d7d2b5cba6ec8a36b1182d4dc48a4b96c213c63b51cba933abad8b4224b755c80c4f39fe95d3cb6473cde9731b5f62892306dc198019e9ef5c38996963bf0aef11be1c0ef302a31b4839c7068c1ad6e46365ee58f45d2f5092de305b05319e4608afa5e6f74f8da94d4a6660d406aba8b06e85b078c8db5e57373ccf7e14550a76ee751ab5dc767a7844901c808a53a63d3f2af56a4f92163e769d273c49cdf9a0231cf27249231f85785cd791f5ce1ecec90d761146ee581f97217d0f603f1c5151da17048f69f8ae20f0bf877c0fe06b67f9b48d396f6fd14f1f6c9c6f6cfa90a47d335eb65b0e65ccce0c4cec50d358f853e06f8bf5c2de5def882f22f0fd901f2b9854f993b03e84614d4e3eade7ca65415f53c96664520a803d80cf1e95e72b267a3e436d231797691afde2df29f7aaa4af32672e4a6d9daeb720b0d14a23f320110c71d7afe95f4353dc81f114bf7f89772a78522389655db8388c004751c9fa5638685f53af35af68a81d1ac6ec49d9d3af3d2bd889f2c96a29563d4600ed4c243430e73d335211572099b60e0f2781516b1aa44b6a49280e324d4b7621ad6c735aacefa8eb1e421c313e58c7a13ed5e5d67ceec7d960a0a850e736fc451c5a7e873a06192a225207decf53fa1adeac553a278b879baf8de73c4a6d2e5935958a205fcc60db475193c715f1b387ef0fd469cbf768efb51b5d734ed1120885f409b86d48e475507d400719af4ea690b1e647dec40ba0681ad5cd8c2a7ed0167f9bcdb89b01b3df2c735d5828b48e0c7cd5ec761a1fc39d6e6388a069c86dbb564520fd39e47bd7aab73c293ba36c7c36f10c8840d2e3e070cd3c40f5e9f7b356ce77115be13788669558d8c6f3b75cdc229c7d58d4326cc8bfe15bf88e1859ae6da385412b869d091ec7069a6ee5461a9c478df45bbb4b7812648d5b24a36f04d793983d0fa4cad5aa195e0fb87d3251348e007185c3062467d074af8fcc75c39f7997ff18f4c8efa499130ea41c115f9e4a3ef9f794dfba5ebff00131f036827528001aadea986ce5383f671d1a6c1e7206429f53ed5f5d94e139bf7acf9cccf14e1ee9e2b11fb74ed386058b96da4e4800e413ee4f35f6f87a773f3bc6d6e5d4a7e269bc9b186150a3cd627e519e83bfa75a8c6bf76c8e2c02bd4bb397310dc339e0e304f15e42a7ee5cfab53f7ac7a46ad6cf0fc2ef01c008267fb6de32e3904c813f92d7a383a7d45567a161d4db7c08d4655054ddf88e080e49f98470b37f33596377b19d077679d9c6ee40fa76ae3e59289bcb56696811a2eaf6ceabb190960c9c11c738ae8c2a9731c58a5ee9d46bbe2fd66d4c420d5aee289410b1b4859476c61b3dabbb1755f3a478f808fef1b352d3c73adff006359db35caf928b951e4aeeeb9eb8cd7a949fb88f3713ad663bfe134d6d9d4ff0068caa14e51530a17f2adb98e7b5a45a1e36d753006a33ae7aa9dac0fe2464d5c6443f8cb36de3bd7219c4c97a4b2e3ef448dd3ea0d6fcc67389b365f157530a22bbb1d2f528812c45e5a2b373e8c391f855739cee9b297893c63e1ad4ec248affc302c6ea46466bad32628acaa7a056ce0e38af331528a81ed65f0bd413c2961e0cbfb0ba92cbc433e98de6902df548198b10a380ea31824f1c714b07c9cb735ccdbe648f36d534ebfd3b5096e558889d9a4492360cb83d738cf3f5af2b10daa8cf73071b5147b07ece7e338c78da59350d074fd4cd868ba85d2ca104726040cbd4673f78565a3944f4915fe05eafa25dfc58f0095f0da46cba844cc56e58ff0939e474e33f857bd5ed1a279ea5fbd380d53c49a16a1e22d66f1bc32a1ae2fae195c5db6706463d801d6be6e9db53d36c61d43c23730849f44bcb290655a4b5ba67dc07f16d6e3f0ad22934673d15cd9b7d3bc232e82922eada9e9ec4fcb0496cae4f5c8f41dabda8c57b23e57133bd7471563a1e8eb7ae575901402543db9dcdfaf15e5508fbc7b15dda91b3a4c4b1a0c9f3230e0ae7ff42afa4a51b23e4eabba3a2472401e956f7381a454d599bfb3eec96daa6261ea071d6b3c46b13ab0d65591cd785647c5e1270c1570c4e3079c1cf6c578d86ba8c8fa4c6abf29cb68da8dc7f685c049e44224dca439520e4f3c1ae5a526e4ce8a8ad4914f56bc96eb589e499dd896f98bb924f158ca5ef9d3497eecd08ef716f0da14004996dfea3a007dab48cbde147446408fcf98e481b491c1c57328f333bb9fdd3d2bc6963a5432e9b7fa3218745d4ecc49147b363c52afc9346dea7761be8c0d7bb86f711e6ce5765ef1545078bfc07a76bf6932a6a3e1f823d3b53b466cbbc05b10ccbeb82db4fe1cd7163a3ef045dce0e08dd49278c9ce3d28a6ad129ab1d2f8527483505520e1d5973fed63a7e95d785972543cccc61cf87b9a7e2a9911ad8b02300aaec3efe82ba7152d6e79994eb17166d6897b3cfa75a92e238f0546482401eb5be1aadce5c7d04aa1a91dec114e0a9f31c0c8c8af479ae78ee97bc5d4d627765251768e839fe559731d1ec9129d4d989393d69f310e92396d2b4fb0bdbc589dce09c30ce71ea78af9ca34f9e573eff1557913713a6d7752b0d374b9007c3b2955e0e36d7a951f246c7cad093af5af239cf0edb69b7f76b22a2e13e6dfcf27b75ef5c787873bb9ef62ea7b2a3ca76020b00010aa78e849af6d2e5563e23da36c1edaddf6c68ca3b801a9c8d7da5caf26911ca42efc36738cd0e3a1526ca973a449f7d5d5b1d14f5aca51358cf94a33e912a0cb8c6eebb4e453844d7db9992d8e24f994eeecc49c63e959b5712aa36e19a0b7da325ba03de851072b9ce788ee5d05ba87c6177138c107b62bc6c5e92b1efe5d0ea6d7823c55aae8966cf05cac893b61ede74574703d7bf3ede82b5a0f96274e3df36826a12689e28bb9e6576d3b51925c6c5502224f7c8e7f0c76af3f113f6950eec253e5a68e6756d2ae749bac4fc1072922b6777be4566d599d8be3b1ecbe17d634bd3be04db6a7a9595bbf88f52f1535cd86a52461a510dbc415b0719da58e31d38aecc1272ab7396bc753ca3c51e0bd4748d0edfc46f16fd22fef65b586e4119965550cff0028e4001baf4cf1518a8daaa25c7dd30e58599a14c95455dc475e6b5b5d9e4cdd990ddb2b3229381d7f0ab93d0b5ef18be2b9b7b5ac0b91b177b0c700b63fa015e0e2a5cd3d0f5b0f1b22cf864ac4d82acd91907debd2c1ab338b19a9d85dddc09a74bbd889085545fc79af42b4f9743c48d1739a61e1bd2a099aee78c9c210149ec4f5faf7a9a31e6773a71d370a762af895e512a400656253ca1e49f71538aa8de86797ebab3312f5e38d43e581e4e7afb579bcccf77a9dc7c20d3acfc57f11f40d3afc14d39256bdbe94ae5520854bb31e3a7ca0554a5cf6437f096fc5be2c3e2df15eafaf850c750b869a28d7d3eea000f3ca85fcebe9e8cbd9d13c6a90e691d4fc7673e181e13f03ac91cbff0008fe9b1cf78911cafdb6e1448f927b852a3dba578956a7b591d34e9591e54ae02b104007aa919cd4bd11d1189abe18b7fb46a89215e2352c081c03d8d7760e3ccee7919854f654596fc6370b2bc56cb90c06e638e84f4ff22bbb112e878b9652bb754dbd22c859e9d6eaa0fcc819bfde3d6bae842c8f2b319f355346394f38ce41ed5dab73cb6ec49feb1b918fad53208cc24a9738c678a92d32bc8df3e3f3a893d4d5a21bcb8105acce490154e0fa1c71fae2b9eacb436a70e6aa918be09b45b9d4e6b971bd631b9b9c00dd88ee7ad79f463ceee7d2e326a9e1addcbde3a9cb0b6b7f9718dec33c8cf4ad3153b2b1e7e514affbc3c9f5acc1a9c33c790d19c67a1cf6af98a8bf7973f41a6ff776342fbc47a8b58dba7f69dce43160be610314e7535b1961e3efb67456379349696f9b8653e58f9b39cd7b541da078b8f56aa20d45ecae114b38046dc97249f7e0f7ae873b44e550e764f26bf24c487959c8230092081f9f350ea9a4e81565d74c919c4ae41041fde919a3da974e9588e2d59de4204a42819e58fe873d6b3f6ba8a34accc9f11df79d6b19624956ee735e6e367747ad8056a83740b94125be5b0a5b9c7639af9dc6eb45b3ea7053ff0068b1ecfe11d34eb378d6e92c705bc119b8babb94ed8ade0032cec7d31d0773802be4f0d85788958fafa9898e1a0db6705f13f5db7f10ebb24b65132c0c896d6b1b1e56dd32149f76c9635fa1d1c3aa105047c2e2f15ed2eccdd2ad5a00db00d99daa40c647bfbd7b7423c913e3b1756eec656b17d1ff0068f94e07971a63a7424f5fe55e663126cf432f8595ca5b2d8202eaa30bb895ae449247b2dea7a87c4bd0a2d374cf00582b18dad7c3b13ca8ac36ef95d9f3ebc82335e8e1217672d7a8dec56f17e95f61f829e088966dc753d5afaf3cbe9808aa80e3d320f35c7898dea58da849d8f356b0951c9086403aece702b9e71d0da5234bc3f6a1b52070400a7a8ebc5756169ea73e3aa72d11de2375fb6c7191f2f96a4e0739effd2af10af551c3973b536cd48158c0ab9e400a011c600af56944f22b4ff7ac9a38f610e46e3d315d5ca72cea22dc60ace109565cff00c078f4fce96c62a60e7ca2781b475abb0f9c742e36371b48271cf27dea9215ee66f894e2caddf2acccfcae738001e6bcac62b44f7b2ffe21268f23c3a0cac87660163fa806b3c3be5a64635f3d6b1c969fab5de99741e095d7e604ab1ca9c1cf2a7835e34a7cb3d4fa4c32b46c7b87c1bf1bd95c4de37d42f740b149ad7c2b78ab359168c9672abb8af4efd3a56f4e7cd33a62b564ff0000f5ff000fc5f16fc333ff00615c6f804b265ae830f96ddb247b8c66bd8c6d4b5338a9afde1e5f69e20f0c4be679ba06a23cc77612477aa0b12c5870471d7b57cfc2a2e53b1e922c4b7de0cba25e31aae9c428ff0058ab2a8f7007539ad69b4396b13667d33c2cba146b69e2830ba46cc16e2024b93d170070793eb5eeb92f647cbd685f108e26d2cec96f1d60bc178db41528a40ce791cf3815e761a09cae8f471b2e5858ecf4bd3a631728150282318e6be9a2ac8f8ca93b33562d367550480475ce47435a495d1c32ac65f88ada41a35f210eac63f959467073c7d2b1c447f7676e1277ac8e32d9a5b5d075290929b95430e01079e477cd7cfc64e34d9f5359f3d781c968f218ee1c8f99b68c67ad7061dbb33babbb408af374ba848148c13c03d3359cbf88694bf866c6af308755832173b0464af3c6de318f4ad6def0a3b19ae419a520601624e7d6b15a48d9ea7a17c37d422d7ecb51f065eaa882fa192eac6e82ee782ea352ea54ff0075954ab7e15d34aa7bf6306ac5af04c2f7de1bf18c80a82ba2b3bc39e582c88c587aed009c57a58a5cd0b98c3738efb479a438232d838ee01ef5c51f80ea92ba342c5da3bab691dcdbc4ac373f43827923d4d1095a673e223cf41a36fc4d771b5b2a5a2b0446c177fbcc0f03e95d98895e278b808f249a17c292968a4597232400064f273e9518367663e8aba91de68fe18d4b5121e2b5658460b4d31088bebf31c0e2bdd4ae7ced64b9d1bd6fa16916c1feddab89a507fd4e9d0b4a00edf31c29fc33cd3e5219a569a768b7b02c91786b58be51f2f9e2f5e3dd8ff644640fceab94cec72fe0ff000c44913dc3020b6140ef81dc7d726bcdc346cae7a39a6339572c0a1e25b582f3516850844841272dcb1f4158d79ddd8d32f8a8c3da4cd8d0344823d3219040c8f2aeef9fef2e6bbf0d0e5573cfccb19cd3e52fbe9b1b100e571dc75aed8ae6678aa6ae5097482f26379e4f53da9ce3a930a9a95c698f0b1dae495e01143d8eb9555623b9fb74441705b68c802a5ea67cf71b1dfcb13fef50b291dc60d52296a4c6e20994072a140e370e952a05a650bbb6867c84dbf2f23038a1a48da2cf3cf15db9fb6b756c2853b4fa7f2af9bc5eb52c7d76034a77346083ec7a5a3853b95013ed9e715dbc96a7738aad5e7a9638e96631cae792d9247739af9a93b4ee7d7528a5491ada6f8ae6b7b616976a2eed983655946f248f97e6c6460fe1550a9cd2b1097ef0f4af897f60b4f0f783744d0ef0ea16ba2693fe946256dc9793399265e71bb0485e07e75ee6163ece0e471d797bc53f89fe207d2b4df0cf801e466b3d06c7ceba4c60fdb6e31248c0f7c2955f6c1af3ebd4e6a8cde2af138648d5ee39e708117dd47735d34dde279389a76650d2ad2cf5bf15adade349058a2b4933aaee0a8a0b312076da2b094f5b1be1e9dd1ca6ab71fda1aadc4f19cc7239200e001fc3c7d00af19fbd50f4d7ba8d8d1835b277f9b2720743f5ed5edd0f759e4e25dd96757b99269a18b775019bff00af59e227791586a6ad73a3d09df4bd391c9c7980b3027a81ebf857a1465c91b9e7e361ceec634dab89ee1dc824b31dbea07a579952b7333d0c3619528889345349b490491d08a57563a946ecf57f869671f867e1778e7c5663297b7e17c3ba6c808fbd20df3b73d828c715d186a6a4ce79e88a1f0b3c2abad7c45d0e0932da75a37f685de1b611040bbdb93eea063be6bd9c57eee8d91c51d59c778b3c5b7be2af17eb5afddb9964d4aea4b83bc72149c22fb61428af9d836b53d14b4282dfc0701b72e7f8b1914d4eeec38a3b3f0918a2b27b807e6762a15bd0639afa2c12e585cf8dcd24e73f6651bc906abaf84076a3b0553d491dcfe558737b4a963b69c3eaf87b23b38a48d14673b30001e98e2bdea7a23e32b3e7a970f301627a0cd5a7a9c925a8e8e41bf04f15ab08a19213b9b9181ce2a46d15ae1806207cc4700f6ac24cd96c8c4f115d1874f74e8ce40c8ebc1cd70d791eae021cf5917bc176e63d3de761b5666278ee00ff00f5d5e1d5a0d8668dfb4548c6d6a7fed1bf9e70a77336d5271d07039ef5e4d59b9cda3dcc050f654944e435ab71705d986d2a0e0a8eb5c138de47bb19d958e7ae9c0748cb60a8c83f5ae195dd4b1d7434d4eb6ca676811146d50a1548ee3d6be92847dc3c2c53539966e2c5ae11be572e30d80304e2b470f74e5a73e5914a4b09d768643e80919359381d4eaa6305912a39ce3d46285023da58960b771f31195c7031d6b3e4d46aa2b993e21f9a08fa2e5b0a7b715e763627a78077a8374381b16ce090cedb707bf3fe35e46228b94394fa1c3d44ab367b0789f547f0d7875fc328de5c93ac777ad380376cc7ee6dc9c71cfcc47a902af07825423cc466388955564cf37b156bed465bd7f959db099ee7a1fc857b7429bab2d4f06b54e589d546a11142a803a6315eac15a5ca7c9d4afcf338b9a08e5bc943820b485738f43c8af131306e47d6611da254bfd344b0482373b8aed19e809200ae4941a47a09ea7a9fc6bd3a7b1f882f66d867d3ac6cad58838059605ce3d0735f418287bb738e724d99df145afb49f0d7c35d3a72ca22d1a4bd11b2e0a19a6620f1cf2a2bc3c4cbf7cd1dd4da48e0d6fdd4a02832338650413ebc573b90366ef866f91de62411d06586715ebe0d5cf2333a96a6914f5e105cebb222ed2acc02943f36300fe559d557ac3c1bb5167476b0c0149c2b03d980e2bdba51d0f9dab37ed5972dd2d4c241da7b900f06b768e672647e5c1e68208560381ed59389936cb46dad2451c12c7afcd56d1ab761cba621dac1f681c0fe2e3d0d5a4546473fe30b016cb0451c8a5d817c118c8ce3fad78f8dec7d065b2d5b21bb49ec7c34cac0b0da0a8240c66a7939695ccdcb9f15638a53890123d49f6af9f9c6f33eb60eda1e8ff0de4367e06f8977ee31ff001268ed571dccb3aa803f006b4a2ad3366ec8d4f814db3c7cf72edb52d349d4660a7a8db6ce339ed8c8af571cbf7671d27ef9e4b6596b48fe739da1b20f7ebfcebe760bdd3a26fde2495bf74a0e46339ade099b6e8d6bb755d054e300a15247424e7fa57ad2bfb23c2ab1fdfa22f064250b3b905cb6178ea055e5d45bd59cd99d4b687a1dadcdcaa8f9368233d0fe55f4f0d343e2eac8be9773c4aa5a3e0f76535a35a1c4d197e20d55e3d1b500caa5648c2b6320fde07fa572e29da99eb60d7ef91c0eaba8c49e179393e64f738031c600e79edffd7af96ad514691f6b1873558b303442ad331047427af3d7a5634248d315a106a0c8baa1298dbc700d673fe21b52fe11a36f25bade46d3293918563c678e39ae88abb335b10ea56ad6ee71ca31241f4f6aca71f78ba72b9b9f0da75b6f1ce82cce5435cac4ccbdb702bf97359d25fbc3499dafc3182e2c3e2045a44ca1e3916eac6ee150086468d9587f235ee5557a473c56a799477496b6b111cb2f009ec01c0af13da5958ed6b4248e676916595cb05e4f7359a9be7128f345a3d1edf449b50d2495c436c57e5b89071ebc0ea4d7bce1cd4cf998cbd9d768b1f0ea5821d544761606ede48ce6eaf46d8d4819ddc1c0f627df8ae6c1e8cf5f1b1bd1523bcb8d434eb5931a9eab77acca9b42c166aa6dd4f701f81f8815efc59f1f56eddc48fc737092edd2ec2d34dd8301cc224930782031fe82b6b91709fc67e21b890bff006eea91ff00b315db2a8fa00314cc9cec5fd5f5ed3347b0012fed96566d91c71b024903af15f39f5ea74e3eec8e8a197e2f1f514a71b1c8e9e965ab6a48a6f60c2e1a43bb2541272327e95e7c3154eaceee47d6d5c254c352e5e5b9dec1736122aac3776b3151cec9578f41c57d3d3ad0b69247e7b88a35a52f7a2c964b12cc0852770cae39cd6d0ac93f88e6f65523f64865b4312b363191deb59548c9e8ccda9ad8a696a58962063ae0d54a7a59315dfda2b490334819810b9e334af7d8cdce4826456864f901cf4c8c9a6b9bb9dd467dcc69f4d1216380806323ff00ad5a587ed52999f7b6325b40d246c08f53c01ef5cd3763b29cb9a679fdc5dbcd7d9963c8964cb123049ce09af9ea8f9ea9f6f46d4e8b3a5d644434c97cb6d8db4282bc1e78c57b7555a91e0d05cf599e797b68d1b6586f553fc3d6be3e71d4fb5a6f9236343c11a145e21f1b681a65c16fb2ddde224b8ea507ccc07d4291f8d67145277676fe1ed667f11fc58b3bf6811e39354376f6bbb09e546c5ca31c600daa013f5af6dcb9289c725738bf166a6fe26d7755d74027edf752dcc8339d85d8b6d1ec01007d2bcdbfb466f4df292e9530874eb89e552d2b2ec84023a773f4feb5d7189e76225ef8c9a4874cf09ea26dcb8d52f76c321ecb16496c1f563807db34a71d054aada479f46851d7236313838ef5e3a8de67a0e5a5cebad6de4b5b605d5b6c83764838fad7b495a079759f33d0cf8e369ef948e016183ed9ae1a6dce67747f754cea352b848b4c11003e718ebc81eb5e856928c4e371f6951339861ceee077e95e42773d98c741f91044d2919da0b019ebed5329586e27aefc4527c2de1bf0678351cc373a659aea97b83feb2e6e41621867f85768e6bd7c0c5c756715785f625f0aebefe10f855e35d7dc91a8eacb1e81627681f2b1dd3b29ee428c1f4a3195eeec73c689e7115d432a79630140032c3b571c65a1bc900b48a585829ead91b4e467d8d5423ccc894b951d43d8b695a51c12cca98ce7d6be8e2bd952b1f19525edb12657861e7fed169482638c1e18773dc1fa57260f5a8d9ea63a4a14d23b58f504ca975c29e323a57bed9f16e1a965648d9480e09ce4734d4ec652a6d80668db2795f4e86b4e721c18f91c321623af6cf6a7ce472b456623692081e80d61f6ae6895d9caf8b2e72b1800ee4cb1c0ebe95e657779d8fa4cba92517236609df4bd0324e36c5b40038dc7ff00d75d727ece91c7592af8947396f316565600923233d0d7cfb9735d9f5f1828d82cac05d6bba75a956db3cde51e84fcca73ed59add1327fbbb1c8dd59c1fdb3b0950a1b690475c1f5a5523fbd4775297ee6c7a058a5a5aa00803e0633b71fa57d0538e87c6d79cbda32d3ddc7138289b9cf603b7a56ab4673d9b95c596eedce1b91db91fcaa648d79b529cb791b4992a5c8cf2179aced6357aa294da9a0dead90841e5bb9ac9a46f083396d7ee632b112430c1f931f74d7978ab1efe063a9d5fc32b682dd5bc4b7d0092c34bc4912be089ae4e444983d707e63feed6518fb4d4f4b9f966cafe269afc58c3613947bdbb617d7521fbcd231c856efd086c7419a6df37ba6352a73459269f66af20d80886106200919ddd58fe78fcabd3c3c1dee7cd632b3b729b6cc21b7042eec024576dad2b9e14173cac70f133932498c1662c09efce7a578159dd9f6d45591b3e15d327d63c51a1d9245be4bad46de2551df322ff4acba1d4b73b5f8d772da8fc55f1acbd33a9cb1ae3fd8c27fecb5f4385d291c12769319fb405d03e37b1d39c05934ed0ec2d1c37f0b08b711ff008f57ced657ab23d28ced1479aa4484159718f502b1946e8232d6e741e1cd2627b29a42acb8936a924f4c673fa9af6b0f0b533c3cce7cd51231e0d385ff008a5c23931ab330651f7863835cf4e3cd5ce9bfb3c31d7dbe8e1a21bd882a3183d0d7bd18e87caceafbe48da3966cee5c7008c753f8553899fb4b95db4e31b9024c8ee07f2a8e4b97ed120fece7604f6f6aa7127da5d924165731e363e100f979271f89a7181ab9ab181e30bab85ba85029dc136fcc0f7f4af1f16bdeb1f4596bb464c77887520be1f8a178ff0089067d481c8abc44b968a47361d73e2ee73d14105cc848c2aedc8c761e95e1b49b3eb99e89e10d19d7e0e7c43b9671e4c975a759283c1c990b647fdf35a61a9a75072d897e115abc37be38bd423fd0fc2ba838c13fc4aa9c7e66bd5c7412a2614773cc2dec00b384af5551f31fa57cf53824cdea309ec25111720396efb80c7e14d475b1b395da2d6b304f1e9050e7e551b50772457ab357a491e44a5fed1cc757e14d356ded230536b05186c7535ebe0a9f2a3e7334ad73b1b781234c8e48e95ed247c7b9ea5991cb5a640cf3c8238a1ad0d5cf4399f163c4744b90400e00c719cf23fa66b8b17a533d9cb657ad13ca75d60749b3b75c052cf2300b820f419fc057c4633567dfd25ef95b45b12cac55b27b28ea47a5561a3a18e2a56958a17b08875295491b831181dbe9583d6b1d34dfeecd67b3dd0c4558f98cb9196e3819fc2bd4843432be835dbed1a726ec1604ee3dff001ac67f01945f24ae334bb74b9d4ad222db034a8038ec770c1ae48bb23b97bd13d0fc53ae5f7853e265dea5b0c37d6ba809884042b0e00c7b329ebd3935eddf9a9a338ab1ccfc40d3574ff1cea90c3198ed9e51756ea57fe59c8a1d40fa6ec7e15f3d2569b3a96a56b148ac195ee5166b83feae23f74371c9f5c7a55c15a57257c773d2b4b6f3b4e43aa4a249d031102b61147518f41fcabe8a93e68d8f9ac546d5f98e467f144cda8daacd3bc9676921db6c8424601c82081d7f1c9ae27eec8f665ef513b582796708628762b2e704000e7d2be828be689f0b8b972552fdb428ed99642a14740dc66baac7146a1761015301370cf5abb039ab9f415a7c31f0cc4495d12c149ffa60ac0fe62bf9a1e26a47791fd2b3c3c632b28d8bcde04f0ea4614e8d6247230b02804fe5584f17516d23a3d8412bca372adc7c31f0bdca80fa059380095fdd01b49eb8ace963271da4cc1e12849fc2bee32cfc0ff0c001a2b6bab4704b0f26e180fa004e2bb619c62a0fdd91cb5f24c1555efc4cbbdf8397655869daeddc790404b9557403b0e99fd6bd7a5c4b8ba7ac9dcf1ab70be0eb2d236312e7e1cf8bf4c552469fa922919218c4db7e9cf35ede1f8c7a4e173e76bf05abbf67230352b8bcd18b9d5b44d42da356da678e13245d71c32e7f90afa2c3f13616b3b267cb62b84b1b875782b95edefecb511bedaf229067950df303e847ad7d3d1c6d2acaf191f2f570389a0ed563619736c62c919033d4d7ab19a68f2a69c67a993ac9169a6dc3119f971eb8cd72d57a1eae0fde9a383d2ed12e35042ca3f77965cfaf4af268479a67da6227c94ac84f14452dbdbaac64b6e6dd8279af47173e5858f3b2cf7ea36ce4d2e94122e01181d4f6ff001af9a92b9f54dddd8ecbe0f69915d7c4ab2bb89b745a65a5dea44004f3142c55bdbe623f4a230d4a9be4455f0bddcbf62f15dda85b7b95d0e57073b4ab48e8a48fa8661f8d7a3898fee5134df31c569f72d1868085db20019470be99af1e93b346f2563a5b48561d491018e4b7b588ae42e164765e4fe00f5f515ed289e15595e6637db15e09c4a08c21c8ef9ed5cf524912a2f9ae7222626e04600e4f1c679f6af1612bccf5dfc173b78fc457b63a41809496075dad1328c7d0f7af52a4ad13ce82e69173c3d2695a9b97bcb592d5d481e641f7413fecfa569868a7a9b626768a45fd6bc27f6a91869da85bea0b1a75c8470081c6d34abd3e7d8ba11d53399bdd12f34a6cdcdb4917ca0fccbc7d7e95e74e3c87a9091bff000bfc263c65e3fd134e9c05b1598de5e337016de1f9dc9f6c003f1ae757932a6c93c71e2a97c5fe2bd5f5f9d429d42e5a58d54676c63e58d40f650bc57d1d25cb4ce26f999aff0015a68b49d2bc1fe10894a3e8f61f6dbd0c396bbb9f9d8f3c8c2ed15f3f5a5cd33ad4558f3b772530410a7d475a4a4d11281a1a2c2d2df431a10837679e838f4af4b0ceecf2f13eea3a5f126aef0d8c3070e24621f8c70a38c7e26bd6c455e87cee168deaf3334bc2ed6e34c01d42bbb16391c1ec3f4ad7091b6a639a49dec8d37b78a76e58203d36d7ad63e6d36437164510ec6dd83c0359c9d8e88ea451adc46ac7e60071823231fd69dca704c79d4cc6a038c9ce060f3f5c555ccdd1b89f6f597a67038a6990a8b4ae733a9cab77ad2206465dca98dd9ef939f4eb5e2ce77aa7d2e169b8619b35fc437823b086012637b125719dc17a56d889de163870145cb10db312090bb6492a171f53ed5e4ed13ea795b91d7fc31d2ffb47c7fa646c1584715cdcb0e7e55485db3fcab486e8c2a2b6879acb6c99b7b9c72c39ee73fd6b59c7f788519daf13b1d3a1492de266c9f94103a119af6a8ea8f99c44b966c9e4b325999136f71cf6ae8e5d4c23555c9228e36003824d4d889cb5d01ec639559f2005ea3a66b0923584ce7efad10b3100e7938cf02b9e7068f5212d0e6753d3e6bebcb5b5811a5bb99c430c6bc9766202afe26bc5c445b3e830323d627d32d34616de19b7db3699e1c5379a94c4ee13dcf05b1ea03ed41f435ba5eca07435cd267046f5eff53d475694c642b1b8646380599beeae7b64e00f415cf0f8ae73cd34ac741a3d91b6b1850e7cc2b96cf2493c9afa3a11b42e7c8626a7b4ab645cbf611d8cec31848c93ce31c71fad151da3739a8c1bac91c2c2dbe252c4eeeb8e99af9a9bbb3ee29ab23d0be055a8bff8c7e0f88fdd8f514b86279f96305cff00e8355637895ee6e1fc59e2e9a5d84cda9ea6582c632c4bcc7a7be0d7d2d25cb491e7cd7bcc9be3a6af1eb1f17bc593c615a18af7ecb110bfc31a220fc7e5af9793bd491dea3eea3864c104e473447544c763aad16758344dec3e5dccc7e83aff002afa0a4ad48f9cc63e6ae9187e17246aac4105983024f1d6b8b0aaf59b3d0c6be4c3a476cdf2a2f3d6bde89f1327790c69cafca0f535522a2371e63138eb4450487f4e3a678a7614772cda27279ca67bd32aacec735e29265d73629036f96a0919e4f6c7e35e1e255eb58fa8c14ed46e45e398d4a5ac0225543217e07230318cfb9359e317ba9065ef9ab499ca269c52624498007ddf435e272bb9f56a5a9e99610dcd8fecfbaab7cd8bef145bc6af9077795033301f4dd5d5858bf683a92b222f05b5e58f803e27df862197424b60e08ce25b84523e98cd7a198a7ecd239a84aecf378cdcc0d901c85507715e05786a2d2475d4229ef6e1b01803cfcc718cff00f5e9d3d676127a366b399352b8b181c0e583608ec3b7f2af6211e6691e14ea5af23bbd322f291539217d7fcf4afa0a31e547c6e36ab933744e64c613e5030302ba933c6b0c90b08c8e3047434dbd0d64b4398f19a18f40ba7c10ff002e07a8dd5e5e365681efe551fdf44f23d5a60f3018ced50315f1d88d59fa2d3f88b9a3a0581d94e00ea4575e1e3ee9e7e2fe2312e9fed1ab39270338c9ec2bcf8ff1ceea6ad48e82360b1e1c07e31f4af662ed138673b10441596ea3c2c401de0b1c71fe15c52f80a96b668ca27ef6794208c75cd79cde87a5056b23b2d66ebfb5b44f0fea129f3ae7ca3a7c8e3716730fddc83e8aca33ed5ebe1a7cd04392b1aff10425bc1e16d52095269af7468e2b96ceef2a58d990a83fdedbb4d79b5b49b3486a70e3526b7898216deed93213cf4e00f4acb9ada9a72f53a1f0adc4b7a1ed9d9f01b7024e783d857b3829f3687cfe3e16f78b5ace9f15adf205da44e371c723dc7d6ba3130e595d1961abf3d2b1dde89225ee9b04ae4f0a102a74e38e6bd6c26b13e53318daa9a890ac6ea608c600ced6e6bd248f1ef62ec71bcc81b695cf61d2aec3e63ea4b704e4035fc9d267f5b28dcbf141c720601ae7a93d01c478b70a08008e320d72f31515624587cc201c16a7ce5b17c92090401e847149cc394864b618c119ab84c5ca51bad3d6e148719073cfa56eaa72cb432e4e8ce475ff00863a2eac9be6b3432024895542bab7a823bd7551c75484f467155c053adba4709ad7c29d474f467d32ee4bb40a42dade312073d335f5985e26c55156be87c6e61c2387c4a6e1a33cefc6cf7ba7d8c90ea3a64b66db82b32e1d30067391d071debecf0dc4785c4c3965f11f153e1cc4e065a2ba39df0cdac37d348f04aae85782a723ad7bd81ac9d4e6bdd19e361250b495999de2b565bc442dc85e00fe75d78aa8aafba8e2cb138cbdf392bfb156dcaf82c3907bd7996d4fa8ea759f0cd25d0fc37f10b598d43cd1e9716991be7054dc498623fe02b8c7bd4518b754b9ec57b7f2acfe1a78af52c3096fa5b5d1e0c00320319653f80551c7a8aefc64b5b19d3dcf3ab68c99d143301b80cf5279af1e947f7973b27b1d879c8b637650025015271f7495e0d7b6e7689f395e3fbc3cf1ee1de5705f7283d7d6bc3ab52ecf6610bc13134e52da8473100ed7ddb71c1e7a56141734ae3a8f4b1d9ea7e27b4bf68e3bfd32dc2a29dd259af96e38e39fe2e83ae7bd76d6a9a2463469d9dcdfd2b45d324b3492d353092840ed15c295ce79c03d8fd6bd1a5eec0f3f11efd45632350d1f53b4cdebda4aa92364ca837023af51e99af3dbf7d9ed538da9a2ce9de29d4f4c7431cbe72271b2e97cc4c6304153db06b3751adca49a67b0f8035ff0dc1f0b7c61afea9a4359eabaa32f87ac6f6c572114e1e76c7181b7838fa575e1e9fb47726acec8a1f0f7e0c69fe3bf1f68165a46b56f776c1fed5776f720c6c90c5f3b1c9e083b40fc6bd3c443963639e9eacf35f88e9aa6afe2ed635bd46da489f51bb7746dbf2950d85504760a057cc3a5ef5cf462ec72bf32c4d9cafae4547bcb46371ba373c3880f9d2b20393b101382381935ed60a3d59f3d99554a1ca88b5fb959354088b8081631c7de6aceb4f9eb0b054f921767510446d6d501182aa071cd7b7496c78b8d8f3cc7472bb3ae0967c7dd1dabba4ec78b2a45c8eedd232cebf3038284f7ac39ca8526588753062d8eac9df2a41fc38ae98b4250b4880c36f759240cb7e06b4b22de92237d3cc69fbb2c0a9c93eb5cb376d498cb9a691c6e9b612ddeb1232856c3176390b8c9af196b56e7d355f768242f88ae66b6bb51824aaf03ae2ab112656029a50b946cf5760eacf12b01d589e47e15e7f3b3d9e55ca7b27c1dbcb7b3f08fc46f15ccab1358e98ba5da48c0e3ceba60a71efb01aeaa1ef4ce5ab0ba3cbdcc62ebecc8a0c4a02ae476af4a71b48f1e69a6695a4d269abcaf991f623ebd2bae0cf2ab41553a0b0bc8ee210eaa1b190c076f6aee8cd23caa9074c6ddc051bcc552c09e40a1a229c93d199f732019525940ebc66b9a7a1d54d6a64dc3b648e08c74c75ac2ab3d383bc4dbf02d81d3eeafbc692a294d0a20b6bbb957bb932b1e4770a32c7e82bcfa90e63dcc24b961633fc4f34fa67852cac9cb7daf559bedd7522bfcde50cf94a463a31dcc7f0ae6c4691e43be3a193a7c2db6d6d5ff00e5affa4374fba3ee823d09cd1868f3cd44f37173b45b3aeb58c285279c0afa58c6d13e3272d4cef13398f4895b03059540271bb9e7f4ae3c4bb40edcbe37aa7245c14248078e33d40af9e933ecdab1e9dfb3f4515af8c752d6dcf1a36877d7c4ff0075bcbd8bff008f355d3d59b6c88be0de9ed7ff00143c196f24a554ea314f21c725532e41fcabe866ed44f31bf78e2fc477a755f12eb7a881b4de5fdc4f8ea06e918e6be6e5d59e8dfdd33828c0049001c8e7a8a23f119c746ceb56136fe1a5258051096240e7049edf8d7bc9da07cad45cd8a333c291196ea7391c600c572e13467a58f97b963b1745100cf27b57b899f169352655970c80e29dcda29d86a124820f4aa4c57b126df39f19e074c51b3d469334ade2db100bf5ce2b496888abccf4671b24c2ff00c58de610886e091939e07d7b715e14ddea9f5305ecf09cc45e2e9bccd4228f780047963ea777ff005ab3c5493d0bca16f3664c5182cac18e3ae4f7af26def1f4f2dcf51d5c0b4fd9e7c0b10450d7daeea778c3b1d8ab1ae7f3af47050bd439b113b220d1a3fb2fc10f89176eb87ba9f4cb01e9ccacecb8fa2839ad71e89c3b3cdae26091b33fdd1e9c935e425a1df37a196926e99463077642d115ef18c9da99d169713c978b291f33b00a41e83e95ef518687ca62aa68cee34db7647dafc16ea6bd984743e52b4ee69ee112e1c8c027a0ab5a339a0aeca8f2f999c0e053ea7435fbcb9c8f8fae4a695b071be4500f6c75fcebc4cc25a1f4995afdf5cf29bd0af2b381839c100f5e7d2be4ea3ba3f41a4b53534b502cb904124e3d323ad7a5417eecf2713fc4308b7fc4c188073bb200e95e6ffcbc3d04bdd36a17df26300281ce2bbd3bb3ce9abb0c4772648c300dc2b0cf63ff00eaac9eb2e4358fba8c79018e529b48c1e7e95e5565cb3e53be8fbc771a7dec3a6f80a38af23963bf92fbed365b97e4680aed9369ee7705feb5e861656f88da6b41de2bb3b6d7b40b2d7b4b8becc96fb6cefad15b22395b3b665f457031f506b1c6c6f514a04d276672d6f6876932e36e40158f2dd1ace6747e18948b88d71b509daa48c1eb5e8e165cacf2b131e64759af69c65b4de838847dee09c1ebfae2bdeaeae8f9ea12f675b9587842f02493c4e4e1b0517d303b7b9a8c349c599e6f49c97340ed20b80540002903b9c57bb19a67c9f2b9c7545d8e48760df32ab7719a6da3585376d0fa9a04450368afe4a93bb3fad917a1c88f9e7d2b0a8b406c9638925c12783d4835c7261b132001b039009c63ad11602e4bb7cc0a8159c99a5c64b186208cd5c187315e4894eeda4d697b4897ef152488b2943ca9eb8a98554a625168cebf884a00ea338e95b7b4bcecd8ed17f16e626ada1457d1bac9189015c10cb90463a62b68d5b4ad1309536f7574797eb7f0a2ced58dce9023b0986731c0a150fd54715f5582cdabe164a49e87cd63b26862e2eeaccf1ef18699a859ea04dfd918936e12653f237ad7db51cf6962e6a3b33e3de4f2c0cbba390b843e6f3f30cf451fcebd855537a133858ee34eb56b3f81d71203e58d63c47e5042305e3b7872791db7374af4706b9aa18cd9ccf8b636d3be1ff862d94ef5bdb9bcd418a1e0fccb128fa8d87f3acf1aff00785c227236885a57b84538862df8ebf36401fcff009573c34615a7645ab990da68b21707cc9065b9e5b3fe15d355da2791fc4a88e3b70c1200c13d2bc293bb3e81ae4a68bda6c1b712152c0e4815db423cb1b9c551ea4b6caf777d9209cb16383d054c17b599b47489b9a8dc79165b149dadf29ddc8fc6bd2a93e48d8f3e9c39e7721d2fc477da4a84b6bb955064b2ee254f18e87b7b5797ed35b9ee72da363a15f1d697716d2a6a5e1cb49a6032b736a5a3727a0070c01fc7229baaa44b47b3fc48f0b786ac741f06f83743d6e2b7d434bb1fb65fdb5ee5035ddc61994b1e385da01af6b047157460e9fe10d6fc0df0dbc67e269ec6449af36f87ec6eeca6122e58879d815ecaa073d39abc6d5b6885462799693e35d4f48805a437af359e066094874c7a61b381f4c5790a57d4eb9686bc9e26d03588d62d57c3f15b639fb55831590800f55fbbfa5546519cac69cd64747a7781b4e9f45173a66b368f26df356d2f1b64b9ea0703938c57bb0a7c91ba3e27152756bf29c845e0fd663d444b73a7cee1584ac510b8503bf1ce3debcd853e6a973db7fbba65f91dd4e5c00149e9d8fa1af769ab33e71cb9e6ee48aac8a8c9c16cfd4d695198357150b4c80b90be993d6b9d2b9ac6239dbcb7f2c15271ce4f3f5a69b39adef90291e6807767381cf20d68e6c6e1ef11eb3a8cd630cc51c8c21c67d715cf5e76a7734c3d04eba4647872e9cb19640bd970a71cd79f8777f78f6b1f1e48a899fab6aab3dfcccaa0056db86e959d5a89b3ab094daa6451c96d311bf696c6e3ce31ed5ccda677a8bb1ea90c50699f00f43b42cd13ebbe20b9be940e4347022a2727b6e26bd1c146f50e3c44b951c9def87ae934c9b5882279ec2de68ada799067ca3229284fb1da466bd2c57bb238e9c3da97744786ee016f3677119538c86fc6b3a73b9e3e2f0f2c3b22937e8d74c704c03eee0614fb5762918d3a4abad4d8b2d5adb548008c8c7465cfdd3ef5a46a5f43c6af8595095c59ed617051d4301d33deadae6142a38a312fededadf7b90576f38072315c55958f530d27346ff88b4d6b6bdd1fc0892aac3683edda9c8abb733c8a189e79fddc640e7b935c539d91f5387a7ee1c0f88b595f13789e592df31dab32c16b1b1c0489405418edc0e7f135e673fb493676cb446ae804cf7773761176802dd4151c05e323f1ef5ebe062ace67cd66136b43a3846031ce2bdc7f09f2f2dcc5f17ee7d36318caa480f4ef5e6e255e07ad967f10e4d30d919fd79af9c91f67267a57c33074bf875f137520c544b636ba62c8dc7cd24db99549e995535d34637139685ff834522f17dddfc8e62874fd2afae8b28e576c2ca0fe6c315ec621da99e6c55e47965bab88622e72cca189e9c9e6bc29fc27aad7ba4c623b770f4f973d2aa2bde317a366d6ab74b1e891ee53f300001d8f5cd7a5295a27ce417362594bc337c6d2332988b190e738c77c119a30fa1d18d57d0e9175f0c4e632abe99c9af4948f0fd82dcad2eab0f99c31c0c9e477f4a9f686b0a3a6a3d35284ee21c020743deb58ccca543521b8f165b69f284f2de67603e4419392718fad72cebda563a2965f52b479cd9bcf11c1a7db8958372405551c938f7ae9ab5ad0308e0e75a7c8723a7ea491eb4b7721c11b8ed0323915e2c6779dcfa6a9867ec3d99a6fe1ebff001c5f6a375a3dab5cc3a6d8fdaae72ca85210df337279f98e31d79a555f34ec5e0b0ee94394cdb3d17509eea0b582ca7b8b9959522822525dc93f2aa8ee4d693a3cba9d8e7767a0f8f2f12d7c01f0ff004124c57da5d9ddcd7568c46f86692e0ee471fc2c02af079c556165cb509acae8af737b0c5fb3b5e428eab7779e28b7dd06e058c71c2e7711d7193f4e2b3c64eec5459e517b3792111d76190e1783cd7125747749e845a7ee6be3210498d485cf1b89e2ae31b48e7a92fddbb1dce836aa157e4fbabc1febef5f43457ba7c5e29dee755031daa49e715e8465a1f3d510f96725724702a6fa8e9a2b4b2346a70073d08a39b53a5aea71fe2b865d41e1812332312ce557ef00aa4923e9d7f035e1e61b1f4b952d6e796cb29755663b8919dcb5f2927a1f754dea6bd8b243a6924f524926bd5a52b533cdaf1bd439e89f171bbbe49073debcb4fdfb9df6f74d6b69372151904f3915d945de67135a8b711359cab3a038ef9f5aaabee54e6145f32b05da80cb2b0e0a8638e95c35d733e73d1c3ae53a8f09eb07c4ba549e16bf559558c971a5ce07ef2de70a58ae7ba3052083c6706b3c3d4f7bde369ec5ef8756cba95af88f4a21cbea1a44d2c3183c09a0c48b9f7f95857a95a1cd479a271c5d99c9dbafda444ee708c0301eb915c74d5d15366a098ab0280fcbc6475ade1eeb3296a8ed64d5ad9f4a8a4b970be6a8528a7e607e9d457beaaa923e7aa619caa7322164b4d2eca79ac519af970eb3331278e785e9d2945d8db9e2df24ca1e1dd6efb50d6173e6ccaea518019009208e077ff102a28e21b766556c152952bc11e909e0ad5675de53cbddc859250a40fa57a5ed2e7cc3a128bb1f55452139c71f4afe5992b33fa9132fc17218124118ebef58d4d84d17c31700e7240c0ae06ec5922e50860075e6b3b92c7c6c1b249e4feb59c9ea58c72de667036f607238ad6fa19b2bcef8dc42e08e80d5a77436c8d54c9116006f23a564b71c59565b40855b7e477e3a9a1b1b6665e29f34051df0735d1076358197a8594b82d85099e38eb5d2aa19ce299c9788341b6bd8ca4d02b46e30db864d7553a8d3ba38aae1d48f14f187c3192c1a5b9d330ea09636cd9071df04d7d4e0b35953f7647ce63301caaf133b5195ee7e0e7872148da2fb26a57e8eb20208918ab0603be57bd7e9794d78d55cd067c76229ca0f54607c5a57b41e13d250954b0d060de0a81996563231fa92456989a8db08bb58e36c14dbd9deb37dd2807b9f981e3f2a9a5a9c78977a83bc4a9e5e99852771030738adf12ad038e8abd4b9c64484baa1c0f515f3d4d3e73e8aa3f711b222f26d700fcec38fa57b1f66c79abdf996343b6725e420ecc10a48c735586859dc556a72cac1aadc2bdcf9646028c75ea6b3c44aecd282b32a8200c0f4fceb8af73d2933adf853a043e23f1e69505e954d3e166bdba7738458621bdb71ed9c01f8d5457332652485f146bd37897c45ab6af29777bbb992441d4842708303d1428af729ae589cb2d4edfe2178cb58f87f67e15f05d96a535a1d1ad0dd5f420feedeee71b989cf52170b5e4d5acdb3682b1c8b78ee0d5dc3eada5c329e8d35bfc8e57d3818ad2157409a2ce9da4685e24b9856c3566b169dbfd45ea128801ea5d40fcb15ad1509cf431ab2e48b357c55e19bfd1ac89f216ea27e124b5264461eb80370fc457b75aea1647ce50873577221f02eab7fa0b49751cf246656da559895c2f046d3d3d2b1c3268e9c74efb1d95cf8b74dd6ae234d6347866182ad35b0f2dc8fa8effa57b1191f3d7b01f0b6837b00feced6459bb0f92db523c6ecf3f301fe352e0670a841a9fc2ff10596cb85b237d6e07125a30751f97359b81ac66d1cb5daf957223951e3940398e45646cfd080715928b33babdc800685558316c0e3d4d534d1d4a29a32bc4b204b145207cec71c924818e7f5af3f14d58f47054f9a7722d31059e9eb2336f53b998018dbf522a2168d334c6fbd3491ccc926652ec480cc7201af2252d4f7a8c6d111f1180e4ed4da5883daa798d796e7a478f15f46d13c07a407685ad7424b9688b646e9e4672df52315e9e066d49b387114ae6d69de286d2ff67cf1344fe5b3ea9e22b2b55f31b04a448cec3f5fd6ab1988bcd21e1e958f3e8ee5a4662931870d95653c0fa5651abca4d5a11a937cc765a241a66b965e5df5cbbcaaa37302719f5af5f0d3e73e4f16aae1a4dd25a19fa75d59687e2036790cb3304185c64804827b50a76a9614e353174798e98ded932b00031c6700f4f6aef53b33c154ea7c25af07dae96d7b7fe22d5adfcdd234155bb9a152019e5cfee6119ea5980e3d01af3eb54bb3dec15292671badebf7571a56a9e25beb909af7882671182a098e30c0bb29c721bee03e80d79f5ea5d58fad82691c6d8c0d8b9bd0540810b027b16e33fad7361a366cceb4b99e874fe18596d74e8a45008909627d4d7bd848d91f298f92750d786fc96fde20073c115e9731e5ba6b732bc5f7b11b041b883bc71fcebccc54ec7af94d2b54e63998ee63c462365deddfdfd2bc072f78faae5d4f55b346d3bf679b72046d2ebbe25666ce72d15bc58e9eccf5dd84d6a19555644fe042346f017c4fd6480193498b4e87763979a60303f006bbf1b2f76c70d28fbc8f2f95c4615065d8280598719ef5e57d8b1eb25eea0f30cb3409b769670a093c649c015495ec72d4f762cd1f13b9135bda1c868c76ee7a7f4aebacf5513c1c346cdc8d086dcc115aa31f996150460e4f535d90467525cc4e6da2c1c0600e4f23debb63b1c8dd8cfbdb72cedb033e4823e5e9f5ae4943534a6ca8b6ae49c82368a6f446f268cbbd8cb6b36992555658d98838380dd2bcf9afdf23dac2cbf70cd9f179f361b670cd87660a187618c7f3abc5bb2470e075aece7239d958170720f615e6c25a9eecd1ebdf08b50367e0af8ab22b6c76d0a0855c2e7e56b801bfa55d17cd5489688bff000367b697e2af846297387d522621c6efbb961faad7d262b4a4cf320ef238bf18eb2755f19f892f182a4b73aa5d4cebcf5699b9fcabe7694f63d5947dd3226b84704005bd086e0d39cf98e38ab48daf0bdc075b9475465da062450c393eff004af43070e63971f539626378adbecf7a560448d5141c2ae393efdea712b9264e127cd499d3e892ada5a41e7a1f30a8624f5391907fad7a545de07ce62237a8cd58af62f98019ef5da99e4ce9b64335dc6ff2a93bb2481eb8e69f3174e9b457935288c790e1481c66b0e73b153e68d8c79d923bf8753de09b58e6cc78fbe5919467b60139af2b16b98f7f02fd9c6c79b5d69c963a5c12ee12bc8a72402bb0e7fc39af01c3961a9f4d4aade4ca715d91a53f9a58e0e178ebf5a70972c4b9abcd1972ae029c6326bce9c8e88ee5dd289323fcfbb18181eb5d386776675968691ccf0146620b1e3d2bd09c6fa9c11f88ababdbcf64b042f83b9412c3bfb57975d743d783b224f0c5fb695afe9f77b847e44e8c491c15270c3e84135c7497bc6d3d8f45b0bc3e02f8a1717291b0b5b4d419044718303377f5055b35f53495e163cd9e8c862f86d7d37c40d47c2da7149a48ee5d6d1ae1bcb578f05d4938eeb5cd0a5c9063bdd9c99d43ecf0930008c78ce33b483838ae5e7e8747b3ba0b182f752b97640f312bf333e718f527b55fb469931a7cccf4ef0f5ce8d696903decab7d3fdd92189b3b31c0dde9c7bd7b142a4648f071145c2a731ccdff008beff47bc9868c91e95196dc8c8bb99476033fccd7975a7c933d6c3fef2163663d66f352892eae6e5ee5e550c1cccca71f41c75cd7ab4eade28f26ad1f7d9f67c13b138c015fce35558fdf52366db94404000f5ae39836685b6d28e7aed3c1ae29a2d120901c80395e49cd62912c07cabc9e49e334a71b081d831049e838a96ec8ce4424124e7a1f5ad60f42ad7238ccbbce4a803d7d2a59a244733f9d80002abe95ce98a48a32c45db1827d6ba62ca8b29cf6e40284eec720d52761b4ee635c419570cbed835d31904d6872baa58ac81815183d6bbe9cd47e2399d3b7c4797f8ebc1c6f51e5b6660eaaca22dd846cf5e3a027d6beaf2fcc6586f859e0637051abaa4798fc4568a5d434e12ce8da82e996d0cf69192cb6cc8bb02e7b920027ad7d9d3c6aaed1f1f5a972339c82061a2f9483779d32e77755c7ff00aff1af6e0b4563c2af2fde953c577e90580b525649988f9bfba33538ba9a58bc3c75b9cbda212cacc858773ed5e7528df53beacecac5ebbb8224450090002b5d717ad8e7a7a3b9d1d9c82db4e2486f2d10b727f9577c128a386a3e6a862bac574fb8820918af3a5ef5cf4a1a111b53103d719e33584237674731dff84c0f0d7c34f126ae47977dacc9fd8d66cea7e58d4079d97b1c8dab5d98785d915242fc2bd16dfc43e34b34b82c96162af7f76f8e162806e6ebd41c015e8d6f72267077390f11788a6f13f88753d5e71992fa77b8218740c7e51edc62be7deacec4ccb50718c90335ae961499b9e1d8da4f3251f32fdd03760fbf4e9d2bba85351d51e6e2a578966f7c51a9e997fb74ed427816301711b707db9adabd49bd111429a8c398f41d13c7490e9eb16ada4c1a9961b8c870b27b8cd7a3426923c5aef999a289e12d56432c57177a4dc383b6394978d49e8093ff00d7aef84936704a364597f07ea3b15f4f8d35581b3896d255618efc6735d4d9e7425ef1423bad53c3d7c1e37bcd3ae50606d2d1903e9d0d60d9d551d91ae3c7f7d7077eab6b6dada1e08bc881723d3701902b54d1c8d3b952697c297c54c96173a62b0ff960e6508deb86e71ed9a9763d1a7768e23c4fe1b8b51d4160b1d456e9380aae9b194e78079c67bd78789836ec7d1e0d72c1c893c47e17d5743d1183da3140047e62a9233e9c6739a7569b8d13928fefb116670be43c6e4ba32e07f12e0feb5e1c933e9d2e543adf4e9357bab4b088169eee68ede318c9cb305feb592dc70d59e8ff001ba786f3e25eb305b9315b69de4e9cab91b71046a9f2fa0c83c57d06122acd9cf55dccef1ac0747f847e05b5036b6a3777baa10d90cea08446fa6335e3625deaa2e8bb1c15bea2ea8a833820e4fbd6129b346a326db3a1f09eacf16a6a0a9742a5586703a75cd7b182ab63c7cc68f2d16e2375bd444be21423071b49dbc71fd6b694ed54f3b01070c3bb9a1a85e8b7865677da31c95e47d78aef9cec8e0a745caa33a5d6b4bb8b4d33c3de13400dddec6babea1b06492ca4c4ade812305beaf9af3f9ae7d0d0a4a279f78ab5a1abea68b0064b18234b7b5848fbb1a8c2f1fde392c7d49af3aac9b67a764912dcca74ed32ded502fef984922919391c01cd752f7523ce51e6933ae81c4682201556355c11c60e327f9d7bd474b1f238b8f34d8e6784a707241c123b1ae938b95f29caf8c6e16492348f208e091d0fff005ebc6c633ea32ca368f31cfdb5a3b156573ce49ed8c77cd792d599f40a27ab78d3ed7a2fc3df86ba2ed1198b4d97537c0dd97b8998a938ee5547e95e8e07e33931084d43563a4fc0850cc1a4d775f50cb8fe0b788e73edb98518ca97a96229535cc8f3c3aa22a6189618ed5c6a5ad8f4793dd45ed06e52ef588154132460cdb48e303b1aeaa3ef4d23c6c6fbb06d162e71a9789628f19f3265400fa9ff00eb9adeaabd748f3a846d41c8e96464975090a8ca86daaea7b0e82bd48a3c59cb949df257279c9ee6ba91cee5cc5592132b01e6089570318e6b36aecd29cc48adc48cc0282c7e5e7a529c74377239ad457cbf11c2870c1248d5801fed03f9d7913fe323ddc33b5065df17ca8eb6c2242a159b39ef9c63f955e2d688e4cbdfef99cfc615f39c8704119e95e2ad19f48f53d5fe18a88be1afc589c90fb74eb2b756f40f3e4e3df2a2bb708af50e5ada22f7c0b8e29fe3078376a74d4159b27b05639afa3c6e9459c14a3ef1e69acb49fdb3aa4aed9796f6720839e3cd6c7e95f254fa1eb54768949331900123be6ada3992bb3a6f0c2130dd392300ae7ebcd7bd8167899a688cbf131326b32ecfb8234207bede7ebcd73e29f34cd301fc23a659161b68571ff2cd4051cff0d7a14572c4f22bff001195259d980c03bc80c303ad6dcc652a6559ee64391d18e0f4e952e411822abcc771273c8239359753a63149d8a7a8dcb5b58c92e4800124e7dab8b10ceec3ae69d91c7eb73b0d3ad6220a9c16e0f526bc4c43b44fa0a11f7d9932487ec3b49ce4e466b839bdc3bdaf790d9d4ada8047cc40c1cd675636572a3b8fd2885f30b12091c7b9abc2bb0aaad0d58d5e64454192c40001ea6bd883e6479cbe221b89e5d4629964def28202a9e381e9f9579f555d9dca564528c964da06063079e4571a56674a95cf41f11dfc57f0687abdc112bcf66b1dca29f98bc4c54647ab2aa9af6a9d4b239a71373c5baa6ada1fc4b4d5eceea49f0f05de9f3eee66836ab22e4751c9527d8d75425cfa12d5987c5ff05d9f86bc4569adc6890e8fe24b35d52d6da07dfe5c8cc5668b2381b58311d3822bc6aab96a33b62f43cfee35c936182dd3ecf6c3aaa1f988f73594aadd6834f9516bc3539fb618d559b764ef2703e9ef5d78294a4cf3f1914e172ff886cb3e433121b24371d471826ba3174fde3930352d1b1a3a0f9d1e9ca9d812073daba21a4522aa3bc9b3ee3b6977b2803a75e0d7f3f547747ee56d0d886556654624291c915e74b7328ad4d1b7c6e660e70074ce335cd50d5b14ea2b1390108e393eb52968095c1eec94f34a75e3f0a1ab94d0e599665054e171deb071307122966da142f2ddc11568d23b15ee25677003600e703b0aa6871239188e8400791b4d66a227b8c12956f5ed5a2561c46cdf37238a9b9a48ccba84ed625863a019e6ab988306eed32e4b01b40ae984c993b9caeaf680a4840e40e00eb5e9509ea734a373c7be25f83d2fa26bfb65ff0048854608e0b0f43eb5f6182abcad1e0e3b089c5d8f39b4056cc00c014dcc41ea08afbdc3d4e689f9ce2a8f24cf3bbdb892ea7777258963907a7d2b9aabe6676538d91aba64040046dc0e48eb5d14e364615fded0b7636eb737df3aee01be5c761daba28c39999cdfb389a9acc4896ca8a4ab312063a103935bd75caac7250f7e5739e30caaeaa7953c822bcc6ac7aed134735c4aa56353239e1401c93d87e7551048f5af893731e9369e1df0921568f44b15120eed71200f293ee0903f3af67094afa9cb55dd09a658c5a07c1fd77595631ddeb9749a3da36ec660037dc1518ce72aaa79e84d3c5db6161f46798c9a6955f94f18e95e4f2591d89950c46162ae1931d588e056718dd8a52b1d3d92b5a69c158aa228dcc4756c8af7230f6703c5af539a4a28c8b0845d6a4ad82c370661eb83c57253839ccf42a254e81d53208c10a32aa338f5af4a303e71c9364b146230cc4920e1b0792dc74aeb8ab0a69345cb3d46ef4d943d9dd4f632630a51b0003cfdde94f98e4e5d753b0d3fe25ead0c52457e2db54876f22ee2dd819e9b852e609c2e89a5f10786b56402f6c6e749998677d99f3100ec76e3bd74a9fb866a919d71a3e9d36e169aaa329190b70421c7ad73f35a3737a49b763cf3fb3af5f56f36285ae155cb3b423780a3bf1d07bd792ed3a973ea69c79683459d4fc497d63e4082ea782504b30dc7e83834ebcdc7439b094ed2b8b6fe3996e22297d676b7c010599e10acc07baf4fd2bcd755b3d997c27a07c0e83c29e2af8b9e1f17ba64f616b66cfaacef6f365516042d800e4f5009f5e95297333486903135cd23c2de25b9d42f6d7c46f1ea1793c932db5e5ab0c991f3c328e73bbd735f494e92a54aece094af2377e3e7c32bab0f17e9de1fd3b57b6d5e0d0748b6b40432c4b0c8543c8bb73d72c33939cd7cc54829d4723ae2b43c8b50f06eafa59092d9b846ced74c3063df1b49e6b3f65646ad5893c37a55d453ca5ed656655f9c6c63819ea703815e860e1cacf371f52f4f94a934325c788246789e3dbf30e3e5207bd6b04dd6671d35ece8d8e8fc13e15baf88df10bc3be16b70864d52f1212257d88230773b13dbe50d5d15aed118585ddcdcf175f4e27f1778a192485751ba9746d2d95b6ed8506d665c750a8aa991fdeae39cd72d91ec53563ccac71f69899c65548247a815c149b522e6f4346d249353d5c4b2952a841da09038e8057a305cecf3e52e44ee74ca4cb304dc517be7d6bd9a6ac8f98946ed95e77108619185fe2ef9f4aae7e5467185e48e32fe77bad424727a9c0e78af031152f23ecf0b0e58225b79260e90a01be5611a1cf192718fd6b8dd4bc4ec713d7fe35eb718f88f7f6090ec8b49b5b4d363c1f942c70aee0076f9998d7bb83d2173cfc42b991f12aee2b3f0afc39d21d15162d2a7d4580393ba799b048ee76a8e6bccad52f599a50563cf4792f8042a81f98fc6a533ae7a9b1e1b55b6bab89d86e8d63017240e7d6bbb0ffcc7838f6a31501fe1dbb49bc42b34e182ab33164eb9c707f3c56d0f7aa5cbab054b0e99bd0ce923b12e230793bb824fa0af5a3a1f295a3ccee8b6b791b0288c1dc7f0915a7358c1c1d8867942b83bc15ebc718a39874d3521f04e03ef003638c67bd4399ac9373305f373e2d2f0f122ccac707018819ea7b5795cd7aa7d128f2e1c97c5fc9b350dfbccb33ede87a63f2e6af147165b1f7e4ce7d1d8305c0c75fc6bc33e9d1eb3e01411fc0ef899382c8d24fa5da918ec66663f8d7a78376ac8e7af0ba36bf67fb7127c69f0c16556114f2ccd8c7caa21725bea00fad7bf8f95e99e7524f98f1fb922ee49e5c615e69194f7237b60fe55f3115a1e84d6830825942f7e991d2aa2b4212b24747e148e3104c64258b3039edc57af823e7b397aa333c492236ad2f960646d013bf419ae7afad536c12b61d9bf3878e28ca908db471d7b74af5a92b1e449fbecae434a923c9bb2303af3d6ae40de84138f954ae413dbb1a948ce0f5217709c9c65863e61c1acae75c1e863788989d29c64b866083079c93fcb8af3714cf4b0517cd7392f13dc17b848c7ca0283803b57858995d58fa1c2ad0c9995da18c0e80f5cd71497b963b16e4d3a6df94ee6014753c5692d69d896f52bd9ccb03b039e9d4561426a3a1725746f452ed86120856525b8e49cd7bd4f63cc6b5230c7eda25d995270ca6b09ad4b7b13d8697f68374e76858c16da782c7b0159fb22e33ba1a37cf2c6b93cf033dab097ba7a3a4a99dfcbafdabfc31b3b2962dface93a90820b9009df6722162bbbfd975cfb6eaedc34f4e521c7431fc5f36a16fe0af0cc1767ed3673f9f796b2a9cf97ba42ac84fd57763dc572625372b053766714250bc679cf04d71c5686b236b43bb92d674209da4807db35e9613dd91c188574761addba5c69ea0b8dd182d938248ee2bdcaf1e68dd1e0519b84acce6e2bff002902a9dc3d735e2b959d8f6d6aae7df56ed2b004b1c0ebef5f82367ee09686ac3148cbbc02ca39e9d2b926ccad665ab5cb1246381d0d734b7092b033ab3b145da7a104e7355d020c557668b69a13b9571d6e0c5163048f5ace42dc8eea425b318c903382684816c4175764ee442769e79aa625b9143380833d077a51131d9cbe49c9c553761c47eece0e7a54b40d956e14b0720671deb2608c5b95cab82726ba608939dd46dcaa103e7dd5df411271fe20b3261c2a610fdee2bdaa555c5ab1cd563cf1678578eadc6997b730c0842cf13386db850c07ddfc6bef72faee513f3cccf0f691e52d6ecd2ec04657190a7bf7cd7a897333822fdd374a882d7059464638ea6bd0d91c8bde91a5e1d818c658296666c2af703d3debb282b2b9c58c9744335796492e82f2a1090456359f332b06ada9046aa5f232000076c135cd289e8ca563b8f843e1cb3d6fc7fa73df32a69da6eed4ef599b004510ddf37b16da2b5a74ee67ed2c667892e2efc4bad5eeaa114dddfdcb48b0c63032ec42a8cfd56bda843d9c2e79ee7cd2b1d37c75b05f0bea5e1ff09583c9e4f8774f48ee083906f651be7623d70cab9af0eb49ce677c5595cf345bb93695201c13d7a9acdcb4348b2f68f32de5dc2922928580624671cd6d420a4ce5ad3b1d1f8905a8854028a65cf3dc8e38cf6af52b3d2c78d42f52adca5e1dd1164b79258bef312a377538ebd7b53c342da978fc535ee1acda74caf875e7a9dbdfdabd18c4f0954d4898908f9553e800c63dbeb44b43a9d5d074513bb2195b79c642fa63b66a1c48e6d49639124902e08dc76edcf159b46edab0b70c6164c600031eb9f7aada25c6ce2636b331fb14b3118c2e33d41cd72e225cb037c2439a462f87354ba8a5966b7b8922214a8c371cfa8af228dd4aecfa3a9eec2c3b53f13ddde5da477016e7cb5196740b9fc854d6ad79d99542368dc9edf53d32f63c4d0bc0cb9f9a151cfe15c9ed533a9af70f4df83d61a68d0be226b69adc3662c345fb2c2b709b1a49676da554fae01e072735d549733b94be02c7c2ef85377e2cf1cf8674bb4b8b7bc5b9bf878132a9f2d583371dbe55ef5f458876c39e7463ef1cc7c5fd3b55d57e2678a75a9eca7314faa4e165119da555f6af3d0e028e95f3315eedcf42d6471b16a9776a1c4734a87952a58f1ed43916fe2b1bbe1ff11ea6b697b31bb9019891b4301b8018dbf4e2bd3a1a2b9f3d8c77a9ca65787f59bb925ba2645f2c2ed60547cd9248fe54519a751b2ebe9048ef7e1ccda868fe1cf14f8dade0909b341a369f3c2a378beb80541538fe140c4e39191553a89dcdf0d1e485ce4fc7fa8be8efa4786c48b345a15a9866041c1ba76df3b67ae7242ffc04d78b525cacf4a28e5d358d325889951a00300ba0ce4fbf7a51aca244a274de1bd02d6f6112e9fa84570f92db64c87ebf7476cfb57bf818a9ab9e0663374ed634df41bdb47632da4aeecb90ebca8e7d7d6bd650b33c594f63175576fb2cac982c54ee05b1fe4d79d5df2a674e1d294d1c8228120672c147538eb5f3736db3ece0b9608e9be19e94daff00c41f0d69ea9e69b9d4601b7d02b0663ff7ca9ac0d56a8bde32d50f883c5fafdee771bed467753df6b485541f7c6057d1d27cb48f3e5ef4ac5af8e6517e245e69f1b1f2749b4b5d310765f2e252c07fc098feb5f3f395eb33b230b1c0331db8cff8d6bcfa0da3acd127834ff0f493b80ccccc0b30cf18c003f1af628e94798f9fc6c1cea224f0ac0934175304531aa00c18f2093faf35ae0fde6d8f306d525146bdb088b11e9ce33c63dabd44cf9c8ae645e8f4e49583060703208eb9f4a1b125a94ee2cde5988465c0ecd4d8934a6431c4e91bb364673d3a9ac5b364d399cfe95e6b7893702cca19be63ce715e645fef4f76ae94109e28bbb84ba8a36214853b50800e09f5ef5a62e56232c826db3256f9900f900e6bc8b9ef72d99ec1e0ebd4b6f801e3772a3177aee9b0861fc442b31c57561e4d5644555a1b9fb3d6ae20f8971de04f96cb4dbfb97cb74516ec323f1615ece3aa7eecf369c3de3c634fd4e336707980872bb8e3904939feb5e042a7ba77ca1a120bc896453bd771396c9e00fc6ba13d0c5ad8eabc35e5bd848e1c659c8f94f4af530723e733785da30b57931af4ab110df3aa9f523ffac2b9672bd63bb0f4ed863ad96454c646d1db3ec2bdc83d0f9c6bdf65572b2b100156c8e01e2aa4ccec566762ee186e50a463fafbd4a65d38942e9d0b007713c67d07b572b7a9d90858c0d69d9dedd012aa645239f40720d7998967b5868f2a394d5e54fb7c8b82540c039cd7ced69fbf63dbc3ad0a2ed892203a1c1a972d5236b6a592a25676e42fa1ae98abe860dea67b90b39c70b9f5af2272e5ab6474ad51af6b38da01c2827a9afa5a33ba38ea46c6ed8e93f6b21dd8a42a4365472dcf415d2a9f33b9c5527645f86311eb4a92110dac8a339e40f6c9e9e95a5b42294af0b983a95ca41732c3126155ce1fae4678af1b13a33d6a126e363a8f09bc7e29f0b5c6810109acda492dfdb9206cb942a0c91e7b300bb81f404546167af29d4f5459f0644dafe9bad6812306b692ce5beb6988c98268977e548ecca18115ead7a57858c63b9c544b190ae4062c01191d335e3c56b634996609647907968705949f97a015db1f74e79aba3d060d2e29f4b477dcde7215f94018ed924d7d1d35ed299f275e6a9d43974b44b65f2f6fdde3bd7cf5585a6cf7a9554e099f785a33337aff00c0b8fcabf0599fba265f8ee9d0285936919032715c522596190347bca1638e0ab631f5ac521c98f8e704282320f248ea293466804c229090ec50f3ef447413dc7adc839209c76cd1257357b114f7008049e9d474a707a0a3b15649496ce0f3fca8b5d99df5232e738cfca6b48ab01643855503391d7bd63345099264203e06738228b831db88c82700fbf152c94cc9bd0b8720019aeaa7a215cc3bc2136640c7715db4b40b1cbebaabe538dc1720e07ad76537a9cd23c5fe244260884a982406196e400457da65d3b1f259a42e78b5920b994c880677601cf06beb69abea7cbd47c94ec5bbf89d658c0c74230a722bb37d0c297c373a1b426d34d8cbed0506723ae7d6bd14f9627955973c8cc6903a9209673c8635e75ef23d1a4b9623a18dd704819033ed4ec6933d1fc2f1c5a1fc28d735472f0dd6bd751e956a01c33411fcf330f62768fc2bb282d4e3aa5ef843a4c5aa7c41d26e6fb234bd277ead78f81b52384171bbeadb47e35e95795a363829df98e2fc45aecfe2ad6b52d6a705a7beb996760cd9c066381f40303e95e125ef5cf657c2653c41d148c16e4734a4afa0a32b9abe19d0bfd74ea42bf0bcf735dd86a56d4f271d5f97428ebe97126a1e5272b1e154639dddff005a557deaa74e01274dcce834e91f4e4485cee2a809e3001eb5eac11e0e324a7366a417893eccf423249ea2ba158f3d459334505e6f500329e0e0f53438a642ba6431e8c14e431500636f514da34e7b6a43fd9d340770e76f7ef9a8b09d6be8546b5965c8900c138196c107d6b3e5d0eb551246078aa6315afd9cb00acd9c67d2bcec6cbdce53ddcbe2a4ee64691008add94e17792c181c71f5ae3a51e589e8e2257958cdb9666b877c8ce7d6bcbacef23be9af7065bcdb564ce0003391dfd6b28ad0df73bcd2261a77c2c8d532b26ada9195d49ce6385405fc0b31af4708aec968eabe0adc49a3f88b5bf1399de38f40d12eaf15d1b044c576263df2d915dd8d95a9f299538ea79b5878cf5cb08c28d4ee58b12ec246de3731cb1c1e0726bc2a73b2b1d2d13bf8eb54bb9237b936d3ba8c65edd7e6e7b8c62b6556e4b567734ee7c4b20d01d1f4fb570e092c576b649edc71f857b31a96a47ce38f3622e66697adc71695761ac20c3329594f2ca00e5457353a96b9d7898de48f4080be88be16d1647f2ad74ab66f136a506e3e5bced864565ecc1762f238dc69b76f91d518fba7926ada8df78935c984acb2dfdecaf2c92741b98966627d073f85785565cf268ed8e88d47d08689e1bb4b99add4dc5d80d1a484fc9193c1231f78f5c7a55af76958c1bbb17c196b3aead716f6f1c93008262b1839049c67f5af6b2b9d9b3c2cc95e099dfc57fa8d91c09e7caafcd048495c67d0d7d441a699f2efe2457bff001018ed2fbcdb0b2b9495428df18241fe2391d2bcdc44a363d7c0457b4399fed0d07505413e946d9cfcad241333f18f46e95e0c9c6e7d94124ceffe0278674bb8f88a350b2d5c42747d2750d480b95d850a42c172c0e3396158fb352a887295a2677c3ef853ac6bbe36f0b69c8905f35e5fdb9668a60430dc1dbf1c026bdd9c39691e745de472bf10ec754bdf1cf8a2fee6ca74371aa5c32b142430de42e31db0057cbf24a4cf5e0ec8e36743016571b587504608ab4aa44c9ef73a49d85a786d213c09541271ca93ce3dabdb6ed4ac786ff79587e828b0da3ef424c8d91206e0003d2b6c268858e5a58d169154e4a6d51d08ea4576a6783c962fdb5d08932b29d9d4e4629c1ea1ca46266cc85597bfdec569296a734e9dd91b5eb08d038328e403d323eb58549591d308193a15ec4dac972782c7383c919e9f8579f4e5fbd3daaf4dfd5d0be2dbcb792fa3233b9611900753b8f39fa71578d9a1e5b1b4198c27b765065db8cf7e2bcbbae63dc67aee82d6c3f672bef34a1f33c5a8a48e3212dd8a81f89aecc3fc66351e86b7c0a4b64d7bc5570af809e16d44ab6ee037963047be0f5f7af4730b7b247261f599e2705b42b616ca198b18d73ce7071d2be7e291df35ef0d92ccb1508ebbff0088b702ba9690308a48e9743d3ae46979040dcc7254819f7af4b0ead1b9f3d98d9d6473977e77f6bc88859b12ed2474621bad79f76eb1e9524961d9d74f34eabe5143f29c727381ef5f4316d23e65c17332a1b89628981639538e57f21553932b910c9b5090b72819800bf303cfbf159a9329534559af4e395cb1ce73ce2b92536765282b983a9dd62ed10004aa96e791e9935e65695cf669c34395bc90cb3b31393ea062be7aa3773d3a688d58c92283c6071574aecb9b2d88f746480493d703ad7a4a178985ee67dd00b310b91eb9f5af0abae491bc4d1d12d64d42f23894fca0e493d857af84bcd9cd59f244f41b0b610c2d8f962438047eb9f7afa44ac8f9e93e69195e22be173736f84db6e07cbf2f2c73ce7d79ae5ab23d0c3c7dc3035ab2305e641cac8a180f4fcabc7c4c6e8f5a87c275bf05b4c6b8f889a2abb32ffaf600719220738fc4f1ef9ae5a11e4923a3a1b3f08e5371e3bd2ac94e45eacd68b85c10d2c6ca063b609afa797bd411cf7b33814b37b5df14ea04d0b18990738218a91f98af069dd33776342da33f30dc41f5ada52306ae777e179ccda4c4001fb962307927273b4fa57d1e0e56a7a9f1f984796a58c3d513c8d4274dc9f78fde6c1fcabc4af3fde33d0a315c88fb0edaf7c920e4b03d48e6bf059c4fe814cdb8a786e22d87709072335cce2365fb49584980fbb8e41358d8963e6df6f93d32722b3921218d72181120193c022b3627b91f9a570013c7af7ab8aba29bd06cd27c9b89e3b8a94ac822f410ccd330c9e31c5117a91d4722891723a0ad2e5d899788f23fc9ac24c918ce36f079ef55143909e702083e98ad1c4cae50bb604100f155b334b58c5bb05932790b9c576c744436735abb024960081d8f7aeca68e6933c4be2fccd6fa4c8366df31880ddb1dabeaf01d0f9ccc763c974fb65860dca07ca324835f79457bb73e2f12efa0db084cb393b771cfae7033e95d34b591153f7748d7d4ee3fd1d532c72d8000e4e3ff00afc574621f2ad0e0c3ae77a99d1e4e57a639231cd70c5f53d16ac5a1746445d8acd211b55475663c01f9915699323d4fe2594d1db41f0b46a92ae83a7a47705460fda24c34a49f519031ed5ece1617d4e3a849a3ccde19f843e21d643882fb5eba4d1ad06d3bcc0b879c83d307001a9c54acec64a9d99e72ef96f63fdd5af3ac77ad88cb0dae40208e83d4f6145bde465f0c59d969519d3b4b4774f99577b03ea7b57bd08a8c0f96c4b752763134e717daa6f705943166fa75c66bcfa71e6a973d7753ead86b23a77b713c5b7b13bb1ee3a57b5086a7c9cab37790d5d354464a9c3b7403a553a762e1593445fd9b2249bf7e7d31c50a21ed15cb515f9b6e1c071d4f3ce2a1b2d46f12c25e4579298e1e54aee058edc8f4a49934a9733d4ad23c480a4a429ea33daa56aca8c1b91c1789eee2bcd55a20462350a0291cf7af1711ef54b1f5d8283a71b89776c2d2c995407c26d19e39ad671e5809cf9a672d2ab070bc827ad7ccd47799efc55a0226e0921ee149e075e2ad6c5a3d13c5d67fd929e1cd18a956b1d2e2790f6f325cbb7d382057b1818dd994e76342cee64f0efc18f125c00a24d7351874d8989c93146bbe4c7a7381f8d638e97bfca55367993b154c8e466bc96accda4c75b466e2e23453d5ba8ae8a51b995595a17367c4d7616d618064020614e3803ffaf5e9d46a34ec79141734ee3bc1fa50f116a9a7696cdb217669ae5fa85897e676f6f9548fc6b9a2b54bb9d72f799d278875cfed0d2bc41af10146bf7df67b70072b047f3303d8748c7e15559d9367443b1e5f0ffa46bd6d0c8eca923a44c7383b4900fe84d7cfb95ab58d2a688fa5be3ef816e57e23eb7616c8cd69a7dadb5cdb22a1cb8da33b4f4c281fa1aee8fbcac70c2576794f87dcc3e21b9b8827784a2edca13d7a8e9fd6bd9c04395b3ccc7eb13ab5f15df798e2e521bd880002c8986f7e41c1af7694ec99f3f282ba285df8ab4afeccb94bbd184824954068e528ca01e57deb871152163d5c041fb43989e5d06f5c98a69ec9f9c211b973ea6bc494e0d9f59cad33d1fe0ef82a69bc27f12357b49edb501168f169e8a18a36fb89946307fd9539ade8c79a68ce7f0973e09f8575ed27e2441a9c36d7112693617b7c769385f2a160a78e98622bd7c5ae5a670d25ef1e550f89bc436b6912cb7d765a41e631998b12589623e6f735f371e78b3d36ec2b788ef6f40827b64b83232a02d08672c48031f8fa575a9b974226fdd2e6bde1ed4ef6e6dac60b692498eec40082e4838202fd735db555a078d43e3b97bfb0eef4ab6862b9b6781d546e565c357561e36818e267cf2b0c923d8a14a16cf438ef5d2968798d93c7e580a8a833b81663df8e9550464e447f6752b300872cc31f5ff0a9ea10d4a93592a46641d57218838e9fd6b1ada23683d4c4d0a112ea9214381b8b64f5c74af2e8eb33e86aabe1d0cf13468b7e80924052095f43dab2c5bf789c047dc664a28640a491b79e3a8ae24fde3d6e53d32c15c7c13b656f30580f1248c5f3d243080a857be47cd9ed8af4f0df19cd591a1f0bfcdb57f15b46af33cbe19be8f7c3f760f9412cdec471f5aeac75dd222853b4cf31b7b478e18523756df855f9864e075f615e1453474d45690f916e624c805c06c0c73f87bd6ce765630706751a4dfcf6fa6088c6ca9b8b382082a4fafa74af7283b53b9f3b8da4dd64615bea25b56dae848697851d00cfa579ea4bdb1ea3a6e344e8e6d5a263c7cdebc74f4cd7bfce8f9a9c1a6c45bd8d8057218b37cc05129225264535cc419805cb8e3dc0f5a85246ca2ccbbc9216c6c7c0070063826b8aa491db4a1a987772c4b25c3b6d2a13682c3a139fd6bcca9247ad089ce4a8a57786c8271d6bc293573d682d096ccc40bb3eec8185c0e09cf7fc2bb6859985565c32f951850428c1ed5e8bf76272c1dd98b7473331ce73df1d6be6f11ef48ed89d4786adbcb818800bb60e7a1af7f2f828abb38316efa1d04b740b790ac7ca51d49c127be7b57aee57763c9e4b6a656a132b6a96a921ca85f9707dcd71d4dcefa5a40a5acb8b7d465001c125a327b0ed5c388d8eea32f74b3e03f10b689e31d0f553929677b14920c7de4dc030fc549ae0a6eed1d29e87737761ff000aff00e26dd2c4fb5f4dd4ccd0ed1b7e4dfbd47fdf2df91afa74ed411cb27a99bf12f4b8b41f899e24b14f9545f3cd18c61764877ae3dbe6c7e15e03a8a27472b663451e641160b31fe15c9635c1571491bc28391dbf8774ad51e1689224b48dd83175197381807dab9639dba71b1aac93eb32e691bb1fc37b2bb066ba91a6b8724bbbc85493f4ae0966fccee7d053c968c629347b9dbce54003d73c9af81a88fad46a5b48c9303b8e3d335cf25a14cd6b1b891dd9831460719e0822b092034e799e78c670580ed5ceca2a24c15be71c8ea07349210db89b2e4c67803818a1e82230e6540c07d6a5bb890e4f95f8c8c9cd4a42659f38a26e18c1e314ee53017195001e9daa1b04001933920003a7ad6f12a44123edce0d5b6668a72b82486247bd42d594cc8bd9000d80719eff00e15d9ba316ce4f597665240380464eec715ead25a1ccdea789fc5fb91730da46a7716948619ec0715f599742ecf9dcca6923cd6f008ad1542ec0c40f7afb67f01f16e5cf509b4585d09946d24e063d0575e1972c6e73e2a7ad88352971384461210d824d675a7762c3c6c47b811d4ab1e383c9ac6fa1d7519dd7c1dd0a1d6fc71653cf1e6c34c47d4ee989c2aac4372e4f4e5b6f1574d5e42e851d4f5c9f5ebfbad4ced92e6f66694ed5fbcccdc67ea48afa0a6f92279b3dced7e35795a2b785fc1d0b0c787f4f0d72233f2b5d4df3c873df68c2d78f567cd3b9bd376479a8254633f954deeee691dcb5a54666be890a672c323ae71cff004aeba51e795ce6c44fd9c19d36bd7661b158d7ef37cd9f403b57a75a5c91b1e0d28fb49dcabe16b68d639df68258654fb8eb586195b52f1d3bbb1b6e410a32476af5e2cf9b97c44880c6b93d0f4ab8ec0b61acc49e0f1e953d4cd3d47858cae1c6e0dc1a396e7429b488ee2d63620018181d3f4acdd32a18871653bbb6fddb481cb796a5be66ea00c9158cfdd89d11a979a8a383b6b296fefe3778f392642ea32063919fd0578d18394ee7da39aa5410fd62668884705492492ddbd2b4c43b2b1cb867ccee6235d18dc97e73dc7a57cecd59dcfa08bbab1b9e0bd32dbc47e34d0b4e64296f717b18980e498c1dcd91e9b41a96f99dbb9a2d11d0f8b3578bc41e32d6756386826b86308cf0b12fcaa3e9b40afa4c1ae58dcf3e6f53a5f8bba5c5e1ef097c3ef0d236e9974d6d66ef3d04b70c0a823d428f5af1b132e6aa74d35a1e433da3c45897dc0b71818fc2b1a8ae55f527d1a122e83f18504fa60d7750a69ab9c788a9687210eac64bfd42304855418cfb7735152d37633c2c7d9abb3b5f0240ba4f83bc5fae919ba9963d1b4f2a3396721e661f445c13fed8a30f1f692b9d4c67c45892c2db41d12288c2d61a7abcbb8f06598f98c48e9900aafe1535ad29f29ac1d8f3c9747ba9c99a200b463391c11ef5c12c3b97be8253f7accfa7a1fdae63f12fc3bb3d36e3c2525e78ceda15857546702d895f9448dfc47e5192bd3355454a6ec91cad4612bb6795d9c0fbe59659449732b99a6603019cf24fe7dabeaf0f479627cee22ba94ec48ecc8a1f8e0e7df35bc6166725fde39ef1138874e81010499371c9e33ea3debcac5ec7b3828fef6e730e0c841e7af402be7dad4fa893d6c7a4e905b4cf8252324ed14daaf8817237152c9045918f5019ebbb097e6b98cd686d7803c5babf877c2bf11359b7d4ee2dcc5a28d3a372fb817b89554aa83d32aa6baf1f56d1b18c237679faf8eb558e14b6778ae20550a0cd10638031c1af3e359f29d535ee92c5e26fb5cd14696304131995d6e61caba303c6d1c8033cf39ada8d4bc8e6a9a403c4fac4dfdb515d8f33ed4c5999bcc218b139dd918c73cd74d6779238284746ce8a4f19ebf77b1cdfb4cdb02959d431c63a7f857ab4f481e7576b98b561e37d5ad5bcbbbb2d3350859b949e13d3d3af1570671ce317b1722f13787efc30bdd0e7d359492ad64c2451efb4906b58cb5339d156d0b167a6787afac67b9b2f11c693a7cc2cef5763bfb0f43deb1e6d4145c23a9993693732db4e6dcc321da78465e0e3b8f53f4aceabd0ba0af2b98fe15f0cea445c5c3d8c9b485190cac413cedc03906bcba3f19ef621fee8c4f12db4d26af32795222c6c061d08e8066b9f14af235cbd7ba65c71046237fa64e318f6e6b8d688f57c8f566220fd9c3418f685377e2bb89813cee11c0a0107d89af5305b9cb5158bbf08e48ec748f88f772e5c41e13ba52319cb3ba2af3db935d18f9d8ba078ec6e62851190280a0578329b35944165292655ca90738071cfad55f4216e753a3cb249a190f233798cc58b313b9830da4e7d326bdfa0ff00747cf625fef4c1b39646d50c45b72ef24e7d466b823ad53d59ff0004df9add32ac8012305b278af68f96a855d8e87907006573dfd8556e35a886dcf98c49d8cd824673c566dd8d9229dcda80ac492403c60d734d5ce9a7a3312ee3124178496e08c1f6c74fd2bc9aafa1ec47632d7074e94163952acaa3a7279fc6bceb2503d04f426d37cb8a00eea4a96fcfdabd2c2a4a3738eab27bcb84d998e355ec07ad6b5a764614d6a5086c8ce1e760a000485cf0715e54a9f3bb9e844d1d32f84570ae00c01c81d0e2bbe8cec72d78dd9685f79b2601c6093d7ae7bd76caadce2a90b21ed0fda9589238fbac073915ab578094ad7441730b5ee96669325e3708ddf820e3f95724e1ee6a6d19dac568e20f0ba170bb86013dbdebceb6876ad51eb1f10447a9f892d75094884ea3a6595d33c8db42968954927b7dde4d7a0ab2a74b514697331de34d326f1b78ff0052bb83074fdb1430cfb726448d028c7b9c1af8fad8dbc99ef52c25ce8fc35e0cb0d2215709b989cb331cb74ea09e057813c4dd33dba385491d38b10190c5116e390304e7b923d2bcb75ae7b34695913b2213f7403dfeb4bda1d163b18252001918ae4a8811ab6d3b36011826b096c5334629ca1c8efc1ae593045b8f5028e393cf19f4a9e5b9a9319c99031cb16ea6a2285125ce230840209cee039a898a4471398b3181c1e7e958475250c6958c9b00cf35ad84c9165332ec0a54a9c126a1a0603264e3a8e0d2510458da235dc0027dcf4ab4514e525839dc483d286c0a13b875c31033e95b528dccd98f7b2128c01ce3bd77d38dd993671fac5d029b0ee00b15271e95eb52899491e23f14ef505edba3be40e814738cd7d8e5903e3736958e0350977cc987dca46429e306be99bd2c7ccd28ead9a9028b6b204900852c39eff5af461a533cea9efd5b18acc6494c8cd924f15e5c9dd9e9d3561f18f31d40209ce0f35af41cd5cf4ff0aa7fc239f0aef351caa4be22b9363010705ade2f9a4fc0b607e15d7855790a5b0ff84da05b6bdf1074c8ee42269da686d4ae8b9daab144a5b9ed8c802bd6acf96279af5671dafebd3f89bc43a9eb3205136a37325c95ce428663b4027d062bc386b16d9d128d8afced0739e3d2b45b1aec8dcf0d28679ee1d06e4c2ae4f006393f5af5b0aada9e0e3e7d0835dbc125eaaa6e08176b0ebce7a81de96225ccec2c146cae749a7402d2da188a04703907ae4f35dd421647938b9de64f2821b3d87a576c51e55fde0f3c4898efcf078a7b236e5d07c2c1c63047b1a98ee735b52e08810081f956899a269a2091c024124fd287230b6a66f88a65b5d25c919695820edc753fcab83172e5d0f5b054bda554ca1e1d8116da4b80981200a08ea40ae6a31bea7b18ead6b40c7d736bea2c1c7ca14600e79f7ae1c4bd6c77e0569730aeb4f490a9424364e4638c7f5af3e74d347b5096a75df092c069d7be21d7dc848345d32595242bc99e52224fa1f989a8852f7d33472d0a9e17f0d5cebbade8fa3c189e4bcba8edca1380c1986efa0da18d7d0b8fb3a7a1c2f59163e3978a878a3e2a788aeed7e4b08661656aa870a2185446bb47a7ca6be666af52e77d3d8f3eb8be9c9da09249ea7b566e5ef584f72d0b99b4fb6d8482ec73cf506bd257a6ac79d38fb499508b8854bc9b8b91bb91ebfe35cdcad3b9ba6afca8f619fc2f2dbea3e16f036e0ad6510bbd4981da44d30124b93fec46aa2bba852744d13bb380f176b32ebde26d52f4952b3cccca01caaae70a07b600ae0717ed6e74243f4b9520f0fddcec9bcb3eddc39c0c631f9d7a0a0a34353cdab53f7b646af8392086ce47e17cc7caa90304018fe649ae9c1d28a8731e5e632947546db450bcb8472063a81cd7b0a363e7949b9ea24f600c6e50e71d37719a250ea6b29da472fe2c475b2b10db70c598051823181cfd6bc1c61f4997caf2b9cc460993214ed1fc20578c95d9f449de47a778d36e97f0e3e1ae9d08f9dec2e753937750f34c5471e9b56bbf030b98d59d9146eee8691f058db86d936bbae8caff007a1b74e5be9b9c7e55963d6b615095d9c13464a15cfdde9c57272a48ec6ef12ef87edfccd46266ced8c97c7ae3b575e162ae706225681675194df6ba1490cbb95579ce3d6b49eb330a3fc36cde91029c02463193eb5ecc74823e76acef518e7601b82594f42684ac8ce2db63cc71954919770ce41527935317a9d125a10496f1baa796aac4f24b0edef4ada9cf7b915cd9c32a0f9373ae70e18819c7eb4aa2d0e8a6f90e7b4779adaee6559e4400920a3b0200e9c8e6bcba5a4cf6ab7f0cb1abea97d692a086f278cb2727793907d7d4715cf8897be6b817a1462d6f514409f68471f7899225624fd6b89bb23d5968cf59bef11dd5d7c0bf04896d6c192df57bf8a345b6d81b28a599b69e4e7e95e8e065a91597bb72df80f55dde07f89ea74c8442de1e512cab232b02675da00e9d7f415b63e6ae461d1e573ded8348a45b4f10c28c24818f4e4f35e5249a3a6456596d5e528b398cb8271246739f4e2925a18a5a9d269c10e98a8d2aed562411c57bd4b4a47cf6257ef4c3d32269756421958b31ddb4e7d6bcfa1ad53d19bfdc9d18f2ad5cab0c67e53bcf4afa091f3535718640dbb6ed38ced5cf7f6a92d46c31cc6d26d62a09419cf5071cfeb58cdd8d919527cc8cc082bd89f5acaf78dcde3b98175f235c867e18718e84f6af1a4af267ab0d8cc74920b2dc41d9212bb80cf23afd2bcfa89a47727a135ba136ebcf0bc819ebf857a345354ce4aaf521bb72dc1e31d85454d4208805e4b12ed0c40ce463d6b8fda726875226849917cc52030f7fe95ad27744cf565cb15f3ee5b3b973c3283d0d7552bca473565647430421106460639c9eff004f5af622bdc3c89cbde6578500b7d4a2c961b83003a10075fceb9eae903aba44cdb419640d179e4b01e50382dfece7b66be6ead5e489edd1a4e47adff62af8a3595d4a789a1458d122b22c59200aa1428279200e39af22be31f21f4186c1f36a7716960b02aa461b9ec3b715f312ab7933e82950b1b09624a120ae148fbe320d79d2a9a33b39794bd1d89e0bc4c118062e015cfbfbd65191d705a0e4b75da0f1cf3f76b6ba25ee6ac173b1426d3f3719f4a251bb318688d18a40b190f291d0640c62b9eaad069ea5eb69c04383b94e793e95c4cd09e26665271803bfa8ab8c4a2e453b18d413c28fd6b09e8c11620bd604a023007208cd437a0363a42ea9bc71e87daa22b41ad8af0b10fd4f07a9eb551216e592c6270e0939ebef50d5c0b0d2a6770e091cd52888aaf2b9e4938f4ed51246a995a499b0c8a490082547bd4a40d94aedc86c01dabae1a1848c2d4db30e416560783bb18af429333391d664dd0f2e379ee78c9af5e96ba18d4972bb9e17e3a7fb46b93b12aeb18da401d702bed72e8f2ab9f9f667539ea58e1544b3dd93f7496c8ef81fe35ed4173cae79ff00044bf7da8b450321099eed83c8f4aebad3e58d8e28439e5729acc922aed6c3753dab8632d0f47949112570444419090abb4752c7000f53922ae3ab0e53d33e23cf1daea1a56816e8a6db40b34b2f97a34cc034adf5dc71f857b3461caee79f51dc974cb96f0e7c2bd7b555942ddeb932e8b6c9b4ab794a774cd9cf20fca3f3aac4cd356338a381e8e46428fba303a0ec2b82c744b52c2b06f94631918e7bd5a57d052d11d5e956c2decd3ccc8e0b3e3bf703f2af661eec0f93c4dea54d0c3d3e37bbd5e16032a1f7b60f4039c572d35799e945fb3a6763e5f9ccec7a13918cf15eb451f315a57622076946496c7191d2bae2cc1a2791111836324706a5a2a4288c292c9c03d0552464917ad67c260804fad296e5a8e82936a490e31275a24f432e5bb392f13cc26bb4855f11c4a58a019c93d3e95e4e225cf23eb3034fdce62fe945ec34c88caa01442e41ec71903eb5d0bdd81e6629bad8951472172e64dcee0b30c93dcf5af15cae7d6518f2a457942aaaee38acdb374ecced2ce33a17c1b8c90165f11eaacac48f98dbc0bc03ec5ce7f0adb0cb9984a7a58bbf0c2ee0f0a5c6b7e2c9c86fec3d3e4921190375c4a3cb8c73d0e4e7f035dd889a8c4c69abb3c7ae276396272ec4b3679396393fa935f3d7b1e8d35725d2ed3ed320900ca8f5f5ad68d3e67739abceda22c5d469717ab1924aab004e3a7ad6d535f74c692b44ec7e1c69569acf8d6d3ed685f4db00d7f783a7ee211bf04ffb442afe38ad296bee18dda95cddb2d6e7bcd3bc73e2fb998adddf1fb1c2fcaed96e1c16553eab1291c7406bbabbb53b1d347591e6d2b217651f787a77cd7934fb1d2dda2cdad462167a0595b328264396dbdc939af567f0729e1427ed2bb91b1a6e9a96da7dac4a04784cb0e4e493927dabbb0b1b46c79b8caa9c993bda3a11b578ce370e86bada3855ac36ee4bab685cb163185c312320126a26ec8a8c5499c66bba83cd728990c225c7279c935f318c97354d0fa9c043962578ae418a45c7ccc36a80392cdc0c571a7ad8f629ee7aafc6e92d3fe1381a6402211687a5d9e9bf30fe2484337e3b98fe35ece122a9ea7356776667c53b3b5b4d33c05a2b3032da684b7371ce0ac971234807d76edaf36bdaa55b954d591c3dce9909642ae4af00ae6b3514db3a39f42fe8360c97173206cc4a80631ce493fd2bd1c2d35cacf1332adcae28aba45a4936b7b81562a598ab30031f5f5a9a71e6aa6dcdcb863a2782424060188ec2bd771d4f9b6ef3165b695c96da703b6da9711c6560586411e5558638d98a4a06fcfa02c2c8e19d080bcb15f4ad39753979b5227400964046149fc3151563a1d509dda4731a2867b9b9208e177648e4e4e0fe86bc7a2bde67d0621feeec57d5d81bbc1e76a85045726217be698156894df2801393f857235ee9e9b7a1e831ced37c13d24890eeb5f114e807a799003c7fdf23f3aebc0fc44d7fe19d0f82a671f08be2cba12246d3ec53007f019fe6fe95798ad854363ca6420a052483c73e82b8fb1d1264653749b40dca07de1d73e95322168753041b349424a8cc7e9c93ef5ed415e91f375e5fbe30f41458efdc81f2856e4715c9868fbc7a9555a89b12db0450180704e70c38af69ec7cf47418d671bf381bc60ee3c63da9266c509a0fb3b30396cb7df1d2b92a26c641286f28904f193c9efeb53276a7636a7f1989a8c44430be006382c7df1d6bc9946cae7ad4fe20bd914e98b17de667129f6f948ac651e781d0d10d8a3342c003c574e1e3cb1396a897b03903e5391cb718c9a756370848a9b1e451819ec2b85a3aef7112296390ed53953dbb63bd634db52259a5a6de2bdc8dc304fbf7f5aeea5539666523a7830e9d7706ebc57ba9f32b9e1d7d08e585227bb6dad9755181c924f4007bd79988aa96876d0a6e74e2743e13f0cfd9809a550d70c77608fba3b015f1588ad73ee30d87b247a569f108d064052d939f5f6af9ec4553e828c1237ecad99550027e6c0dc4e066bcce6d0f4a3b1bb1c2a8489101da3839e9ff00d6ae56f508abb277ff005313ec66c3e5769cf38fe541ba561b22966e100038fbb4f984d966d7648f823f78a4700e6bd15aab9c9d0b4aecb22fc898539d9d726b866eeec4c5ea4f25cbc6a4050a83e5c0ae471354c9a2bc2360392dc6474cd6912ae5e8a60ee541046326b9aaa2917e143f33e06dc75acad740c72485a391436501fba7b534b4296c4b1aaed0010c48e78e952b7216e4d80abc9ed8a84c0ad293b82e7815a809296d80e09fa56731a642090ad91c9eb5117706ccebc90282e73efec2ba968239cd62e02a3104fcc428f4aeda0ce7968717afddaac05f072aa4b6d27f957bb423ef2383112b26cf9ffc4da9196eae1d484dcc71f4cd7dce1972c0fcfebfbf58a5a3142e5dd070305bd057af434d4c6b7623d4fcaba9731150a06303ae69d6f7c28c6da99d25b9083e601bb0ef5c76b23b19d5fc32b713f8945f5c8db67a5c4d7d3646558a0ca2fb12db6b7a3b99321bcd71f51bd79e75679ee25321c1c6599b3fccd7b6a5689c2e376753f137524b69344f0ddac8043a1d92acbd306e24f9e46fafdd5fc2bcba957999a4699c6c726ee5b04fb55c257445b52f6989f68b954280a93c6ded5b50f7a473d69591d0eab75e45b98d5f3bc6dc9fa735ead57caac785469f3d423f0d45102ee403b8eddde98ef4b0eaecdf307ece164743b4c40649cb670739e2bd447cacf71eac823cfdec839038fd6b44c3a0aac5e21818c75c526c4f51d14eb9c11922a9326c4a661180539c9a722d6c340496379dba81c0f7aca6f4082bc8e3eda09750d6019118798c485279007ad78d17cd33ebe97eea958d6f105f8b6b07b5c90cec143631902baf112e481e551a7cd89e6393791222382597b0c75af0933eae3a2285cdc4aee881771721576f2724e07ea454ca5a8923bef896eba5eaba5787227125bf87f4f4b227d666f9e53ff7d363f0af5294793539a6f530fc59abff0062782b4fd0942f9fa8baea37a0755500ac28df5196fcab8b1952fa1db08697380f30c8cc00f63f8d797bb364f96274163347a7e9d9c904ae79e07b0fad7a717ece279527cf33221bd25ddc962c4e067af358a775cc77dacac7a36840f877e17de5d17297de239845f78656ca1625ce3afccfb464765af43090bbe639a71b13f8c97fb13c0de17d01b266b80daedda91f75e5f9215f6c46b9ff00815678a9fbfca8d287c470d6d11b8ba8a21f2ee600b0e78cf3fa5654a3790ea3b419d1eb891dceb16d68bbcc2a8a081c1ce71c7a1c57754fe22478b87568ca67401364615092a4e006e715ec528d8f9b94dd49b1c241e538dcfb88c063c9cd5360ee8cbd72f9adac5c33039c707a1ae2af3b23bb0b07267136d6ff006994bcdb882d80d8af9e97bf33ebe97ba8ebbe1af85e1f117c4bf0be9f200d6f25f24938c758d32ed9fc16b354fdf3a54ac5bbeb53e36f1ddd4c796d53546548c7f187976a807b9db8af73d9da95ce39ceec7fc6d61a87c5cf10881c7d9eda54b085587dd586354238ff00681af05c5a773a212d0e0a4b19e162572ce4f241e714e117766939a8a3a5d056eedb4eb8973b09c82580e40c0e2bd3c3dd459e163ad39c4ccd01a59356770199555b03e94613de99d3897cb4123a25bd765c95f971d41c74af55fc67ce752d2ea8aaa81a22ac5413ce0919a4b529e8482e965058031927a11d7f1ad6c4dcb16f711089b7c8ca49da428e0d37b936d082f4dac56ef263eea9566390338c8cd635b634c36b3b1c9e816b14a6679183160a1801d0f5af268af78fa1c54ed148a9aa0825d46e014da7700bb781f8571e217be76e11da995574f59640a5b3ce7f0ae4b5e27639687a5c3a50b2f815a66f1b4ea3e269a4421437c9141b49f6e5b1cd77e021790eb4bf7669787f4c6b7f825f13e65761bce9b68b9180774c5b07d7a55e630d8ce8d4d0f297b378e4258ac996c32a8ed5c1cbb04eabb91fd91d5708793d452940af6874b246d6fa2464e5c244401dc7e3e95ecc236a47cdd595eb181e198cf9931e7605e01e71ed5cb86dcf571552d4ec6e94942850a473c2f620f7af59c743c352d08ae637672e401b80527fa545ac747319f3eec14cee5ea0639ac243e62b4d0badbbe4e5b1c28edef5cd555a274d3f8cc0bedeb696eaedc8191cf5ae1aba40f5a9fc40ca208602c3fd60cf3e993cd611d20693616076a4a48ea70057552d62734e4586b607952429eb9391f856938e860a5627d2f4bdd0b3ca19903718af3a6ac7a349dcb579a4ac84c90a852570c00c66b9799299d2e273ba9c2209814c8c0008f7aa9cad231713a3f0ddd17b7c30258703db8af6684ef03c3c546ccd6f0fdbc973aa493b6648c304018e0f03191fe7d6be731959a9347d1e5d479a946e7a269fc90188dbce0fa9f4af90af26d9f694e9d91d569b6fe62e012a8a413df9af16b33ba9459d159c236a6586d276e09e33f4ae54f43b2d646a18d8ba9da1f77ca47aff00f5ab16b534a7b968aac276f1b554160a3807a62ad9a9048a379c631ef58b645875a4688aa43003716c67935eb543923aa2d18c6c5618756620906b8efa894751770e49c80149208e952cb1f1e2211b9c92a095c8e067be7b71eb49145fb62a7e6000271cff002ac2687735ada5f908660011dfbd736c17205ca48dc60139a571b27b37225604eeddd01ed556b822d10542b0e57a75e959a889114a32770e2acab0c699c15da063b9a9946e22b492ee7619ebcf151ca43d0cebd5f354a0623041247d6bb22b41c4e6356ba2ac70a1c2b7f163f3aefa08c6a33cebc61726dac277de09c6148febeb5f4b8283bdcf1b173b41a3c0b5ab92665040c7438ff0afae83b2b1f1a95a6d92c1fe8f6fb01db215c915e941da273cdf33321e522424e467827d6b8dd4f78ea846c87c57037648e338e4f35319dc9677da1dc45a4fc3fd4e47c2cfabdc2dbc4c31c45190cc0fd588e7dabba8ee66c3c03a3db5ff008bf4f7b8dbf60b490dedc961b808a352cd91dfa015e85592e5328a399d6eedb59d6f51bf2eaa6ee7926d9eccc4803f0fcabc7e5bb373390bc20027073dc935d14d696327137bc3d74c164948c84f9739e79aefc37baee79989d8b3aaea514f71180c23455da17dcf73ea6b5ab539998e161caee751a3b25b5b43131c851b881dcfad7a587d11e4661794ee6a02190b6e3b47502bd083d0f0dc591b0c28180a3a8cb669a666ee89b6b340a4150072403cd4b29c6d0162643b86e52d8ea0d34cc9af746a111b8571c93c67d3d6af72a2fa14f59ba5b6b4976bb6e276a8e99fad72d79591d98383f6a65e80aed74f2c8c46d52158738241e2bcec3abbb9ede327caac6778a6e9dae140258850319c7d6a7172e85e0217776604b281237527b13d2bc96cf6daf78ddf87904175e2ab7b8bb0af67a72b6a13027036c43728fc5b68ad60ae6b51fbba11ab9f156bd717779758fb44af75772ae0054cee73edc715ea3928c0e1b5d9cd789357fedfd66e75063b04ce0220ced48c0daaa01ec0015f3d53599e9c15911e996e5e4e402a39231906baa9c2fa98557ca89b5190b48222c1718247606aea3b6873d185ddcb167a43ea97d6ba7404493cd2a5bc6c0646e6217b7d6b4e4e66ac54a7667a66ada6c5e24f1fd8f8774f024b58258b45b57db80d1a1c3371fde6ded9af620953819c9dcc0f899abc3aef8db5abab6245bfda5a084e7a451011a01e830bfad78f37cd2b9ad35cb2b995e18b5fb46a40b9e2352c148e09cf5cd6d878fbf738b1b57960d773434eff004ef14348e498610e70188e718535df08f34ae79f397261ec741e6111372723d3b9af5d2f74f9bfb7719382f181cac8318cf707bd11d11aaec72be24965926484107009248efd8578b887a9f438187529c4fe5a803238e86bcc8fc47b4d688f43f8316ed0ea5e2bd6832a9d23c3f773472123e491d446bf892d815ac15ea1155d9a2dfc0bd356f7e28f8292550560bc49e40c3af968cc78ff0080d7b78856a473277679eeada8aea7aaea57eccc66b9ba9a7dc7a92d231c9f7af9cbe874c74204776603208c60e4726b58ad2c54bde8f31b37f29b6f0d5a4584dcebb98a7254927827b64015d8f4858f113f6b5cb1e1bb1115af9ac98f3305723a01d7f5ae8c253b18e6557a234ded11e40c63503af1d3f2af4dc6e7871a96226b25dca7613cf5cfad2e5b1d11a9cc3decb75c1540c369c6debf8fd6ab94a73e51f2e9dba4030063191ea7d6b2e5b331f6b6998fe2085e2d3481828e7601bb393fe4572d7d8f4702f9eb5ccfd1f4ef274c92562d197248041e48e2b8e9439627a58b9deb247393dbbcd348c093939ce7a0af2eb2bc8f730ef41ff6778619255f9b6a962bbb1c0fad73f2d958daf791eb1f1360b9d134bf037873ed0f22d9e8c97b2c6abb5566b825cb7b9da1466bd9cbe16772710ee892e6ee6d3ff66ed4598b2fdbfc536f0e3aee58a12c4123dfb7b56598b7ed09a2b43c9db542189c6e24e493c115e72934c728dd919d40b1076707ae5aaef714968ce9afb5058f46313aed0220b9273f4e47535ebc9da89e0538deb99de1a64c4ce54e0f048ac3088edc6bb2376e6f02aa05ce01c28cf4f7af4e478f16539ef01032b951d7dcd6736752336e270cecc70a57900739f6ae7b95ca52d46f42da394009239ed83dab9aab3d0c3474316fe4262b70f8c346083efed5e6d591ea525660d999a242bb4950b9278c6688a2ab6c596b708c546060e31d6bb2313cb93d4b56f120900c8e54e39e2b592b445bb342cd885603852071e86bc1acf53d9a31b2255f963c9eb9ae1a6f53d2e5d0cad72c5268dee3e50770caa8e00fa574b673ca249a7cc96f60c50aabe39cf5fc2bd08d54a163c5af4dba88eafc39b62b7855f0095dc5bb0635f2b8a97bcd9f61848da9a3b7d151caed621b9c027d335f395247d14354761a7c0918e50b74e41e86bc9aba9dd4626f2db8f9080173804d711d5634a0568dd1c3798dd339c6df71485b0f9264652a610d233762477ea6aa2f41bdcacf3428ecb9e41c1fad66d5d9a587d947f6762e3013aec247cbf4f735ea543969c742e40b2c8376e26100b000e7f0e7a571b1ecc565019b273819dc3a1f6a522190db395204acc88d86dbdb26aa1a9372e40cb0cc541214fcc3273f851289a1ad03238193c019ae3946c02c9306f941e9c0acf94a63ad24f98124920f1f4a57b022ecd2663da3a0e714ae244503efc8635695cd520998c67080ec239e39a488466cb30c9001538c8dcb8e2833968635dccce58875ca9e3d45764568389caead7223791410001f364e79aefa0b5396a33cc3c7777104f25ee040241bb731c703d2bed301493573e5f1f52cec7945c69135c5c0789d67556eb19c9fcabde853d4f0a6ecae43a82490a6250636e9c8e7ff00ad5d725689c94d5e4633296248c900fad7937f78f41e885f958009cb76f4cd694d18c8eabc4005a5be9b608a63582059092d9cbb7ccd81dabd0a5a18c89f4e99f47f06eb3a82bb452deb269f1b0ea01cbbfd01000aaab52c87147351de96c0720a81c82706b929d4d4d1a2c4734570c8a4051dc93dabae9bbb3399d0585a4696009c02096603f4af4e2b955cf13132d6c644366d7576020c867dcc49e8b9e6b961ef48ed4b969dcea639e483e48cb019c96ebf857b50f751e16223cda9a76daa3a9daa3713807b66ba94f43cff006572cbea8b24b8e98eecc39aa8cf5309d1248750432801c124740460734dc8aa94ed02d0993e724866e540f4c7414948e170f74ae2f15d01c959070338e2b58c871a7ef1cff892f965923899c6506f1db19efef5e662ea743dfc251b4ae4fa5cc96b60a3764366427ea2aa97b91b98635735448e5f51b91717124a0863d786cf1dabcdab2e791eee1a9fb3a699414e5b24f078f6ae093d4f422aeae74d6732e8be0b9cc64adeebb21450c3056d636e48f666047e06bba8ab99c8cabfb84d27c3ac800fb4ea40027182b006ec7fda61d3d05457ab6d0884753982c2570a0e7f0af393bb3ade88dcb20b65665dc7246e18183d38af5d2e589e7ce5ceec67094c8e495dc7391bb9e6b923efc8e88ae4477df0daddf4e8f53f14b10bfd9a861b57914856ba914aa907b95196c7d2bbb0fef48e6aaac743f0feedf43d33c43e283b99b49b4fb3db93fc77b71945c13fc4aa59b8f415d58b9f24742e11b9e76e5182e1b7aaf0ac4e49f53f89af2e3ac6e68f6b9bfe1812476d71285daa149248c718e4d7a347485cf0b30f8a2897c2f09927ba9d4801db6e48ce4f5c576e1b53971ef9292474d10712fef10290368e3a9ec457a49f43c4b68995ae06e52d8e4743e949e882f691c6ea7331d49cbf2554633f5af02b3f78fadc1e91b906e131c8c633c8e9c5726d23d0bdd23d07c1971169ff000c7c7f300f1dc5ebe9fa747c655c190c873f82d745057a8457dd1bbf02888bc773dfb3ed6d3b4bbebb663fc2042c33f866bd6c53fdd1c90d4f20870f6b1305c657767bf3cd7cec55d1d7b6844a64f310479dc582fd4935ad2d6760aaf920d1afaf34577756d6b012a83071e848c73f91aed9af7ac79b8587bae6755680243145d046a141af5e846c8f9ec6cdca43a4611121886c73f5aeab1c495d038190ccc483d021e40f5cd44b42d7ba397cc2fe6468437dd0c4fafbf6aa8ea6c97321f10970410d8ee4f5ace4ac7338df539bf165ca89e3b75428506580eec4e01af1f132d6c7d1e5b0b2e665dd4633a668a50860eaa55811d09ebc5692f76060aa3ab5ce51a050a0818cfde24f5af1b791f5c972451774fb2fb7dfd9592f0d75711403e5cf0ceabfd6a64b51419e99f1c2fa097e2df89d14b98ad2686cd481c298a2542a3d0641e2bdfc142cae71d6abad8ade379574ff00819e07b44c31bdd66fefc7030caaa101fccff3af331ed3a877537647924aa25705c2e4f520570d95c7cfa891daac9730c423386382739cd6b4e17339cb466f6b36c834f70a020dbb703d3e9dabd1c446d48f1683bd619e19b689a097729c961c03ed46122698f91b171689e56f62432f000efcd7a3289e3424655d44acc493b5715cb2476c25733ae61058b027939c573b474dccfd582a40a8adcbb0273e82b8aab3d1c2ec676a7896e102fdc550aab8c62bcda88f460f52d6a16e7fb44909940a3e5078e001fd2ba208555dc36315dc4f6e302bbe28f35a2dc08b1b0cb1c6dce40acebcad11d2d644f07cec9819c1c9627afa715f35395e47d1538e859643c1273cf4c7bd73c74674b62cb6ab71018d8850dc1cd6d7277302f2d66b0b9d843344e329c70de9f4e29b9b5a1c328a723b8f0f12608d8e402bc8ef5e3627b9f4787d2363bed1e2f3a00402a076f5af9ba87bd4b63afd3e311b22872a0618afaf15e5d567753958dd1f3c470700633cfad721d48b71ab341bc7de5ca9c7a7b5519cb41630c588cf3b7eefb67d7d684ac86b7238adce5f3e57dee32c738c0ebef5274a44a11ba92a11881ef5e854d0e582b234a1431db608e03118cf6f5ae4990ddd88ea7cf7721154aed503a7d7daa62c868a53e2390024618f4fee8ad20f5113c122303c9031b46475ad12b8dea69dbc88eaa030c819c0ac671250e76c64e338ec2b99ab1af527b538e71593572a5b133c986039e4d4b4101a8d894904f22b589b314c81ff00322a12306f533afa431b333676818ce7815a5ae44d9ceea33aacb21316e181860dc1fa8aee8215f4390d66e030901253be179e3e95e8d05a9c555d8f1ff1518ef5a590dd452c8a486493e6dbcf4afbac0414291f1b8b97348e2aded278e612c2cc080d865ce1b35e9d15d4f36bfc23b54d5e566115c0de07cccacb8fa574ce5a6a654519f27d967b72c55adcf1f30e40e6b824a9bdcec91a3e1df0a4dabeb7656d672c370d24808f9b0081cb673d3815ad182b993d03c47733df6b37571240d1b48ec02672ab8e300fa715dae16218dd7ef043a3e9b60b952b99a51db71e9f8e2bcec4b348ab1cfa302d927a038f7ae6816c92d14bb601182718f415d34f495c8fb26e5eea1e55a848a4c1036ed15e955aed46c794e9f3489741b8712f9848000dbf377aac3cb536ab1b2b1d11d56074553f29ee718af639ee8f1a749dee4df6c8a64caf5c60e3bfb8a6a662e993bc2b291b480a46eda79da319c56c9dce6945dc81a171f3a9f9b185db4e4292b8e59e585b01c7cbc9073d7eb4a2c8952ba1926a67cd2d28ca819aa9cf910e387f78c0b9bf8ef2fb686059db68cf61dabc894f9e47d0c29a840d4babc16f60cbe6a931af96a370cff9eb5d9cea31b1e42a2ea4ee72af70c470b8ed818c115e3ca7ef33e8a30b42c58d2ac67d7756b4d3e04ccd3cab1a81c753c93e800c9ace0eefd4d3647417b35b6b9e248ecad24923d3ec95a083cc23290a6589cfd771e4f7af4ef68fa1925a9c86b1ab0d53519ae00c2b00aa31d1546147e42bc4a951ce7cc69cba8694b1dc5dc71b65109cb3fa01eb5ad16e7215476468eb7791ba2428f81c678e463802bb2a54e872518946070b184285dc9c00bd492460538bba356b53d37c5b05b685a7e89e168c6cfecd46babec756ba940255bdd542ad7ad878282b9cb51ea5cf1a5c1f0e7823c1fe1e8a42b77701f5cd4a365c9477f9605391918404e3debcfc554bb3a29ec79db001d8af23393cf4fc2b9d6c8d13f7790ea237fb3f870b44c0798bbbae38271d7e95ec474a47ce56f7f10a258f0cc4f1e96180023762c3279cf4fe95d1853971facd1a82763d1cf072173cd7a67932d185e7103b646d2b83ebcf159d4d871d5a391d49849aa4e463236a9c9e400a00fc6bc0aeeccfadc1afdd1537fa00bcfaf5ae65b9d715a9dfd8cad6bf041c1254ddf89411eb88e11c0f6f9abb30bfc426bec697c3902dfc3bf12afc36d922f0ec88a4f62f22af5fc6baf14cc692bd8f2e77f2e1072000a36e4f6c735e43d4e88ad592f87ed05f6a05b6975850cac338c00383fcab7a2b5e633c4cf9205db38927f14481dc98e238caf39007f89ade0b9aa5ce1bfb2c3b91d5468922ede630a38c01c9af6eda9f2f525764e881a3c000e7a861d7f11cd6ede86490c58f0090005236ecc76ebdf9a861224b73f238ce01209a94383b0fb490b481493b73b41e868aba46e6d056d0e54c4754f1010732949431017ef2af3fd2bc57efc8fa48c7d8d1e62d78ae632b20008258c8def4f1523cdcbe3795cc0f2f78c676ee6381f8579b25a1f58a57563a9f859a78d53e287852d363b97d4e0185ea70c189fc003f9528fc3a8456a3fc6ba97f6878e3c537734a0a49aadd48cc4f5c48dcfe55efe1b4a6793357aa5cf8bb318fc3ff000eb4c326d4b7d15ae99171f234d33367f15515e262b5a87a9097b879abc8553214328fbdebed5cef4652d6258d2d4cd7911390a0ee383d457561fe232acfdc343c44fb6d625047cedc927000f7ff003dabb715f09e56195e772cf86d1a180ed20966e1bf0e6ab08cc71baccbf7928129565dd939c13d47e15df3d4e051d4ccba7ce490703b0e45721d74d149d448d921957f849e3047ad72cceda6645d6e6bd898b060832703f31f9570c9687a145e867c52b5dea40bafcaee0647619ff0ae2dd9e8c117ae5c3df3905b03eee7d2bb39089b25895888c9c10321b3d6baa0ac714a4590c228d8e390a71c77ae5c4e91230fac896d887c28e38fbc7a1af9b6ed23e9e92d0b0103166c96c763d2a2c54862b17c29ce5b83db1458225836ab750b412066dcb8561c6d3ea0d04ca1ef5cb1e1c76b7536f28f9d18a92d9e7078fd2bcfc4ad0f628bd0f43d1898d51492630a5b20f46cd7ca62172b3dba4f43b0d2cef4dc4e49c76e6bce92b9d90dcdeb59101de06410176fbd73bd0eb6f52ec6ed0249f29dbbc8f5edfe7f5a95a95257426e79bcb20631c8206dc0cf14dad448af2c259c96c93ebbab6e434e634ed4bbb20401971d4d74d64637d0d06731207d80e07cdb7927f0ae496c282bb119e39e3f31091cede99045630d892b5cc3bb1c2ae0609c74ab892ca077c4ac04b903a026b68b256a6a69ee21519fbc07ca4f24d3914cd1dc426ec6491dfa135cb345f51d0ca4124ae38e80d629152d86a5c1930cbd738c1a562513b0c1249fc2a6f636443bc8e00ffeb54dcc9ad4cfd49caa00c530d8c9fe2fcaba22675168731a85ca8e572015241dbc66bd0a68c93d0f3df166abf65b69e5270e0738ebff00ebaf7f094b98f17175794f07d72e0b33bb0f9a462c483c13ea6beb63170a763e5652e6654d3ee2655211e45723208200ff00ebd7550bf29cd53509357df2b1961594e000cc4e47d2b594d7515243deeec2750a5248cfe056b1e7a5d4e991a9e18d3d5a5bbbbb7ba857c8859807728db8f0a17d4f24d6d456a67208ec7538ee63528d20760a849de198f618ce7f0af41ad0ce247e2dbf17badcc25b548ca811fc8bb08c0c722bcac42d4e9b58e79a28c2654919ea8c391f8d4421a105ad3ec25790b29070338ddce6b7a549ee43d10dd491d240af1b29039dc3145476d0ca9abb25b495e24da0641e6b785e2456f8ac58175230249fc477aec551985482b162db507f2594804fb7a552a873ba68bb06a2e4b7cc48270327a8ec2ba2354e49d22ec5a931c825b20e40cf6addd4b98386a5917b149bf276ee39cb761551916e1a0dbc7896d4b96e42e01cf5359579f320a71bc8c3d2ec629aecb2862465973ebe95c14d6a7a356f188be218c46b1c48dc81f373dfbf34559b5a0b0b056b987f6673c6724f627a570799e943de763a6f0a23e9369a86b32968a6800b6b575e479b229c9fc1413f5ada8c75f4265b14dbfe259a24d2b05fb55fb18101e4f96305987a1278fceb7af53955bb99c16a738a630c3031cf26bcd6d4626ece9344b1430c92382371c0c0af570f0518731e76225633b51b6f3af9828ca961803a8ae3a9ab34a5a23a3f04e99f65b89b59b88c9b4d3809577282b24dff002cd083d46464fb0af428c2e8ce6f5367c2b68fe2df152cda9ca16d03b5eea572c725635f9dd893f4da3ea2bb24dc222942ecc5f14f8b67f187896fb5ab96512de4dbc22ae36c606d45fc1428af2675149ea75285919b0399e52b10c9240009ada1aa25ab2723a6d7da382cad6d9149246001d09c7e9cd7ab2d29a47cf508f3d57366a69cc9058a606c7d8010dcf38e715dd4236479d8b7cd54bca516266070ac090db7181e95bb958e0a91d4a93cca5153ccdd938c11f9d272ba1c63a1cd6a4106a72b8f403af4e2bc6c42d4fa9c2bb53209151b85ce783bb3d6b896e77c56a7a1ea710b3f837e0c85132b77a95f5ee54f4036a01efd3ad77e13e331c46c3f45b9fb07c25f880eccc82ea4d3ec97fdadd233907db0b578b66787d6c7975d386241c6ef4f402bce89dc96e69f8795edac2fee49180a155bf5c7e3c577535fba6cf3b12b9da897bc37062079ce0976ea79c7ad6d845cda9e7636564a91d3991194020051d31dfdebdb82ba3e724ac58450cc06d07b8039c5437a9116365457ba545cae41e3df8a19720f242104b90475c0a48c2f620bb9d6d2ce773c3056fcc8e28c46903d6c2c7da34cc0f0b5b236a5f6872ce141f9ba10c7d715e3e17de9b3dcc73b51484f14c8a6f8283b42a81b7a9cd678ad19865b4f4b990ac431c90076ed5c6de87b6b73d07e02ca4fc5cf0f4ac06d81e59831ea36c4e45668dd2d4e2a59de679a797f7ccef248c5b92c5989e7d739afa2a7a513cc71fde1d1fc78b8dbe3e5b001563d3b4cb2b65503a6210c73eff3578159dea1e828da070423ca024f5f4ace7a32a1f09ade1d8b66a219002444c1b9c119e08fcaba30ff0011c788f8487c427c968c0c1c64e3af5ae9c53d0e6c0abc997f472b169d03e06403c9efce2b5c32b238b15fc424b895e500924328ebd88aea948c2da94269888c36490cdb71deb0474d2454770c3efe78e6b1a874c77316e5c837533124290a369ff3d6bc894cf4e947421d0976dc3ca7e6508768cf4278e2b3a6aecef8684aabe64f3b282155b807824576c5dce6acec6ac0864049185000cfad75c4f29c8277d9b9476e071c62bcbc6cada1df848ea4902ed703a6e3f867d6bc06ba9f417b22dec0aae181503eee3a1f5a7605a919dc064e573d28b1a22ddbb10ea00e47201a1234dd1ad71a7f970c77e08daec10923a1ed93f857062763b30eeece9343b9df6ea8a3201e58f5cfa66be66bc7999ed52676163290aab161491c6e1919f7af2ea2e53ba9bd4dcb49d932a3043101bdbe95c55743a9ee69dbcc3cdca8263c1c07eff8d674cd9bd0b0c4792b1a931e4671edf5aabea3899f3cb74aca117e5da39cf5aed4f41d8d5b399e770a1cc7850e001904118a8ab2398bd6fbadc191e45f98ed036fdd278e7deb083344ec4fbc4411182f3d3d7dcd4dac223725909099247dd26ad6a050960cfcc470a7031dfdea8c76625ab6508036823001ef81d2aae68d9a964c0ae09c9182067a67a7f2ac65a95256268dbce76390a832a54f5e3af35958993d08e19f6c880263271d770c7d7d6a58a3a96e69371183f29ea7d2b0923a0819cab2be703a7b67b544a3648cfa999752078a4e479a41ddbba7e15d31f890aa6c71dacea1b43221da471cf4af6a8c6e8e09bb3b9e41e3fbe69a3fb3c24925c1249c67d7eb5f5d9752b3bb3e6732aa9ab1e5dab2491b2a29de39ff0027d2bdba91d4f0a3a2235495200db406038c57628da264f56522a4e77704e7271d2b924b9a274463618e718c6481eb5cee36894d9a7016b7d29dc2ff00ac70a0e7a639cd77518b4889b27f0fceed7c2569a58962569b7a310d903800f6e6ba39ac629941b55b87b97b8773233b163e6fcdd7d73d6bca955f7b53715af62bb7ccb188c1ff009e75d31945a24d3b0b0b79149b6bbd8e492165014e3fad77518a8c4c2a3bb21b937515cfcc3cd1c72e37022b37772348ec5db77b5930258cc44f528d8c8fa7f8d7a114d9c726393478eec1fb34cacc09f909da71d8fa5374d31291565b29eca5cba6507f167835cf28b06451b3b1e46029c8ed4e3746122dc0cfc91919ea075c57473992458595cb648f94d6b1909a2a6af75b5214cf53c907903b571569bbd8da842c4fa412b63e6e48cb7cbdb38aaa6da571d75cda1937ba9bcd7326dc30078cfa57254a9a9d142164556bc670bce307248ed5cb3a8de88ee48ea75082586cb4ad2211219949b8991bbcae06de3d971f9d7a74e3cb0462d143c7332c7adb5823ef8ac116dd4ab0209032c41f7626b8b11539a5e80a273caa1982818cf63584649e86ad591bed21b7d343a1c10307071926bd475396958f2e51bc8c7b39669ee54a02cd90001ea6bcba33f693e53a5c6c8f47f11ea3068da2e91e1c876b4b6c1aeafae78c3dc371818ea117039ef9afa38bb4794c1c6e3efee60f0cf8045a060355f1136e94f478acd0fcab8edbd867dc0ac2bcee8d28a3839212e480f818ce6bcc5b9d296a5fd02d256be8d972db583124f008e80d7a587a7cd3e738b112e58b45fd52eae2eb53820e0a2f0371ce096cb0aea8cb9eb58f3e8a51a6d9d124ce80078990638dddabd94ec8f0aaa4d931d41582215c151cff008d657d4c9d32237314b74b9657017231d01356a56474429729ce5d4c1ef6e72db817e3d6bc8ad2bb3dca10b21f190cac73c01e95c69e877c6363d0fc62ad65e0af86d631b3a634a9aed99ba0f366240fd2bd4c0ab3b9c55caba8dcc9a77c0e21cab36a9e22181ea90439c8ff00813561984af2b178681e67212cd8271bf18c1ae14eeac7a1356474d776eda57861632a3cc560c46e3972c7a0f615e97c14794f094b9ebdcd0f0f4423b0524707918e86bb7051f70e1c73f7cd4f2c9f997e5561cfff005abd23c2996e22579dc1194020a8c803150ce75b8c6470cd2825c633bbad5b35931d1c8b374edcf5aa465cb7666f892e49d34e2153bd829dcdc819e48f6ae4c5cff7763dbcbe3fbc21f0918d6de667200560a173f7b8ae4c2434b9dd994b548c9d6242fa84c4a8c838dc6b9314f53a704ad4ccb9989072b9c1c8ae37b1e9f43bbf823706db5cf116a2d806c3c3f793464f6765080ffe3d4455ec6917689ccf86ec5eeb5cd32d1159927b88a255eb92ce3afb66bdd96944e25ac8d0f8bd70f7bf16bc5ee4862b7ec9c0c00155540fc02e2be7d3bb3d16728091112415fe956e3722e6d786581bb75c8036eee6bab0b1f7cf3f172b5329789642d7603aaaf61b460601eb8a7898fbe6597ecd9a567088aca1c1cab2f5fe75bc4e1c57f10ad7b28ddc8e40c71e95ab223b9554b7031d7dbad6674d32bba859189e323afa5653d0da2f530e57516ec4119773f9578d559ebd28de68b9616f1476858b842e4e31e82a68bb1d751eb61b66034aeb90700360f7aeca5b9c75be03622895177004061d074cd77cb63c9b5e688c049c3107eeb11907b8eb5e1631dcfa1c2d3b0b1110c8a3248031c9af20f45a2d970b1e07cd81c03d45386c35a22bc8c0e080738e3352f71c19621718c83bb1d7d6a24eecd23b9dc787edd35ad0f58d3c866616c668c2f3b9d3918f5f4ac3110e689d7875ef95743976c68db4903e53cf19af9ca91b33dc82e53b5d22e0b12a54038cf5af32ac4ec8bb1b966e029c292a7a0cf535e655d4ee81af1b9c32b12854642f6359d3341d048d2cea1c908bc9c9e83d284acca8ea3bcd8cff00cb40b8e315d8a46bca6adbb312a109208c74c7b0ac6a6a7222ec714442ed01f037609c7ffaeb18e80c15048ee705c6d2cc1ce303be289bb0c6b2b4af18284c600da4b679f7f4a21202a4b203b81466cb7cc518000d55cce6accabe63205c7cb186cb1ef8f63540f62fdbca17718f730770406c631f5a16a54d96f71998ac640ddf79873f5a8921dae88a40215620b80790d588a98904c1c2aefe00e49f5ace48da44b23a2b02499136f4ce3a7634a5aa444b46635ccab1da9270af9c609e83af15b457bc8ce6f4387f10dd85321c676804b01d33eddf15f47878dda3caab2d19e29e2ab99af3506646ca024290781f876afbbc1d3b46e7c5e327cd2b1c75c199e7650d920e335d6d5e471b9590b334b15bb20042b8e7d4d744f4899c25765076743f3ae78e83b8af3e4f96276290e478dbaa1ddfdd1de947de892d9b5aada41696b65024a5d9904acb8c1466ec7f0c57a74a3a19c98db6b1f2f44bebb1d0910af3fc5dff004a89c75334ce75a2618dd9c5791569eba1ba63d6167518c024f4a854e4cd56c5f8f30c0b80570393dc57af1bc627135a8fd3b509ede560acef1e7250f20fbd5d19a72346ec8df86f2d6f5025c5b2c2c4f3246a01fa9af594933cfb932e83e7ee3652fda368c9403e6a4e37d8cdc868967b290a491965c60a483fa1e94685f332d28d32f18acf135b10a03328c827d7de8e5473ca4247e16b9922325abadc463aaa1e7a7bd274c98cee54b8b59ed8e2588a051ceee9428d8df730f5197ed37601c600dab8e98af32b4bdf3ba9ab44bf7578b6f61b146d0aa02e0639aec6d281cb7e691ce0652d91f31c703debca724d1e8d35646978674c3ad6b9636643209a65562173b573f331f60324d674d733354cec6c271acfc42b9d4e445305bbcd780afcca23854edf4c8caad7b3b45995ce0269a5d42e649981796476918818c92735e046f36fc8d624b6ba55ccb2065824600e72a38aeaa341a999d49590baa5dcc17c9901504838e9c7d2ab1757d9be54614d733bb37fc3160be1dd33fe122bc42672c574f809003bf791bfd95cf1ea7e946128f2fbecb98ef0de9c35bd4e59afe574b18899ef6e0618aaf27bf5663803eb5eb397da32451f136bf2f88f56b9bc907941f0b1c6dc6c551b5540ec00038af32a546cde9c2c8cb825609b482cc08e86b252bb29bb1d4e8f702c2c8cb203b8e58f738f4af7e93e481e5567cf2b09a13b5e5f4977205458f919f527ff00d75187fe27318e217b3a1a1d147247232b17272d92a73c0f5e6bd84f43e7dc1b658956294b150be8b8393ffd7acd3d4d79199b1a08e39e538e0138031fa544e5646edfbc91c93ac8f23ca18924e781d3f1af1673bb3e828c7dd182ee70a522dc4bfca31dc938031dfad65cdad8ebe5d0f59f8d1a898fc676fa544a5e1d234bb4d3d71c6e658c331dbeb96c1fa57b5877caae79f5617673bf11f5630d9f86bc3c806dd2609269ca9e3ed13b6f61f82ed1f5cd78d8aa8e556c74d056391b2892eeee188a6e0ec0139c607f4aac3fbd52c6959d91b1adea12de5f416409f2d1b77fbc4fff005abd19caf2e53c6a70e58b91bf6b3ac76d1a6c6db180aa07603b71db9af5e1fbb8a3c4ad2e7917e1bf055495ce7a1535ab91c8e993fda9253b172b91d430c7e66852b983a64bb83214040c60153c0356a41285844dbe68501133e9d08fad53638c4c3f1449fb98a20320b96ce3a71d057958a9e963d9c047de1da002964ee063e6e0e3be318adf0ced4ce7cc657a96306f67679e7ddf78b9da6bc9c44af23dcc22b53290e01f989fad73ca5a1dabe13b7f8790343e0bf88da8070163d2a2b6d83a932cca3afd16b5a5a849da24bf0bac9aebe26f85a244c97d4edfef7a06ddfc857bb574a271537791ccf8a67179e2fd7ee464f99a95cb039ea0c8c01fd2be620f53d39197203b090324104e6ba798cae68f879899662e1780381d7db8aedc23f7cf1b1d2f76c47af9ff004b5f308618c00476a316fdf35c07c05d49374118400155ce08ab89962637a851b994c85436381838ee6b46cc52d48a691782bbbd997b564a474c636451b977585b38c0071eb5cd564690576655c4612081400a5867fdef7af364b43d9c3eb22cde158ed2de01b43aaee72320926b2868cb9eb31748712acae41dc70bc0ed9aeda6f539f11a40d7bc216db2494c2e0633d7b5765496879f1579a12d50c76e2274f9b058b03ebcd7cfe265767d350d895221ce57395c0c0ae15b1d4f52d7fcb20080847438e4d52d89e8547dab8fef1e48eb592dcce2f51d149e502e0640c7ca3a9e714a28e88bd4eb3c0faa8d3f5a8a6570100390412a4f5c9c75a6d5d1d18795aa162ead0e9dac3cb10c5a5dff00a55bb72a8c8dc9da0f501b70c8f715f3b5a3a9ed4e767a1b9a75d3b4f8457623a720e7e9ed5e5d55a1db17a1d6d94c0400a01c1dd93d4fe15e3ca277d366b42be6b60b1249cb3679acd46c6d2278ca2b1071b72411dea67a32e3a118f29c9dc0641c715b45686f737903302809d983871db273d6a26ce78a278675b68953258f07e504f1d6b91b09449525864dc5dcec3ea3eed510279a1e2032150823033ce3a543761914840550143127248efee6ba22cce4b528cc9e66d285948638214118c7a1e2b4224496f27972852725980e00e307ad38ab0d97d1524e1012b9c10cd83532d4b8b193322ed0036f072a339538eb8fd2b0b6a24ac42fd5f20163fdee39cfb77a5523a1a5c9976b8272170b9cff004ff3eb45342a8bdd30af2e0b44c99c3962370504607ad76a85cc9af70f34f18ea2d6f1cccce7730eb80077e95f4982a77923e7f1553960cf1db8bc32a4a65900c138000eb9afbb82b40f869cef36625bb79923be08049c11de9d3d589cb41f7c0a95652ccacb921bb1f415ad55a0525adca4c7242f3d4678cf15c3357356f52e58e9e750bd86da2476323855c0c9393fe15708df4367b17b5dd971abdd49085582376453e814e07e82bd6846e8c16c5bf1646743d2347b02185c4d09bc981f573f283ebf2807f1ae39be5628abb39a4911c8126edbb7e5ec735caf546bd492dac83cca10e71f99a8842ec539d9136a313c4a554e09fd4576d5869639a9bbb1da4c0df66694e003c027bd56161677369b4d16f69dc0ac65980e483dabbd9e7c5976d6f0c2caf1bec39c0238a94c9ea6a45e2149d42de20b85e7120e1c7e35a42ab7a0dad0be9a15a6a2049637713c9c66091b6b8fa7635b34a4713dccd923bdd39b859976b6011c60fd7bd435665d8b03c4f71109049b67555cfef17807b1a72a8e28eaa68c1b6bfb5bed44c92d9441092cc8b95c9f6fc6b86128cddceb9fbb1b0bae5ce9d2886348248c8e5c170403db69ff001a752714ac67868f34ae66bff662f3145296032d93fc88ae2f70f42c753e0ab8d234db1d7b579eda6965b7b55b5b3dce06db89b2a5b8eb850d5b51e4b8ae334ed523d2fc29ad4f6d6eaaf388f4e52dcb00c72e47b95500fd6bab113fdd344a38e33ccd929950401c0c6315e3c1e8d9bad0bd672de5a58cb2fcc88dc8c9f4aefa72e58f333cf9cb9a43fc35a449e23d55aeaf8bff0066db9125d4f8c855e485e3b9c600ae0845d7ab77b1d5f64dcd7b54b6f1a6b4b2bc06d18a882de0b552511470aa07a7a9af72d09ea8c66aecb7e32d2dbc2b03787b4fbdb7d414049af64b66c1690ae4467d76679f7ae5ad52525cb1358c743829432162e8548e338e2bce9a68e98e9b92d846279d09720e72715a538b671d6a891abab6a185102fcb9219bd71e95e84aadbdd38e9ab2d4d6d22dcc36250edfde7cc58fe95d9463c9ef1cb5bde2f2dc3380148620609aefe63cc7118f746380f077138520f7aca52b0d42e66eb176d6b6db19f323120f38fcff0ae4ad579627a386a0a6ee612df39c807048fc0d78aaab6cf6fd9a8ab1dafc2bb3b4d4fc63a6a5e911db5bca2ee760a1808e2f99b39e9d00cfbd75537ced1525634351f147f6df88b5af13df445e396696e51547ca5d8e224c8fa03f81af4a555423638a6aece1e5d40dd4b2dc5c967b8958bb3939c93c9af139aeceb8c6c6e787c5b191ee445bd04586741c06cf0315eb61236d4f3f152134878eff5f9e770bb46719e718e98cd6f097354396a2e5a163a89238658d4c7818386c0ef5eadeecf989bb2236b63112a49da791c5558132bf973a838f9973c73d0569146c87849e1dedb0ee1d792719a86ddc3915822be96291013bc7230dfe144a4ec4c229330bc41ac6d9e10ca78ce006e0e7b815e462a5a1ef6169a6ee5fd1b5851a7a2a170accc486000cfad75d097ee8e0c65252a8603dec324aee2720eee9c8cf3d79af26a49739ebd1859169ae617525d941381c9ce6b3949731b1e85e110917c1cf8872075fdedce996d8e33cc8edf971d2b5c3cbdf2a50f74bbf0363f37e2f7844821cadf2b1dc33b70adcd7b9899da0715387bc79c6b5107d7b591103f2df5c6d0b8e57cd638f415f3d1677cd58a32c122e5c80a481ce7934e2f526da1a5e1e89fce99812580c91ea2bd4c32ea7898d5aa287892622f093800000f3cfd6b1c44aece9c1ab459a01c7d9a3c1270a327d78ae986c6151dd95d823100e14938fad45f51289048e873b380bc0e383508d2456bc21ad5faa8620107d8d73d4674d28996fbee6fa385307cb014739c81e95c12773d3a1a216eb324b2b039017007bd42d0bda469e93088a340c546141c71939aeea51b1c55dea1aa4c5ee16353853f31e7a81da95595874e372def232fb8fddf4cfe15e05595d9ef52564496bbe39c334b918e80631ed5ced686e8ba332a397c658f1c74c53892ca12005ce09e3d462b4645864797047040fbd9ac87b1b5a15cad9df23ee0b1ae1893c903d48ad22691dcf7df8c1e09b87f825e0ef1709a2be6d22eae34abd9eda111a8864756898a8e000cd8fc6bc3c5479667ad4e6797d8dc11346436d2a002477c7a57912f7a47a7459d8697379a0380a18f0558f5fad7935e1667a74d9b96933b48e0141e8073b7d2b9d688e896a4d8217838ce3713d4d4c90e2c95e2850e3746a7b8c53513a0e8e12aa1887dc00dac8cb85ace663065b57d91b0c8f2c36001dfdc572365b22780283bd5986096dab927e829c35328902948d950b12724aa13ca8f7144e254864dba266539653fc63d2b44cce4f52a191d22208ea06dc9c6013ce4575415cce48850853d0e07438edfe355256116eda50cdb9c1049c06c542571dec4925c0da32e38caf071b73efeb59b5a85c6194ec2ac014c6e048e73f5a6e374689154ce419003db8c8e94a11b04dfba626a4e1158b64b1e700e335e8c118cdda07917c43d4487109dc8acb93c0c8c1edef5f619752bea7c666552c8f3abff002d50aa26149cf5eb5f4db687cbc1733b94ad33b38fb8bc01e83d2b6a51d47555991decdb98609c01d2a710ec8de9ab2b95532c324719ea2b9e2ae852d19d3f836d962babdbf3285fb15b33ab13cef6f95401df939fc2ba30f1bb094b422d0f4afed4d6ed2dde504cb32abb670305b927b576df9519296841e29d486ade22d42e460c4d2958c7f7557e551e9d076af3e7ef335a6eecc49a10ea02753ce3b66b1b1bc8b9a5b1b562ce79e878cf15d146279d5e5d09b50ba33dc2a124aa1f971ef5acdddd8ba5a46e6a0b22612a8f1baedc6ec1ebec2bae11b238a759f358ac606590a85381c3122b494488484788990e140c0c2e475acac691dc54c938c6e38e78eb4f4e8696ba012ba3928c51979ce71814f530e4d4d1b3f14df59ab01289f70e43ae463f1a7cd634e520d6bc5515dc215ed103364314e322b96be212d0eda7023d26e34a36be6cb6b2090b1f9f793dbae3b5551708c6e556d7432ef6ef4cb89cb00f93c6411c0ae395584a5635a11e52bc6f64b91f36e0307d4d42707a1d0ceb2fee34cd23c21a0592c2cd7372b25f5e64e7712db621fece1413f8d76d18c0e76c6789b51b2b5f08f876ce1b158a795a6beb863905813b101ff80a93f8d6388a8ac90e071b36a12484940230c3ee815e7f3da099bbd89a4b9b9b98e2b55669267c28451c9cf415d4e5ed172238b935b9d27f6f4ba0694be1eb792296dbccf3aeb68cf9b2e3039ff679007d6baa9b515ecfa9aa91ad64da6787bc3d36b2ef2db6b970c23d3a1419545fe39893d87403b93553a91a4ac8a6aece227b79e5b8678b2e09ddb94f3cf5ae18b949dd1d0a3a0ab76fb1a092312678f9856f0b4f733ad2e446fc2ba75a5882e1e19cafcd95cafb015d7650478f2973b2858e852dfdd79d1cb14c9c923278f41822a69d2e7773593e589b02396270b24456303eeed381f4af5211bab1e7f35d8f0c1c29030a33952304fd475a22734b721baba2248add40440c64718e0e0719fceb2a9746d4d5d1cf6bd75f6abc5443b828e4f6e7f91af22b55e6763d9c2439515d1561032a700f7ec6b8a4f94ed57933abd1661a27856f1d588bdd65bc88c22ee616e0fcedec1986df7c1af430aacae45463fc65145a4c1a76849b45c5b2fda2f5a31c995c02ab9f4552063d49acebceeec6718dce5e461b5463a5733f88d5b37b4ab85b2d12563f2b3960ca4fe59c57bb47dd89e66215d90f8647c9712282554852d8e87d334b0facee638bd23ca74324edb5806181c81fe35e8a97be7cfba771c6e9e5da4f6e0fb569ce1ecec585ba323aa90471c606727fa55c665729236a40365c1048da777b7f3e2ab9d362707615a68402011c9cb1ef5abb3460a2ee737e21581668cb619587000fbbc9af0715b1f47824d96b4eb089ac102361429fc3e9eb5d547f8472d64dd53989ad49694abee218f18e95e1d4bfb43d8a6b42158e42e87702474079231eb59cafcc5a8ea7a5787830f82de2d61f7db57d3c13ea406fcf8fe75d585f8cdaa2f74d4f8193cebf17bc2a1d5fe5bc25703d2372735ece2dda071515ef1e5fa95f4dfda17a40251ae667ce01ce5dabe6e9cd9d956030ea5234481f057d8726af9ddc98c7436fc3ba88549c90421c2ee0718af670b3bc0f23170d518fad5ea5dea6db988e70b9ee3b5734e5cd335c3c2d166af9c0852082319e9d6bb60f4386a2d4aef3ab872ca4ae3f5a3a96b623120c0d808f5e2a5e868e24572e5e48c38c2160cddfa5724d9d745141a45b08e4b9cab4ae595158741eb5c151f29de97295ed649258c8049794e481f5aba5ef9337637accec8fcc20647181dabd189c157565432992e19c615cf0a597207e07ad70d7676d08e8695bef1012ca339efd2bc196e7af0d10e8b730dcc7a1c0c7f234a4f435897033e4823181ebc1a506495ae03b60b0036f4c56921d884654918e49e47ad4b2245cb3946c0c986538c67bfb5544d207dddfb3be8f6df16ff67ff1a785aeee0491dd440a28e4c6ccbb5587fc09411f435e6e323791df4cf90e3b4bbd1f55b9d33508becf7d6333dadd2b0c6d951b691f438cfe35e04bdd99e9d1675da34df22b940db79c06cf35e7e219eac19d0dbed3202a14331eaa3afb1ae13ad6a5d4fe3dd84e32c0fdeeb50d9a4558af2dc8f31832e307039ebef5b2d81b3b02be6e50966000db8da31c572c914b41c8e6152c02961f2927938ae69207a934929b80cc3729007238c11fd292324cad06254523195e4b28e7f5ad3729b144fc9df945da429206723daa672d4cd94ee89312bb0c9040248cd75d37a128a939038076a750a381f856b3d41e8c6ab001304e01c852d9c67d2b38ab04f62e15dca110e23cf248e689a25105cbe0042370c6001dc510d0d0ce693764636e3f87d2aa28c6fa993aadc90e71c10319e80115d9456a4621fba78878bee8cfaace431619f94373f5afd072e8f2c4f82cc27ccce52f66531a950a8dd38f5f6af45bbc8f2a921b6eca232481b71d07526bae0ac85596a5091d19d89c2fb7415cd37a9507644414c6c092573c8f422b25a0d236edee0db682eb112b2cf28dcc57aaa8cf07ea6bb69bb1122e7871becb1dedf60968216553d8330c027f5aaa8ec458e67797ed9c024e0702b85c8de92019073dba8c5117766b2d11aa8523b10080c704eef73e95e925689e5b4dc8ceb6b7df3ef5cee1f779e95c708b752e74cf446e4574ebb57670a3838af57a9e54d5d9692e9268c83c107b9eb5b5c715a11bc195dc09c9e368e807ad632572a2ca6018ba82541c0f7a56691b41e84734c72772a90dc563ccee54756546b82b8c0381c1c0ed49cac8dd2b99b7572f25ce170003b7039af26a4af23b21a12cd766183ca520123b5754e6a31b112d599dbb72f5e95e573733b9bdf42e6976e2eaf115ce101dcec7b28ebd3bd6b455a7704cd0bdbc7d635873180a25658e35ce400005507f0af622ecee431de35d55350d7e7f29b7c16e896b1771b5142e73ee413f8d7915eaa9cee52d0c4f30a80719f4a94d2858a6ce9b428a4d3b4e9755917cb95b315af2436ec7ccc3d80e3f1af4f0d07257661263fc2fa2aeab7f9b9711d8c03ceba958e3118fbd83ea7a0f735d364dea4a28f8ab5c7d6f5579c1d902feeede004ed8a2030aa076e319f7cd78b88973bd0e989950decb07dd723e848a8854e546973a0d235132ca8f736d1cdb47cb95dbb4faf1d4d7ad866dabb38310f9f444b7b736da9ddc7146cd0ed232197393df18ad9c955958e68c7951b3068735983f65904c3ef1788e4b7e1e95df4e9f2a38aacc9e3d46fad4f95348ce9cb05917bfaf35d94e5638ee68aea36b3c4e6ead2369580c3c6c432fbf1c50d6a64d9893b5aa5c49298e4922048197f988f738acaaec74d0dccfb8d33499605960b8786623263914900fbf35e1ce28fa28681e12f093f8abc4167a69ba8e08a57fdfcec0858a200b331f41b41fceb254b9cd5b3d2fc13a059f8abc793ea0d3430e8fa2dac97df66790ed36f00f9101c701cedcfd4d7ace2a9c0e7dd9c0df687ac788af6f759976dc49752b4f2b2303c939c0fa702bc954f9e573aba18177a4de5982678248c63237038fce88d37ed11937637750b636fe1b8c06642abb8ef523049e9fcabdcab171a678aaa7356b14bc3ece2c4a82761937609e8718ae7c2de45630dc8a48c107077af5fad772f75ea794f62563b98b2636e70173fa56b2b31343b7b1c052ab9ea7b8a948115e497e62a01dc3f8981f5ed4e2ec6ee048d1ecde725989fbdd38ab9488f6672faebb0bc50c7200e95f3f8a97be7b783a762fdbdccb158a9423943920f43e98aeda72f70e6aabf7a73df6b95cba860a4924e6bc89cf53d584423be24056007383fe359a99b389e85a26aaf1fc1dd6936318df5bb50c7233c23153fe7d4577e166b988a8bdd3a2f803ac4527c57d119815587ce9f9249f9617ce7f3af5b132bc4e4a11f78f315bf898b3a9521a472491c80589feb5e1d36accefa8885e789dc2ec52c4f279e9eb493bb31822fe910c6b04a546ecb0014f4e33c8af4f0eed13ccc5ea66dcc514faa6012c03f5ef915caf599bd3d291aec06eec0280067bfb57a31d0f364438dc724617bf615a19262848d9f70c903a80dc5673d0e85a94aee413cc1509e064e064000f35e6ce47a34a062ea3219a72a0ee09c0c0c67deb86a7beceeb591b9a5e984db820853b796cf3f4af570f4b43cda952ccd7655b7818155cb0c73d4d74d4d0ca2f98c8fb509ee3cb28768c7007240ee3f3af231133d5c3c4d27c1de04b80a010339e2bcbe53ba22c4a55439c6d3d3159ca26acb792e40249c2f0734e31b1096856b870ce376e231d475a526540aa5829249c77cf7a6c25b966d24d91ae1703390076a945ec8fb43f604d61c78a2fb4e46221b8d3a40177b60babab2fcbd3a3373d6b97171bab9d745dd1cd7ed97e049fc15f16e2d7e2840b0f12282c546156ea3501bf16521bf035f3f515f53ba8bd4f34d16f08d8180ddfdd1debcdc423daa6753a69652503fcd8dd82071eb8ae2e87725734279336e090a580da463b0ef9acad73746599371cee357620edede5890baa22e376e21ce083ed9ac24ca7a12a0251c0236b1cb1c73f4f6ae394816a3a190aa305380dd463ef01ef4c8486ac8a9c2a0543c9c3735a47529a19bc3f2eb81cf231cf35135a98b29ddcbb642402630dc21ee2baa9ec2452bd45b87f94943c1c1e98f4ad56a398c8dcc78180a48c138e2aec672d87c4f229640c0673924f5f4a97a86c2ca77f1c2b80a4e391923a5091a2322f2e446ee4e4639e3a115ad35739a4ecce675fbb48ad2660c5fcb52c18ff000e6bd2a34eece3c554b44f0ad4eec8bf76625833139f51e95fa0e163cb03f3dc4cf999917b3a7da008c8c63238e8338a69fbc3a4b42d18d45b64f1f2e720e73fe15e8db432a9ab329b3b8bf7ef5c32dcb8ad09125562a5b25402781d80fe9444a48e9f5cb44b0d1b4ab50bfbd78fed323f4e5cfca319ce36815df4e26132832bdbf87a7218e66995460f0546491f9d2acac8bb6860ac9c93b8807208fef0af2dee69487c69be4070180ff6ab6a4b52aa6c5dbb94085405d8381ffd7aef93b238e0aec34f475190413efe9554a3eedcceb4ac6b5bb074c3019ae85aa382f709ad915770215c1cf15a241177182ededd55dc863dc77c50cd2088df518e6889652801e1700fe55329ab1d2a162a49750c8490781e83ad722926ca8ad4cfb9b9440c172c5860573ce5a1bd345550b6e772fcce41c03c57128dddce87a0c557ba982a8cb12001ea7b5293e77612d8b57da44ba7ce609136cca312230e51bbafd6b7542cae4736a593a7c967a50b82c104cdb106d3b9f03939f41d2abd9f2c6e526662b3ae597838c1c0e94f9bdd2995da36c743c75af2a749a57299734ad3e4d4f5182dd07cd23019238519e49f602ba2952729244dce9f53d552faf562807fa2400410ae7219578dd8f53d6be8609357466d9abe29860f0ef86adf428be4beba2b777e0820819f9139f6f9bf2ae3acf9b489513817876b103a93c1cf4af3dd3d0d1487da5a34b722323ebc74f7a9a747998a53b1ada84ab6b6c23427d07a9f7af5a56a71e547241f3b1348b6539958927b13dcd450a6f9b98cebcb95686f59dc185f28e536f3c1c57aea5a1e3c9dcd33ae492444dd225c2150a4bae4e33c63d2aa9ee424326b8d385af9a8248ee14e1a2cee461e83d3ebf4ad6fa95c826aaba41d390457725b4edcb2ccbc7d41fceb8f113b23bb0f4f53926b712380d3aed2d85238ce2bc977b1edf2d8f51d1bc211e89f0e3edff6b825d47c44e62862c80f1dac4c4337afccfc647615d983873bd4968b571e13d4fc37f0635ad4c5b389356bf874b8658db978d4177031d14e403f4ad319a2b2153573cca2b8bbd3cbb23c96db1be5e48f6fc6bcda7789b2dcd8b0f176a72225acb74f3c25be58e450e013e9f5aeca724ea239aabb45b3a1f117898ae8ed6d3da417024da55c8dacbc8cf23d857ad8992e43c1c2fbd5d999a19d1a7b66596092dd8c8c56489b2ac0ff005a8c225634c7cb535ffe11ed36f194db6a015c80424bf2e31ea6ba6547999e4a9684a3c0f7b926dd1674c71b0f427f9d53a43e7332fb41bdb04432db480839240e7ffd55938346d128caad2331c90c40e09c118acde8755c63b972a381ebeb9acdb3689cbeb603de0527241af1315f11ece14d258becd6f1b801c88f38c700915d94fe03cfacbf7c731b19b86fd7bd79135a9ead324584b23163820f5eb5314749e8b6b0c76df052e642b89aef5d8e25603eeaa445998f63d715d787d2467557ba5cf82a041e36694012f95a7de36ef41e4377ec79cd7a78895a063868dd9e571ee8e35233b986464f15e042a68744d5c9256738202ee2bd455427764246b68f295b290ee1b864019ef5ebd27ee9e3d757652d39fced4ce3e5209f6cd453d647528da91a4f216ce0962bd47a576dec797288d672ea09e09ebed5a295c8b123b24768cc77163e8062b2ad23a6946ec345b6f2167ba931b023385ddc10064e7f1af3f7dcf4e2ac63db20babd44891412d8dcc723ad73d35791ad47689d3456c209033c8085046c5eff00d2bde83e58e87cf5593e6296a97e55a4298e9856233c9e2b92b48ecc3ab9422ba2d346fe632b2aed6fef73d71ec6bc6acee7bb49729afba3312b448dbca92c7a8fafd2b036888a00910b3ef5201e9806a24548bf048ac6505728385fa532d2d065dc6b19001fbc060771536b931dccd31f96d9269c90dee5a84bf7070ac00f715984f63e8cfd8dbc691f85fe2be8626bb8ada2babb4b56f373862e0a819ec49c54e2237a3737c3bd0fad3f6e0f87d378c3e16cd73630f997fa4486fd4282598229dca3eabbb9f6af9cb5e077d37a9f08683768de53800ee55e5870411c62bcaafa9ecd391d7d9b1db1b2057c60fdecee1dcfd2b8f9743d2832dcf31319727e5ce36f7fc294626b27632a4bc3bce7afe15bf20267a2bc40c59923576c6738e507a8af16acb535b0f8d0a9c444ae01ce38cf15c72626c976cb648a83e7619fb9e98ed5d11f84945695d0a2a0dd1290082cbd73efde9d37a943232308918071919e9baaa4b533657c82ac0ee5604e54f35bc74466d15a7b9668800790d8c8ea6ae3a0e457333328240ce781838aea8ea60396e511b07730ce081d01f4cd2b13cc30dc85f9870092071d3f1aab1a2918ba8c83cdda39279dc4f4f5e29c11cf37a9c2f8f7545834b9447200e78e477f4afa0c1c2e7858e9d91e4067f3a47c924a8ce3db1d6bece9fbb13e4a71bb32ee8472dd801c120e0e0738ac14af2374ac89ae6331c384202af182719af4e6ec726eca2b7040e54673d7b62b8ea33a22b42de9d626f2fe38172de6908bb4f04938c55d38a0b591b5af43243aacd0155678818f2a738c718f7af4a2ac8e56f51fe24b75b1b5d32cc9fdeac6669548c6c66e80fa9c01f9d73d465c7530190302b8e4023a571346f6b16ecec19ba8381c920735d74299cf526b6197a8c6658b9247a8c5693566630958bf0dbb24401003018e3d2bad46c8f3eaceec9d5763658819ef9eb4415999a5744934caab939e7dbb55b7612899b73722550d9ebc0fc2b2723ae11b14e64664120419e8a4d73557ca8ec8096b6be7390cdb4630001dea70f1e6d4caacac17d6c1670abf30039fad67559745e842a80bf4cfd7b573c0eb4751f0db4986e7c4b1dc5cb08ed6c55af2660d83b53a007ddb6d6918d9dfb19c998ea6e3c45ae67979eea72ccce7392c724b1f4aea93baf530dc3c55a9c9717a968180b7b15f2225006300924e7be4926b9e72b9ac519b1c802a807a8c1a98b37e8284553b88069a5722f63a7d06d134ad0ae3517c9bbbb736d6e07044631e6383df9217f3aedc3d2b3b9cd39d8dbf047872d2ef52b8d46f9d62d334985afa718ff5a41f963cfab311f866bb24b950a32b9c56bf7b79aa6a5737d72c44b712176600e393d39ec0715e2cd3e63ad32a79db58657191d7154b41799d1e916aaf6cd2b1c6f1c7ad77d28e973cbc454d6c8cfbfb437da81442a001b46d18047ad370f68ceaa4fd9c75352dec4c116c20fcaa07d6bb55251478d5ab7bc5948847cb00483bb15763372b8810cc70075eb8a2d61a772be434ac8c70aab91e80fa5439591ac758d8c4d56e45dcf9c1745e16bc5c44eecf73070b44a88af26238b21d88545f424e00fc6b9252d2c7733d37e2cbc1a678b21d12dc235ae8d636b641133f2c81374983ebb98e6bd6c2e97662c7f8a7c57aae8df0dfc13a65b6a3710c32b5d6a0f16ec2ee2fb01e7d8572626abf686915a1ca5bf8eae5302e60b7b94cf475c9350abd98ada9b5a4ebb61aeeb092c5a1c36c1222ec88e5865467773dfdabb70f539aa5ce3c57f0ac3bc43a96857b756d01696c76a167254b8e831ef5df5eaa93b1e6e0e935791b361e178bfb2a26b7d4adda429f342c0ab1c9e327a5755156470e32779105e7876fed943bc0d201d5a1f9be99aeb89c8968436f7779a7b21479adf6fcc0062adf8545d916d4d63e35d4e08d5a4b9f3c93c2ce377e78a5cc75a8ab083c6d0cb233dd69b6f300a0021412c7d0f1fd6b172229bd49e2d6741bc2cf3e9460661f29831b54fb83dbae3153ab3b398e4f5bb5f0d5ceaac51aea2888001618391d78c9e3debc9ad49739ebe19b51b9b5a8785f4a3a733d96af6f215886570c368e8724f53f415d5ec946079f5ea375d23897f075e8dbe498ae039f9595c0fcf3d2bc5952933de8bd08aefc2fa9d94799ed5e38f76d3c6467b74a9f63246ea476de23b6974cf83fe09b77428d77757b7455b20e3851f5e86b7c2c5f319d5d83e1986b5b1f16ea2871f60d0e76076e4ab395453e99e4f5ae9c64da562283b33ca3710c9ce70bdba62be7db48dd91492a9c107bf4a719c5126bda01058b3827919af6e12b523c7aaaf58874e40e24739e4e0d3c3f73a2b4acac5dc88db392171c9edf5aea72679af52761e63ed0029603be49f7ad14999d8875178e18db602cc1b6039f4ed5c55a5a1df4117d6d8c3a3a4f2b315fb332b0dd80497e091fa5652fe19db2464e8e60698ac487cc04b6f278c51875733afb1b6c0246707e6c74f4af4e3a1e2cbde7a18d70a4b3cb282557390bc0cf6af36bcf53d6a11b11e9c3e705c07dcbc8c74e7a579d2b48f596c6ac6db830e636663c0e9b7b2d66de86f0d8955de528a3cb5dabb57238c76e9582dc97b972dc98b6920380093b47f9e2b545a7a1348fd72a1891819ed4db256e6595f9c8ee3939ace4eecd1a25b620b0627d7354f627a1e93f01ec4eb7f133c3d6114570f712df44d0790c0307460e1b9ec36f3ed9acea7f08d28ab1facde26b48b5bd22fa19e2df1cf11561d410ca548fd6be69753b61a33f2b35bd01fc07e2ed6fc3f76479fa55e496a39dc0aab654ff00df256b8a703d58bd4d5b0bd0f182a0918fc2bcf9ab33d252d0b0d73219155cb1524fcc00c03e98ebf8d68958b73b95f76d27e446c9ce58f35d0915cc7ad35ba6d57756fc0f51ed5f293d4ec6c8f7a1e55c1c73c1152d68525721bb9040543820363183b8faf6ade9ad0cfa95def231204392158aa923b67b629c55994452dc42b233162dfddc74fc6ab764114d75130015887eedea7d2bab97426c54773b4800618f2c7b5264c8cf323a12a0700fae73ef5d1030227ba217682362e4800f39279ad2c60466f982b210bb0f2703be3ad558b899baadca7925d4824719c7b56d0a4ee65559e57f103ccba485806da1b2d83c66beaf0140f96cc2679ecd238572a082bd72b8cd7d14972c4f156accfb384cb7395033ee2b92946f23593b21f78e436c233ea2ba6737739d440c493e17201038c516e646d7b1d1783ac1ac279b569b2b058aef8895f95e520855faff0017e155469c96a26f42df862d7fb5f529aeaf242b6d029b9b976248600e7693d7e63c7e35e8f372a3952bb31f59d4bfb62fa7b8236976f94649daa3a2827b018ae094ee6b089492272420040ec33d5bd69c2371cdd8d98a36d36ccbb364e38f526bbfe0479755be62b69a86eae99dd4900924f519f4a705cec6f489b0d1a0190315d55343cf9ad4cfbc996250c002b9e9eb5949d8e982d0a129f38819f989e48e98ec2a24ca4ac3e3b4da4e41207e9492b8395886f490153031e82b0aeaeec6f42572cda26d8119f38e76e3d6b7a4b922655ccfbacc933153803393e86bcc9bb9db4559106e647241c1c734a26b7b33afd1d574df875ab5db6d136a332d94649f98a2e19c8f6ced15dd4a3ccbd4ca656f0cff00c4a74cd57593ba3f2221040460869a4c80a73ce36863c544bddd3b0415ce4cdb955e464f6f6ae4b5cd6d621427760673db152f42d6c685969cf752c36f182659582a81d89aed847538f9ceb35199649e3b48c6d82d544510072011f7883df2d935ed420a3139a6ee6febb28f0f7c3fd374b8541bcd61c6a377b8e0c71292b12e3a82c72d5e7e227d0aa6cf3e58cce40c1c1eec72335c76bea775c962b34bb2b0fa12372d6f0a7cd2319cf9626cde5a49a75a623390a30093cfff005cd76558fb38e87970fdecccfd0a271712338ca85e09f5a9c341ee756265c8ac8e9eddc18be74e0900647415dfcade87cf4eeddc7cf625d57cbc631dc835491b27ee905d5b9b6b6779005dbf99fcaa26ac145dd983761e3b477209771c7bd79d56563b29eb52c61468c480410476af2e4b999f454df2e874bf0eb49fedaf1f7876cc83b65be8b77a00adb893f82d60d7be91b498be31d546ade27d66fce584d77338e771dbb8ed19efc57bb4a3cb04ce7722c7c515167a968ba648e3ccd3f4ab785c1182ac4172a4763f374af1ab34ea1d317a1c81518241ce3818f5a89a5715f5377c34ab1c57121058ed0aa57a03d79af4b074efa9e663656562acb7624d5d4e72030073ed5329375ac5d2f7695cea85d44ea0c4721477fad7b109d91f3f5d73489edf5abdb48d5a1bb9220a71b41c2fe55ac6a1d10a49c4d3b5f1d5cc2ac2e22b6bb1d8c908c8cf604734fdadcc6742cc9adfc4da25e8c5e6927cc048251f00fe955ce989a690e597c3970d832cb6dbba646e1cfd0567cc85044c9a1694ed8b6d5d1c8e42347b0afd4d6a9ab1ab395d57c1f732ead28b59adee56270a0ab81dba0f6af1aac5fb4b1efd0b7b32f6bda1ea363a64b9b69559555463af27b1fd715dd88838d3d0f2d7bd8938afdfda4a4032211ce464106bc18b99f4562da78a353b772c6e5df0bb7127ce071d7eb53394d08f5cf89be2cb88f4cf87da6dfc305c1b0d0639042d105e6562c49ffeb7bd7ab82b3415361be1af136856bf09be24cb2e97243713da5ad9a496ee0e7cc972411c71f29ae6c6b5733a5b9e39716fa248c4c72cb01ea04996c71d3eb5c3285337dca0f616831b2e48e3272b9c566a9d3b932d11a179a4c30698765c838008edc7e35e94e3cb03c98be6a972ce95a6d9ad9232cbf33062c4fd78c57461a1ee8ebef62c2db59a8c24a590762319addd8e093275489f6c56f1379af80475c9ab562148cabef2a2d3e22599e4690e40030a33c73ebeb5e4563d9a08940377a392467f72cb83c0187ce7fef9205293fdd9d33451f0fc6448e541dc3e520ff773ff00d6adb0a8e6c47c26ddd8115bb9420c8480067a5764b43c9a1ef48e675bbe431a5ac2c0804b3953d58f6fc0d7858caaba1f4108d88ac58f9499f5c1af3a2dc8ec46c798496949c72496edd6b4be86f0d897224dac03138ce4f4fc2a22f525ee6a589657c392a586067a1ad11298e95970990413f2e4720d26c23b94a7f91790339e9deb3bdd9d0d15f718d8807018648c74ad64f4333bff00827e223e15f8a1e16d504be42db6a56f9718ca82e14e33ecc6a27fc265c1eb63f62a565959c0c32499c0ecc0ff00faebe6de8d9e838e87e71fed85e1b87c3df1fae67811a28b53b182e0aa9f959d772b37d4e054c96876d37a1e716f7a162c22839185c9ae09c353aa9c9b2d5b4a59e325d97284360e71ec4d4346c98f176c836b00c471904d6a9686a7ae33ca1f26458f938555e0e7d3bd7c7d4f88f55ab15a4b642ac4e38c900607ff00aaadad0a8c8af36270083b59142a8cf4edd2b6a68c599fbd8cc4903209ef819f6ada5126e31f72bb3e42e3901791ff00eba74e0547528cd70449820118e9ef5d96b1a3239672aaa09c6793e829389c9329cf7077a924f39e01ebe95a5289915a49bcb405c82467903902ae31f7cc6441f6b001273cf46ce49aeb8c7de25199a9dd308dc1202950793cfd715d718fbc615676479dead7ac6e70497543f773c115f67828da27c663a77673de228609d375b0dd211f3459e87fad77d68f39e7c27a18cb6a74c8e367421892319e41edfad6318f22296accc676bd90be42b138c7bfe35c9527cc74c4d2d0f439f58bbf2a3c224637cb3c876a44a3f898f41ec3a9aba11e66123a0b8bcfed15b6d2b4c8d8da47c286e1e690fde76f407f402bd4fb1c871cdea52f125cdae9b68346b025ca906f6e4312b338e42af3caa9efdcf35c356a7bbeccda2ae8c08640cc100c1c00403d4fad67177611d0dbd220c3899c0dbd06ff00d4d7ad463a1c35e7fbd23d42e8df5cac41f6c6adc0c704fad3a8f99828e85c8e64b18484c608c938ea7bd6b097223965b956e35725c88c73db1d853a93b89c4a05de562c589ef823ad72deecd628b56ecbb431186248391fcaad6e4b2f298dd41f30291c10075ae96ec8c199572c5ee4aa1033c64738ae197bd23b692e4897ee675b3b421f1c2f1ee6ba652e4898463cf330d240c3711b07619cd79299e946361225324c150e5988503ae4f61574f565545a1d4f8ce68ede2d274785d5934ab6db294fe299cef739ee4640fc2bd28fbb34617ba23f1291a7681a368e1f12b2b6a371b4ff001bf08ac3d42aff00e3d5c95f5922a9ee73449d84ed2d9eddeb05b1ab61636e27970cadcfe95b5285cc9c8eafc32b1da5d5eea24895ed63d90c4dfc5238db93ecaa4b7d40af469475389e86df827c3c9e25f155858ca596db7196660d82b1a0dcc727d863f1aef9bb448dcc6f19788bfe120f12dfea20048e76db146ac76ac6bf2aa8f40140fd6bc49bbb35a71b19bb7705c909c7000ea6a59d5b17b41b70924af9de1411b47a918e7e9d6bd2c32d0f3b132d09b5ebc134b1a602851fc3efda9d67a986156b735b4cb38a3b14258172b923d33d8d76518591c95ea5e4c7cb64368d8c428200e7bfa568d6a71295e2c8025cc5232862300fe028b1ba774417ba83cf024400562725b19ce08e2b3aa5416a61eb97ca2e44783c0270062bc4c44b53d9c153d2e504bb8991463904f278e3fad62de87a4d599e89f044dbc5e30bbd4ddc46ba4e95777bf30003108554127a72df9d6495da0bfba627843c33ff00095788f4ed3b014dccd1ab0ce33960589f4e335eeb56a273d3d64677c46d407893c7baeea11b0749ae58465390117e553f92fe75f3f53591d6d9cf8b76950027685fd694e37408ea3c35649fd9ae031fdeb16271e9c0f6c706bdec142d499e462e5ef9ce3a32eaafb14101f001ebd6bce4bf7acea52fdd9d05acc514a80b9f4ea2bd55b1e44b724794fcc9bb8f4c5386e6d0761aa48562485270aab9c96e2a98a6246ad21cb8191c120918f6a946289cca6272ab94079f949f9a8668f41de76d5c92c5bb607345c4b5673fe7c8fac6e466043e30ac718e95e239b9d747bf0d291d0eb1e26d42dac561fb5bb8dc0853d3815eae26b72c6c793415eab31e3f17dc73e724536390197b7a1af1dd5f74fa1b0e935bb0bd8de392c446ccbb72ac473eb4a557dc22c7ad7c6f9bc3fa8f8e238adeea5b6fb2699656bb2452426d881623d7e626bd7c24bdd64d53226f0e2597c16d62e20d46d2e3ed9ad5b5b001f6b00a8586e07dc9e9eb5c78c57687491e4f2e8b764b158c31ce090c327f0ae2953f7516f7205d32e0cb189108c90a083c9e7d3350e1ef222a6c69ead6132470442224ee2083c74aeeaab4479f4bb97acf4c9248628906d761850cf81c75f6af42947630aceec996c251111be3f979e0e4e3b8aa51b1c9d057ba785d5e02546dc839e41c734a4ec63d4c2b862d661548243104015e54f53dca0cdad019a4d10fcbb9c4ef032e46595a3240c9ec0ad38eaac75cd157c3c515259429c920648fba07ff00aeba30ead13c9c5c89b5fd422b3b762b82ec3000f5a3135796067858dce2959a59b71e589cfe35f2f525cccfa0a6ac69427f7eaa1b0c00241ad63b1ab669c7213b8ba613dba50cde9bd0b71ef5382e48c77fe542225b96609918807391900e7bd116645af3c2a70fc018da455391454ba901604215c80791dfdab26ec6ac81242af92d9627927b8ada0496f4d95a39e29012e525128603ee9072a71ec40a24bdd66b03f673e1feae3c49e07f0e6abe7fcd7ba7412ee23ab14193f9d7cdd45ef33d08ec7ca3ff000501f0bb2d9f87fc45080d259dd0b391978f96543b4b7b6e5fd69b5fbb368b3e53b7bc472523040c8c31ed5cf51688eaa4cbb1dd10cc0e76962703a0358f29d912592f115dbae49c9fad1ca6e7ffd9);

-- --------------------------------------------------------

--
-- Table structure for table `sys_lockdate`
--

CREATE TABLE `sys_lockdate` (
  `_id` int(11) NOT NULL,
  `_date` date NOT NULL,
  `_day` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_lockdate`
--

INSERT INTO `sys_lockdate` (`_id`, `_date`, `_day`) VALUES
(1, '2011-12-31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sys_menu`
--

CREATE TABLE `sys_menu` (
  `_id` int(11) NOT NULL,
  `_parents` int(11) NOT NULL,
  `_name` varchar(100) NOT NULL,
  `_setting` longtext DEFAULT NULL,
  `_type` varchar(10) DEFAULT NULL,
  `_table` varchar(100) DEFAULT NULL,
  `_formid` int(11) NOT NULL DEFAULT 0,
  `_sort` varchar(40) DEFAULT NULL,
  `_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_option`
--

CREATE TABLE `sys_option` (
  `_id` int(11) NOT NULL,
  `_app` varchar(10) NOT NULL,
  `_group` varchar(20) NOT NULL,
  `_masterkey` varchar(20) NOT NULL,
  `_caption` varchar(100) NOT NULL,
  `_value` longtext NOT NULL,
  `_type` varchar(15) NOT NULL,
  `_note` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_option`
--

INSERT INTO `sys_option` (`_id`, `_app`, `_group`, `_masterkey`, `_caption`, `_value`, `_type`, `_note`) VALUES
(0, 'all', 'Global', 'logo', 'Company Logo :', '', 'image', 'Formats Supported are jpg/png/gif<br/>Recommended maximum size is 100 X 100 Pixels<br/>Company logo is printed on specific reports and vouchers only.'),
(1, 'all', 'Global', 'address', 'Address :', '', 'text', 'e.g. Home No. #543, Road No. #23, Gulshan, Dhaka.'),
(2, 'all', 'Global', 'phone', 'Phone :', '', 'text', 'e.g. Phone: 140XXXXXX, Mobile: +88017XXXXXXXX, Fax: +123XXXXXX'),
(3, 'all', 'Global', 'financialyear', 'Financial Year Start :', '2016-01-01', 'date', ''),
(4, 'all', 'Global', 'dateformat', 'Date Format :', '7', 'list', '6=MM/DD/YYYY|7=DD/MM/YYYY'),
(5, 'all', 'Global', 'currencyformat', 'Currency Format :', '2', 'list', '0=Normal (100000.00)|1=Thousands Splitter (100,000.00)|2=Hundred Splitter (1,00,000.00)'),
(6, 'all', 'Global', 'time_zone', 'Time Zone :', 'Asia/Dhaka', 'list', 'Pacific/Pago_Pago=GMT-11:00 Pacific/Pago_Pago|Pacific/Niue=GMT-11:00 Pacific/Niue|Pacific/Midway=GMT-11:00 Pacific/Midway|Pacific/Tahiti=GMT-10:00 Pacific/Tahiti|Pacific/Rarotonga=GMT-10:00 Pacific/Rarotonga|Pacific/Honolulu=GMT-10:00 Pacific/Honolulu|Pacific/Marquesas=GMT-09:30 Pacific/Marquesas|Pacific/Gambier=GMT-09:00 Pacific/Gambier|America/Adak=GMT-09:00 America/Adak|Pacific/Pitcairn=GMT-08:00 Pacific/Pitcairn|America/Yakutat=GMT-08:00 America/Yakutat|America/Sitka=GMT-08:00 America/Sitka|America/Nome=GMT-08:00 America/Nome|America/Metlakatla=GMT-08:00 America/Metlakatla|America/Juneau=GMT-08:00 America/Juneau|America/Anchorage=GMT-08:00 America/Anchorage|America/Whitehorse=GMT-07:00 America/Whitehorse|America/Vancouver=GMT-07:00 America/Vancouver|America/Tijuana=GMT-07:00 America/Tijuana|America/Phoenix=GMT-07:00 America/Phoenix|America/Mazatlan=GMT-07:00 America/Mazatlan|America/Los_Angeles=GMT-07:00 America/Los_Angeles|America/Hermosillo=GMT-07:00 America/Hermosillo|America/Fort_Nelson=GMT-07:00 America/Fort_Nelson|America/Dawson_Creek=GMT-07:00 America/Dawson_Creek|America/Dawson=GMT-07:00 America/Dawson|America/Creston=GMT-07:00 America/Creston|America/Chihuahua=GMT-07:00 America/Chihuahua|Pacific/Galapagos=GMT-06:00 Pacific/Galapagos|America/Yellowknife=GMT-06:00 America/Yellowknife|America/Tegucigalpa=GMT-06:00 America/Tegucigalpa|America/Swift_Current=GMT-06:00 America/Swift_Current|America/Regina=GMT-06:00 America/Regina|America/Ojinaga=GMT-06:00 America/Ojinaga|America/Monterrey=GMT-06:00 America/Monterrey|America/Mexico_City=GMT-06:00 America/Mexico_City|America/Merida=GMT-06:00 America/Merida|America/Managua=GMT-06:00 America/Managua|America/Inuvik=GMT-06:00 America/Inuvik|America/Guatemala=GMT-06:00 America/Guatemala|America/El_Salvador=GMT-06:00 America/El_Salvador|America/Edmonton=GMT-06:00 America/Edmonton|America/Denver=GMT-06:00 America/Denver|America/Costa_Rica=GMT-06:00 America/Costa_Rica|America/Cambridge_Bay=GMT-06:00 America/Cambridge_Bay|America/Boise=GMT-06:00 America/Boise|America/Belize=GMT-06:00 America/Belize|America/Bahia_Banderas=GMT-06:00 America/Bahia_Banderas|Pacific/Easter=GMT-05:00 Pacific/Easter|America/Winnipeg=GMT-05:00 America/Winnipeg|America/Rio_Branco=GMT-05:00 America/Rio_Branco|America/Resolute=GMT-05:00 America/Resolute|America/Rankin_Inlet=GMT-05:00 America/Rankin_Inlet|America/Rainy_River=GMT-05:00 America/Rainy_River|America/Panama=GMT-05:00 America/Panama|America/North_Dakota/New_Salem=GMT-05:00 America/North_Dakota/New_Salem|America/North_Dakota/Center=GMT-05:00 America/North_Dakota/Center|America/North_Dakota/Beulah=GMT-05:00 America/North_Dakota/Beulah|America/Menominee=GMT-05:00 America/Menominee|America/Matamoros=GMT-05:00 America/Matamoros|America/Lima=GMT-05:00 America/Lima|America/Jamaica=GMT-05:00 America/Jamaica|America/Indiana/Tell_City=GMT-05:00 America/Indiana/Tell_City|America/Indiana/Knox=GMT-05:00 America/Indiana/Knox|America/Guayaquil=GMT-05:00 America/Guayaquil|America/Eirunepe=GMT-05:00 America/Eirunepe|America/Chicago=GMT-05:00 America/Chicago|America/Cayman=GMT-05:00 America/Cayman|America/Cancun=GMT-05:00 America/Cancun|America/Bogota=GMT-05:00 America/Bogota|America/Atikokan=GMT-05:00 America/Atikokan|America/Tortola=GMT-04:00 America/Tortola|America/Toronto=GMT-04:00 America/Toronto|America/Thunder_Bay=GMT-04:00 America/Thunder_Bay|America/St_Vincent=GMT-04:00 America/St_Vincent|America/St_Thomas=GMT-04:00 America/St_Thomas|America/St_Lucia=GMT-04:00 America/St_Lucia|America/St_Kitts=GMT-04:00 America/St_Kitts|America/St_Barthelemy=GMT-04:00 America/St_Barthelemy|America/Santo_Domingo=GMT-04:00 America/Santo_Domingo|America/Puerto_Rico=GMT-04:00 America/Puerto_Rico|America/Porto_Velho=GMT-04:00 America/Porto_Velho|America/Port-au-Prince=GMT-04:00 America/Port-au-Prince|America/Port_of_Spain=GMT-04:00 America/Port_of_Spain|America/Pangnirtung=GMT-04:00 America/Pangnirtung|America/Nipigon=GMT-04:00 America/Nipigon|America/New_York=GMT-04:00 America/New_York|America/Nassau=GMT-04:00 America/Nassau|America/Montserrat=GMT-04:00 America/Montserrat|America/Martinique=GMT-04:00 America/Martinique|America/Marigot=GMT-04:00 America/Marigot|America/Manaus=GMT-04:00 America/Manaus|America/Lower_Princes=GMT-04:00 America/Lower_Princes|America/La_Paz=GMT-04:00 America/La_Paz|America/Kralendijk=GMT-04:00 America/Kralendijk|America/Kentucky/Monticello=GMT-04:00 America/Kentucky/Monticello|America/Kentucky/Louisville=GMT-04:00 America/Kentucky/Louisville|America/Iqaluit=GMT-04:00 America/Iqaluit|America/Indiana/Winamac=GMT-04:00 America/Indiana/Winamac|America/Indiana/Vincennes=GMT-04:00 America/Indiana/Vincennes|America/Indiana/Vevay=GMT-04:00 America/Indiana/Vevay|America/Indiana/Petersburg=GMT-04:00 America/Indiana/Petersburg|America/Indiana/Marengo=GMT-04:00 America/Indiana/Marengo|America/Indiana/Indianapolis=GMT-04:00 America/Indiana/Indianapolis|America/Havana=GMT-04:00 America/Havana|America/Guyana=GMT-04:00 America/Guyana|America/Guadeloupe=GMT-04:00 America/Guadeloupe|America/Grenada=GMT-04:00 America/Grenada|America/Grand_Turk=GMT-04:00 America/Grand_Turk|America/Dominica=GMT-04:00 America/Dominica|America/Detroit=GMT-04:00 America/Detroit|America/Curacao=GMT-04:00 America/Curacao|America/Cuiaba=GMT-04:00 America/Cuiaba|America/Caracas=GMT-04:00 America/Caracas|America/Campo_Grande=GMT-04:00 America/Campo_Grande|America/Boa_Vista=GMT-04:00 America/Boa_Vista|America/Blanc-Sablon=GMT-04:00 America/Blanc-Sablon|America/Barbados=GMT-04:00 America/Barbados|America/Aruba=GMT-04:00 America/Aruba|America/Antigua=GMT-04:00 America/Antigua|America/Anguilla=GMT-04:00 America/Anguilla|Atlantic/Stanley=GMT-03:00 Atlantic/Stanley|Atlantic/Bermuda=GMT-03:00 Atlantic/Bermuda|Antarctica/Rothera=GMT-03:00 Antarctica/Rothera|Antarctica/Palmer=GMT-03:00 Antarctica/Palmer|America/Thule=GMT-03:00 America/Thule|America/Sao_Paulo=GMT-03:00 America/Sao_Paulo|America/Santiago=GMT-03:00 America/Santiago|America/Santarem=GMT-03:00 America/Santarem|America/Recife=GMT-03:00 America/Recife|America/Punta_Arenas=GMT-03:00 America/Punta_Arenas|America/Paramaribo=GMT-03:00 America/Paramaribo|America/Montevideo=GMT-03:00 America/Montevideo|America/Moncton=GMT-03:00 America/Moncton|America/Maceio=GMT-03:00 America/Maceio|America/Halifax=GMT-03:00 America/Halifax|America/Goose_Bay=GMT-03:00 America/Goose_Bay|America/Godthab=GMT-03:00 America/Godthab|America/Glace_Bay=GMT-03:00 America/Glace_Bay|America/Fortaleza=GMT-03:00 America/Fortaleza|America/Cayenne=GMT-03:00 America/Cayenne|America/Belem=GMT-03:00 America/Belem|America/Bahia=GMT-03:00 America/Bahia|America/Asuncion=GMT-03:00 America/Asuncion|America/Argentina/Ushuaia=GMT-03:00 America/Argentina/Ushuaia|America/Argentina/Tucuman=GMT-03:00 America/Argentina/Tucuman|America/Argentina/San_Luis=GMT-03:00 America/Argentina/San_Luis|America/Argentina/San_Juan=GMT-03:00 America/Argentina/San_Juan|America/Argentina/Salta=GMT-03:00 America/Argentina/Salta|America/Argentina/Rio_Gallegos=GMT-03:00 America/Argentina/Rio_Gallegos|America/Argentina/Mendoza=GMT-03:00 America/Argentina/Mendoza|America/Argentina/La_Rioja=GMT-03:00 America/Argentina/La_Rioja|America/Argentina/Jujuy=GMT-03:00 America/Argentina/Jujuy|America/Argentina/Cordoba=GMT-03:00 America/Argentina/Cordoba|America/Argentina/Catamarca=GMT-03:00 America/Argentina/Catamarca|America/Argentina/Buenos_Aires=GMT-03:00 America/Argentina/Buenos_Aires|America/Araguaina=GMT-03:00 America/Araguaina|America/St_Johns=GMT-02:30 America/St_Johns|Atlantic/South_Georgia=GMT-02:00 Atlantic/South_Georgia|America/Noronha=GMT-02:00 America/Noronha|America/Miquelon=GMT-02:00 America/Miquelon|Atlantic/Cape_Verde=GMT-01:00 Atlantic/Cape_Verde|Atlantic/Azores=GMT-01:00 Atlantic/Azores|America/Scoresbysund=GMT-01:00 America/Scoresbysund|Africa/Abidjan=GMT+00:00 Africa/Abidjan|Africa/Accra=GMT+00:00 Africa/Accra|Africa/Bamako=GMT+00:00 Africa/Bamako|Africa/Banjul=GMT+00:00 Africa/Banjul|Africa/Bissau=GMT+00:00 Africa/Bissau|Africa/Casablanca=GMT+00:00 Africa/Casablanca|Africa/Conakry=GMT+00:00 Africa/Conakry|Africa/Dakar=GMT+00:00 Africa/Dakar|Africa/El_Aaiun=GMT+00:00 Africa/El_Aaiun|Africa/Freetown=GMT+00:00 Africa/Freetown|Africa/Lome=GMT+00:00 Africa/Lome|Africa/Monrovia=GMT+00:00 Africa/Monrovia|Africa/Nouakchott=GMT+00:00 Africa/Nouakchott|Africa/Ouagadougou=GMT+00:00 Africa/Ouagadougou|America/Danmarkshavn=GMT+00:00 America/Danmarkshavn|Antarctica/Troll=GMT+00:00 Antarctica/Troll|Atlantic/Canary=GMT+00:00 Atlantic/Canary|Atlantic/Faroe=GMT+00:00 Atlantic/Faroe|Atlantic/Madeira=GMT+00:00 Atlantic/Madeira|Atlantic/Reykjavik=GMT+00:00 Atlantic/Reykjavik|Atlantic/St_Helena=GMT+00:00 Atlantic/St_Helena|Europe/Dublin=GMT+00:00 Europe/Dublin|Europe/Guernsey=GMT+00:00 Europe/Guernsey|Europe/Isle_of_Man=GMT+00:00 Europe/Isle_of_Man|Europe/Jersey=GMT+00:00 Europe/Jersey|Europe/Lisbon=GMT+00:00 Europe/Lisbon|Europe/London=GMT+00:00 Europe/London|UTC=GMT+00:00 UTC|Africa/Algiers=GMT+01:00 Africa/Algiers|Africa/Bangui=GMT+01:00 Africa/Bangui|Africa/Brazzaville=GMT+01:00 Africa/Brazzaville|Africa/Ceuta=GMT+01:00 Africa/Ceuta|Africa/Douala=GMT+01:00 Africa/Douala|Africa/Kinshasa=GMT+01:00 Africa/Kinshasa|Africa/Lagos=GMT+01:00 Africa/Lagos|Africa/Libreville=GMT+01:00 Africa/Libreville|Africa/Luanda=GMT+01:00 Africa/Luanda|Africa/Malabo=GMT+01:00 Africa/Malabo|Africa/Ndjamena=GMT+01:00 Africa/Ndjamena|Africa/Niamey=GMT+01:00 Africa/Niamey|Africa/Porto-Novo=GMT+01:00 Africa/Porto-Novo|Africa/Sao_Tome=GMT+01:00 Africa/Sao_Tome|Africa/Tunis=GMT+01:00 Africa/Tunis|Arctic/Longyearbyen=GMT+01:00 Arctic/Longyearbyen|Europe/Amsterdam=GMT+01:00 Europe/Amsterdam|Europe/Andorra=GMT+01:00 Europe/Andorra|Europe/Belgrade=GMT+01:00 Europe/Belgrade|Europe/Berlin=GMT+01:00 Europe/Berlin|Europe/Bratislava=GMT+01:00 Europe/Bratislava|Europe/Brussels=GMT+01:00 Europe/Brussels|Europe/Budapest=GMT+01:00 Europe/Budapest|Europe/Busingen=GMT+01:00 Europe/Busingen|Europe/Copenhagen=GMT+01:00 Europe/Copenhagen|Europe/Gibraltar=GMT+01:00 Europe/Gibraltar|Europe/Ljubljana=GMT+01:00 Europe/Ljubljana|Europe/Luxembourg=GMT+01:00 Europe/Luxembourg|Europe/Madrid=GMT+01:00 Europe/Madrid|Europe/Malta=GMT+01:00 Europe/Malta|Europe/Monaco=GMT+01:00 Europe/Monaco|Europe/Oslo=GMT+01:00 Europe/Oslo|Europe/Paris=GMT+01:00 Europe/Paris|Europe/Podgorica=GMT+01:00 Europe/Podgorica|Europe/Prague=GMT+01:00 Europe/Prague|Europe/Rome=GMT+01:00 Europe/Rome|Europe/San_Marino=GMT+01:00 Europe/San_Marino|Europe/Sarajevo=GMT+01:00 Europe/Sarajevo|Europe/Skopje=GMT+01:00 Europe/Skopje|Europe/Stockholm=GMT+01:00 Europe/Stockholm|Europe/Tirane=GMT+01:00 Europe/Tirane|Europe/Vaduz=GMT+01:00 Europe/Vaduz|Europe/Vatican=GMT+01:00 Europe/Vatican|Europe/Vienna=GMT+01:00 Europe/Vienna|Europe/Warsaw=GMT+01:00 Europe/Warsaw|Europe/Zagreb=GMT+01:00 Europe/Zagreb|Europe/Zurich=GMT+01:00 Europe/Zurich|Africa/Blantyre=GMT+02:00 Africa/Blantyre|Africa/Bujumbura=GMT+02:00 Africa/Bujumbura|Africa/Cairo=GMT+02:00 Africa/Cairo|Africa/Gaborone=GMT+02:00 Africa/Gaborone|Africa/Harare=GMT+02:00 Africa/Harare|Africa/Johannesburg=GMT+02:00 Africa/Johannesburg|Africa/Khartoum=GMT+02:00 Africa/Khartoum|Africa/Kigali=GMT+02:00 Africa/Kigali|Africa/Lubumbashi=GMT+02:00 Africa/Lubumbashi|Africa/Lusaka=GMT+02:00 Africa/Lusaka|Africa/Maputo=GMT+02:00 Africa/Maputo|Africa/Maseru=GMT+02:00 Africa/Maseru|Africa/Mbabane=GMT+02:00 Africa/Mbabane|Africa/Tripoli=GMT+02:00 Africa/Tripoli|Africa/Windhoek=GMT+02:00 Africa/Windhoek|Asia/Amman=GMT+02:00 Asia/Amman|Asia/Beirut=GMT+02:00 Asia/Beirut|Asia/Damascus=GMT+02:00 Asia/Damascus|Asia/Famagusta=GMT+02:00 Asia/Famagusta|Asia/Gaza=GMT+02:00 Asia/Gaza|Asia/Hebron=GMT+02:00 Asia/Hebron|Asia/Jerusalem=GMT+02:00 Asia/Jerusalem|Asia/Nicosia=GMT+02:00 Asia/Nicosia|Europe/Athens=GMT+02:00 Europe/Athens|Europe/Bucharest=GMT+02:00 Europe/Bucharest|Europe/Chisinau=GMT+02:00 Europe/Chisinau|Europe/Helsinki=GMT+02:00 Europe/Helsinki|Europe/Kaliningrad=GMT+02:00 Europe/Kaliningrad|Europe/Kiev=GMT+02:00 Europe/Kiev|Europe/Mariehamn=GMT+02:00 Europe/Mariehamn|Europe/Riga=GMT+02:00 Europe/Riga|Europe/Sofia=GMT+02:00 Europe/Sofia|Europe/Tallinn=GMT+02:00 Europe/Tallinn|Europe/Uzhgorod=GMT+02:00 Europe/Uzhgorod|Europe/Vilnius=GMT+02:00 Europe/Vilnius|Europe/Zaporozhye=GMT+02:00 Europe/Zaporozhye|Africa/Addis_Ababa=GMT+03:00 Africa/Addis_Ababa|Africa/Asmara=GMT+03:00 Africa/Asmara|Africa/Dar_es_Salaam=GMT+03:00 Africa/Dar_es_Salaam|Africa/Djibouti=GMT+03:00 Africa/Djibouti|Africa/Juba=GMT+03:00 Africa/Juba|Africa/Kampala=GMT+03:00 Africa/Kampala|Africa/Mogadishu=GMT+03:00 Africa/Mogadishu|Africa/Nairobi=GMT+03:00 Africa/Nairobi|Antarctica/Syowa=GMT+03:00 Antarctica/Syowa|Asia/Aden=GMT+03:00 Asia/Aden|Asia/Baghdad=GMT+03:00 Asia/Baghdad|Asia/Bahrain=GMT+03:00 Asia/Bahrain|Asia/Kuwait=GMT+03:00 Asia/Kuwait|Asia/Qatar=GMT+03:00 Asia/Qatar|Asia/Riyadh=GMT+03:00 Asia/Riyadh|Europe/Istanbul=GMT+03:00 Europe/Istanbul|Europe/Kirov=GMT+03:00 Europe/Kirov|Europe/Minsk=GMT+03:00 Europe/Minsk|Europe/Moscow=GMT+03:00 Europe/Moscow|Europe/Simferopol=GMT+03:00 Europe/Simferopol|Europe/Volgograd=GMT+03:00 Europe/Volgograd|Indian/Antananarivo=GMT+03:00 Indian/Antananarivo|Indian/Comoro=GMT+03:00 Indian/Comoro|Indian/Mayotte=GMT+03:00 Indian/Mayotte|Asia/Tehran=GMT+03:30 Asia/Tehran|Asia/Baku=GMT+04:00 Asia/Baku|Asia/Dubai=GMT+04:00 Asia/Dubai|Asia/Muscat=GMT+04:00 Asia/Muscat|Asia/Tbilisi=GMT+04:00 Asia/Tbilisi|Asia/Yerevan=GMT+04:00 Asia/Yerevan|Europe/Astrakhan=GMT+04:00 Europe/Astrakhan|Europe/Samara=GMT+04:00 Europe/Samara|Europe/Saratov=GMT+04:00 Europe/Saratov|Europe/Ulyanovsk=GMT+04:00 Europe/Ulyanovsk|Indian/Mahe=GMT+04:00 Indian/Mahe|Indian/Mauritius=GMT+04:00 Indian/Mauritius|Indian/Reunion=GMT+04:00 Indian/Reunion|Asia/Kabul=GMT+04:30 Asia/Kabul|Antarctica/Mawson=GMT+05:00 Antarctica/Mawson|Asia/Aqtau=GMT+05:00 Asia/Aqtau|Asia/Aqtobe=GMT+05:00 Asia/Aqtobe|Asia/Ashgabat=GMT+05:00 Asia/Ashgabat|Asia/Atyrau=GMT+05:00 Asia/Atyrau|Asia/Dushanbe=GMT+05:00 Asia/Dushanbe|Asia/Karachi=GMT+05:00 Asia/Karachi|Asia/Oral=GMT+05:00 Asia/Oral|Asia/Samarkand=GMT+05:00 Asia/Samarkand|Asia/Tashkent=GMT+05:00 Asia/Tashkent|Asia/Yekaterinburg=GMT+05:00 Asia/Yekaterinburg|Indian/Kerguelen=GMT+05:00 Indian/Kerguelen|Indian/Maldives=GMT+05:00 Indian/Maldives|Asia/Colombo=GMT+05:30 Asia/Colombo|Asia/Kolkata=GMT+05:30 Asia/Kolkata|Asia/Kathmandu=GMT+05:45 Asia/Kathmandu|Antarctica/Vostok=GMT+06:00 Antarctica/Vostok|Asia/Almaty=GMT+06:00 Asia/Almaty|Asia/Bishkek=GMT+06:00 Asia/Bishkek|Asia/Dhaka=GMT+06:00 Asia/Dhaka|Asia/Omsk=GMT+06:00 Asia/Omsk|Asia/Qyzylorda=GMT+06:00 Asia/Qyzylorda|Asia/Thimphu=GMT+06:00 Asia/Thimphu|Asia/Urumqi=GMT+06:00 Asia/Urumqi|Indian/Chagos=GMT+06:00 Indian/Chagos|Asia/Yangon=GMT+06:30 Asia/Yangon|Indian/Cocos=GMT+06:30 Indian/Cocos|Antarctica/Davis=GMT+07:00 Antarctica/Davis|Asia/Bangkok=GMT+07:00 Asia/Bangkok|Asia/Barnaul=GMT+07:00 Asia/Barnaul|Asia/Ho_Chi_Minh=GMT+07:00 Asia/Ho_Chi_Minh|Asia/Hovd=GMT+07:00 Asia/Hovd|Asia/Jakarta=GMT+07:00 Asia/Jakarta|Asia/Krasnoyarsk=GMT+07:00 Asia/Krasnoyarsk|Asia/Novokuznetsk=GMT+07:00 Asia/Novokuznetsk|Asia/Novosibirsk=GMT+07:00 Asia/Novosibirsk|Asia/Phnom_Penh=GMT+07:00 Asia/Phnom_Penh|Asia/Pontianak=GMT+07:00 Asia/Pontianak|Asia/Tomsk=GMT+07:00 Asia/Tomsk|Asia/Vientiane=GMT+07:00 Asia/Vientiane|Indian/Christmas=GMT+07:00 Indian/Christmas|Antarctica/Casey=GMT+08:00 Antarctica/Casey|Asia/Brunei=GMT+08:00 Asia/Brunei|Asia/Choibalsan=GMT+08:00 Asia/Choibalsan|Asia/Hong_Kong=GMT+08:00 Asia/Hong_Kong|Asia/Irkutsk=GMT+08:00 Asia/Irkutsk|Asia/Kuala_Lumpur=GMT+08:00 Asia/Kuala_Lumpur|Asia/Kuching=GMT+08:00 Asia/Kuching|Asia/Macau=GMT+08:00 Asia/Macau|Asia/Makassar=GMT+08:00 Asia/Makassar|Asia/Manila=GMT+08:00 Asia/Manila|Asia/Shanghai=GMT+08:00 Asia/Shanghai|Asia/Singapore=GMT+08:00 Asia/Singapore|Asia/Taipei=GMT+08:00 Asia/Taipei|Asia/Ulaanbaatar=GMT+08:00 Asia/Ulaanbaatar|Australia/Perth=GMT+08:00 Australia/Perth|Australia/Eucla=GMT+08:45 Australia/Eucla|Asia/Chita=GMT+09:00 Asia/Chita|Asia/Dili=GMT+09:00 Asia/Dili|Asia/Jayapura=GMT+09:00 Asia/Jayapura|Asia/Khandyga=GMT+09:00 Asia/Khandyga|Asia/Pyongyang=GMT+09:00 Asia/Pyongyang|Asia/Seoul=GMT+09:00 Asia/Seoul|Asia/Tokyo=GMT+09:00 Asia/Tokyo|Asia/Yakutsk=GMT+09:00 Asia/Yakutsk|Pacific/Palau=GMT+09:00 Pacific/Palau|Australia/Darwin=GMT+09:30 Australia/Darwin|Antarctica/DumontDUrville=GMT+10:00 Antarctica/DumontDUrville|Asia/Ust-Nera=GMT+10:00 Asia/Ust-Nera|Asia/Vladivostok=GMT+10:00 Asia/Vladivostok|Australia/Brisbane=GMT+10:00 Australia/Brisbane|Australia/Lindeman=GMT+10:00 Australia/Lindeman|Pacific/Chuuk=GMT+10:00 Pacific/Chuuk|Pacific/Guam=GMT+10:00 Pacific/Guam|Pacific/Port_Moresby=GMT+10:00 Pacific/Port_Moresby|Pacific/Saipan=GMT+10:00 Pacific/Saipan|Australia/Adelaide=GMT+10:30 Australia/Adelaide|Australia/Broken_Hill=GMT+10:30 Australia/Broken_Hill|Antarctica/Macquarie=GMT+11:00 Antarctica/Macquarie|Asia/Magadan=GMT+11:00 Asia/Magadan|Asia/Sakhalin=GMT+11:00 Asia/Sakhalin|Asia/Srednekolymsk=GMT+11:00 Asia/Srednekolymsk|Australia/Currie=GMT+11:00 Australia/Currie|Australia/Hobart=GMT+11:00 Australia/Hobart|Australia/Lord_Howe=GMT+11:00 Australia/Lord_Howe|Australia/Melbourne=GMT+11:00 Australia/Melbourne|Australia/Sydney=GMT+11:00 Australia/Sydney|Pacific/Bougainville=GMT+11:00 Pacific/Bougainville|Pacific/Efate=GMT+11:00 Pacific/Efate|Pacific/Guadalcanal=GMT+11:00 Pacific/Guadalcanal|Pacific/Kosrae=GMT+11:00 Pacific/Kosrae|Pacific/Norfolk=GMT+11:00 Pacific/Norfolk|Pacific/Noumea=GMT+11:00 Pacific/Noumea|Pacific/Pohnpei=GMT+11:00 Pacific/Pohnpei|Asia/Anadyr=GMT+12:00 Asia/Anadyr|Asia/Kamchatka=GMT+12:00 Asia/Kamchatka|Pacific/Fiji=GMT+12:00 Pacific/Fiji|Pacific/Funafuti=GMT+12:00 Pacific/Funafuti|Pacific/Kwajalein=GMT+12:00 Pacific/Kwajalein|Pacific/Majuro=GMT+12:00 Pacific/Majuro|Pacific/Nauru=GMT+12:00 Pacific/Nauru|Pacific/Tarawa=GMT+12:00 Pacific/Tarawa|Pacific/Wake=GMT+12:00 Pacific/Wake|Pacific/Wallis=GMT+12:00 Pacific/Wallis|Antarctica/McMurdo=GMT+13:00 Antarctica/McMurdo|Pacific/Auckland=GMT+13:00 Pacific/Auckland|Pacific/Enderbury=GMT+13:00 Pacific/Enderbury|Pacific/Fakaofo=GMT+13:00 Pacific/Fakaofo|Pacific/Tongatapu=GMT+13:00 Pacific/Tongatapu|Pacific/Chatham=GMT+13:45 Pacific/Chatham|Pacific/Apia=GMT+14:00 Pacific/Apia|Pacific/Kiritimati=GMT+14:00 Pacific/Kiritimati'),
(7, 'all', 'Global', 'sms_mobile', 'SMS Mobile :', '', 'text', 'e.g.017XXXXXXXX | Note: This number will be displayed in the message with SMS'),
(8, 'acc', 'Account', 'allow_cc_v', 'Cost Center :', '0', 'list', '0=Not Use|1=Voucher Wise|2=Multi Line'),
(9, 'acc', 'Account', 'allow_cb_jv', 'Allow Cash & Bank in Journal Voucher :', '0', 'list', '1=Yes|0=No'),
(10, 'acc', 'Account', 'group_v_print', 'Allow Print Group in Voucher :', '0', 'list', '1=Yes|0=No'),
(11, 'acc', 'Account', 'pl_account', 'Allow Profit & Loss Account :', '0', 'list', '1=Yes|0=No'),
(12, 'acc', 'Account', 'acc_singlevoucher', 'Allow All Type Voucher on Single Form :', '0', 'list', '1=Yes|0=No'),
(13, 'acc', 'Account', 'acc_shortnarr', 'Allow Short Narration in Voucher :', '0', 'list', '1=Yes|0=No'),
(14, 'acc', 'Account', 'ledgerbalance', 'Ledger Balance :', '0', 'list', '1=Show|0=Hide'),
(15, 'acc', 'Account', 'ledgercrlimit', 'Ledger Credit Limit :', '0', 'list', '1=Show|0=Hide'),
(16, 'inv', 'Inventory', 'inv_do', 'Dlivery Order Added in Sales, <br/>Purchase Return & Stock Journal :', '0', 'list', '1=Yes|0=No'),
(17, 'inv-po', 'Inventory', 'pur_order', 'Allow Purchase Order :', '0', 'list', '1=Yes|0=No'),
(18, 'inv', 'Inventory', 'pur_ledger', 'Allow Purchase Ledger :', '', 'multi', 'SELECT _id, _group FROM acc_group WHERE (NOT (_group IN(SELECT _related FROM acc_group)))'),
(19, 'inv', 'Inventory', 'pur_bill_qty', 'Allow Purchase Actual & Bill Quantity Columns:', '0', 'list', '1=Yes|0=No'),
(20, 'inv-so', 'Inventory', 'sal_order', 'Allow Sales Order :', '0', 'list', '1=Yes|0=No'),
(21, 'inv', 'Inventory', 'sal_ledger', 'Allow Sales Ledger :', '', 'multi', 'SELECT _id, _group FROM acc_group WHERE (NOT (_group IN(SELECT _related FROM acc_group)))'),
(22, 'inv', 'Inventory', 'sal_vat', 'Allow Sales Vat :', '0', 'list', '1=Yes|0=No'),
(23, 'inv', 'Inventory', 'inv_store', 'Allow Store :', '0', 'list', '1=Yes|0=No'),
(24, 'inv', 'Inventory', 'inv_batchlot', 'Allow BatchLot & Expiry Dates :', '0', 'list', '1=Yes|0=No'),
(25, 'inv', 'Inventory', 'inv_salesdiscount', 'Allow Item wise Sales Discount :', '0', 'list', '1=Yes|0=No'),
(26, 'inv', 'Inventory', 'inv_salesinvoice_hf', 'Sales Invoice Header and Footer :', '1', 'list', '1=Show|0=Hide'),
(27, 'inv', 'Inventory', 'inv_purchasediscount', 'Allow Item wise Purchase Discount :', '1', 'list', '1=Yes|0=No'),
(28, 'inv', 'Inventory', 'inv_stockvalue', 'The stock value set to default :', '1', 'list', '1=Standard Average Rate Wise|2=Last Purchase Rate Wise|3=Average Rate Wise'),
(29, 'inv-pos', 'Point of Sales', 'inv_possale', 'Allow POS System :', '0', 'list', '1=Yes|0=No'),
(30, 'inv-pos', 'Point of Sales', 'inv_positem', 'Allow POS Manual Item Selection :', '0', 'list', '1=Yes|0=No'),
(31, 'inv-pos', 'Point of Sales', 'inv_posqty', 'Allow POS Flexible Quantity :', '0', 'list', '1=Yes|0=No'),
(32, 'inv-pos', 'Point of Sales', 'inv_posrate', 'Allow POS Flexible Rate :', '0', 'list', '1=Yes|0=No'),
(33, 'inv-pos', 'Point of Sales', 'inv_posaccount', 'Allow POS Account Detail :', '0', 'list', '1=Yes|0=No'),
(34, 'inv-pos', 'Point of Sales', 'inv_posvat', 'Vat Registration No. :', '', 'text', ''),
(35, 'inv-pos', 'Point of Sales', 'inv_posnote', 'POS Note :', '', 'text', ''),
(36, 'inv-pos', 'Point of Sales', 'inv_posround', 'POS Rounding System :', 'round', 'list', 'ceil=Upward Rounding (1.10 &equals; 2)|round=Normal Rounding (1.49 &equals; 1, 1.50 &equals; 2)|floor=Downward Rounding (1.99 &equals; 1)');

-- --------------------------------------------------------

--
-- Table structure for table `sys_reportfields`
--

CREATE TABLE `sys_reportfields` (
  `_id` int(11) NOT NULL,
  `_reportid` int(11) NOT NULL,
  `_show` enum('1','0') NOT NULL DEFAULT '1',
  `_serial` int(11) NOT NULL,
  `_field` varchar(60) NOT NULL,
  `_datatype` int(11) NOT NULL,
  `_title` varchar(60) NOT NULL,
  `_width` int(11) NOT NULL DEFAULT 0,
  `_align` int(11) NOT NULL DEFAULT 1,
  `_formating` int(11) NOT NULL DEFAULT 0,
  `_ifzero` varchar(10) DEFAULT NULL,
  `_calculation` longtext DEFAULT NULL,
  `_detail` int(11) NOT NULL DEFAULT 0,
  `_filterby` varchar(10) NOT NULL DEFAULT 'none',
  `_filterserial` int(11) NOT NULL DEFAULT 0,
  `_groupby` int(11) NOT NULL DEFAULT 0,
  `_summarizeby` varchar(20) DEFAULT 'none',
  `_totalsummarizeby` varchar(5) NOT NULL DEFAULT 'none',
  `_runningtotal` enum('1','0') NOT NULL DEFAULT '0',
  `_orderby` varchar(4) NOT NULL DEFAULT 'none',
  `_orderserial` int(11) NOT NULL DEFAULT 0,
  `_pivottable` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_setting`
--

CREATE TABLE `sys_setting` (
  `_id` int(11) NOT NULL,
  `_name` varchar(1000) NOT NULL,
  `_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_setting`
--

INSERT INTO `sys_setting` (`_id`, `_name`, `_value`) VALUES
(1, 'LAST_ACTIVITY', '1662527198');

-- --------------------------------------------------------

--
-- Table structure for table `sys_smsinfo`
--

CREATE TABLE `sys_smsinfo` (
  `_id` int(11) NOT NULL,
  `_date` date DEFAULT NULL,
  `_number` varchar(15) NOT NULL,
  `_sms` varchar(1000) NOT NULL,
  `_length` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_count` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_cost` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `_ledger` int(11) NOT NULL DEFAULT 0,
  `_user` varchar(60) NOT NULL,
  `_action` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_sqlviews`
--

CREATE TABLE `sys_sqlviews` (
  `_id` int(11) NOT NULL,
  `_name` varchar(50) NOT NULL,
  `_type` int(11) NOT NULL DEFAULT 0,
  `_query` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_temptablelist`
--

CREATE TABLE `sys_temptablelist` (
  `_id` int(11) NOT NULL,
  `_table` varchar(100) NOT NULL,
  `_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_user`
--

CREATE TABLE `sys_user` (
  `_id` int(11) NOT NULL,
  `_user` varchar(15) NOT NULL,
  `_fullname` varchar(60) NOT NULL,
  `_email` varchar(60) NOT NULL,
  `_phone` varchar(30) NOT NULL,
  `_password` varchar(4000) NOT NULL,
  `_activeted` enum('Y','N') NOT NULL,
  `_profile` longtext DEFAULT NULL,
  `_photo` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_user`
--

INSERT INTO `sys_user` (`_id`, `_user`, `_fullname`, `_email`, `_phone`, `_password`, `_activeted`, `_profile`, `_photo`) VALUES
(-1, 'admin', 'Administrator', '', '', '240d01d12bfab5bcce2b8dc80786487d:AlLaH987qwety', 'Y', 'a:2:{s:15:\"LoginRetryCount\";i:0;s:20:\"LastBadLoginDateTime\";s:19:\"2018/09/17 20:32:03\";}', ''),
(1, 'akas', 'Md. Alamgir Hossain', 'goodakas@gmail.com', '01711066089', 'ecc1394a7afe3991577c4e9cf4692c87:AlLaH987qwety', 'Y', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `sys_userrights`
--

CREATE TABLE `sys_userrights` (
  `_user_id` int(11) NOT NULL,
  `_tablename` varchar(255) NOT NULL,
  `_permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_userrights`
--

INSERT INTO `sys_userrights` (`_user_id`, `_tablename`, `_permission`) VALUES
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}acc_assets', 15),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}acc_budget', 15),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}acc_costcenter', 15),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}acc_group', 15),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}acc_ledger', 15),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}acc_voucher', 9),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_attendproduct7', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_department11', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_employees1', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_employees20', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_employees4', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_holidays24', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_jobtitle9', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_leaveentitlement28', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_leavetypes27', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_payheads3', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_recruitment8', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_vacancies10', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}cus_weekworkday26', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_batchlot', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_cost', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_groupsetting', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_item', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_pos', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_purchase', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_purchaseorder', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_purchasereturn', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_sales', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_salesorder', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_salesreturn', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_stock', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_store', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}inv_unit', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_balancesheet', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_bankbook', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_barcodesticker', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_billofparty', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_budgets', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_cashbook', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_costcenter', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_creditlimit', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_daybook', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_expireditem', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_fixedassets', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_generalledger', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_grossprofit', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_incomestatement', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_purchase', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_purchaseorder', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_purchaseordercompare', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_purchasereturn', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_purchasesummary', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_receiptpayment', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_sales', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_salesorder', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_salesordercompare', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_salesreturn', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_salessummary', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_standingbyorder', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_stockjournal', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_stockledger', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_stockposition', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_stockvalue', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_stockvalueregister', 0),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_trialbalance', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}rep_worksheet', 8),
(1, '{D5429CFD-D2C3-4DA7-8D7C-5409EBC09064}sys_currency', 0);

-- --------------------------------------------------------

--
-- Structure for view `acc_account`
--
DROP TABLE IF EXISTS `acc_account`;

CREATE ALGORITHM=UNDEFINED DEFINER=`softin`@`localhost` SQL SECURITY DEFINER VIEW `acc_account`  AS  select 'Account' AS `_transaction`,`s1`.`_id` AS `_sl`,concat('A-',`s1`.`_id`) AS `_id`,`s1`.`_date` AS `_date`,`s1`.`_type` AS `_type`,`s1`.`_reference` AS `_reference`,NULL AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`s2`.`_ledger`) AS `_group`,`s2`.`_ledger` AS `_ledger`,`s2`.`_initial_cost` - `s2`.`_depreciation` AS `_dr_amount`,0 AS `_cr_amount`,`s1`.`_narration` AS `_narration` from (`acc_voucher` `s1` join `acc_assets` `s2`) where `s1`.`_id` = 1 and `s2`.`_initial_cost` - `s2`.`_depreciation` <> 0 union all select 'Account' AS `_transaction`,`s1`.`_id` AS `_sl`,concat('A-',`s1`.`_id`) AS `_id`,`s1`.`_date` AS `_date`,`s1`.`_type` AS `_type`,`s1`.`_reference` AS `_reference`,if(`coption`('allow_cc_v') = 2,`s2`.`_costcenter`,`s1`.`_costcenter`) AS `_costcenter`,`s2`.`_shortnarr` AS `_shortnarr`,`fgroup`(`s2`.`_ledger`) AS `_group`,`s2`.`_ledger` AS `_ledger`,`s2`.`_dr_amount` AS `_dr_amount`,`s2`.`_cr_amount` AS `_cr_amount`,`s1`.`_narration` AS `_narration` from (`acc_voucher` `s1` join `acc_voucherdetail` `s2` on(`s1`.`_id` = `s2`.`_voucher_id`)) union all select 'Account' AS `_transaction`,`s1`.`_id` AS `_sl`,concat('A-',`s1`.`_id`) AS `_id`,`s1`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`s1`.`_reference` AS `_reference`,NULL AS `_costcenter`,NULL AS `_shortnarr`,`mgroup`(1005) AS `_group`,`s3`.`_category` AS `_ledger`,sum(`s2`.`_value`) AS `_dr_amount`,0 AS `_cr_amount`,`s1`.`_narration` AS `_narration` from ((`inv_purchase` `s1` join `inv_purchasedetail` `s2` on(`s1`.`_id` = `s2`.`_no`)) join `inv_item` `s3` on(`s2`.`_item` = `s3`.`_item`)) where `s1`.`_id` = 1 group by `s1`.`_date`,`s1`.`_reference`,`s1`.`_narration`,`s3`.`_category` union all select 'Purchase' AS `_transaction`,`inv_purchase`.`_id` AS `_sl`,concat('P-',`inv_purchase`.`_id`) AS `_id`,`inv_purchase`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_purchase`.`_reference` AS `_reference`,`inv_purchase`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`inv_purchase`.`_invledger`) AS `_group`,`inv_purchase`.`_invledger` AS `_ledger`,`inv_purchase`.`_subtotal` AS `_dr_amount`,0 AS `_cr_amount`,`inv_purchase`.`_narration` AS `_narration` from `inv_purchase` where `inv_purchase`.`_id` <> 1 and `inv_purchase`.`_subtotal` <> 0 union all select 'Purchase' AS `_transaction`,`inv_purchase`.`_id` AS `_sl`,concat('P-',`inv_purchase`.`_id`) AS `_id`,`inv_purchase`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_purchase`.`_reference` AS `_reference`,`inv_purchase`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`inv_purchase`.`_ledger`) AS `_group`,`inv_purchase`.`_ledger` AS `_ledger`,0 AS `_dr_amount`,`inv_purchase`.`_subtotal` + (`inv_purchase`.`_dr_amount` - `inv_purchase`.`_cr_amount`) AS `_cr_amount`,`inv_purchase`.`_narration` AS `_narration` from `inv_purchase` where `inv_purchase`.`_id` <> 1 and `inv_purchase`.`_subtotal` + (`inv_purchase`.`_dr_amount` - `inv_purchase`.`_cr_amount`) <> 0 union all select 'Purchase' AS `_transaction`,`s1`.`_id` AS `_sl`,concat('P-',`s1`.`_id`) AS `_id`,`s1`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`s1`.`_reference` AS `_reference`,`s1`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`s2`.`_ledger`) AS `_Group`,`s2`.`_ledger` AS `_ledger`,`s2`.`_dr_amount` AS `_dr_amount`,`s2`.`_cr_amount` AS `_cr_amount`,`s1`.`_narration` AS `_narration` from (`inv_purchase` `s1` join `inv_purchaseaccount` `s2` on(`s1`.`_id` = `s2`.`_no`)) where `s2`.`_dr_amount` + `s2`.`_cr_amount` <> 0 union all select 'Purchase Return' AS `_transaction`,`inv_purchasereturn`.`_id` AS `_sl`,concat('PR-',`inv_purchasereturn`.`_id`) AS `_id`,`inv_purchasereturn`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_purchasereturn`.`_reference` AS `_reference`,`inv_purchasereturn`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`inv_purchasereturn`.`_invledger`) AS `_group`,`inv_purchasereturn`.`_invledger` AS `_ledger`,0 AS `_dr_amount`,`inv_purchasereturn`.`_subtotal` AS `_cr_amount`,`inv_purchasereturn`.`_narration` AS `_narration` from `inv_purchasereturn` where `inv_purchasereturn`.`_subtotal` <> 0 union all select 'Purchase Return' AS `_transaction`,`inv_purchasereturn`.`_id` AS `_sl`,concat('PR-',`inv_purchasereturn`.`_id`) AS `_id`,`inv_purchasereturn`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_purchasereturn`.`_reference` AS `_reference`,`inv_purchasereturn`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`inv_purchasereturn`.`_ledger`) AS `_group`,`inv_purchasereturn`.`_ledger` AS `_ledger`,`inv_purchasereturn`.`_subtotal` - (`inv_purchasereturn`.`_dr_amount` - `inv_purchasereturn`.`_cr_amount`) AS `_dr_amount`,0 AS `_cr_amount`,`inv_purchasereturn`.`_narration` AS `_narration` from `inv_purchasereturn` where `inv_purchasereturn`.`_subtotal` - (`inv_purchasereturn`.`_dr_amount` - `inv_purchasereturn`.`_cr_amount`) <> 0 union all select 'Purchase Return' AS `_transaction`,`s1`.`_id` AS `_sl`,concat('PR-',`s1`.`_id`) AS `_id`,`s1`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`s1`.`_reference` AS `_reference`,`s1`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`s2`.`_ledger`) AS `_Group`,`s2`.`_ledger` AS `_ledger`,`s2`.`_dr_amount` AS `_dr_amount`,`s2`.`_cr_amount` AS `_cr_amount`,`s1`.`_narration` AS `_narration` from (`inv_purchasereturn` `s1` join `inv_purchasereturnaccount` `s2` on(`s1`.`_id` = `s2`.`_no`)) where `s2`.`_dr_amount` + `s2`.`_cr_amount` <> 0 union all select 'Sales' AS `_transaction`,`inv_sales`.`_id` AS `_sl`,concat('S-',`inv_sales`.`_id`) AS `_id`,`inv_sales`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_sales`.`_reference` AS `_reference`,`inv_sales`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`inv_sales`.`_invledger`) AS `_group`,`inv_sales`.`_invledger` AS `_ledger`,0 AS `_dr_amount`,`inv_sales`.`_subtotal` AS `_cr_amount`,`inv_sales`.`_narration` AS `_narration` from `inv_sales` where `inv_sales`.`_subtotal` <> 0 union all select 'Sales' AS `_transaction`,`inv_sales`.`_id` AS `_sl`,concat('S-',`inv_sales`.`_id`) AS `_id`,`inv_sales`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_sales`.`_reference` AS `_reference`,`inv_sales`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`inv_sales`.`_ledger`) AS `_group`,`inv_sales`.`_ledger` AS `_ledger`,`inv_sales`.`_subtotal` - `inv_sales`.`_distotal` + `inv_sales`.`_vattotal` + `inv_sales`.`_round` - `inv_sales`.`_paid` - (`inv_sales`.`_dr_amount` - `inv_sales`.`_cr_amount`) AS `_dr_amount`,0 AS `_cr_amount`,`inv_sales`.`_narration` AS `_narration` from `inv_sales` where `inv_sales`.`_subtotal` - `inv_sales`.`_distotal` + `inv_sales`.`_vattotal` - `inv_sales`.`_paid` + `inv_sales`.`_round` - (`inv_sales`.`_dr_amount` - `inv_sales`.`_cr_amount`) <> 0 union all select 'Sales' AS `_transaction`,`inv_sales`.`_id` AS `_sl`,concat('S-',`inv_sales`.`_id`) AS `_id`,`inv_sales`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_sales`.`_reference` AS `_reference`,`inv_sales`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`mgroup`(1011) AS `_group`,`mledger`(1011) AS `_ledger`,`inv_sales`.`_distotal` AS `_dr_amount`,0 AS `_cr_amount`,`inv_sales`.`_narration` AS `_narration` from `inv_sales` where `inv_sales`.`_distotal` <> 0 union all select 'Sales' AS `_transaction`,`inv_sales`.`_id` AS `_sl`,concat('S-',`inv_sales`.`_id`) AS `_id`,`inv_sales`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_sales`.`_reference` AS `_reference`,`inv_sales`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`mgroup`(1001) AS `_group`,`mledger`(1001) AS `_ledger`,`inv_sales`.`_paid` AS `_dr_amount`,0 AS `_cr_amount`,`inv_sales`.`_narration` AS `_narration` from `inv_sales` where `inv_sales`.`_paid` <> 0 and `inv_sales`.`_type` = 'pos' union all select 'Sales' AS `_transaction`,`inv_sales`.`_id` AS `_sl`,concat('S-',`inv_sales`.`_id`) AS `_id`,`inv_sales`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_sales`.`_reference` AS `_reference`,`inv_sales`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`mgroup`(1016) AS `_group`,`mledger`(1016) AS `_ledger`,0 AS `_dr_amount`,`inv_sales`.`_vattotal` AS `_cr_amount`,`inv_sales`.`_narration` AS `_narration` from `inv_sales` where `inv_sales`.`_vattotal` <> 0 union all select 'Sales' AS `_transaction`,`inv_sales`.`_id` AS `_sl`,concat('S-',`inv_sales`.`_id`) AS `_id`,`inv_sales`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_sales`.`_reference` AS `_reference`,`inv_sales`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,if(`inv_sales`.`_round` > 0,`mgroup`(1020),`mgroup`(1019)) AS `_group`,if(`inv_sales`.`_round` > 0,`mledger`(1020),`mledger`(1019)) AS `_ledger`,if(`inv_sales`.`_round` < 0,abs(`inv_sales`.`_round`),0) AS `_dr_amount`,if(`inv_sales`.`_round` > 0,`inv_sales`.`_round`,0) AS `_cr_amount`,`inv_sales`.`_narration` AS `_narration` from `inv_sales` where `inv_sales`.`_round` <> 0 union all select 'Sales' AS `_transaction`,`s1`.`_id` AS `_sl`,concat('S-',`s1`.`_id`) AS `_id`,`s1`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`s1`.`_reference` AS `_reference`,`s1`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`s2`.`_ledger`) AS `_Group`,`s2`.`_ledger` AS `_ledger`,`s2`.`_dr_amount` AS `_dr_amount`,`s2`.`_cr_amount` AS `_cr_amount`,`s1`.`_narration` AS `_narration` from (`inv_sales` `s1` join `inv_salesaccount` `s2` on(`s1`.`_id` = `s2`.`_no`)) where `s2`.`_dr_amount` + `s2`.`_cr_amount` <> 0 union all select 'Sales Return' AS `_transaction`,`inv_salesreturn`.`_id` AS `_sl`,concat('SR-',`inv_salesreturn`.`_id`) AS `_id`,`inv_salesreturn`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_salesreturn`.`_reference` AS `_reference`,`inv_salesreturn`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`mgroup`(1009) AS `_group`,`inv_salesreturn`.`_invledger` AS `_ledger`,`inv_salesreturn`.`_subtotal` AS `_dr_amount`,0 AS `_cr_amount`,`inv_salesreturn`.`_narration` AS `_narration` from `inv_salesreturn` where `inv_salesreturn`.`_subtotal` <> 0 union all select 'Sales Return' AS `_transaction`,`inv_salesreturn`.`_id` AS `_sl`,concat('SR-',`inv_salesreturn`.`_id`) AS `_id`,`inv_salesreturn`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`inv_salesreturn`.`_reference` AS `_reference`,`inv_salesreturn`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`inv_salesreturn`.`_ledger`) AS `_group`,`inv_salesreturn`.`_ledger` AS `_ledger`,0 AS `_dr_amount`,`inv_salesreturn`.`_subtotal` + (`inv_salesreturn`.`_dr_amount` - `inv_salesreturn`.`_cr_amount`) AS `_cr_amount`,`inv_salesreturn`.`_narration` AS `_narration` from `inv_salesreturn` where `inv_salesreturn`.`_subtotal` + (`inv_salesreturn`.`_dr_amount` - `inv_salesreturn`.`_cr_amount`) <> 0 union all select 'Sales Return' AS `_transaction`,`s1`.`_id` AS `_sl`,concat('SR-',`s1`.`_id`) AS `_id`,`s1`.`_date` AS `_date`,'JV - Journal Voucher' AS `_type`,`s1`.`_reference` AS `_reference`,`s1`.`_costcenter` AS `_costcenter`,NULL AS `_shortnarr`,`fgroup`(`s2`.`_ledger`) AS `_Group`,`s2`.`_ledger` AS `_ledger`,`s2`.`_dr_amount` AS `_dr_amount`,`s2`.`_cr_amount` AS `_cr_amount`,`s1`.`_narration` AS `_narration` from (`inv_salesreturn` `s1` join `inv_salesreturnaccount` `s2` on(`s1`.`_id` = `s2`.`_no`)) where `s2`.`_dr_amount` + `s2`.`_cr_amount` <> 0 ;

-- --------------------------------------------------------

--
-- Structure for view `cus_leavebalance`
--
DROP TABLE IF EXISTS `cus_leavebalance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cus_leavebalance`  AS  select `t2`.`_ltype` AS `_id`,`t1`.`_id` AS `_eid`,(select `cus_leavetypes`.`_type` from `cus_leavetypes` where `cus_leavetypes`.`_id` = `t2`.`_ltype`) AS `_ltype`,`t2`.`_lday` AS `_lday` from (`cus_employees` `t1` join `cus_leaveentitlement` `t2`) where concat(',',`t2`.`_lemployid`,',') like convert(concat('%,',`t1`.`_id`,',%') using utf8) ;

-- --------------------------------------------------------

--
-- Structure for view `cus_payheadview`
--
DROP TABLE IF EXISTS `cus_payheadview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cus_payheadview`  AS  select `t2`.`_id` AS `_id`,`t2`.`_ledger` AS `_ledger`,`t1`.`_type` AS `_type`,`t1`.`_calculation` AS `_calculation`,`t1`.`_onhead` AS `_onhead` from (`cus_payheads` `t1` join `acc_ledger` `t2` on(`t1`.`_ledger` = `t2`.`_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `cus_year`
--
DROP TABLE IF EXISTS `cus_year`;

CREATE ALGORITHM=UNDEFINED DEFINER=`softin`@`localhost` SQL SECURITY DEFINER VIEW `cus_year`  AS  select year(curdate()) - 1 AS `_id`,year(curdate()) - 1 AS `_year` union all select year(curdate()) AS `_id`,year(curdate()) AS `_year` union all select year(curdate()) + 1 AS `_id`,year(curdate()) + 1 AS `_year` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acc_assets`
--
ALTER TABLE `acc_assets`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_ledger` (`_ledger`) USING BTREE;

--
-- Indexes for table `acc_budget`
--
ALTER TABLE `acc_budget`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_budget_name` (`_budget_name`);

--
-- Indexes for table `acc_budgetdetail`
--
ALTER TABLE `acc_budgetdetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_groupledger` (`_ledger`) USING BTREE,
  ADD KEY `_no` (`_no`);

--
-- Indexes for table `acc_costcenter`
--
ALTER TABLE `acc_costcenter`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_name` (`_name`);

--
-- Indexes for table `acc_group`
--
ALTER TABLE `acc_group`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `group` (`_group`),
  ADD KEY `_related` (`_related`);

--
-- Indexes for table `acc_ledger`
--
ALTER TABLE `acc_ledger`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_ledger` (`_ledger`) USING BTREE,
  ADD KEY `_group` (`_group`);

--
-- Indexes for table `acc_master`
--
ALTER TABLE `acc_master`
  ADD PRIMARY KEY (`_code`),
  ADD UNIQUE KEY `_groupledger` (`_group`,`_ledger`),
  ADD KEY `_group` (`_group`) USING BTREE,
  ADD KEY `_ledger` (`_ledger`) USING BTREE;

--
-- Indexes for table `acc_voucher`
--
ALTER TABLE `acc_voucher`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_costcenter` (`_costcenter`);

--
-- Indexes for table `acc_voucherdetail`
--
ALTER TABLE `acc_voucherdetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_voucher_id` (`_voucher_id`),
  ADD KEY `_ledger` (`_ledger`),
  ADD KEY `_costcenter` (`_costcenter`);

--
-- Indexes for table `cus_assignleave`
--
ALTER TABLE `cus_assignleave`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_attendance`
--
ALTER TABLE `cus_attendance`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_attendproduct`
--
ALTER TABLE `cus_attendproduct`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_costcenter`
--
ALTER TABLE `cus_costcenter`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_department`
--
ALTER TABLE `cus_department`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_education`
--
ALTER TABLE `cus_education`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_education` (`_education`);

--
-- Indexes for table `cus_emergency`
--
ALTER TABLE `cus_emergency`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_emergency` (`_emergency`);

--
-- Indexes for table `cus_empaddress`
--
ALTER TABLE `cus_empaddress`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_eaddress` (`_eaddress`),
  ADD KEY `_address` (`_address`),
  ADD KEY `_address_2` (`_address`),
  ADD KEY `_address_3` (`_address`);

--
-- Indexes for table `cus_employees`
--
ALTER TABLE `cus_employees`
  ADD PRIMARY KEY (`_id`) USING BTREE,
  ADD UNIQUE KEY `_number` (`_number`),
  ADD KEY `_photo` (`_photo`);

--
-- Indexes for table `cus_experience`
--
ALTER TABLE `cus_experience`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_experience` (`_experience`);

--
-- Indexes for table `cus_grade`
--
ALTER TABLE `cus_grade`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_guarantor`
--
ALTER TABLE `cus_guarantor`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_guarantor` (`_guarantor`);

--
-- Indexes for table `cus_holidaydetail`
--
ALTER TABLE `cus_holidaydetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_holidaysid` (`_holidaysid`);

--
-- Indexes for table `cus_holidays`
--
ALTER TABLE `cus_holidays`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_itaxconfig`
--
ALTER TABLE `cus_itaxconfig`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_itaxledger`
--
ALTER TABLE `cus_itaxledger`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_ledgerno` (`_ledgerno`);

--
-- Indexes for table `cus_itaxpayable`
--
ALTER TABLE `cus_itaxpayable`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_payableno` (`_payableno`);

--
-- Indexes for table `cus_itaxrebate`
--
ALTER TABLE `cus_itaxrebate`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_rebateno` (`_rebateno`);

--
-- Indexes for table `cus_job`
--
ALTER TABLE `cus_job`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_joining` (`_job`);

--
-- Indexes for table `cus_jobcontract`
--
ALTER TABLE `cus_jobcontract`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_jobcontract` (`_jobcontract`);

--
-- Indexes for table `cus_jobtitle`
--
ALTER TABLE `cus_jobtitle`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_language`
--
ALTER TABLE `cus_language`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_languageid` (`_languageid`);

--
-- Indexes for table `cus_leaveentitlement`
--
ALTER TABLE `cus_leaveentitlement`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_leavetypes`
--
ALTER TABLE `cus_leavetypes`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_nominee`
--
ALTER TABLE `cus_nominee`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_nominee` (`_nominee`);

--
-- Indexes for table `cus_payheads`
--
ALTER TABLE `cus_payheads`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_payinfo`
--
ALTER TABLE `cus_payinfo`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_payinfo` (`_payinfo`);

--
-- Indexes for table `cus_payunit`
--
ALTER TABLE `cus_payunit`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_profitcenter`
--
ALTER TABLE `cus_profitcenter`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_recruitment`
--
ALTER TABLE `cus_recruitment`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_reward`
--
ALTER TABLE `cus_reward`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_rewardid` (`_rewardid`);

--
-- Indexes for table `cus_salarystructure`
--
ALTER TABLE `cus_salarystructure`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_sstructuredetails`
--
ALTER TABLE `cus_sstructuredetails`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_structureno` (`_structureno`);

--
-- Indexes for table `cus_test`
--
ALTER TABLE `cus_test`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_training`
--
ALTER TABLE `cus_training`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_training` (`_training`);

--
-- Indexes for table `cus_transfer`
--
ALTER TABLE `cus_transfer`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_transfer` (`_transfer`);

--
-- Indexes for table `cus_vacancies`
--
ALTER TABLE `cus_vacancies`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cus_weekworkday`
--
ALTER TABLE `cus_weekworkday`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `inv_batchlot`
--
ALTER TABLE `inv_batchlot`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_batchlot` (`_batchlot`);

--
-- Indexes for table `inv_cost`
--
ALTER TABLE `inv_cost`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `inv_costdetail`
--
ALTER TABLE `inv_costdetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_costitemdetail`
--
ALTER TABLE `inv_costitemdetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_item` (`_item`);

--
-- Indexes for table `inv_item`
--
ALTER TABLE `inv_item`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_item` (`_item`),
  ADD KEY `_unit` (`_unit`),
  ADD KEY `_category` (`_category`);

--
-- Indexes for table `inv_purchase`
--
ALTER TABLE `inv_purchase`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_purchaseaccount`
--
ALTER TABLE `inv_purchaseaccount`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_purchasedetail`
--
ALTER TABLE `inv_purchasedetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_item` (`_item`),
  ADD KEY `_store` (`_store`),
  ADD KEY `_batchlot` (`_batchlot`);

--
-- Indexes for table `inv_purchaseorder`
--
ALTER TABLE `inv_purchaseorder`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_orderno` (`_orderno`),
  ADD KEY `_ledger` (`_ledger`),
  ADD KEY `_currency` (`_currency`);

--
-- Indexes for table `inv_purchaseorderdetail`
--
ALTER TABLE `inv_purchaseorderdetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_item` (`_item`);

--
-- Indexes for table `inv_purchasereturn`
--
ALTER TABLE `inv_purchasereturn`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_purchasereturnaccount`
--
ALTER TABLE `inv_purchasereturnaccount`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_purchasereturndetail`
--
ALTER TABLE `inv_purchasereturndetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_item` (`_item`),
  ADD KEY `_batchlot` (`_batchlot`),
  ADD KEY `_store` (`_store`);

--
-- Indexes for table `inv_purchaseslcode`
--
ALTER TABLE `inv_purchaseslcode`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_item` (`_item`);

--
-- Indexes for table `inv_sales`
--
ALTER TABLE `inv_sales`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_salesaccount`
--
ALTER TABLE `inv_salesaccount`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_salesdetail`
--
ALTER TABLE `inv_salesdetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_item` (`_item`),
  ADD KEY `_batchlot` (`_batchlot`),
  ADD KEY `_store` (`_store`);

--
-- Indexes for table `inv_salesorder`
--
ALTER TABLE `inv_salesorder`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_orderno` (`_orderno`),
  ADD KEY `_ledger` (`_ledger`),
  ADD KEY `_currency` (`_currency`);

--
-- Indexes for table `inv_salesorderdetail`
--
ALTER TABLE `inv_salesorderdetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_item` (`_item`);

--
-- Indexes for table `inv_salesreturn`
--
ALTER TABLE `inv_salesreturn`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_salesreturnaccount`
--
ALTER TABLE `inv_salesreturnaccount`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_ledger` (`_ledger`);

--
-- Indexes for table `inv_salesreturndetail`
--
ALTER TABLE `inv_salesreturndetail`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `_item` (`_item`),
  ADD KEY `_no` (`_no`),
  ADD KEY `_store` (`_store`),
  ADD KEY `_batchlot` (`_batchlot`);

--
-- Indexes for table `inv_stock`
--
ALTER TABLE `inv_stock`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `inv_stockindetail`
--
ALTER TABLE `inv_stockindetail`
  ADD PRIMARY KEY (`_iid`),
  ADD KEY `_ino` (`_ino`),
  ADD KEY `_iitem` (`_iitem`),
  ADD KEY `_ibatchlot` (`_ibatchlot`),
  ADD KEY `_istore` (`_istore`);

--
-- Indexes for table `inv_stockoutdetail`
--
ALTER TABLE `inv_stockoutdetail`
  ADD PRIMARY KEY (`_oid`),
  ADD KEY `_ono` (`_ono`),
  ADD KEY `_oitem` (`_oitem`),
  ADD KEY `_obatchlot` (`_obatchlot`),
  ADD KEY `_ostore` (`_ostore`);

--
-- Indexes for table `inv_store`
--
ALTER TABLE `inv_store`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_store` (`_store`) USING BTREE;

--
-- Indexes for table `inv_unit`
--
ALTER TABLE `inv_unit`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_unit` (`_unit`);

--
-- Indexes for table `sys_audittrail`
--
ALTER TABLE `sys_audittrail`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_chats`
--
ALTER TABLE `sys_chats`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_date` (`_date`,`_from`,`_to`);

--
-- Indexes for table `sys_currency`
--
ALTER TABLE `sys_currency`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_name` (`_name`) USING BTREE;

--
-- Indexes for table `sys_customeffect`
--
ALTER TABLE `sys_customeffect`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_customfields`
--
ALTER TABLE `sys_customfields`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_customfilter`
--
ALTER TABLE `sys_customfilter`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_customform`
--
ALTER TABLE `sys_customform`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_customformfields`
--
ALTER TABLE `sys_customformfields`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_tableid_field` (`_tableid`,`_field`),
  ADD KEY `_tableid` (`_tableid`);

--
-- Indexes for table `sys_customreport`
--
ALTER TABLE `sys_customreport`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_dashboard`
--
ALTER TABLE `sys_dashboard`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_editortemplates`
--
ALTER TABLE `sys_editortemplates`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_title` (`_table`,`_title`) USING BTREE;

--
-- Indexes for table `sys_filterfields`
--
ALTER TABLE `sys_filterfields`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_field` (`_field`,`_filterid`) USING BTREE,
  ADD KEY `_filterid` (`_filterid`);

--
-- Indexes for table `sys_image`
--
ALTER TABLE `sys_image`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_name` (`_related`,`_name`);

--
-- Indexes for table `sys_lockdate`
--
ALTER TABLE `sys_lockdate`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_date` (`_date`);

--
-- Indexes for table `sys_menu`
--
ALTER TABLE `sys_menu`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_option`
--
ALTER TABLE `sys_option`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `masterkey` (`_masterkey`);

--
-- Indexes for table `sys_reportfields`
--
ALTER TABLE `sys_reportfields`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_field` (`_field`,`_reportid`) USING BTREE,
  ADD KEY `_reportid` (`_reportid`);

--
-- Indexes for table `sys_setting`
--
ALTER TABLE `sys_setting`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_smsinfo`
--
ALTER TABLE `sys_smsinfo`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_sqlviews`
--
ALTER TABLE `sys_sqlviews`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `_name` (`_name`);

--
-- Indexes for table `sys_temptablelist`
--
ALTER TABLE `sys_temptablelist`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `sys_user`
--
ALTER TABLE `sys_user`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `username` (`_user`),
  ADD UNIQUE KEY `email` (`_email`);

--
-- Indexes for table `sys_userrights`
--
ALTER TABLE `sys_userrights`
  ADD PRIMARY KEY (`_user_id`,`_tablename`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acc_assets`
--
ALTER TABLE `acc_assets`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_budget`
--
ALTER TABLE `acc_budget`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_budgetdetail`
--
ALTER TABLE `acc_budgetdetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_costcenter`
--
ALTER TABLE `acc_costcenter`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_group`
--
ALTER TABLE `acc_group`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `acc_ledger`
--
ALTER TABLE `acc_ledger`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `acc_voucher`
--
ALTER TABLE `acc_voucher`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `acc_voucherdetail`
--
ALTER TABLE `acc_voucherdetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_assignleave`
--
ALTER TABLE `cus_assignleave`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cus_attendance`
--
ALTER TABLE `cus_attendance`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cus_attendproduct`
--
ALTER TABLE `cus_attendproduct`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_costcenter`
--
ALTER TABLE `cus_costcenter`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_department`
--
ALTER TABLE `cus_department`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cus_education`
--
ALTER TABLE `cus_education`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_emergency`
--
ALTER TABLE `cus_emergency`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_empaddress`
--
ALTER TABLE `cus_empaddress`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cus_employees`
--
ALTER TABLE `cus_employees`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cus_experience`
--
ALTER TABLE `cus_experience`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_grade`
--
ALTER TABLE `cus_grade`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_guarantor`
--
ALTER TABLE `cus_guarantor`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_holidaydetail`
--
ALTER TABLE `cus_holidaydetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cus_holidays`
--
ALTER TABLE `cus_holidays`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cus_itaxconfig`
--
ALTER TABLE `cus_itaxconfig`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cus_itaxledger`
--
ALTER TABLE `cus_itaxledger`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cus_itaxpayable`
--
ALTER TABLE `cus_itaxpayable`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cus_itaxrebate`
--
ALTER TABLE `cus_itaxrebate`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cus_job`
--
ALTER TABLE `cus_job`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_jobcontract`
--
ALTER TABLE `cus_jobcontract`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_jobtitle`
--
ALTER TABLE `cus_jobtitle`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cus_language`
--
ALTER TABLE `cus_language`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cus_leaveentitlement`
--
ALTER TABLE `cus_leaveentitlement`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cus_leavetypes`
--
ALTER TABLE `cus_leavetypes`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cus_nominee`
--
ALTER TABLE `cus_nominee`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_payheads`
--
ALTER TABLE `cus_payheads`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cus_payinfo`
--
ALTER TABLE `cus_payinfo`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cus_payunit`
--
ALTER TABLE `cus_payunit`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cus_profitcenter`
--
ALTER TABLE `cus_profitcenter`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_recruitment`
--
ALTER TABLE `cus_recruitment`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_reward`
--
ALTER TABLE `cus_reward`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_salarystructure`
--
ALTER TABLE `cus_salarystructure`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `cus_sstructuredetails`
--
ALTER TABLE `cus_sstructuredetails`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `cus_test`
--
ALTER TABLE `cus_test`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_training`
--
ALTER TABLE `cus_training`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_transfer`
--
ALTER TABLE `cus_transfer`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_vacancies`
--
ALTER TABLE `cus_vacancies`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cus_weekworkday`
--
ALTER TABLE `cus_weekworkday`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inv_batchlot`
--
ALTER TABLE `inv_batchlot`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inv_cost`
--
ALTER TABLE `inv_cost`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_costdetail`
--
ALTER TABLE `inv_costdetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_costitemdetail`
--
ALTER TABLE `inv_costitemdetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_item`
--
ALTER TABLE `inv_item`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_purchase`
--
ALTER TABLE `inv_purchase`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inv_purchaseaccount`
--
ALTER TABLE `inv_purchaseaccount`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_purchasedetail`
--
ALTER TABLE `inv_purchasedetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_purchaseorder`
--
ALTER TABLE `inv_purchaseorder`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_purchaseorderdetail`
--
ALTER TABLE `inv_purchaseorderdetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_purchasereturn`
--
ALTER TABLE `inv_purchasereturn`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_purchasereturnaccount`
--
ALTER TABLE `inv_purchasereturnaccount`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_purchasereturndetail`
--
ALTER TABLE `inv_purchasereturndetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_purchaseslcode`
--
ALTER TABLE `inv_purchaseslcode`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_sales`
--
ALTER TABLE `inv_sales`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_salesaccount`
--
ALTER TABLE `inv_salesaccount`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_salesdetail`
--
ALTER TABLE `inv_salesdetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_salesorder`
--
ALTER TABLE `inv_salesorder`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_salesorderdetail`
--
ALTER TABLE `inv_salesorderdetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_salesreturn`
--
ALTER TABLE `inv_salesreturn`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_salesreturnaccount`
--
ALTER TABLE `inv_salesreturnaccount`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_salesreturndetail`
--
ALTER TABLE `inv_salesreturndetail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_stock`
--
ALTER TABLE `inv_stock`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_stockindetail`
--
ALTER TABLE `inv_stockindetail`
  MODIFY `_iid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_stockoutdetail`
--
ALTER TABLE `inv_stockoutdetail`
  MODIFY `_oid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_store`
--
ALTER TABLE `inv_store`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inv_unit`
--
ALTER TABLE `inv_unit`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_audittrail`
--
ALTER TABLE `sys_audittrail`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sys_chats`
--
ALTER TABLE `sys_chats`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_currency`
--
ALTER TABLE `sys_currency`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `sys_customeffect`
--
ALTER TABLE `sys_customeffect`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sys_customfields`
--
ALTER TABLE `sys_customfields`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_customfilter`
--
ALTER TABLE `sys_customfilter`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sys_customform`
--
ALTER TABLE `sys_customform`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `sys_customformfields`
--
ALTER TABLE `sys_customformfields`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `sys_customreport`
--
ALTER TABLE `sys_customreport`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_dashboard`
--
ALTER TABLE `sys_dashboard`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `sys_editortemplates`
--
ALTER TABLE `sys_editortemplates`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_filterfields`
--
ALTER TABLE `sys_filterfields`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sys_image`
--
ALTER TABLE `sys_image`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sys_lockdate`
--
ALTER TABLE `sys_lockdate`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sys_menu`
--
ALTER TABLE `sys_menu`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `sys_reportfields`
--
ALTER TABLE `sys_reportfields`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_setting`
--
ALTER TABLE `sys_setting`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sys_smsinfo`
--
ALTER TABLE `sys_smsinfo`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_sqlviews`
--
ALTER TABLE `sys_sqlviews`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_temptablelist`
--
ALTER TABLE `sys_temptablelist`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acc_assets`
--
ALTER TABLE `acc_assets`
  ADD CONSTRAINT `acc_assets_fk2` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `acc_budgetdetail`
--
ALTER TABLE `acc_budgetdetail`
  ADD CONSTRAINT `acc_budgetdetail_fk1` FOREIGN KEY (`_no`) REFERENCES `acc_budget` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `acc_budgetdetail_fk2` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `acc_ledger`
--
ALTER TABLE `acc_ledger`
  ADD CONSTRAINT `acc_ledger_fk1` FOREIGN KEY (`_group`) REFERENCES `acc_group` (`_group`) ON UPDATE CASCADE;

--
-- Constraints for table `acc_master`
--
ALTER TABLE `acc_master`
  ADD CONSTRAINT `acc_mastergroup_fk1` FOREIGN KEY (`_group`) REFERENCES `acc_group` (`_group`) ON UPDATE CASCADE,
  ADD CONSTRAINT `acc_mastergroup_fk2` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `acc_voucher`
--
ALTER TABLE `acc_voucher`
  ADD CONSTRAINT `acc_voucher_fk1` FOREIGN KEY (`_costcenter`) REFERENCES `acc_costcenter` (`_name`) ON UPDATE CASCADE;

--
-- Constraints for table `acc_voucherdetail`
--
ALTER TABLE `acc_voucherdetail`
  ADD CONSTRAINT `acc_voucherdetail_fk1` FOREIGN KEY (`_voucher_id`) REFERENCES `acc_voucher` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `acc_voucherdetail_fk3` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE,
  ADD CONSTRAINT `acc_voucherdetail_fk4` FOREIGN KEY (`_costcenter`) REFERENCES `acc_costcenter` (`_name`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_education`
--
ALTER TABLE `cus_education`
  ADD CONSTRAINT `cus_education_ibfk_1` FOREIGN KEY (`_education`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_emergency`
--
ALTER TABLE `cus_emergency`
  ADD CONSTRAINT `cus_emergency_emergency_fk` FOREIGN KEY (`_emergency`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_empaddress`
--
ALTER TABLE `cus_empaddress`
  ADD CONSTRAINT `cus_empaddress_eaddress_fk` FOREIGN KEY (`_eaddress`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_employees`
--
ALTER TABLE `cus_employees`
  ADD CONSTRAINT `cus_employees_photo_fk` FOREIGN KEY (`_photo`) REFERENCES `sys_image` (`_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `cus_experience`
--
ALTER TABLE `cus_experience`
  ADD CONSTRAINT `cus_experience_experience_fk` FOREIGN KEY (`_experience`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_guarantor`
--
ALTER TABLE `cus_guarantor`
  ADD CONSTRAINT `cus_guarantor_guarantor_fk` FOREIGN KEY (`_guarantor`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_holidaydetail`
--
ALTER TABLE `cus_holidaydetail`
  ADD CONSTRAINT `cus_holidaydetail_holidaysid_fk` FOREIGN KEY (`_holidaysid`) REFERENCES `cus_holidays` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_itaxledger`
--
ALTER TABLE `cus_itaxledger`
  ADD CONSTRAINT `cus_itaxledger_ledgerno_fk` FOREIGN KEY (`_ledgerno`) REFERENCES `cus_itaxconfig` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_itaxpayable`
--
ALTER TABLE `cus_itaxpayable`
  ADD CONSTRAINT `cus_itaxpayable_payableno_fk` FOREIGN KEY (`_payableno`) REFERENCES `cus_itaxconfig` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_itaxrebate`
--
ALTER TABLE `cus_itaxrebate`
  ADD CONSTRAINT `cus_itaxrebate_rebateno_fk` FOREIGN KEY (`_rebateno`) REFERENCES `cus_itaxconfig` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_job`
--
ALTER TABLE `cus_job`
  ADD CONSTRAINT `cus_joining_joining_fk` FOREIGN KEY (`_job`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_jobcontract`
--
ALTER TABLE `cus_jobcontract`
  ADD CONSTRAINT `cus_jobcontract_jobcontract_fk` FOREIGN KEY (`_jobcontract`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_language`
--
ALTER TABLE `cus_language`
  ADD CONSTRAINT `cus_language_languageid_fk` FOREIGN KEY (`_languageid`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_nominee`
--
ALTER TABLE `cus_nominee`
  ADD CONSTRAINT `cus_nominee_nominee_fk` FOREIGN KEY (`_nominee`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_payinfo`
--
ALTER TABLE `cus_payinfo`
  ADD CONSTRAINT `cus_payinfo_payinfo_fk` FOREIGN KEY (`_payinfo`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_reward`
--
ALTER TABLE `cus_reward`
  ADD CONSTRAINT `cus_reward_rewardid_fk` FOREIGN KEY (`_rewardid`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_sstructuredetails`
--
ALTER TABLE `cus_sstructuredetails`
  ADD CONSTRAINT `cus_sstructuredetails_structureno_fk` FOREIGN KEY (`_structureno`) REFERENCES `cus_salarystructure` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_training`
--
ALTER TABLE `cus_training`
  ADD CONSTRAINT `cus_training_training_fk` FOREIGN KEY (`_training`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cus_transfer`
--
ALTER TABLE `cus_transfer`
  ADD CONSTRAINT `cus_transfer_transfer_fk` FOREIGN KEY (`_transfer`) REFERENCES `cus_employees` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_costdetail`
--
ALTER TABLE `inv_costdetail`
  ADD CONSTRAINT `inv_costdetail_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_cost` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_costdetail_fk2` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_costitemdetail`
--
ALTER TABLE `inv_costitemdetail`
  ADD CONSTRAINT `inv_costitemdetail_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_cost` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_costitemdetail_fk2` FOREIGN KEY (`_item`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_item`
--
ALTER TABLE `inv_item`
  ADD CONSTRAINT `inv_item_fk1` FOREIGN KEY (`_unit`) REFERENCES `inv_unit` (`_unit`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_fk2` FOREIGN KEY (`_category`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchase`
--
ALTER TABLE `inv_purchase`
  ADD CONSTRAINT `inv_purchase_fk1` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchaseaccount`
--
ALTER TABLE `inv_purchaseaccount`
  ADD CONSTRAINT `inv_purchaseaccount_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_purchase` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchaseaccount_fk2` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchasedetail`
--
ALTER TABLE `inv_purchasedetail`
  ADD CONSTRAINT `inv_purchasedetail_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_purchase` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchasedetail_fk2` FOREIGN KEY (`_item`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchasedetail_fk3` FOREIGN KEY (`_store`) REFERENCES `inv_store` (`_store`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchasedetail_fk4` FOREIGN KEY (`_batchlot`) REFERENCES `inv_batchlot` (`_batchlot`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchaseorder`
--
ALTER TABLE `inv_purchaseorder`
  ADD CONSTRAINT `inv_purchaseorder_fk1` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchaseorder_fk3` FOREIGN KEY (`_currency`) REFERENCES `sys_currency` (`_name`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchaseorderdetail`
--
ALTER TABLE `inv_purchaseorderdetail`
  ADD CONSTRAINT `inv_purchaseorderdetail_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_purchaseorder` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchaseorderdetail_fk2` FOREIGN KEY (`_item`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchasereturn`
--
ALTER TABLE `inv_purchasereturn`
  ADD CONSTRAINT `inv_purchasereturn_fk1` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchasereturnaccount`
--
ALTER TABLE `inv_purchasereturnaccount`
  ADD CONSTRAINT `inv_purchasereturnaccount_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_purchasereturn` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchasereturnaccount_fk2` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchasereturndetail`
--
ALTER TABLE `inv_purchasereturndetail`
  ADD CONSTRAINT `inv_purchasereturndetail_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_purchasereturn` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchasereturndetail_fk2` FOREIGN KEY (`_item`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchasereturndetail_fk3` FOREIGN KEY (`_batchlot`) REFERENCES `inv_batchlot` (`_batchlot`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchasereturndetail_fk4` FOREIGN KEY (`_store`) REFERENCES `inv_store` (`_store`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_purchaseslcode`
--
ALTER TABLE `inv_purchaseslcode`
  ADD CONSTRAINT `inv_purchaseslcode_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_purchase` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_purchaseslcode_fk2` FOREIGN KEY (`_item`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_sales`
--
ALTER TABLE `inv_sales`
  ADD CONSTRAINT `inv_sales_fk1` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_salesaccount`
--
ALTER TABLE `inv_salesaccount`
  ADD CONSTRAINT `inv_salesaccount_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_sales` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesaccount_fk2` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_salesdetail`
--
ALTER TABLE `inv_salesdetail`
  ADD CONSTRAINT `inv_salesdetail_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_sales` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesdetail_fk2` FOREIGN KEY (`_item`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesdetail_fk3` FOREIGN KEY (`_batchlot`) REFERENCES `inv_batchlot` (`_batchlot`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesdetail_fk4` FOREIGN KEY (`_store`) REFERENCES `inv_store` (`_store`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_salesorder`
--
ALTER TABLE `inv_salesorder`
  ADD CONSTRAINT `inv_salesorder_fk1` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesorder_fk2` FOREIGN KEY (`_currency`) REFERENCES `sys_currency` (`_name`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_salesorderdetail`
--
ALTER TABLE `inv_salesorderdetail`
  ADD CONSTRAINT `inv_salesorderdetail_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_salesorder` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesorderdetail_fk2` FOREIGN KEY (`_item`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_salesreturn`
--
ALTER TABLE `inv_salesreturn`
  ADD CONSTRAINT `inv_salesreturn_fk1` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_salesreturnaccount`
--
ALTER TABLE `inv_salesreturnaccount`
  ADD CONSTRAINT `inv_salesreturnaccount_fk1` FOREIGN KEY (`_no`) REFERENCES `inv_salesreturn` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesreturnaccount_fk2` FOREIGN KEY (`_ledger`) REFERENCES `acc_ledger` (`_ledger`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_salesreturndetail`
--
ALTER TABLE `inv_salesreturndetail`
  ADD CONSTRAINT `inv_salesreturndetail_fk1` FOREIGN KEY (`_item`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesreturndetail_fk2` FOREIGN KEY (`_no`) REFERENCES `inv_salesreturn` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesreturndetail_fk3` FOREIGN KEY (`_store`) REFERENCES `inv_store` (`_store`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_salesreturndetail_fk4` FOREIGN KEY (`_batchlot`) REFERENCES `inv_batchlot` (`_batchlot`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_stockindetail`
--
ALTER TABLE `inv_stockindetail`
  ADD CONSTRAINT `inv_stockindetail_fk1` FOREIGN KEY (`_ino`) REFERENCES `inv_stock` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stockindetail_fk2` FOREIGN KEY (`_iitem`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stockindetail_fk3` FOREIGN KEY (`_ibatchlot`) REFERENCES `inv_batchlot` (`_batchlot`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stockindetail_fk4` FOREIGN KEY (`_istore`) REFERENCES `inv_store` (`_store`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_stockoutdetail`
--
ALTER TABLE `inv_stockoutdetail`
  ADD CONSTRAINT `inv_stockoutdetail_fk1` FOREIGN KEY (`_ono`) REFERENCES `inv_stock` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stockoutdetail_fk2` FOREIGN KEY (`_oitem`) REFERENCES `inv_item` (`_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stockoutdetail_fk3` FOREIGN KEY (`_obatchlot`) REFERENCES `inv_batchlot` (`_batchlot`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stockoutdetail_fk4` FOREIGN KEY (`_ostore`) REFERENCES `inv_store` (`_store`) ON UPDATE CASCADE;

--
-- Constraints for table `sys_customformfields`
--
ALTER TABLE `sys_customformfields`
  ADD CONSTRAINT `sys_customformfields_fk1` FOREIGN KEY (`_tableid`) REFERENCES `sys_customform` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `sys_filterfields`
--
ALTER TABLE `sys_filterfields`
  ADD CONSTRAINT `sys_filterfields_ibfk_1` FOREIGN KEY (`_filterid`) REFERENCES `sys_customfilter` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sys_reportfields`
--
ALTER TABLE `sys_reportfields`
  ADD CONSTRAINT `sys_reportfields_ibfk_1` FOREIGN KEY (`_reportid`) REFERENCES `sys_customreport` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
