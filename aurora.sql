/*
SQLyog Ultimate v8.55 
MySQL - 5.2.0-falcon-alpha-community-nt : Database - aurora
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`aurora` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `aurora`;

/*Table structure for table `area` */

DROP TABLE IF EXISTS `area`;

CREATE TABLE `area` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `area_id` char(32) NOT NULL DEFAULT '',
  `area_name` varchar(50) NOT NULL DEFAULT '',
  `printer_id` char(32) NOT NULL DEFAULT '',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`area_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `area` */

LOCK TABLES `area` WRITE;

insert  into `area`(`branch_id`,`area_id`,`area_name`,`printer_id`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','19bbcc6bbc973464b839989cc117aaef','Main Hall','','ENABLED','2014-07-20 05:41:28','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','a7a9c70607408fd0afebf9706470b8df','Main Restaurant','','ENABLED','2014-06-13 14:24:52','0b161a3f8ca81d127ffcbd651b46c6c5','2014-06-13 14:25:24','0b161a3f8ca81d127ffcbd651b46c6c5');

UNLOCK TABLES;

/*Table structure for table `associate` */

DROP TABLE IF EXISTS `associate`;

CREATE TABLE `associate` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `associate_id` char(32) NOT NULL DEFAULT '',
  `associate_name` varchar(50) NOT NULL DEFAULT '',
  `associate_code` varchar(50) NOT NULL DEFAULT '',
  `associate_type` enum('SUPPLIER','CLIENT','BOTH') NOT NULL DEFAULT 'BOTH',
  `physical_address` varchar(255) NOT NULL DEFAULT '',
  `physical_city` varchar(50) NOT NULL DEFAULT '',
  `physical_province` varchar(50) NOT NULL DEFAULT '',
  `physical_zip_code` varchar(15) NOT NULL DEFAULT '',
  `mailing_address` varchar(255) NOT NULL DEFAULT '',
  `mailing_city` varchar(50) NOT NULL DEFAULT '',
  `mailing_province` varchar(50) NOT NULL DEFAULT '',
  `mailing_zip_code` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `phone2` varchar(50) NOT NULL DEFAULT '',
  `fax` varchar(50) NOT NULL DEFAULT '',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`associate_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `associate` */

LOCK TABLES `associate` WRITE;

insert  into `associate`(`branch_id`,`associate_id`,`associate_name`,`associate_code`,`associate_type`,`physical_address`,`physical_city`,`physical_province`,`physical_zip_code`,`mailing_address`,`mailing_city`,`mailing_province`,`mailing_zip_code`,`email`,`phone`,`phone2`,`fax`,`remarks`,`photo_url`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','5f44ee6a2b517af89686b878a3f5b9b1','Good Market','','SUPPLIER','','','','','','','','','','','','','','','N','ENABLED','2014-06-14 09:36:23','0b161a3f8ca81d127ffcbd651b46c6c5','2014-06-14 09:36:45','0b161a3f8ca81d127ffcbd651b46c6c5');

UNLOCK TABLES;

/*Table structure for table `associate_contact_link` */

DROP TABLE IF EXISTS `associate_contact_link`;

CREATE TABLE `associate_contact_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `associate_contact_id` char(32) NOT NULL DEFAULT '',
  `associate_id` char(32) NOT NULL DEFAULT '',
  `contact_id` char(32) NOT NULL DEFAULT '',
  `is_primary_contact` enum('Y','N') NOT NULL DEFAULT 'N',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`associate_contact_id`),
  KEY `SYNC` (`created_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `associate_contact_link` */

LOCK TABLES `associate_contact_link` WRITE;

insert  into `associate_contact_link`(`branch_id`,`associate_contact_id`,`associate_id`,`contact_id`,`is_primary_contact`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','8a5272d83073b77ed1b3570d1badf3f2','5f44ee6a2b517af89686b878a3f5b9b1','3e976f91167119e5349a1c5fab3ff77b','N','2014-06-14 10:37:50','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `branch` */

DROP TABLE IF EXISTS `branch`;

CREATE TABLE `branch` (
  `branch_id` char(32) NOT NULL DEFAULT '' COMMENT 'Unique global Id',
  `registration_key` tinyblob NOT NULL,
  `server_id` char(32) NOT NULL DEFAULT '',
  `host_address` varchar(255) NOT NULL DEFAULT '',
  `parent_branch_id` char(32) NOT NULL DEFAULT '' COMMENT 'Id of default main branch',
  `branch_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'Unique branch code used for transactions',
  `branch_name` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `province` varchar(50) NOT NULL DEFAULT '',
  `zip_code` varchar(15) NOT NULL DEFAULT '',
  `country` varchar(75) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `phone2` varchar(50) NOT NULL DEFAULT '',
  `fax` varchar(50) NOT NULL DEFAULT '',
  `website` varchar(255) NOT NULL DEFAULT '',
  `general_manager` varchar(255) NOT NULL DEFAULT '',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`branch_id`),
  KEY `LIST` (`status`,`parent_branch_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `branch` */

LOCK TABLES `branch` WRITE;

insert  into `branch`(`branch_id`,`registration_key`,`server_id`,`host_address`,`parent_branch_id`,`branch_code`,`branch_name`,`address`,`city`,`province`,`zip_code`,`country`,`email`,`phone`,`phone2`,`fax`,`website`,`general_manager`,`photo_url`,`status`,`created_on`,`modified_on`,`modified_by`) values ('a75c2b6af02f387f27852951c970492a','','a75c2b6af02f387897138d43bd090c8d','192.168.0.7','','CAB001','Cabalen Pampanga','','','','','','','','','','','','','ENABLED','0000-00-00 00:00:00','2014-05-25 14:04:13','a75c2b6af02f387f27852951c970492a'),('e0edd35d5e9eab9897138d43bd090c8d','','a75c2b6af02f387897138d43bd090c8d','192.168.0.7','a75c2b6af02f387f27852951c970492a','CAB002','Cabalen Ortigas','','','','','','','','','','','','','ENABLED','0000-00-00 00:00:00','2014-07-19 16:24:50','0b161a3f8ca81d127ffcbd651b46c6c5');

UNLOCK TABLES;

/*Table structure for table `branch_variable` */

DROP TABLE IF EXISTS `branch_variable`;

CREATE TABLE `branch_variable` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `branch_variable_id` char(32) NOT NULL DEFAULT '',
  `variable_name` varchar(50) NOT NULL DEFAULT '' COMMENT 'Variable name used for customized content',
  `variable_value` varchar(255) NOT NULL DEFAULT '' COMMENT 'Value displayed based on variable name',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`branch_variable_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `branch_variable` */

LOCK TABLES `branch_variable` WRITE;

insert  into `branch_variable`(`branch_id`,`branch_variable_id`,`variable_name`,`variable_value`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','90b6f39383ed0ad9bb38b5518056ad3f','tin','123-454-000-113','2014-06-13 09:51:08','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `contact` */

DROP TABLE IF EXISTS `contact`;

CREATE TABLE `contact` (
  `branch_id` char(32) NOT NULL DEFAULT '' COMMENT 'Accessible to all if branch_id is empty',
  `contact_id` char(32) NOT NULL DEFAULT '',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `department` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(250) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `province` varchar(50) NOT NULL DEFAULT '',
  `zip_code` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `phone2` varchar(50) NOT NULL DEFAULT '',
  `fax` varchar(50) NOT NULL DEFAULT '',
  `instant_message_id` varchar(255) NOT NULL DEFAULT '',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Determines if this record is useable(read only) to child branches',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`contact_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `contact` */

LOCK TABLES `contact` WRITE;

insert  into `contact`(`branch_id`,`contact_id`,`first_name`,`last_name`,`department`,`address`,`city`,`province`,`zip_code`,`email`,`phone`,`phone2`,`fax`,`instant_message_id`,`remarks`,`photo_url`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','3e976f91167119e5349a1c5fab3ff77b','Mark','Zuckerass','SUPPLIER','','','','','gm@yahoo.com','','','','','','','N','ENABLED','2014-06-14 10:01:53','0b161a3f8ca81d127ffcbd651b46c6c5','2014-06-14 10:03:38','0b161a3f8ca81d127ffcbd651b46c6c5');

UNLOCK TABLES;

/*Table structure for table `custom_module` */

DROP TABLE IF EXISTS `custom_module`;

CREATE TABLE `custom_module` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `module_id` char(32) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `custom_module` */

LOCK TABLES `custom_module` WRITE;

insert  into `custom_module`(`branch_id`,`module_id`,`name`,`description`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','barcode_read','Barcode Reader','none','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','super_viewer','Super Viewer','none','0000-00-00 00:00:00','','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `custom_module_access` */

DROP TABLE IF EXISTS `custom_module_access`;

CREATE TABLE `custom_module_access` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `module_id` char(32) NOT NULL DEFAULT '',
  `access_id` char(32) NOT NULL DEFAULT '',
  `access_name` varchar(50) NOT NULL DEFAULT '',
  `access_code` varchar(50) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `custom_module_access` */

LOCK TABLES `custom_module_access` WRITE;

insert  into `custom_module_access`(`branch_id`,`module_id`,`access_id`,`access_name`,`access_code`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','barcode_read','e0edd35d5e9eab9897138d43bd090c8d','Read','READ','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','barcode_read','e0edd35d5e9eab9897138d43ad090c8d','Write','READ+WRITE','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','super_viewer','e0edd35d5e9eab9897138d43ad090c8a','Read','READ','0000-00-00 00:00:00','','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `customer` */

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `customer_id` char(32) NOT NULL DEFAULT '',
  `customer_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'Optional customer code (used for membership)',
  `title` enum('MR','MISS','MS','MRS','U') NOT NULL DEFAULT 'U',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `date_of_birth` date NOT NULL DEFAULT '0000-00-00',
  `address` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `province` varchar(50) NOT NULL DEFAULT '',
  `zip_code` varchar(15) NOT NULL DEFAULT '',
  `alt_address` varchar(255) NOT NULL DEFAULT '',
  `alt_city` varchar(50) NOT NULL DEFAULT '',
  `alt_province` varchar(50) NOT NULL DEFAULT '',
  `alt_zip_code` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `phone2` varchar(50) NOT NULL DEFAULT '',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`customer_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `customer` */

LOCK TABLES `customer` WRITE;

insert  into `customer`(`branch_id`,`customer_id`,`customer_code`,`title`,`first_name`,`last_name`,`date_of_birth`,`address`,`city`,`province`,`zip_code`,`alt_address`,`alt_city`,`alt_province`,`alt_zip_code`,`email`,`phone`,`phone2`,`remarks`,`photo_url`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','f67ca2aff6399e9d25804dce7cf3a34b','','MR','Migz','Lat','1900-01-01','','','','','','','','','','','','','','ENABLED','2014-07-01 10:18:11','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `customer_variable` */

DROP TABLE IF EXISTS `customer_variable`;

CREATE TABLE `customer_variable` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `customer_id` char(32) NOT NULL DEFAULT '',
  `customer_variable_id` char(32) NOT NULL DEFAULT '',
  `variable_name` varchar(50) NOT NULL DEFAULT '',
  `variable_value` varchar(255) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`customer_variable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `customer_variable` */

LOCK TABLES `customer_variable` WRITE;

insert  into `customer_variable`(`branch_id`,`customer_id`,`customer_variable_id`,`variable_name`,`variable_value`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','f67ca2aff6399e9d25804dce7cf3a34b','7f7ceada6e11b95a2e138dc9679825e9','palm_card_no','123123123123','2014-07-01 10:49:17','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `deleted_data` */

DROP TABLE IF EXISTS `deleted_data`;

CREATE TABLE `deleted_data` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `row_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) NOT NULL DEFAULT '',
  `primary_key` varchar(255) NOT NULL DEFAULT '',
  `primary_key_value` char(32) NOT NULL DEFAULT '',
  `deleted_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `deleted_data` */

LOCK TABLES `deleted_data` WRITE;

insert  into `deleted_data`(`branch_id`,`row_id`,`table_name`,`primary_key`,`primary_key_value`,`deleted_on`,`deleted_by`) values ('e0edd35d5e9eab9897138d43bd090c8d',1,'department','department_id','05018fd762eff81b253baf800018cffe','2014-07-27 04:11:17','0b161a3f8ca81d127ffcbd651b46c6c5');

UNLOCK TABLES;

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `department_id` char(32) NOT NULL DEFAULT '',
  `department_name` varchar(50) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`department_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `department` */

LOCK TABLES `department` WRITE;

insert  into `department`(`branch_id`,`department_id`,`department_name`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('a75c2b6af02f387f27852951c970492a','33b94974485a7b7137baca06815163a1','Waiter','N','ENABLED','2014-05-25 15:37:02','a75c2b6af02f387f27852951c970492a','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','5be0b8c78a780464b67c418bd4eb9a0c','Managing','Y','ENABLED','2014-06-28 10:30:14','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','6d95bc981a75774057424807f83d0f76','Service','Y','ENABLED','2014-06-28 10:19:33','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','a217a9bbb917f1fd2625416f8fb9f853','Production','Y','ENABLED','2014-06-28 09:48:56','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','ac1f791ae6c0dd386a06b6c0c6ddaad4','update test','N','ENABLED','2014-06-28 11:52:35','0b161a3f8ca81d127ffcbd651b46c6c5','2014-06-28 11:53:15','0b161a3f8ca81d127ffcbd651b46c6c5'),('e0edd35d5e9eab9897138d43bd090c8d','e21b1f89fbd5b1e854c673c0d5980458','Administrator','Y','ENABLED','2014-06-28 09:48:01','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `discount` */

DROP TABLE IF EXISTS `discount`;

CREATE TABLE `discount` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `discount_id` char(32) NOT NULL DEFAULT '',
  `discount_name` varchar(30) NOT NULL DEFAULT '',
  `print_label` varchar(30) NOT NULL DEFAULT '',
  `multiplier` decimal(5,4) unsigned NOT NULL DEFAULT '1.0000',
  `constant` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `discount_type` enum('STANDARD') NOT NULL DEFAULT 'STANDARD',
  `applicable_to` enum('ITEM','TRANSACTION','BOTH') NOT NULL DEFAULT 'BOTH',
  `require_authorization` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Determines if this modifier requires manual authorization',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`discount_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `discount` */

LOCK TABLES `discount` WRITE;

insert  into `discount`(`branch_id`,`discount_id`,`discount_name`,`print_label`,`multiplier`,`constant`,`discount_type`,`applicable_to`,`require_authorization`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','99199164fb14fc4876ef7299606330cc','E-VAT','','1.1200','0.0000','STANDARD','BOTH','N','N','ENABLED','2014-06-14 08:55:22','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `global_setting` */

DROP TABLE IF EXISTS `global_setting`;

CREATE TABLE `global_setting` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `global_setting_id` char(32) NOT NULL DEFAULT '',
  `time_offset` decimal(4,2) NOT NULL DEFAULT '0.00',
  `currency_symbol` tinyblob NOT NULL,
  `max_covers` smallint(5) unsigned NOT NULL DEFAULT '1',
  `turn_time_in_minutes` smallint(5) unsigned NOT NULL DEFAULT '20',
  `quick_transaction_default_type` char(32) NOT NULL DEFAULT '',
  `table_transaction_default_type` char(32) NOT NULL DEFAULT '',
  `tab_transaction_default_type` char(32) NOT NULL DEFAULT '',
  `tax_computation_default_type` enum('INCLUSIVE','EXCLUSIVE') NOT NULL DEFAULT 'INCLUSIVE',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`global_setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `global_setting` */

LOCK TABLES `global_setting` WRITE;

insert  into `global_setting`(`branch_id`,`global_setting_id`,`time_offset`,`currency_symbol`,`max_covers`,`turn_time_in_minutes`,`quick_transaction_default_type`,`table_transaction_default_type`,`tab_transaction_default_type`,`tax_computation_default_type`,`created_on`,`modified_on`,`modified_by`) values ('a75c2b6af02f387f27852951c970492a','a75c2b6af02f387f27852951c970492a','0.00','E282B1',1,20,'6f4e60be87221c86f8d6043d2c912636','6f4e60be87221c86f8d6043d2c912636','6f4e60be87221c86f8d6043d2c912636','INCLUSIVE','0000-00-00 00:00:00','2014-07-09 10:35:49','0b161a3f8ca81d127ffcbd651b46c6c5');

UNLOCK TABLES;

/*Table structure for table `happy_hour` */

DROP TABLE IF EXISTS `happy_hour`;

CREATE TABLE `happy_hour` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `happy_hour_id` char(32) NOT NULL DEFAULT '',
  `happy_hour_name` varchar(50) NOT NULL DEFAULT '',
  `multiplier` decimal(5,4) unsigned NOT NULL DEFAULT '1.0000',
  `constant` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `apply_discount_on_modifier` enum('NO','FORCED ONLY','OPTIONAL ONLY','BOTH') NOT NULL DEFAULT 'NO',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`happy_hour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `happy_hour` */

LOCK TABLES `happy_hour` WRITE;

insert  into `happy_hour`(`branch_id`,`happy_hour_id`,`happy_hour_name`,`multiplier`,`constant`,`apply_discount_on_modifier`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','defc4b25c22294176fa6ba2091099ad6','After Dark','0.8000','0.0000','NO','ENABLED','2014-08-22 05:28:29','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `happy_hour_timing` */

DROP TABLE IF EXISTS `happy_hour_timing`;

CREATE TABLE `happy_hour_timing` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `happy_hour_id` char(32) NOT NULL DEFAULT '',
  `happy_hour_timing_id` char(32) NOT NULL DEFAULT '',
  `sun` enum('Y','N') NOT NULL DEFAULT 'Y',
  `mon` enum('Y','N') NOT NULL DEFAULT 'Y',
  `tue` enum('Y','N') NOT NULL DEFAULT 'Y',
  `wed` enum('Y','N') NOT NULL DEFAULT 'Y',
  `thu` enum('Y','N') NOT NULL DEFAULT 'Y',
  `fri` enum('Y','N') NOT NULL DEFAULT 'Y',
  `sat` enum('Y','N') NOT NULL DEFAULT 'Y',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`happy_hour_timing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `happy_hour_timing` */

LOCK TABLES `happy_hour_timing` WRITE;

insert  into `happy_hour_timing`(`branch_id`,`happy_hour_id`,`happy_hour_timing_id`,`sun`,`mon`,`tue`,`wed`,`thu`,`fri`,`sat`,`start_time`,`end_time`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('','','','Y','Y','Y','Y','Y','Y','Y','00:00:00','00:00:00','ENABLED','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','defc4b25c22294176fa6ba2091099ad6','a59846c4ec3addc7b50feaefa383d34b','Y','Y','Y','Y','Y','Y','Y','18:00:00','20:00:00','ENABLED','2014-08-22 05:41:18','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `inventory_group` */

DROP TABLE IF EXISTS `inventory_group`;

CREATE TABLE `inventory_group` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `inventory_group_id` char(32) NOT NULL DEFAULT '',
  `inventory_group_name` varchar(50) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`inventory_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `inventory_group` */

LOCK TABLES `inventory_group` WRITE;

insert  into `inventory_group`(`branch_id`,`inventory_group_id`,`inventory_group_name`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','0a2df8b136c9e14f52edfad0d5cd0726','Bottles','N','ENABLED','2014-07-17 16:17:29','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `inventory_item_link` */

DROP TABLE IF EXISTS `inventory_item_link`;

CREATE TABLE `inventory_item_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `inventory_item_id` char(32) NOT NULL DEFAULT '',
  `inventory_group_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`inventory_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `inventory_item_link` */

LOCK TABLES `inventory_item_link` WRITE;

insert  into `inventory_item_link`(`branch_id`,`inventory_item_id`,`inventory_group_id`,`item_id`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','79068314106f7dadfeccb76033e911f9','0a2df8b136c9e14f52edfad0d5cd0726','3caea12ca4f562401852d396b4f465a3','2014-07-20 12:11:26','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `item` */

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `item_name` varchar(50) NOT NULL DEFAULT '',
  `item_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'Used for short hand display',
  `item_sku` varchar(50) NOT NULL DEFAULT '' COMMENT 'Used for inventory tracking',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Optional description',
  `unit_of_measure` varchar(50) NOT NULL DEFAULT 'item',
  `item_cost` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT 'Price of item from supplier or production cost',
  `item_price` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT 'Item''s selling price',
  `kitchen_area_id` char(32) NOT NULL DEFAULT '' COMMENT 'Optional. Overrides the default kitchen area used in menu category',
  `preparation_time` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Amount of preparation time in minutes ',
  `alert_level` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Amount of item to trigger restock alert',
  `max_stock` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Maximum amount of item to store',
  `re_order_quantity` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Quantity of stock to order from the supplier',
  `item_purchase_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'Code use when generating purchase orders',
  `main_supplier_id` char(32) NOT NULL DEFAULT '' COMMENT 'Item will be ordered by default from this supplier',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `tax_setting` enum('INHERIT','TAXABLE','NON-TAXABLE','EXEMPTED','ZERO RATED') NOT NULL DEFAULT 'INHERIT',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `item` */

LOCK TABLES `item` WRITE;

insert  into `item`(`branch_id`,`item_id`,`item_name`,`item_code`,`item_sku`,`description`,`unit_of_measure`,`item_cost`,`item_price`,`kitchen_area_id`,`preparation_time`,`alert_level`,`max_stock`,`re_order_quantity`,`item_purchase_code`,`main_supplier_id`,`photo_url`,`tax_setting`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','3862060db1de8790312db3e5c954a491','Regular Burger','Regular Burger','','','item','0.0000','120.0000','19bbcc6bbc973464b839989cc117aaef',0,'5.00','1000.00','1000.00','RGRBGR','5f44ee6a2b517af89686b878a3f5b9b1','','INHERIT','N','ENABLED','2014-07-20 08:06:28','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','3caea12ca4f562401852d396b4f465a3','Cheese Burger','Cheese Burger','','','item','0.0000','120.0000','19bbcc6bbc973464b839989cc117aaef',0,'5.00','1000.00','1000.00','CHBGR','5f44ee6a2b517af89686b878a3f5b9b1','','INHERIT','N','ENABLED','2014-07-20 08:03:42','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `item_happy_hour_link` */

DROP TABLE IF EXISTS `item_happy_hour_link`;

CREATE TABLE `item_happy_hour_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `item_happy_hour_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `happy_hour_id` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_happy_hour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `item_happy_hour_link` */

LOCK TABLES `item_happy_hour_link` WRITE;

insert  into `item_happy_hour_link`(`branch_id`,`item_happy_hour_id`,`item_id`,`happy_hour_id`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','2f245eb77fe4d22041c816f6849bdf2e','3caea12ca4f562401852d396b4f465a3','defc4b25c22294176fa6ba2091099ad6','2014-08-22 05:59:29','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `item_ingredient_link` */

DROP TABLE IF EXISTS `item_ingredient_link`;

CREATE TABLE `item_ingredient_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `item_ingredient_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `ingredient_id` char(32) NOT NULL DEFAULT '',
  `quantity` decimal(10,4) NOT NULL DEFAULT '1.0000' COMMENT 'Amount of ingredient used based on unit of stock of the item',
  `wastage_multiplier` decimal(10,4) unsigned NOT NULL DEFAULT '1.0000' COMMENT 'Used for waste calculation during production',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_ingredient_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `item_ingredient_link` */

LOCK TABLES `item_ingredient_link` WRITE;

insert  into `item_ingredient_link`(`branch_id`,`item_ingredient_id`,`item_id`,`ingredient_id`,`quantity`,`wastage_multiplier`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','fbec885bda92922b05d73633085ec40a','3caea12ca4f562401852d396b4f465a3','3862060db1de8790312db3e5c954a491','2.0000','1.0000','2014-07-27 05:07:53','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `item_modifier_link` */

DROP TABLE IF EXISTS `item_modifier_link`;

CREATE TABLE `item_modifier_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `item_modifier_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `modifier_id` char(32) NOT NULL DEFAULT '',
  `priority_level` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_modifier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `item_modifier_link` */

LOCK TABLES `item_modifier_link` WRITE;

insert  into `item_modifier_link`(`branch_id`,`item_modifier_id`,`item_id`,`modifier_id`,`priority_level`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','ed9a67d4cc5b2144aad74a57ca4da6fd','3caea12ca4f562401852d396b4f465a3','a8bb113919ee0a10f2dd1c7a8cc2620a',0,'2014-07-21 15:57:14','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `item_special_availability` */

DROP TABLE IF EXISTS `item_special_availability`;

CREATE TABLE `item_special_availability` (
  `item_id` char(32) NOT NULL DEFAULT '',
  `day_number` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `available_from` time NOT NULL DEFAULT '00:00:00',
  `available_until` time NOT NULL DEFAULT '00:00:00',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_id`,`day_number`),
  KEY `SYNC` (`item_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `item_special_availability` */

LOCK TABLES `item_special_availability` WRITE;

UNLOCK TABLES;

/*Table structure for table `item_supplier_link` */

DROP TABLE IF EXISTS `item_supplier_link`;

CREATE TABLE `item_supplier_link` (
  `supplier_id` char(32) NOT NULL DEFAULT '' COMMENT 'Foreign key of associate_id',
  `item_sku` varchar(50) NOT NULL DEFAULT '',
  `supplier_price` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`supplier_id`,`item_sku`),
  KEY `SYNC` (`supplier_id`,`created_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `item_supplier_link` */

LOCK TABLES `item_supplier_link` WRITE;

UNLOCK TABLES;

/*Table structure for table `item_tag_link` */

DROP TABLE IF EXISTS `item_tag_link`;

CREATE TABLE `item_tag_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `item_tag_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `tag_id` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `item_tag_link` */

LOCK TABLES `item_tag_link` WRITE;

UNLOCK TABLES;

/*Table structure for table `item_time_sitting_link` */

DROP TABLE IF EXISTS `item_time_sitting_link`;

CREATE TABLE `item_time_sitting_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `item_time_sitting_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `time_sitting_id` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_time_sitting_id`),
  KEY `SYNC` (`item_id`,`created_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `item_time_sitting_link` */

LOCK TABLES `item_time_sitting_link` WRITE;

UNLOCK TABLES;

/*Table structure for table `location` */

DROP TABLE IF EXISTS `location`;

CREATE TABLE `location` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `area_id` char(32) NOT NULL DEFAULT '',
  `location_id` char(32) NOT NULL DEFAULT '',
  `location_name` varchar(50) NOT NULL DEFAULT '' COMMENT 'Name of the location (can be a table,kitchen,function room etc.)',
  `max_covers` smallint(5) unsigned NOT NULL DEFAULT '0',
  `remarks` varchar(500) NOT NULL DEFAULT '',
  `condition_status` enum('AVAILABLE','DIRTY','BROKEN','UNAVAILABLE') NOT NULL DEFAULT 'AVAILABLE',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`location_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `location` */

LOCK TABLES `location` WRITE;

insert  into `location`(`branch_id`,`area_id`,`location_id`,`location_name`,`max_covers`,`remarks`,`condition_status`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','a7a9c70607408fd0afebf9706470b8df','1c1e403138a1c4c94d82bfd5bdc21a83','TBL1',2,'','AVAILABLE','ENABLED','2014-06-13 15:19:11','0b161a3f8ca81d127ffcbd651b46c6c5','2014-06-13 15:28:16','0b161a3f8ca81d127ffcbd651b46c6c5'),('e0edd35d5e9eab9897138d43bd090c8d','a7a9c70607408fd0afebf9706470b8df','cf5d9f6d93801b1c7aa311adb6312a5c','Table 5',10,'','AVAILABLE','ENABLED','2014-07-01 04:08:56','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `location_variable` */

DROP TABLE IF EXISTS `location_variable`;

CREATE TABLE `location_variable` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `location_id` char(32) NOT NULL DEFAULT '',
  `location_variable_id` char(32) NOT NULL DEFAULT '',
  `variable_name` varchar(50) NOT NULL DEFAULT '',
  `variable_value` varchar(255) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`location_variable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `location_variable` */

LOCK TABLES `location_variable` WRITE;

insert  into `location_variable`(`branch_id`,`location_id`,`location_variable_id`,`variable_name`,`variable_value`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','1c1e403138a1c4c94d82bfd5bdc21a83','613f5763da61ca35376fafa64fd0777c','x_coordinate','123.9999','2014-06-13 16:47:01','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `menu_category` */

DROP TABLE IF EXISTS `menu_category`;

CREATE TABLE `menu_category` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `menu_category_id` char(32) NOT NULL DEFAULT '',
  `menu_category_name` varchar(50) NOT NULL DEFAULT '',
  `kitchen_area_id` char(32) NOT NULL DEFAULT '' COMMENT 'Kitchen area responsible for preparation',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`menu_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `menu_category` */

LOCK TABLES `menu_category` WRITE;

insert  into `menu_category`(`branch_id`,`menu_category_id`,`menu_category_name`,`kitchen_area_id`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','87ec15b80cf2f88a9d9edd6f695cf98f','Pizza','','N','ENABLED','2014-07-21 18:41:23','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','9a740d7d1f2f421e0f20ee2afb663b51','Chiken','','N','ENABLED','2014-07-21 18:41:20','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','d74fdf212bd1ae9a7c4c8056dc7b560b','Carbo','','N','ENABLED','2014-07-17 15:58:13','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','ee23959f6f202ba89aa835808dd4edfb','Pasta','','N','ENABLED','2014-07-21 18:41:12','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `menu_category_item_link` */

DROP TABLE IF EXISTS `menu_category_item_link`;

CREATE TABLE `menu_category_item_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `menu_category_item_id` char(32) NOT NULL DEFAULT '',
  `menu_category_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `priority_level` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`menu_category_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `menu_category_item_link` */

LOCK TABLES `menu_category_item_link` WRITE;

insert  into `menu_category_item_link`(`branch_id`,`menu_category_item_id`,`menu_category_id`,`item_id`,`priority_level`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','528d6945da34736f0062ff70bf35dd28','d74fdf212bd1ae9a7c4c8056dc7b560b','3caea12ca4f562401852d396b4f465a3',1,'2014-07-21 17:02:48','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','58be7a32bc143dc5c5f7250627eac91d','d74fdf212bd1ae9a7c4c8056dc7b560b','3862060db1de8790312db3e5c954a491',0,'2014-07-21 17:00:55','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `menu_category_modifier_link` */

DROP TABLE IF EXISTS `menu_category_modifier_link`;

CREATE TABLE `menu_category_modifier_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `menu_category_modifier_id` char(32) NOT NULL DEFAULT '',
  `menu_category_id` char(32) NOT NULL DEFAULT '',
  `modifier_id` char(32) NOT NULL DEFAULT '',
  `priority_level` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`menu_category_modifier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `menu_category_modifier_link` */

LOCK TABLES `menu_category_modifier_link` WRITE;

insert  into `menu_category_modifier_link`(`branch_id`,`menu_category_modifier_id`,`menu_category_id`,`modifier_id`,`priority_level`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','a762f1855e8efd72cf88c8b13d989828','d74fdf212bd1ae9a7c4c8056dc7b560b','a8bb113919ee0a10f2dd1c7a8cc2620a',0,'2014-07-21 16:22:21','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `menu_group` */

DROP TABLE IF EXISTS `menu_group`;

CREATE TABLE `menu_group` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `menu_group_id` char(32) NOT NULL DEFAULT '',
  `menu_group_name` varchar(50) NOT NULL DEFAULT '',
  `priority_level` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`menu_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `menu_group` */

LOCK TABLES `menu_group` WRITE;

insert  into `menu_group`(`branch_id`,`menu_group_id`,`menu_group_name`,`priority_level`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','14ba21925a7231f3ec2b33d2dd065966','Breakfast',1,'N','ENABLED','2014-07-17 15:34:02','0b161a3f8ca81d127ffcbd651b46c6c5','2014-07-17 15:34:45','0b161a3f8ca81d127ffcbd651b46c6c5'),('e0edd35d5e9eab9897138d43bd090c8d','6ba9e2548247460baa1df68c5f2b446c','Again',0,'N','ENABLED','2014-07-17 15:34:18','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `menu_group_category_link` */

DROP TABLE IF EXISTS `menu_group_category_link`;

CREATE TABLE `menu_group_category_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `menu_group_category_id` char(32) NOT NULL DEFAULT '',
  `menu_group_id` char(32) NOT NULL DEFAULT '',
  `menu_category_id` char(32) NOT NULL DEFAULT '',
  `priority_level` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`menu_group_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `menu_group_category_link` */

LOCK TABLES `menu_group_category_link` WRITE;

insert  into `menu_group_category_link`(`branch_id`,`menu_group_category_id`,`menu_group_id`,`menu_category_id`,`priority_level`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','0f5776b949c426cd7385780cd0a2509b','6ba9e2548247460baa1df68c5f2b446c','87ec15b80cf2f88a9d9edd6f695cf98f',2,'2014-07-21 18:42:54','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','5c393350eff797fd4adf8e15b400c914','6ba9e2548247460baa1df68c5f2b446c','9a740d7d1f2f421e0f20ee2afb663b51',1,'2014-07-21 18:42:41','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','71ef5ccc186f21f1256254075f4f45a9','6ba9e2548247460baa1df68c5f2b446c','d74fdf212bd1ae9a7c4c8056dc7b560b',0,'2014-07-19 09:28:16','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `modifier` */

DROP TABLE IF EXISTS `modifier`;

CREATE TABLE `modifier` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `modifier_id` char(32) NOT NULL DEFAULT '',
  `modifier_name` varchar(50) NOT NULL DEFAULT '',
  `selection_type` enum('SINGLE','MULTI') NOT NULL DEFAULT 'MULTI',
  `modifier_type` enum('REQUIRED','OPTIONAL') NOT NULL DEFAULT 'OPTIONAL',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`modifier_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `modifier` */

LOCK TABLES `modifier` WRITE;

insert  into `modifier`(`branch_id`,`modifier_id`,`modifier_name`,`selection_type`,`modifier_type`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','a8bb113919ee0a10f2dd1c7a8cc2620a','Drinks','SINGLE','REQUIRED','N','ENABLED','2014-07-21 09:46:58','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `modifier_option` */

DROP TABLE IF EXISTS `modifier_option`;

CREATE TABLE `modifier_option` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `modifier_id` char(32) NOT NULL DEFAULT '',
  `modifier_option_id` char(32) NOT NULL DEFAULT '',
  `modifier_option_name` varchar(50) NOT NULL DEFAULT '',
  `print_label` varchar(50) NOT NULL DEFAULT '',
  `price_change` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT 'Price modification (add or subtract) from main item',
  `follow_up_modifier_id` char(32) NOT NULL DEFAULT '',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `priority_level` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`modifier_option_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `modifier_option` */

LOCK TABLES `modifier_option` WRITE;

insert  into `modifier_option`(`branch_id`,`modifier_id`,`modifier_option_id`,`modifier_option_name`,`print_label`,`price_change`,`follow_up_modifier_id`,`photo_url`,`priority_level`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','a8bb113919ee0a10f2dd1c7a8cc2620a','14c8858b30f90de135adcfc854b9eb25','Juice','JC','1.5000','','',1,'ENABLED','2014-07-21 18:47:41','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','a8bb113919ee0a10f2dd1c7a8cc2620a','199fbbfcddb7ab188d22230872b610de','Royal','JC','1.5000','','',2,'ENABLED','2014-07-21 18:48:11','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','a8bb113919ee0a10f2dd1c7a8cc2620a','466cc4654299644e1a4ae2355aca0d0c','Coke','asd','3.5000','','',0,'ENABLED','2014-07-21 15:08:45','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `modifier_option_ingredient_link` */

DROP TABLE IF EXISTS `modifier_option_ingredient_link`;

CREATE TABLE `modifier_option_ingredient_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `modifier_option_ingredient_id` char(32) NOT NULL DEFAULT '',
  `modifier_option_id` char(32) NOT NULL DEFAULT '',
  `ingredient_id` char(32) NOT NULL DEFAULT '',
  `quantity` decimal(10,4) NOT NULL DEFAULT '1.0000',
  `wastage_multiplier` decimal(10,4) NOT NULL DEFAULT '1.0000',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`modifier_option_ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `modifier_option_ingredient_link` */

LOCK TABLES `modifier_option_ingredient_link` WRITE;

UNLOCK TABLES;

/*Table structure for table `operating_time` */

DROP TABLE IF EXISTS `operating_time`;

CREATE TABLE `operating_time` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `operating_time_id` char(32) NOT NULL DEFAULT '',
  `day_of_week` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `opening_time` time NOT NULL DEFAULT '00:00:00',
  `closing_time` time NOT NULL DEFAULT '00:00:00',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`operating_time_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `operating_time` */

LOCK TABLES `operating_time` WRITE;

insert  into `operating_time`(`branch_id`,`operating_time_id`,`day_of_week`,`opening_time`,`closing_time`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','d28302361a5bacc79d68ea06a687d231',6,'08:00:00','20:00:00','2014-06-21 16:29:52','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `payment_mode` */

DROP TABLE IF EXISTS `payment_mode`;

CREATE TABLE `payment_mode` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `payment_mode_id` char(32) NOT NULL DEFAULT '',
  `payment_mode_name` varchar(50) NOT NULL DEFAULT '',
  `print_label` varchar(50) NOT NULL DEFAULT '',
  `payment_mode_type` enum('STANDARD','CREDIT CARD','GIFT CARD','ACCOUNT') NOT NULL,
  `post_action` enum('CLOSE TAB','NO ACTION') NOT NULL DEFAULT 'CLOSE TAB',
  `require_data` enum('Y','N') NOT NULL DEFAULT 'N',
  `prompt_title` varchar(50) NOT NULL,
  `prompt_input_type` enum('NUMBER','TEXT') NOT NULL DEFAULT 'TEXT',
  `require_authorization` enum('Y','N') NOT NULL DEFAULT 'N',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`payment_mode_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `payment_mode` */

LOCK TABLES `payment_mode` WRITE;

insert  into `payment_mode`(`branch_id`,`payment_mode_id`,`payment_mode_name`,`print_label`,`payment_mode_type`,`post_action`,`require_data`,`prompt_title`,`prompt_input_type`,`require_authorization`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','6f8604e9530016f0275007a76d244bf8','Cash','','STANDARD','CLOSE TAB','N','','TEXT','N','N','ENABLED','2014-07-01 05:35:13','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `printer` */

DROP TABLE IF EXISTS `printer`;

CREATE TABLE `printer` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `printer_id` char(32) NOT NULL DEFAULT '',
  `printer_name` varchar(255) NOT NULL DEFAULT '',
  `data_type` enum('RAW','TEXT','RAW [FF appended]','RAW [FF auto]','NT EMF 1.003','NT EMF 1.006','NT EMF 1.007','NT EMF 1.008') NOT NULL DEFAULT 'RAW',
  `printer_address` varchar(255) NOT NULL DEFAULT '',
  `parameter_name` varchar(255) NOT NULL DEFAULT '',
  `printer_type` enum('DIRECT','VIRTUAL') NOT NULL DEFAULT 'DIRECT',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`printer_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `printer` */

LOCK TABLES `printer` WRITE;

insert  into `printer`(`branch_id`,`printer_id`,`printer_name`,`data_type`,`printer_address`,`parameter_name`,`printer_type`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','e6a154b1a0eca890b4279a493b3ea545','Kitchen Printer','RAW','','////Mr-Fantastic//POSIFLEX','DIRECT','ENABLED','2014-07-01 04:06:49','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `purchase_order` */

DROP TABLE IF EXISTS `purchase_order`;

CREATE TABLE `purchase_order` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `purchase_order_id` char(32) NOT NULL DEFAULT '',
  `purchase_order_number` int(10) unsigned NOT NULL DEFAULT '1',
  `revision_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `associate_id` char(32) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `remarks` varchar(1000) NOT NULL DEFAULT '',
  `order_on` date NOT NULL DEFAULT '0000-00-00',
  `ship_on` date NOT NULL DEFAULT '0000-00-00',
  `authorized_by` char(32) NOT NULL DEFAULT '',
  `purchase_order_status` enum('PENDING','CANCELED','APPROVED','IN-PROGRESS','RECEIVED') NOT NULL DEFAULT 'PENDING',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`purchase_order_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `purchase_order` */

LOCK TABLES `purchase_order` WRITE;

UNLOCK TABLES;

/*Table structure for table `purchase_order_detail` */

DROP TABLE IF EXISTS `purchase_order_detail`;

CREATE TABLE `purchase_order_detail` (
  `purchase_order_id` char(32) NOT NULL DEFAULT '',
  `item_sku` char(32) NOT NULL DEFAULT '',
  `unit_price` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `order_quantity` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `received_quantity` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `rejected_quantity` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `stocked_quantity` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(1) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`purchase_order_id`,`item_sku`),
  KEY `SYNC` (`purchase_order_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `purchase_order_detail` */

LOCK TABLES `purchase_order_detail` WRITE;

UNLOCK TABLES;

/*Table structure for table `reservation` */

DROP TABLE IF EXISTS `reservation`;

CREATE TABLE `reservation` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `customer_id` char(32) NOT NULL DEFAULT '',
  `reservation_id` char(32) NOT NULL DEFAULT '',
  `start_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remarks` varchar(8000) NOT NULL DEFAULT '',
  `reservation_status` enum('PENDING','NO SHOW','CANCELED','NOT YET SEATED','TABLE DIRTY','SEATED','COMPLETED') NOT NULL DEFAULT 'PENDING',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`branch_id`,`reservation_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `reservation` */

LOCK TABLES `reservation` WRITE;

UNLOCK TABLES;

/*Table structure for table `reservation_detail` */

DROP TABLE IF EXISTS `reservation_detail`;

CREATE TABLE `reservation_detail` (
  `reservation_id` char(32) NOT NULL DEFAULT '',
  `reservation_detail_id` char(32) NOT NULL DEFAULT '',
  `reserved_location_id` char(32) NOT NULL DEFAULT '',
  `covers` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Determines number of cover for this specific location reservation',
  `remarks` varchar(8000) NOT NULL DEFAULT '',
  `status` enum('ENABLED','DISABLED','REMOVED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`reservation_detail_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `reservation_detail` */

LOCK TABLES `reservation_detail` WRITE;

UNLOCK TABLES;

/*Table structure for table `reservation_promotion_link` */

DROP TABLE IF EXISTS `reservation_promotion_link`;

CREATE TABLE `reservation_promotion_link` (
  `reservation_id` char(32) NOT NULL DEFAULT '',
  `promotion_id` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`reservation_id`,`promotion_id`),
  KEY `SYNC` (`created_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `reservation_promotion_link` */

LOCK TABLES `reservation_promotion_link` WRITE;

UNLOCK TABLES;

/*Table structure for table `reservation_tag` */

DROP TABLE IF EXISTS `reservation_tag`;

CREATE TABLE `reservation_tag` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `reservation_tag_id` char(32) NOT NULL DEFAULT '',
  `reservation_tag` varchar(50) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED','REMOVED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`reservation_tag_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `reservation_tag` */

LOCK TABLES `reservation_tag` WRITE;

UNLOCK TABLES;

/*Table structure for table `reservation_tag_link` */

DROP TABLE IF EXISTS `reservation_tag_link`;

CREATE TABLE `reservation_tag_link` (
  `reservation_id` char(32) NOT NULL DEFAULT '',
  `reservation_tag_id` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`reservation_id`,`reservation_tag_id`),
  KEY `SYNC` (`reservation_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `reservation_tag_link` */

LOCK TABLES `reservation_tag_link` WRITE;

UNLOCK TABLES;

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `role_id` char(32) NOT NULL DEFAULT '',
  `role_name` varchar(50) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Determines if this record is useable(read only) to child branches',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`role_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `role` */

LOCK TABLES `role` WRITE;

insert  into `role`(`branch_id`,`role_id`,`role_name`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('a75c2b6af02f387f27852951c970492a','40ae04f7720ddf9856dccf763634395f','Waiter','N','ENABLED','2014-05-25 15:22:40','a75c2b6af02f387f27852951c970492a','0000-00-00 00:00:00',''),('a75c2b6af02f387f27852951c970492a','b3a976e61b921db37b78daff6f68368f','Cashier','N','ENABLED','2014-05-25 15:21:24','a75c2b6af02f387f27852951c970492a','0000-00-00 00:00:00',''),('a75c2b6af02f387f27852951c970492a','e216f7161a32647081ce4e850cda45db','Administrator','Y','ENABLED','2014-05-25 15:22:23','a75c2b6af02f387f27852951c970492a','2014-05-25 15:27:17','a75c2b6af02f387f27852951c970492a'),('e0edd35d5e9eab9897138d43bd090c8d','fa54b7d76983560554ec627b487affda','Server','Y','ENABLED','2014-05-25 15:25:43','a75c2b6af02f387f27852951c970492a','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `role_access_link` */

DROP TABLE IF EXISTS `role_access_link`;

CREATE TABLE `role_access_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `role_access_id` char(32) NOT NULL DEFAULT '',
  `role_id` char(32) NOT NULL DEFAULT '',
  `module_id` char(32) NOT NULL DEFAULT '',
  `access_code` char(32) NOT NULL DEFAULT '',
  `target_branch_id` char(32) NOT NULL DEFAULT '' COMMENT 'Leave blank for global access',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`role_access_id`),
  KEY `SYNC` (`branch_id`,`created_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `role_access_link` */

LOCK TABLES `role_access_link` WRITE;

insert  into `role_access_link`(`branch_id`,`role_access_id`,`role_id`,`module_id`,`access_code`,`target_branch_id`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','f30da76dfe63b34f369d800daa5a89eb','e216f7161a32647081ce4e850cda45db','department','READ','','2014-07-20 10:37:21','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `settings_menu` */

DROP TABLE IF EXISTS `settings_menu`;

CREATE TABLE `settings_menu` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `menu_id` char(32) NOT NULL DEFAULT '',
  `menu_title` varchar(50) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Determines if this record is useable(read only) to child branches',
  `status` enum('ENABLED','DISABLED','REMOVED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`branch_id`,`menu_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `settings_menu` */

LOCK TABLES `settings_menu` WRITE;

UNLOCK TABLES;

/*Table structure for table `settings_menu_tab` */

DROP TABLE IF EXISTS `settings_menu_tab`;

CREATE TABLE `settings_menu_tab` (
  `menu_id` char(32) NOT NULL DEFAULT '',
  `menu_tab_id` char(32) NOT NULL DEFAULT '',
  `menu_tab_priority_level` smallint(5) unsigned NOT NULL DEFAULT '1',
  `menu_tab_title` varchar(50) NOT NULL DEFAULT '',
  `status` enum('ENABLED','DISABLED','REMOVED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`menu_tab_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `settings_menu_tab` */

LOCK TABLES `settings_menu_tab` WRITE;

UNLOCK TABLES;

/*Table structure for table `settings_menu_tab_item_link` */

DROP TABLE IF EXISTS `settings_menu_tab_item_link`;

CREATE TABLE `settings_menu_tab_item_link` (
  `menu_tab_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `priority_level` smallint(5) unsigned NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`menu_tab_id`,`item_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `settings_menu_tab_item_link` */

LOCK TABLES `settings_menu_tab_item_link` WRITE;

UNLOCK TABLES;

/*Table structure for table `settings_promotion` */

DROP TABLE IF EXISTS `settings_promotion`;

CREATE TABLE `settings_promotion` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `promotion_id` char(32) NOT NULL DEFAULT '',
  `promotion_name` varchar(100) NOT NULL DEFAULT '',
  `promotion_description` varchar(1000) NOT NULL,
  `start_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_recurring` enum('Y','N') NOT NULL DEFAULT 'N',
  `day_number` tinyint(4) NOT NULL,
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED','REMOVED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modfied_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`promotion_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `settings_promotion` */

LOCK TABLES `settings_promotion` WRITE;

UNLOCK TABLES;

/*Table structure for table `settings_receipt_template` */

DROP TABLE IF EXISTS `settings_receipt_template`;

CREATE TABLE `settings_receipt_template` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `receipt_template_id` char(32) NOT NULL DEFAULT '',
  `receipt_style` enum('THERMAL','LEGAL') NOT NULL DEFAULT 'THERMAL',
  `receipt_template_name` varchar(50) NOT NULL DEFAULT '',
  `banner_image_url` varchar(500) NOT NULL DEFAULT '',
  `header_text` varchar(2000) NOT NULL DEFAULT '',
  `footer_text` varchar(2000) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Determines if this record is useable(read only) to child branches',
  `status` enum('ENABLED','DISABLED','REMOVED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL,
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL,
  PRIMARY KEY (`receipt_template_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `settings_receipt_template` */

LOCK TABLES `settings_receipt_template` WRITE;

UNLOCK TABLES;

/*Table structure for table `shift` */

DROP TABLE IF EXISTS `shift`;

CREATE TABLE `shift` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `shift_id` char(32) NOT NULL DEFAULT '',
  `shift_name` varchar(50) NOT NULL DEFAULT '',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`shift_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `shift` */

LOCK TABLES `shift` WRITE;

insert  into `shift`(`branch_id`,`shift_id`,`shift_name`,`start_time`,`end_time`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','94af9782fa6c4a7c7dd4a7969cd33535','Second','08:00:00','17:00:00','N','ENABLED','2014-05-25 15:11:40','a75c2b6af02f387f27852951c970492a','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','e787ebd0fcc53c7857d9cecf14fff89e','First','06:00:00','15:00:00','N','ENABLED','2014-05-25 15:11:09','a75c2b6af02f387f27852951c970492a','2014-05-25 15:20:26','a75c2b6af02f387f27852951c970492a');

UNLOCK TABLES;

/*Table structure for table `special_operation_date` */

DROP TABLE IF EXISTS `special_operation_date`;

CREATE TABLE `special_operation_date` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `special_operation_date_id` char(32) NOT NULL DEFAULT '',
  `special_operation_name` varchar(50) NOT NULL DEFAULT '',
  `special_operation_date` date NOT NULL DEFAULT '0000-00-00',
  `opening_time` time NOT NULL DEFAULT '00:00:00',
  `closing_time` time NOT NULL DEFAULT '00:00:00',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`special_operation_date_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `special_operation_date` */

LOCK TABLES `special_operation_date` WRITE;

insert  into `special_operation_date`(`branch_id`,`special_operation_date_id`,`special_operation_name`,`special_operation_date`,`opening_time`,`closing_time`,`remarks`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','311afac6af117eb257a44f44cd02ae8f','','2012-01-01','00:00:00','00:00:00','New Year','2014-07-01 06:16:36','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','86bab4a722ecaa8af3d084ec149f6434','','2012-04-15','07:00:00','10:00:00','ValemtimesX','2014-07-01 07:11:35','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','a99ea6b7a5a4df562d806b2d17d8e711','','2012-02-14','00:00:00','00:00:00','Valemtimes','2014-07-01 06:16:52','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','c585b79dd133e2304469f5c0665f963f','','2012-02-15','00:00:00','00:00:00','ValemtimesX','2014-07-01 07:10:58','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `special_turn_time` */

DROP TABLE IF EXISTS `special_turn_time`;

CREATE TABLE `special_turn_time` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `special_turn_time_id` char(32) NOT NULL DEFAULT '',
  `day_of_week` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `turn_time` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Overrides the default turn time',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`special_turn_time_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `special_turn_time` */

LOCK TABLES `special_turn_time` WRITE;

insert  into `special_turn_time`(`branch_id`,`special_turn_time_id`,`day_of_week`,`turn_time`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','600622ef639550b1d554d4b0ff1dac07',0,90,'2014-07-01 08:29:07','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `stock_movement` */

DROP TABLE IF EXISTS `stock_movement`;

CREATE TABLE `stock_movement` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `reference_id` char(32) NOT NULL DEFAULT '' COMMENT 'Can be a sales/inventory/purchase transaction',
  `item_sku` varchar(50) NOT NULL DEFAULT '',
  `location_id` char(32) NOT NULL DEFAULT '' COMMENT 'Determines the location of this stock',
  `stock_movement_id` char(32) NOT NULL DEFAULT '',
  `quantity` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `stock_movement_type` enum('INBOUND','OUTBOUND','PRODUCTION','SALES','PURCHASE','TRANSFER','RETURN FROM CLIENT','RETURN TO SUPPLIER','STOCKTAKE') NOT NULL DEFAULT 'INBOUND',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`branch_id`,`stock_movement_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_movement` */

LOCK TABLES `stock_movement` WRITE;

UNLOCK TABLES;

/*Table structure for table `stock_transaction` */

DROP TABLE IF EXISTS `stock_transaction`;

CREATE TABLE `stock_transaction` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `stock_transaction_id` char(32) NOT NULL DEFAULT '',
  `transaction_number` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(50) NOT NULL DEFAULT '',
  `remarks` varchar(1000) NOT NULL DEFAULT '',
  `transaction_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transaction_type` enum('INBOUND','OUTBOUND','RETURN TO SUPPLIER','RETURN FROM CLIENT') NOT NULL DEFAULT 'INBOUND',
  `transaction_status` enum('PENDING','CANCELED','RECEIVED') NOT NULL DEFAULT 'PENDING',
  `authorized_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`branch_id`,`stock_transaction_id`),
  KEY `SEARCH` (`branch_id`,`transaction_status`,`transaction_type`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_transaction` */

LOCK TABLES `stock_transaction` WRITE;

UNLOCK TABLES;

/*Table structure for table `stock_transaction_detail` */

DROP TABLE IF EXISTS `stock_transaction_detail`;

CREATE TABLE `stock_transaction_detail` (
  `stock_transaction_id` char(32) NOT NULL DEFAULT '',
  `item_sku` varchar(50) NOT NULL DEFAULT '',
  `expected_unit_quantity` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `received_unit_quantity` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`stock_transaction_id`,`item_sku`),
  KEY `SYNC` (`stock_transaction_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_transaction_detail` */

LOCK TABLES `stock_transaction_detail` WRITE;

UNLOCK TABLES;

/*Table structure for table `stock_transfer` */

DROP TABLE IF EXISTS `stock_transfer`;

CREATE TABLE `stock_transfer` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `stock_transfer_id` char(32) NOT NULL DEFAULT '',
  `stock_transfer_number` int(10) unsigned NOT NULL DEFAULT '1',
  `revision_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `receiving_branch_id` char(32) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `remarks` varchar(1000) NOT NULL DEFAULT '',
  `transfer_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `received_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `aurhotized_by` char(32) NOT NULL DEFAULT '',
  `stock_transfer_status` enum('PENDING','CANCELED','APPROVED','IN-PROGRESS','RECEIVED') NOT NULL DEFAULT 'PENDING',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`stock_transfer_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_transfer` */

LOCK TABLES `stock_transfer` WRITE;

UNLOCK TABLES;

/*Table structure for table `stock_transfer_detail` */

DROP TABLE IF EXISTS `stock_transfer_detail`;

CREATE TABLE `stock_transfer_detail` (
  `stock_transfer_id` char(32) NOT NULL DEFAULT '',
  `item_sku` varchar(50) NOT NULL DEFAULT '',
  `transfered_quantity` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `received_quantity` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `rejected_quantity` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `stocked_quantity` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`stock_transfer_id`,`item_sku`),
  KEY `SYNC` (`stock_transfer_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_transfer_detail` */

LOCK TABLES `stock_transfer_detail` WRITE;

UNLOCK TABLES;

/*Table structure for table `system_api_credential` */

DROP TABLE IF EXISTS `system_api_credential`;

CREATE TABLE `system_api_credential` (
  `api_id` char(14) NOT NULL DEFAULT '',
  `api_key` tinyblob NOT NULL,
  `server_id` char(32) NOT NULL DEFAULT '',
  `consumer_name` varchar(30) NOT NULL DEFAULT '',
  `consumer_type` enum('WEB APP','MOBILE APP','DESKTOP APP','WEB SERVICE','OTHERS') NOT NULL DEFAULT 'WEB APP',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  PRIMARY KEY (`api_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `system_api_credential` */

LOCK TABLES `system_api_credential` WRITE;

insert  into `system_api_credential`(`api_id`,`api_key`,`server_id`,`consumer_name`,`consumer_type`,`status`) values ('12345678901234','\Z?Œ¨ü½eFÆÅ','a75c2b6af02f387897138d43bd090c8d','Vanilla','WEB APP','ENABLED');

UNLOCK TABLES;

/*Table structure for table `system_log` */

DROP TABLE IF EXISTS `system_log`;

CREATE TABLE `system_log` (
  `system_log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` char(32) NOT NULL DEFAULT '',
  `ip_address` varchar(20) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL,
  `task` varchar(50) NOT NULL DEFAULT '',
  `log_remark` varchar(255) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`system_log_id`),
  KEY `SYNC` (`branch_id`,`created_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `system_log` */

LOCK TABLES `system_log` WRITE;

UNLOCK TABLES;

/*Table structure for table `system_sync_log` */

DROP TABLE IF EXISTS `system_sync_log`;

CREATE TABLE `system_sync_log` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `table_name` varchar(255) NOT NULL DEFAULT '',
  `synchronization_method` enum('UPLOAD','DOWNLOAD') NOT NULL DEFAULT 'UPLOAD',
  `synchronized_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`branch_id`,`table_name`,`synchronization_method`,`synchronized_on`),
  KEY `SYNC` (`branch_id`,`created_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `system_sync_log` */

LOCK TABLES `system_sync_log` WRITE;

insert  into `system_sync_log`(`branch_id`,`table_name`,`synchronization_method`,`synchronized_on`,`created_on`,`created_by`) values ('a75c2b6af02f387f27852951c970492a','branch','UPLOAD','2014-05-11 14:15:47','2014-05-11 14:15:47',''),('a75c2b6af02f387f27852951c970492a','branch','UPLOAD','2014-06-12 14:15:47','2014-05-11 14:15:47','');

UNLOCK TABLES;

/*Table structure for table `system_sync_table` */

DROP TABLE IF EXISTS `system_sync_table`;

CREATE TABLE `system_sync_table` (
  `table_name` varchar(255) NOT NULL,
  `table_group` enum('DEPARTMENT','ITEM','BRANCH','CUSTOMER') DEFAULT NULL,
  `modified_on` datetime DEFAULT '0000-00-00 00:00:00',
  `sync_type` enum('CONDITIONAL','ALWAYS') DEFAULT 'CONDITIONAL',
  `remarks` tinytext,
  PRIMARY KEY (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `system_sync_table` */

LOCK TABLES `system_sync_table` WRITE;

insert  into `system_sync_table`(`table_name`,`table_group`,`modified_on`,`sync_type`,`remarks`) values ('branch','BRANCH','2014-05-10 14:15:47','ALWAYS','sample only'),('department','DEPARTMENT','2014-05-10 14:15:47','CONDITIONAL','sample only');

UNLOCK TABLES;

/*Table structure for table `tag` */

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `tag_id` char(32) NOT NULL DEFAULT '',
  `tag_name` varchar(50) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tag` */

LOCK TABLES `tag` WRITE;

UNLOCK TABLES;

/*Table structure for table `tax` */

DROP TABLE IF EXISTS `tax`;

CREATE TABLE `tax` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `tax_profile_id` char(32) NOT NULL DEFAULT '',
  `tax_id` char(32) NOT NULL DEFAULT '',
  `tax_name` varchar(30) NOT NULL DEFAULT '',
  `print_label` varchar(30) NOT NULL DEFAULT '',
  `multiplier` decimal(5,4) unsigned NOT NULL DEFAULT '1.0000',
  `constant` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `tax_type` enum('INCLUSIVE','EXCLUSIVE') NOT NULL DEFAULT 'INCLUSIVE',
  `computation_type` enum('STANDARD','INCREMENTAL') NOT NULL DEFAULT 'STANDARD',
  `priority_level` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tax` */

LOCK TABLES `tax` WRITE;

insert  into `tax`(`branch_id`,`tax_profile_id`,`tax_id`,`tax_name`,`print_label`,`multiplier`,`constant`,`tax_type`,`computation_type`,`priority_level`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','2d643d709c7bbe77a6b740c3c8ccb693','5e1dee6458bea6ed3ba7ad5e4d9ff4fa','5% Tax','Tax5','1.0500','0.00','INCLUSIVE','STANDARD',0,'ENABLED','2014-07-17 15:21:16','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','2d643d709c7bbe77a6b740c3c8ccb693','67ac76de7cd6eb92b858ec59b32fda80','Vat2','Vat','1.1200','0.00','INCLUSIVE','STANDARD',1,'ENABLED','2014-07-11 04:46:11','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','2d643d709c7bbe77a6b740c3c8ccb693','7139fee103892a83e39ef28a339fdf09','Vat3','Vat','1.1200','0.00','INCLUSIVE','STANDARD',3,'ENABLED','2014-07-11 04:39:55','0b161a3f8ca81d127ffcbd651b46c6c5','2014-07-11 04:46:39','0b161a3f8ca81d127ffcbd651b46c6c5'),('e0edd35d5e9eab9897138d43bd090c8d','2d643d709c7bbe77a6b740c3c8ccb693','af21c3c7bb11aa0d8b50c9a04e0a6708','Vat','Vat','1.1200','0.00','INCLUSIVE','STANDARD',2,'ENABLED','2014-07-11 04:39:34','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `tax_profile` */

DROP TABLE IF EXISTS `tax_profile`;

CREATE TABLE `tax_profile` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `tax_profile_id` char(32) NOT NULL DEFAULT '',
  `tax_profile_name` varchar(50) NOT NULL DEFAULT '',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tax_profile` */

LOCK TABLES `tax_profile` WRITE;

insert  into `tax_profile`(`branch_id`,`tax_profile_id`,`tax_profile_name`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','2d643d709c7bbe77a6b740c3c8ccb693','Basic','N','ENABLED','2014-07-11 03:48:56','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `terminal` */

DROP TABLE IF EXISTS `terminal`;

CREATE TABLE `terminal` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `terminal_id` char(32) NOT NULL DEFAULT '',
  `terminal_name` varchar(50) NOT NULL DEFAULT '',
  `default_transaction_type` enum('TAB','TABLE') NOT NULL DEFAULT 'TABLE',
  `default_printer_id` char(32) NOT NULL DEFAULT '',
  `current_user` char(32) NOT NULL DEFAULT '',
  `last_activity` char(32) NOT NULL DEFAULT '',
  `activity_status` enum('CLOSED','OPEN','STANDBY') NOT NULL DEFAULT 'CLOSED',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`terminal_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `terminal` */

LOCK TABLES `terminal` WRITE;

UNLOCK TABLES;

/*Table structure for table `terminal_activity` */

DROP TABLE IF EXISTS `terminal_activity`;

CREATE TABLE `terminal_activity` (
  `terminal_activity_id` char(32) NOT NULL DEFAULT '',
  `opened_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `opened_by` char(32) NOT NULL DEFAULT '',
  `closed_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closed_by` char(32) NOT NULL DEFAULT '',
  `initial_cash` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`terminal_activity_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `terminal_activity` */

LOCK TABLES `terminal_activity` WRITE;

UNLOCK TABLES;

/*Table structure for table `terminal_posted_cash_count` */

DROP TABLE IF EXISTS `terminal_posted_cash_count`;

CREATE TABLE `terminal_posted_cash_count` (
  `terminal_activity_id` char(32) NOT NULL DEFAULT '',
  `cash_denomination` decimal(10,2) unsigned NOT NULL DEFAULT '1.00',
  `cash_count` int(10) unsigned NOT NULL DEFAULT '0',
  `posted_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `posted_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`terminal_activity_id`,`cash_denomination`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `terminal_posted_cash_count` */

LOCK TABLES `terminal_posted_cash_count` WRITE;

UNLOCK TABLES;

/*Table structure for table `terminal_posted_payment` */

DROP TABLE IF EXISTS `terminal_posted_payment`;

CREATE TABLE `terminal_posted_payment` (
  `terminal_activity_id` char(32) NOT NULL DEFAULT '',
  `payment_mode_id` char(32) NOT NULL DEFAULT '',
  `total_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `posted_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `posted_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `posted_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`terminal_activity_id`,`payment_mode_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `terminal_posted_payment` */

LOCK TABLES `terminal_posted_payment` WRITE;

UNLOCK TABLES;

/*Table structure for table `terminal_posted_price_modifier` */

DROP TABLE IF EXISTS `terminal_posted_price_modifier`;

CREATE TABLE `terminal_posted_price_modifier` (
  `terminal_activity_id` char(32) NOT NULL DEFAULT '',
  `price_modifier_id` char(32) NOT NULL DEFAULT '',
  `total_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `posted_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `posted_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `posted_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`terminal_activity_id`,`price_modifier_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `terminal_posted_price_modifier` */

LOCK TABLES `terminal_posted_price_modifier` WRITE;

UNLOCK TABLES;

/*Table structure for table `terminal_posted_transaction` */

DROP TABLE IF EXISTS `terminal_posted_transaction`;

CREATE TABLE `terminal_posted_transaction` (
  `terminal_activity_id` char(32) NOT NULL DEFAULT '',
  `transaction_type_id` char(32) NOT NULL DEFAULT '',
  `total_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `posted_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `posted_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `posted_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`terminal_activity_id`,`transaction_type_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `terminal_posted_transaction` */

LOCK TABLES `terminal_posted_transaction` WRITE;

UNLOCK TABLES;

/*Table structure for table `time_sitting` */

DROP TABLE IF EXISTS `time_sitting`;

CREATE TABLE `time_sitting` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `time_sitting_id` char(32) NOT NULL DEFAULT '',
  `time_sitting_name` varchar(50) NOT NULL DEFAULT '',
  `sun` enum('Y','N') NOT NULL DEFAULT 'Y',
  `mon` enum('Y','N') NOT NULL DEFAULT 'Y',
  `tue` enum('Y','N') NOT NULL DEFAULT 'Y',
  `wed` enum('Y','N') NOT NULL DEFAULT 'Y',
  `thu` enum('Y','N') NOT NULL DEFAULT 'Y',
  `fri` enum('Y','N') NOT NULL DEFAULT 'Y',
  `sat` enum('Y','N') NOT NULL DEFAULT 'Y',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`time_sitting_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `time_sitting` */

LOCK TABLES `time_sitting` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction` */

DROP TABLE IF EXISTS `transaction`;

CREATE TABLE `transaction` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `location_id` char(32) NOT NULL DEFAULT '',
  `terminal_id` char(32) NOT NULL DEFAULT '' COMMENT 'ID of the register used to create the transaction',
  `transaction_type_id` char(32) NOT NULL DEFAULT '',
  `customer_id` char(32) NOT NULL DEFAULT '',
  `reservation_id` char(32) NOT NULL DEFAULT '',
  `cashier_id` char(32) NOT NULL DEFAULT '',
  `server_id` char(32) NOT NULL DEFAULT '',
  `transaction_id` char(32) NOT NULL DEFAULT '',
  `day_number` int(10) unsigned NOT NULL DEFAULT '1',
  `order_number` smallint(5) unsigned NOT NULL DEFAULT '1',
  `covers` smallint(5) unsigned NOT NULL DEFAULT '1',
  `special_instructions` varchar(255) NOT NULL DEFAULT '',
  `transaction_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transaction_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remarks` varchar(255) NOT NULL DEFAULT '' COMMENT 'Used for cashiering remarks',
  `is_priority` enum('Y','N') NOT NULL DEFAULT 'N',
  `is_seated` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Determines if transaction is waiting for table to open',
  `transaction_activity_status` enum('OPEN','CLOSED') NOT NULL DEFAULT 'OPEN',
  `transaction_status` enum('VALID','CANCELED','VOID') NOT NULL DEFAULT 'VALID',
  `authorized_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`transaction_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction` */

LOCK TABLES `transaction` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction_detail` */

DROP TABLE IF EXISTS `transaction_detail`;

CREATE TABLE `transaction_detail` (
  `transaction_id` char(32) NOT NULL DEFAULT '',
  `item_id` char(32) NOT NULL DEFAULT '',
  `location_id_override` char(32) NOT NULL DEFAULT '' COMMENT 'Override the location of the transaction to where this item is to be served',
  `order_item_id` char(32) NOT NULL DEFAULT '' COMMENT 'Generated Id for item uniqueness',
  `quantity` decimal(10,4) unsigned NOT NULL DEFAULT '1.0000' COMMENT 'Total items ordered',
  `special_instructions` varchar(255) NOT NULL DEFAULT '',
  `selling_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `serve_later` tinyint(1) NOT NULL DEFAULT '0',
  `serving_time_offset` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Additional time given to preparation in minutes',
  `order_item_remarks` varchar(255) NOT NULL DEFAULT '' COMMENT 'Used for cashiering remarks',
  `order_item_status` enum('VALID','CANCELED','VOID','RETURNED') NOT NULL DEFAULT 'VALID',
  `authorized_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`order_item_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_detail` */

LOCK TABLES `transaction_detail` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction_invoice` */

DROP TABLE IF EXISTS `transaction_invoice`;

CREATE TABLE `transaction_invoice` (
  `transaction_id` char(32) NOT NULL DEFAULT '',
  `invoice_id` char(32) NOT NULL DEFAULT '',
  `invoice_code` varchar(50) NOT NULL DEFAULT '',
  `check_no` smallint(5) unsigned NOT NULL DEFAULT '1',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `printed_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `emailed_to` varchar(255) NOT NULL DEFAULT '',
  `invoice_status` enum('UNPAID','PAID') NOT NULL DEFAULT 'UNPAID',
  `issued_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isuued_by` char(32) NOT NULL DEFAULT '',
  `reprinted_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reprinted_by` char(32) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`invoice_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_invoice` */

LOCK TABLES `transaction_invoice` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction_invoice_detail` */

DROP TABLE IF EXISTS `transaction_invoice_detail`;

CREATE TABLE `transaction_invoice_detail` (
  `invoice_id` char(32) NOT NULL DEFAULT '',
  `order_item_id` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`invoice_id`,`order_item_id`),
  KEY `SYNC` (`invoice_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_invoice_detail` */

LOCK TABLES `transaction_invoice_detail` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction_item_modifier` */

DROP TABLE IF EXISTS `transaction_item_modifier`;

CREATE TABLE `transaction_item_modifier` (
  `order_item_id` char(32) NOT NULL DEFAULT '' COMMENT 'Link to ordered items',
  `item_modifier_id` char(32) NOT NULL DEFAULT '',
  `item_modifier_option_id` char(32) NOT NULL DEFAULT '',
  `item_modifier_option_name` varchar(50) NOT NULL DEFAULT '',
  `item_modifier_quantity` smallint(5) unsigned NOT NULL DEFAULT '1',
  `price_modifier` decimal(10,4) unsigned NOT NULL DEFAULT '0.0000',
  `price_change` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Can be a postive or negative value',
  `order_item_modifier_status` enum('VALID','CANCELED','VOID') NOT NULL DEFAULT 'VALID',
  `authorized_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`order_item_id`,`item_modifier_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_item_modifier` */

LOCK TABLES `transaction_item_modifier` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction_item_monitoring` */

DROP TABLE IF EXISTS `transaction_item_monitoring`;

CREATE TABLE `transaction_item_monitoring` (
  `order_item_id` char(32) NOT NULL DEFAULT '',
  `location_id` char(32) NOT NULL DEFAULT '',
  `order_item_status` enum('PENDING','CANCLED','READY','SERVED') NOT NULL DEFAULT 'PENDING',
  `sent_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ready_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `served_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_printed` enum('Y','N') NOT NULL DEFAULT 'N',
  `poked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`order_item_id`,`location_id`),
  KEY `SYNC` (`order_item_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_item_monitoring` */

LOCK TABLES `transaction_item_monitoring` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction_payment` */

DROP TABLE IF EXISTS `transaction_payment`;

CREATE TABLE `transaction_payment` (
  `invoice_id` char(32) NOT NULL DEFAULT '',
  `payment_mode_id` char(32) NOT NULL DEFAULT '',
  `payment_id` char(32) NOT NULL DEFAULT '',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `amount_due` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `change` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tip_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `paid_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `authorized_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`payment_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_payment` */

LOCK TABLES `transaction_payment` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction_price_modifier` */

DROP TABLE IF EXISTS `transaction_price_modifier`;

CREATE TABLE `transaction_price_modifier` (
  `invoice_id` char(32) NOT NULL DEFAULT '',
  `check_no` smallint(5) unsigned NOT NULL DEFAULT '1',
  `order_item_id` char(32) NOT NULL DEFAULT '' COMMENT 'Modifier will apply to whole transaction if this field is empty',
  `price_modifier_id` char(32) NOT NULL DEFAULT '' COMMENT 'Can be tax or discount',
  `price_modifier_multiplier` decimal(10,4) unsigned NOT NULL DEFAULT '1.0000',
  `price_modifier_value` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `authorized_by` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`invoice_id`,`order_item_id`,`price_modifier_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_price_modifier` */

LOCK TABLES `transaction_price_modifier` WRITE;

UNLOCK TABLES;

/*Table structure for table `transaction_type` */

DROP TABLE IF EXISTS `transaction_type`;

CREATE TABLE `transaction_type` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `transaction_type_id` char(32) NOT NULL DEFAULT '',
  `transaction_type_name` varchar(50) NOT NULL DEFAULT '',
  `print_label` varchar(50) NOT NULL DEFAULT '',
  `tax_setting` enum('TAXABLE','NON-TAXABLE','EXEMPTED','ZERO RATED') NOT NULL DEFAULT 'TAXABLE',
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`transaction_type_id`),
  KEY `SYNC` (`branch_id`,`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_type` */

LOCK TABLES `transaction_type` WRITE;

insert  into `transaction_type`(`branch_id`,`transaction_type_id`,`transaction_type_name`,`print_label`,`tax_setting`,`is_public`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','4f3fe5bf3a4c80ba1c152c81ff84fe3a','Dine In','','','N','ENABLED','2014-06-14 05:42:00','0b161a3f8ca81d127ffcbd651b46c6c5','2014-06-14 05:54:19','0b161a3f8ca81d127ffcbd651b46c6c5'),('e0edd35d5e9eab9897138d43bd090c8d','6f4e60be87221c86f8d6043d2c912636','To Go','To Go','','N','ENABLED','2014-07-11 07:46:03','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `user_id` char(32) NOT NULL DEFAULT '',
  `department_id` char(32) NOT NULL DEFAULT '',
  `shift_id` char(32) NOT NULL DEFAULT '',
  `designation` varchar(50) NOT NULL DEFAULT '',
  `employee_id` varchar(50) NOT NULL DEFAULT '',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `province` varchar(50) NOT NULL DEFAULT '',
  `zip_code` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `phone2` varchar(50) NOT NULL DEFAULT '',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  KEY `STATUS` (`branch_id`,`status`),
  KEY `DEPARTMENT` (`department_id`),
  KEY `SHIFT` (`shift_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user` */

LOCK TABLES `user` WRITE;

insert  into `user`(`branch_id`,`user_id`,`department_id`,`shift_id`,`designation`,`employee_id`,`first_name`,`last_name`,`address`,`city`,`province`,`zip_code`,`email`,`phone`,`phone2`,`remarks`,`photo_url`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','0b161a3f8ca81d127ffcbd651b46c6c5','c835dc58fc7ebef09bdf2fe5088bbebf','','Manager','','Migz','Lat','','','','','','','','','','ENABLED','2014-05-25 15:37:34','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00',''),('a75c2b6af02f387f27852951c970492a','e657c462b6ccfd61849e5096f5f2e643','c835dc58fc7ebef09bdf2fe5088bbebf','','Manager','','Bon','Jones','','','','','','','','','','ENABLED','2014-05-25 16:18:02','0b161a3f8ca81d127ffcbd651b46c6c5','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*Table structure for table `user_access_token` */

DROP TABLE IF EXISTS `user_access_token`;

CREATE TABLE `user_access_token` (
  `user_id` char(32) NOT NULL DEFAULT '',
  `access_token` char(32) NOT NULL DEFAULT '',
  `token_expiry` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_access_token` */

LOCK TABLES `user_access_token` WRITE;

insert  into `user_access_token`(`user_id`,`access_token`,`token_expiry`,`created_on`) values ('0b161a3f8ca81d127ffcbd651b46c6c5','03e0a8786504c25818c366513824cb89','2014-06-22 14:25:45','2014-06-21 15:05:45'),('0b161a3f8ca81d127ffcbd651b46c6c5','054880439b3067ad0c9d3f38e9610052','2014-06-14 15:26:21','2014-06-13 15:26:21'),('0b161a3f8ca81d127ffcbd651b46c6c5','06913741c0a9c3c6653630137acc96aa','2014-06-22 10:46:19','2014-06-21 10:46:19'),('0b161a3f8ca81d127ffcbd651b46c6c5','074a912e59ed0362656ac0a6d847343f','2014-06-22 06:46:21','2014-06-21 06:46:21'),('0b161a3f8ca81d127ffcbd651b46c6c5','079ca759e9dd82ec9ea73a6f199a947a','2014-06-14 08:44:22','2014-06-13 08:44:22'),('0b161a3f8ca81d127ffcbd651b46c6c5','0c668e002d82a7a1644064a4ff8e3b5a','2014-06-15 08:05:47','2014-06-14 08:05:47'),('0b161a3f8ca81d127ffcbd651b46c6c5','1120c0d16d032f609fa0f0c86a25cd7e','1970-01-01 00:00:00','2014-06-21 15:04:46'),('0b161a3f8ca81d127ffcbd651b46c6c5','14caf71f95bd8f4c525181f3833b479b','2014-06-14 15:22:30','2014-06-13 15:22:30'),('0b161a3f8ca81d127ffcbd651b46c6c5','1791b7ea8c02dddf7d797f9bda083afa','2014-07-15 04:30:49','2014-07-14 05:10:49'),('0b161a3f8ca81d127ffcbd651b46c6c5','1ba1c9182b7abb11cf7ff4fa438f8cb1','2014-06-22 08:17:33','2014-06-21 08:17:33'),('0b161a3f8ca81d127ffcbd651b46c6c5','1ca84b93623ac1476b08577367bc6a0b','2014-06-14 06:00:04','2014-06-13 06:00:04'),('0b161a3f8ca81d127ffcbd651b46c6c5','1d8d2c22452b7b48f40c2c0e87ac5c83','2014-06-22 13:45:14','2014-06-21 13:45:14'),('0b161a3f8ca81d127ffcbd651b46c6c5','1e306cb1205c957933dd4a65753bdb5b','2014-06-22 08:01:53','2014-06-21 08:01:53'),('0b161a3f8ca81d127ffcbd651b46c6c5','1e9dca6a31748ccab905f84ca402f771','2014-06-14 15:26:14','2014-06-13 15:26:14'),('0b161a3f8ca81d127ffcbd651b46c6c5','1f476ddcc76bcafe229cc1b63c731cfa','2014-06-22 06:45:30','2014-06-21 06:45:30'),('0b161a3f8ca81d127ffcbd651b46c6c5','21214d6c49970c23a0d8ae337e89cbdc','2014-06-14 15:22:28','2014-06-13 15:22:28'),('0b161a3f8ca81d127ffcbd651b46c6c5','2160fb41859618ea5f2cbe37068b13a5','2014-06-14 18:28:29','2014-06-13 18:28:29'),('0b161a3f8ca81d127ffcbd651b46c6c5','2163a86f09d90c4f00724a1f48b6dd7a','2014-06-22 08:43:26','2014-06-21 08:43:26'),('0b161a3f8ca81d127ffcbd651b46c6c5','231e3af361ff07ecc68bcba528adbb3b','2014-06-22 14:30:19','2014-06-21 14:30:19'),('0b161a3f8ca81d127ffcbd651b46c6c5','236ec31869f83cd5e05068ffa42d072d','2014-06-22 08:07:33','2014-06-21 08:07:33'),('0b161a3f8ca81d127ffcbd651b46c6c5','25de9742ffb430055eb8edb7cf48a3ce','2014-06-22 06:46:28','2014-06-21 06:46:28'),('0b161a3f8ca81d127ffcbd651b46c6c5','266f9ad14832fe5e9cfba2e639b8efc7','2014-06-14 11:29:29','2014-06-13 11:29:29'),('0b161a3f8ca81d127ffcbd651b46c6c5','267dcaa1e411afedbdee91148210e02d','2014-06-22 14:28:06','2014-06-21 15:08:06'),('0b161a3f8ca81d127ffcbd651b46c6c5','288d1c930d0343cec990bdc733585c39','2014-06-14 10:44:52','2014-06-13 10:44:52'),('0b161a3f8ca81d127ffcbd651b46c6c5','28c843f7a3d9fe8453e6a1117e73c242','2014-07-15 04:30:59','2014-07-14 05:10:59'),('0b161a3f8ca81d127ffcbd651b46c6c5','2be585643b2294e5ca18b7dd4d5f1dc6','2014-06-29 05:56:53','2014-06-28 06:36:53'),('0b161a3f8ca81d127ffcbd651b46c6c5','2df880eef0ac484a01bb616bd1d5dc5e','2014-06-14 06:09:39','2014-06-13 06:09:39'),('0b161a3f8ca81d127ffcbd651b46c6c5','2e031c7d0bbf9150d87f1da1b15e07d0','2014-06-14 15:26:44','2014-06-13 15:26:44'),('0b161a3f8ca81d127ffcbd651b46c6c5','2f87d6b48d0b1ef855211e4ab51d1a5a','2014-06-22 07:56:08','2014-06-21 07:56:08'),('0b161a3f8ca81d127ffcbd651b46c6c5','30c7f0e18859950067449c76b41f4730','2014-06-22 14:57:33','2014-06-21 14:57:33'),('0b161a3f8ca81d127ffcbd651b46c6c5','32687934bc0ab47a236903844308e023','2014-06-22 14:58:09','2014-06-21 14:58:09'),('0b161a3f8ca81d127ffcbd651b46c6c5','32f88513feeb648a6abe15bb9b630957','2014-06-14 10:43:22','2014-06-13 10:43:22'),('0b161a3f8ca81d127ffcbd651b46c6c5','3450f8f174f094c4f0d714297dc60452','2014-06-14 10:50:31','2014-06-13 10:50:31'),('0b161a3f8ca81d127ffcbd651b46c6c5','357c54dcdaa06f3f623a06aeff7dd812','2014-06-14 09:13:22','2014-06-13 09:13:22'),('0b161a3f8ca81d127ffcbd651b46c6c5','39be106b701b99f09ce53cda40fbdf80','2014-06-22 08:02:26','2014-06-21 08:02:26'),('0b161a3f8ca81d127ffcbd651b46c6c5','39e437933b7648dcb0a4ca6eb8646fdf','2014-06-22 08:33:24','2014-06-21 08:33:24'),('0b161a3f8ca81d127ffcbd651b46c6c5','3dcf6d34d3a92338091e1f43b9fad351','2014-06-22 14:26:08','2014-06-21 15:06:08'),('0b161a3f8ca81d127ffcbd651b46c6c5','3e5b722715973b82394319d75b04c581','2014-06-14 17:17:00','2014-06-13 17:17:00'),('0b161a3f8ca81d127ffcbd651b46c6c5','3f2bd3c80f059b8e7bfb2379949c247e','2014-06-22 07:59:23','2014-06-21 07:59:23'),('0b161a3f8ca81d127ffcbd651b46c6c5','3f7feb8841b9e0adfe0821583655e6d5','2014-06-22 06:46:22','2014-06-21 06:46:22'),('0b161a3f8ca81d127ffcbd651b46c6c5','40e5feee73f2ef761d97bd2b8dbbf93b','2014-06-14 15:22:31','2014-06-13 15:22:31'),('0b161a3f8ca81d127ffcbd651b46c6c5','436058f249c699ec8efa5535561196b4','1970-01-01 00:00:00','2014-06-21 15:02:41'),('0b161a3f8ca81d127ffcbd651b46c6c5','4402d1fe21de4c6d60ea740758ec4cdb','2014-06-15 10:47:21','2014-06-14 10:47:21'),('0b161a3f8ca81d127ffcbd651b46c6c5','46c802f2d9b5d7d5a4b59500932f0bde','2018-06-14 05:27:57','2014-06-13 05:27:57'),('0b161a3f8ca81d127ffcbd651b46c6c5','46e991a9b95387065ae87cea525ffc67','2014-06-14 05:57:17','2014-06-13 05:57:17'),('0b161a3f8ca81d127ffcbd651b46c6c5','4851e99ae3672ad87a412dcf08374c14','2014-06-14 13:20:38','2014-06-13 13:20:38'),('0b161a3f8ca81d127ffcbd651b46c6c5','4bfbf557ba1a149e816e9b60e9a81efb','2014-06-29 05:07:57','2014-06-28 05:47:57'),('0b161a3f8ca81d127ffcbd651b46c6c5','4ca5dd200e92e194ce5c37f63650a5b1','2014-06-22 06:46:28','2014-06-21 06:46:28'),('0b161a3f8ca81d127ffcbd651b46c6c5','5181b3a5b9bba1144e9e360db5718656','2014-06-21 15:04:31','2014-06-21 15:04:31'),('0b161a3f8ca81d127ffcbd651b46c6c5','528633744a528bc919b002954d959b05','2014-06-14 15:30:33','2014-06-13 15:30:33'),('0b161a3f8ca81d127ffcbd651b46c6c5','5363b4fa6a1e2350b9a4838eb6dffeff','2014-06-22 06:46:09','2014-06-21 06:46:09'),('0b161a3f8ca81d127ffcbd651b46c6c5','5566ce434f2b6149cc1d98328742324b','2014-06-22 08:20:39','2014-06-21 08:20:39'),('0b161a3f8ca81d127ffcbd651b46c6c5','5579fdf7cfb01d5113c41f1810f82c38','2014-06-22 07:59:24','2014-06-21 07:59:24'),('0b161a3f8ca81d127ffcbd651b46c6c5','5761e55b950aad362c6013d56530eb6a','1970-01-01 00:00:00','2014-06-21 15:05:04'),('0b161a3f8ca81d127ffcbd651b46c6c5','58b4fb83365159ae337c402abbd45bed','2014-06-14 10:44:47','2014-06-13 10:44:47'),('0b161a3f8ca81d127ffcbd651b46c6c5','5bb9d0c0f9f4515674266a40b041611d','2014-06-22 09:47:31','2014-06-21 09:47:31'),('0b161a3f8ca81d127ffcbd651b46c6c5','5d1bec4a9e9d65b39f69b77c932764f8','2014-06-14 15:22:31','2014-06-13 15:22:31'),('0b161a3f8ca81d127ffcbd651b46c6c5','5dd62bad9141bcbfdd57dad0ad1b3285','2014-06-22 08:07:32','2014-06-21 08:07:32'),('0b161a3f8ca81d127ffcbd651b46c6c5','5e175ecdd4a638524d02bebecf5b73a0','2014-06-22 06:46:28','2014-06-21 06:46:28'),('0b161a3f8ca81d127ffcbd651b46c6c5','6059b48a5a420949af6bb97b89b04f24','2014-06-14 17:17:18','2014-06-13 17:17:18'),('0b161a3f8ca81d127ffcbd651b46c6c5','61c823d357c59d6f8572dd81e5c9e665','2014-06-22 08:07:34','2014-06-21 08:07:34'),('0b161a3f8ca81d127ffcbd651b46c6c5','6545d14e6e0917d3265108b0cb8f294b','2014-06-14 06:09:42','2014-06-13 06:09:42'),('0b161a3f8ca81d127ffcbd651b46c6c5','668da53a1850e97bdf4376cf0b39793e','2014-06-22 11:29:39','2014-06-21 11:29:39'),('0b161a3f8ca81d127ffcbd651b46c6c5','669591a1919637ad15db417093108499','2014-06-22 08:01:05','2014-06-21 08:01:05'),('0b161a3f8ca81d127ffcbd651b46c6c5','671a5c48ee2595acec7fedf6efa93327','2014-06-14 10:44:50','2014-06-13 10:44:50'),('0b161a3f8ca81d127ffcbd651b46c6c5','679f8ee29622579598362897432ace22','2014-06-14 15:24:30','2014-06-13 15:24:30'),('0b161a3f8ca81d127ffcbd651b46c6c5','67e801e930fc7db9ddc83e62f671ca0a','2014-06-22 08:07:36','2014-06-21 08:07:36'),('0b161a3f8ca81d127ffcbd651b46c6c5','6cf0287f38ac934e0b7ca313fa67bc71','1970-01-01 00:00:00','2014-06-21 15:04:06'),('0b161a3f8ca81d127ffcbd651b46c6c5','6e09cfb97eae6d142b0626a6b4d20793','2014-06-22 08:07:49','2014-06-21 08:07:49'),('0b161a3f8ca81d127ffcbd651b46c6c5','6fb5744a947aa4ed35aa59c42999bfa7','2014-06-14 15:22:30','2014-06-13 15:22:30'),('0b161a3f8ca81d127ffcbd651b46c6c5','70887567295c8d2a76d132445074da06','2014-06-22 06:50:58','2014-06-21 06:50:58'),('0b161a3f8ca81d127ffcbd651b46c6c5','70b7a487f91dd8fcf81f5de7e9a13347','2014-06-14 15:23:37','2014-06-13 15:23:37'),('0b161a3f8ca81d127ffcbd651b46c6c5','70bf526bf7b8c76037cf2ee7ce63a865','2014-06-14 06:03:36','2014-06-13 06:03:36'),('0b161a3f8ca81d127ffcbd651b46c6c5','721eebce6cb56b0d4639a2b31f785150','2014-06-22 06:46:28','2014-06-21 06:46:28'),('0b161a3f8ca81d127ffcbd651b46c6c5','7792902cc8e8914a755dbc864296600f','2014-06-22 14:57:47','2014-06-21 14:57:47'),('0b161a3f8ca81d127ffcbd651b46c6c5','77a566a59379d6696b5c5dd674173ec0','2014-06-14 06:09:42','2014-06-13 06:09:42'),('0b161a3f8ca81d127ffcbd651b46c6c5','789c544c1c75dbbeb160aea66136ab18','2014-06-22 06:46:27','2014-06-21 06:46:27'),('0b161a3f8ca81d127ffcbd651b46c6c5','7b901c7020d7026144315aec0c7f1e18','2014-06-22 06:46:23','2014-06-21 06:46:23'),('0b161a3f8ca81d127ffcbd651b46c6c5','7f1afe826ea579c001e90a72c11f34b8','2014-06-22 14:29:11','2014-06-21 14:29:11'),('0b161a3f8ca81d127ffcbd651b46c6c5','8024dc7fd62f62ebe799c28758a67d28','2014-06-14 05:55:51','2014-06-13 05:55:51'),('0b161a3f8ca81d127ffcbd651b46c6c5','80e4acd1f326caff2a483345d95c1581','2014-06-22 14:27:56','2014-06-21 15:07:56'),('0b161a3f8ca81d127ffcbd651b46c6c5','8352f9925c53dc5c76cd23c2c1157357','2014-06-14 17:14:42','2014-06-13 17:14:42'),('0b161a3f8ca81d127ffcbd651b46c6c5','891deb877f186c0d892fc7d02401c508','2014-06-29 03:42:20','2014-06-28 04:22:20'),('0b161a3f8ca81d127ffcbd651b46c6c5','8928b03e959ee9e87c58a29e9af799ad','2014-07-15 04:30:56','2014-07-14 05:10:56'),('0b161a3f8ca81d127ffcbd651b46c6c5','89f2d362706c92b350580c2a3193e5ec','2014-06-22 14:25:55','2014-06-21 15:05:55'),('0b161a3f8ca81d127ffcbd651b46c6c5','8acbb91e6a16612adaec2ae6b7ee0dfb','2014-06-14 06:09:43','2014-06-13 06:09:43'),('0b161a3f8ca81d127ffcbd651b46c6c5','8e15daea5c79374632b64b0e1f5be6cd','2014-06-14 10:49:55','2014-06-13 10:49:55'),('0b161a3f8ca81d127ffcbd651b46c6c5','8fb8b3fe8d89a62123a781804595a1f1','2014-06-14 06:09:34','2014-06-13 06:09:34'),('0b161a3f8ca81d127ffcbd651b46c6c5','8fbea323c195b959c1a0322d52229566','2014-06-14 15:30:00','2014-06-13 15:30:00'),('0b161a3f8ca81d127ffcbd651b46c6c5','8feba23cd047a3adaa13960c7ab78aed','2014-06-22 08:07:22','2014-06-21 08:07:22'),('0b161a3f8ca81d127ffcbd651b46c6c5','9218f026691159c3e43a2bd94df024c5','2014-06-14 15:23:45','2014-06-13 15:23:45'),('0b161a3f8ca81d127ffcbd651b46c6c5','93f450d587081dfc5c403535138ef08d','2014-06-14 15:34:06','2014-06-13 15:34:06'),('0b161a3f8ca81d127ffcbd651b46c6c5','9591e16174653fdcc85c95aedaa7f09a','2014-06-22 06:50:42','2014-06-21 06:50:42'),('0b161a3f8ca81d127ffcbd651b46c6c5','97de57fe60fb3d9e60048897a1c5ad3e','2014-06-14 13:22:48','2014-06-13 13:22:48'),('0b161a3f8ca81d127ffcbd651b46c6c5','987a39ab1ecf294bf83a3af11ed00203','2014-06-29 09:04:23','2014-06-28 09:44:23'),('0b161a3f8ca81d127ffcbd651b46c6c5','9a5245d635afd0fa76fe43850129b756','2014-06-22 08:56:03','2014-06-21 08:56:03'),('0b161a3f8ca81d127ffcbd651b46c6c5','9af5a8d617b059f61a47b169bbae9dac','2014-06-22 06:46:20','2014-06-21 06:46:20'),('0b161a3f8ca81d127ffcbd651b46c6c5','9bf6b5b29ae3ac80289114f047029426','2014-06-22 08:07:48','2014-06-21 08:07:48'),('0b161a3f8ca81d127ffcbd651b46c6c5','9caf0431349361c60643ffe6a7ef2fee','2014-06-14 15:23:46','2014-06-13 15:23:46'),('0b161a3f8ca81d127ffcbd651b46c6c5','9f2f56f6abb4abb7b97f8040003609aa','2014-06-14 15:22:31','2014-06-13 15:22:31'),('0b161a3f8ca81d127ffcbd651b46c6c5','a1fd3bdeb3b011f077e7056a99e45dbc','2014-06-14 17:17:27','2014-06-13 17:17:27'),('0b161a3f8ca81d127ffcbd651b46c6c5','a461a1d35f7d8184c398d290790e607e','2014-06-22 06:46:28','2014-06-21 06:46:28'),('0b161a3f8ca81d127ffcbd651b46c6c5','a8c798da409e6a449806d563cdc87223','2014-06-14 07:40:37','2014-06-13 07:40:37'),('0b161a3f8ca81d127ffcbd651b46c6c5','a95d1c971eed2fe1512591c94534e477','2014-06-14 10:44:51','2014-06-13 10:44:51'),('0b161a3f8ca81d127ffcbd651b46c6c5','a9977d44e1a7257f234f81586706edd9','2014-06-22 08:00:46','2014-06-21 08:00:46'),('0b161a3f8ca81d127ffcbd651b46c6c5','aa72a2cfd16a940cca27025797be1d91','2014-06-22 14:53:40','2014-06-21 14:53:40'),('0b161a3f8ca81d127ffcbd651b46c6c5','adea18841195b210405acabfc30dd415','2014-06-14 10:45:35','2014-06-13 10:45:35'),('0b161a3f8ca81d127ffcbd651b46c6c5','af2effc6273cdda173df7ade63c6ae84','2014-06-14 10:50:31','2014-06-13 10:50:31'),('0b161a3f8ca81d127ffcbd651b46c6c5','afcf6c720a2068d5c9ade09e7aea607f','2014-06-22 06:50:58','2014-06-21 06:50:58'),('0b161a3f8ca81d127ffcbd651b46c6c5','b033db2941461554b282e66c3cfbcde3','2014-06-14 15:23:48','2014-06-13 15:23:48'),('0b161a3f8ca81d127ffcbd651b46c6c5','b08a1df362b1deec1092652bb2d9e303','2014-06-22 06:46:23','2014-06-21 06:46:23'),('0b161a3f8ca81d127ffcbd651b46c6c5','b36da83c06a27e0644ea54efc0809343','2014-06-14 10:44:53','2014-06-13 10:44:53'),('0b161a3f8ca81d127ffcbd651b46c6c5','b3d90dadde47363f555402d7e78fc048','2014-06-22 14:56:54','2014-06-21 14:56:54'),('0b161a3f8ca81d127ffcbd651b46c6c5','b859db525cb5fb31bf64e57e6850b71c','2014-06-22 14:30:49','2014-06-21 14:30:49'),('0b161a3f8ca81d127ffcbd651b46c6c5','ba154bc7073b3f71fe5d39c870a120d4','2014-06-14 05:57:03','2014-06-13 05:57:03'),('0b161a3f8ca81d127ffcbd651b46c6c5','bb08d151c3082c58de3824047dbc5f51','2014-06-22 14:27:36','2014-06-21 15:07:36'),('0b161a3f8ca81d127ffcbd651b46c6c5','bbda74535c3ce109b6963b88a4fd5ab3','2014-06-14 10:44:53','2014-06-13 10:44:53'),('0b161a3f8ca81d127ffcbd651b46c6c5','bcb3089cd94ea08e07ef072f30927cd7','2014-06-14 15:22:30','2014-06-13 15:22:30'),('0b161a3f8ca81d127ffcbd651b46c6c5','bf1a7070ff706e98fcd2b5e216d4b17c','2014-06-22 14:26:42','2014-06-21 15:06:42'),('0b161a3f8ca81d127ffcbd651b46c6c5','c09f37428448a31378128761bddbac60','1970-01-01 00:00:00','2014-06-21 15:03:01'),('0b161a3f8ca81d127ffcbd651b46c6c5','c4b0cea5a71420fc93c28253fab3b895','2014-06-14 13:21:45','2014-06-13 13:21:45'),('0b161a3f8ca81d127ffcbd651b46c6c5','c6982d68bae37dc0dd63d018b82c6386','2014-06-22 13:15:32','2014-06-21 13:15:32'),('0b161a3f8ca81d127ffcbd651b46c6c5','c8da1419af7ced9979e1249db6e6a3a1','2014-06-14 06:09:42','2014-06-13 06:09:42'),('0b161a3f8ca81d127ffcbd651b46c6c5','c9ca0cccf7de40a24d49cba99d12b4f4','2014-06-29 07:46:16','2014-06-28 08:26:16'),('0b161a3f8ca81d127ffcbd651b46c6c5','cdd4a9577341550ec8b18b4151fa248e','2014-06-14 15:26:23','2014-06-13 15:26:23'),('0b161a3f8ca81d127ffcbd651b46c6c5','cedc14da51fbc4caa04ef41b6e4f0315','1970-01-01 00:00:00','2014-06-21 15:01:22'),('0b161a3f8ca81d127ffcbd651b46c6c5','cfd68ba966fd45913841e081eb0668d5','2014-07-21 13:27:04','2014-07-20 14:07:04'),('0b161a3f8ca81d127ffcbd651b46c6c5','d1e07d17c98b4c6fcc34f37524a85cde','1970-01-01 00:00:00','2014-06-21 15:04:53'),('0b161a3f8ca81d127ffcbd651b46c6c5','d2d21c34c0edfc82327a11c5a05bb53c','2014-06-14 11:32:25','2014-06-13 11:32:25'),('0b161a3f8ca81d127ffcbd651b46c6c5','d2e8ee4829db4121c3324f4aae5da7d9','2014-06-14 15:22:31','2014-06-13 15:22:31'),('0b161a3f8ca81d127ffcbd651b46c6c5','d5ba54a04ead22f152ce5af9e3555637','2014-06-22 14:25:17','2014-06-21 15:05:17'),('0b161a3f8ca81d127ffcbd651b46c6c5','da9bb3fe75d0af48cc881f9875f2aafd','2014-06-14 14:32:20','2014-06-13 14:32:20'),('0b161a3f8ca81d127ffcbd651b46c6c5','dade646d9d713ce94b7cfa2f49b050b0','2014-06-22 08:37:38','2014-06-21 08:37:38'),('0b161a3f8ca81d127ffcbd651b46c6c5','de0881dc4f9e6fc340fc733c94223d6e','2014-07-15 04:30:58','2014-07-14 05:10:58'),('0b161a3f8ca81d127ffcbd651b46c6c5','e1e3219af60ed40b9415f04c59f855f0','1970-01-01 00:00:00','2014-06-21 14:59:02'),('0b161a3f8ca81d127ffcbd651b46c6c5','e463bd9a120b46992befc2fb23bbf6f9','1970-01-01 00:00:00','2014-06-21 14:59:16'),('0b161a3f8ca81d127ffcbd651b46c6c5','e4be0cef7928e8b85a878950381815de','2014-06-22 14:25:43','2014-06-21 15:05:43'),('0b161a3f8ca81d127ffcbd651b46c6c5','e579d7415881a32354db53370f90daea','2014-06-22 07:56:06','2014-06-21 07:56:06'),('0b161a3f8ca81d127ffcbd651b46c6c5','e8aa660b2429aab80e85da1f61235d18','2014-06-22 14:25:35','2014-06-21 14:25:35'),('0b161a3f8ca81d127ffcbd651b46c6c5','ea8c182c6c496356e1ef5bee63c4d530','2014-06-14 15:19:58','2014-06-13 15:19:58'),('0b161a3f8ca81d127ffcbd651b46c6c5','ebb74595acea83c6ff9d1bbb07075b47','2014-06-15 10:47:04','2014-06-14 10:47:04'),('0b161a3f8ca81d127ffcbd651b46c6c5','eca394880291cd24f9c75eba0433d4f2','2014-06-22 08:01:03','2014-06-21 08:01:03'),('0b161a3f8ca81d127ffcbd651b46c6c5','ee973ea6dc23486acf068b00d30fa27e','2014-06-15 10:49:35','2014-06-14 10:49:35'),('0b161a3f8ca81d127ffcbd651b46c6c5','ef3b9c52d085b2b9cfd6e83f84d8487b','2014-06-14 15:22:26','2014-06-13 15:22:26'),('0b161a3f8ca81d127ffcbd651b46c6c5','ef726e0229bedfb91da1be98ca2f5d5f','2014-06-14 14:33:30','2014-06-13 14:33:30'),('0b161a3f8ca81d127ffcbd651b46c6c5','f0052bb5955f4e27c60c768ef0c99baf','2014-06-22 08:19:06','2014-06-21 08:19:06'),('0b161a3f8ca81d127ffcbd651b46c6c5','f00e4314278af045f6a6aa43ab7f442d','2014-06-14 15:22:29','2014-06-13 15:22:29'),('0b161a3f8ca81d127ffcbd651b46c6c5','f1365c0d9685005214b788c6bbd89140','2014-06-22 07:56:15','2014-06-21 07:56:15'),('0b161a3f8ca81d127ffcbd651b46c6c5','f15969f1a5f56bb784873173c85782c0','2014-06-14 05:56:42','2014-06-13 05:56:42'),('0b161a3f8ca81d127ffcbd651b46c6c5','f1d44cf560a240990f3a4e38e258c659','1970-01-01 00:00:00','2014-06-21 15:03:09'),('0b161a3f8ca81d127ffcbd651b46c6c5','f260fcf9f2e95eb3b47e0ba9e9a1450e','2014-06-22 14:51:24','2014-06-21 14:51:24'),('0b161a3f8ca81d127ffcbd651b46c6c5','f27a4ccb274b6cf2046db11427805217','2014-06-29 12:10:09','2014-06-28 12:50:09'),('0b161a3f8ca81d127ffcbd651b46c6c5','f31a6222d4f18b382a91054b5d5a7167','2014-06-14 07:38:55','2014-06-13 07:38:55'),('0b161a3f8ca81d127ffcbd651b46c6c5','f546be199de6e6ffb42ab3350e6fb8a3','2014-06-22 06:46:19','2014-06-21 06:46:19'),('0b161a3f8ca81d127ffcbd651b46c6c5','fd0385749205fde9788bdf9ce6aac52e','2014-06-22 06:46:27','2014-06-21 06:46:27');

UNLOCK TABLES;

/*Table structure for table `user_credential` */

DROP TABLE IF EXISTS `user_credential`;

CREATE TABLE `user_credential` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `user_id` char(32) NOT NULL DEFAULT '',
  `user_credential_id` char(32) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL DEFAULT '',
  `hashed_password` varchar(255) NOT NULL DEFAULT '',
  `password_salt` varchar(13) NOT NULL DEFAULT '',
  `pin_code` char(4) NOT NULL DEFAULT '',
  `last_activity` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_credential_id`),
  KEY `SYNC` (`created_on`,`modified_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_credential` */

LOCK TABLES `user_credential` WRITE;

insert  into `user_credential`(`branch_id`,`user_id`,`user_credential_id`,`username`,`hashed_password`,`password_salt`,`pin_code`,`last_activity`,`status`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','0b161a3f8ca81d127ffcbd651b46c6c5','55244fc540c31f2d026fe0c226851585','migz','$5$53bcd77abcc59$pWeoztbLyZwdyrr8ChrMEFGP8QFs8xJroOzWuKZO761','53bcd77abcc59','1234','0000-00-00 00:00:00','ENABLED','2014-05-25 16:26:05','a75c2b6af02f387f27852951c970492a','2014-07-09 05:47:38','0b161a3f8ca81d127ffcbd651b46c6c5');

UNLOCK TABLES;

/*Table structure for table `user_role_link` */

DROP TABLE IF EXISTS `user_role_link`;

CREATE TABLE `user_role_link` (
  `branch_id` char(32) NOT NULL DEFAULT '',
  `user_role_id` char(32) NOT NULL DEFAULT '',
  `user_credential_id` char(32) NOT NULL DEFAULT '',
  `role_id` char(32) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` char(32) NOT NULL DEFAULT '',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_role_id`),
  KEY `SYNC` (`branch_id`,`created_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_role_link` */

LOCK TABLES `user_role_link` WRITE;

insert  into `user_role_link`(`branch_id`,`user_role_id`,`user_credential_id`,`role_id`,`created_on`,`created_by`,`modified_on`,`modified_by`) values ('e0edd35d5e9eab9897138d43bd090c8d','8d5ad6d94ff2d08de7cdcf1a321b6d91','55244fc540c31f2d026fe0c226851585','e216f7161a32647081ce4e850cda45db','2014-05-31 08:29:17','a75c2b6af02f387f27852951c970492a','0000-00-00 00:00:00',''),('e0edd35d5e9eab9897138d43bd090c8d','da02df3f41e9753745cab4039a2a2807','55244fc540c31f2d026fe0c226851585','fa54b7d76983560554ec627b487affda','2014-05-31 05:15:12','a75c2b6af02f387f27852951c970492a','0000-00-00 00:00:00','');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
