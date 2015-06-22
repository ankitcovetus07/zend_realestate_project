-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 25, 2013 at 03:15 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `property`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `users` (
 `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `type` ENUM('admin', 'superadmin', 'salesagent') NULL ,
 `first_name` varchar(100) NULL ,
 `last_name` varchar(100) NULL ,
 `email_address` varchar(100) NULL UNIQUE,
 `password` varchar(100) NULL ,
 `created_by` SMALLINT UNSIGNED NULL ,
 `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
 `updated_by` SMALLINT UNSIGNED NULL ,
 `updated_at` TIMESTAMP NULL ,
 PRIMARY KEY (`id`) ) ENGINE = InnoDB;

insert into users values (1,'superadmin','superadmin','superadmin','superadmin@superadmin.com','5f4dcc3b5aa765d61d8327deb882cf99',1,'2014-01-01',1,'2014-01-01');
insert into users values (2,'admin','admin','admin','admin@admin.com','5f4dcc3b5aa765d61d8327deb882cf99',1,'2014-01-01',1,'2014-01-01');
insert into users values (3,'salesagent','salesagent','salesagent','salesagent@salesagent.com','5f4dcc3b5aa765d61d8327deb882cf99',1,'2014-01-01',1,'2014-01-01');

 
--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
 `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `name` varchar(100) NULL ,
 `status` ENUM('active', 'deactive') NULL,
 `created_by` SMALLINT UNSIGNED NULL ,
 `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
 `updated_by` SMALLINT UNSIGNED NULL ,
 `updated_at` TIMESTAMP NULL ,
 PRIMARY KEY (`id`) ) ENGINE = InnoDB;
 
 
 --
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project_user` (
 `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `project_id` SMALLINT UNSIGNED NOT NULL,
 `user_id` SMALLINT UNSIGNED NOT NULL,
 PRIMARY KEY (`id`) ) ENGINE = InnoDB;
 
 
 CREATE TABLE IF NOT EXISTS `client` (
 `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `first_name` varchar(100) NULL ,
 `last_name` varchar(100) NULL ,
 `project_id` SMALLINT UNSIGNED NOT NULL,
 `suit_number` int(10),
 `unit_number` int(10),
 `suit_level` int(10),
 `parking_number` int(10),
 `parking_unit_number` int(10),
 `parking_level_number` int(10),
 `locker_number` int(10),
 `locker_unit_number` int(10),
 `locker_level_number` int(10),
 `sin_number` int(10),
 `email_address` varchar(100) NULL ,
 `phone_number` varchar(20) NULL ,
 `date_of_birth` DATE NULL ,
 `status` ENUM('active', 'deactive') NULL,
 `purchase_price` float(10,2),
 `purchase_type` ENUM('purchase', 'upgrade'),
 `type` ENUM('individual', 'corporation', 'trustee') NULL ,
 `address` varchar(20) NULL ,
 `created_by` SMALLINT UNSIGNED NULL ,
 `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
 `updated_by` SMALLINT UNSIGNED NULL ,
 `updated_at` TIMESTAMP NULL ,
 PRIMARY KEY (`id`) ) ENGINE = InnoDB;
 
 
ALTER TABLE  `project` ADD  `address` VARCHAR( 30 ) NOT NULL AFTER  `name`;
ALTER TABLE  `project` ADD  `project_type` ENUM(  'condo_units',  'town_houses' ) NOT NULL AFTER  `address`;


CREATE TABLE IF NOT EXISTS `payment_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `cheque_number` int(15) NOT NULL,
  `payment_date` date NOT NULL,
  `status` enum('deposit','clear','NSF') NOT NULL,
  `NSF_fee` float(5,2) DEFAULT NULL,
  `type` varchar(15) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE  `client` ADD  `purchase_date` DATE NULL AFTER  `purchase_type`;
ALTER TABLE  `client` CHANGE  `unit_number`  `suit_unit_number` INT( 10 ) NULL DEFAULT NULL;
ALTER TABLE  `payment_log` add  `tarion_amount`  FLOAT( 10, 2 ) NOT NULL;
ALTER TABLE  `payment_log` add  `regular_amount`  FLOAT( 10, 2 ) NOT NULL;
ALTER TABLE  `payment_log` ADD  `suit_number` INT( 10 ) NOT NULL AFTER  `project_id` , ADD INDEX (  `suit_number` );
ALTER TABLE  `payment_log` ADD  `payment_type` ENUM('purchase','parking','locker','upgrade')  NOT NULL AFTER  `cheque_number`;
ALTER TABLE  `payment_log` ADD  `payment_method` ENUM('cheque','wire_transfer','visa')  NOT NULL AFTER  `payment_type`;

ALTER TABLE  `project` ADD  `condo` ENUM(  'yes',  'no' ) NOT NULL AFTER  `project_type`;
ALTER TABLE  `users` ADD  `address` VARCHAR( 100 ) NULL DEFAULT NULL AFTER  `password`;
ALTER TABLE  `users` ADD  `position` VARCHAR( 20 ) NULL DEFAULT NULL AFTER  `address`;
ALTER TABLE  `users` ADD  `agent_name` VARCHAR( 50 ) NULL DEFAULT NULL AFTER  `address`;
ALTER TABLE  `payment_log` ADD  `financial_institution` VARCHAR( 50 ) NULL DEFAULT NULL AFTER  `type`;
ALTER TABLE  `payment_log` ADD  `address` VARCHAR( 100 ) NULL DEFAULT NULL AFTER  `financial_institution`;
ALTER TABLE  `payment_log` ADD  `telephone` VARCHAR( 15 ) NULL DEFAULT NULL AFTER  `address`;
ALTER TABLE  `payment_log` ADD  `account_number` VARCHAR( 15 ) NULL DEFAULT NULL AFTER  `financial_institution`;
ALTER TABLE  `project` ADD  `condo` ENUM(  'yes',  'no' ) NOT NULL AFTER  `address`;
ALTER TABLE  `payment_log` CHANGE  `status`  `status` ENUM(  'cheque_outstanding',  'not_deposit',  'deposit',  'clear',  'NSF' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `payment_log` ADD  `widthdraw_type` ENUM(  'tarion',  'regular',  'upgrade' ) NOT NULL AFTER  `telephone`;
ALTER TABLE  `payment_log` CHANGE  `status`  `status` ENUM(  'cheque_outstanding',  'not_deposit',  'deposit',  'clear',  'NSF' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE  `payment_log` CHANGE  `payment_method`  `payment_method` ENUM(  'cheque',  'wire_transfer',  'visa' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE  `payment_log` CHANGE  `payment_type`  `payment_type` ENUM(  'purchase',  'parking',  'locker',  'upgrade' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE  `payment_log` CHANGE  `widthdraw_type`  `widthdraw_type` ENUM(  'tarion',  'regular',  'upgrade' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE  `users` ADD  `entity` ENUM(  'partnership',  'corporation',  'trust' ) NULL DEFAULT NULL AFTER  `position`;
ALTER TABLE  `client` ADD  `terminate` ENUM(  'yes',  'no' ) NULL DEFAULT NULL AFTER  `status`;
ALTER TABLE  `client` CHANGE  `terminate`  `terminate` ENUM(  'yes',  'no' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT  'no';


ALTER TABLE  `payment_log` ADD  `transit_number` VARCHAR( 50 ) NOT NULL AFTER  `financial_institution`;



