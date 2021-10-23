-- MySQL dump 10.18  Distrib 10.3.27-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: inv
-- ------------------------------------------------------
-- Server version	10.3.27-MariaDB-0+deb10u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `brand_model`
--

DROP TABLE IF EXISTS `brand_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_model` (
  `brand_model_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_model_brand_model_id` int(10) NOT NULL,
  `brand_model_name` varchar(100) NOT NULL,
  `long_description` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `manufacturer_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`brand_model_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category_f_value`
--

DROP TABLE IF EXISTS `category_f_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_f_value` (
  `category_field_value_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_field_id` int(10) unsigned NOT NULL,
  `category_field_value` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`category_field_value_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category_field`
--

DROP TABLE IF EXISTS `category_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_field` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_parent_id` int(10) unsigned NOT NULL,
  `category_type_id` int(10) unsigned NOT NULL,
  `default_category_value_id` int(10) unsigned DEFAULT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `active_flag` bit(1) DEFAULT NULL,
  `required_flag` bit(1) DEFAULT NULL,
  `created_by` varchar(50) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `category_field_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=MyISAM AUTO_INCREMENT=10147 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category_field_type`
--

DROP TABLE IF EXISTS `category_field_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_field_type` (
  `category_field_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`category_field_type_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `category_inv_view`
--

DROP TABLE IF EXISTS `category_inv_view`;
/*!50001 DROP VIEW IF EXISTS `category_inv_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `category_inv_view` (
  `inventory_id` tinyint NOT NULL,
  `category_id` tinyint NOT NULL,
  `category_version` tinyint NOT NULL,
  `softwarename` tinyint NOT NULL,
  `flag` tinyint NOT NULL,
  `inventory_name` tinyint NOT NULL,
  `location_id` tinyint NOT NULL,
  `category` tinyint NOT NULL,
  `category_name` tinyint NOT NULL,
  `inventory_model_id` tinyint NOT NULL,
  `status` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `category_ipv6`
--

DROP TABLE IF EXISTS `category_ipv6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_ipv6` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) DEFAULT NULL,
  `ipv6_min_version` varchar(200) NOT NULL,
  `parent_category` varchar(200) NOT NULL,
  `flag` varchar(10) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=MyISAM AUTO_INCREMENT=10147 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category_rel`
--

DROP TABLE IF EXISTS `category_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_rel` (
  `inventory_id` int(10) NOT NULL,
  `category_id` varchar(100) NOT NULL,
  `category_version` varchar(100) NOT NULL,
  `softwarename` varchar(255) NOT NULL,
  `flag` varchar(10) NOT NULL,
  KEY `categoryid` (`category_id`),
  KEY `inventoryid` (`inventory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category_score`
--

DROP TABLE IF EXISTS `category_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_score` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `component` varchar(100) NOT NULL,
  `maket` varchar(100) NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `category_field_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`category_field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT 0,
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `components_score`
--

DROP TABLE IF EXISTS `components_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `components_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `cp_name` varchar(100) NOT NULL,
  `cp_market` varchar(50) NOT NULL,
  `physical_v_s` varchar(10) NOT NULL,
  `techical_v_s` varchar(10) NOT NULL,
  `technology_o_s` varchar(10) NOT NULL,
  `impacton_b_s` varchar(10) NOT NULL,
  `unit_enhancem_end_cost` varchar(30) NOT NULL,
  `normalised` varchar(200) NOT NULL,
  `reference` text NOT NULL,
  `no_action_overall_condition_score` varchar(50) NOT NULL,
  `incur_en_hancem_end_cost` varchar(50) NOT NULL,
  `investigate_retire_mitigation` varchar(50) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `file_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cp_name` (`cp_name`)
) ENGINE=InnoDB AUTO_INCREMENT=286 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inv11`
--

DROP TABLE IF EXISTS `inv11`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inv11` (
  `inventory_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_inventory_id` int(10) unsigned DEFAULT NULL,
  `inventory_model_id` int(10) unsigned NOT NULL,
  `serialnumber` varchar(100) DEFAULT NULL,
  `inventory_name` varchar(100) NOT NULL,
  `location_id` int(10) DEFAULT NULL,
  `primary_services` text NOT NULL,
  `check_in_date` datetime DEFAULT NULL,
  `warranty` tinyint(4) DEFAULT NULL,
  `expirty_date` datetime DEFAULT NULL,
  `status` enum('active','inactive','deleted') DEFAULT NULL,
  `lock` varchar(20) NOT NULL DEFAULT '0',
  `created_by` varchar(50) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`inventory_id`),
  UNIQUE KEY `inventoryName` (`inventory_model_id`,`inventory_name`)
) ENGINE=MyISAM AUTO_INCREMENT=32080 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `inventory_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_inventory_id` int(10) unsigned DEFAULT NULL,
  `inventory_model_id` int(10) unsigned NOT NULL,
  `serialnumber` varchar(100) DEFAULT NULL,
  `inventory_name` varchar(100) NOT NULL,
  `location_id` int(10) DEFAULT NULL,
  `primary_services` text NOT NULL,
  `check_in_date` datetime DEFAULT NULL,
  `warranty` tinyint(4) DEFAULT NULL,
  `expirty_date` datetime DEFAULT NULL,
  `status` enum('active','inactive','deleted') DEFAULT 'active',
  `lock` varchar(20) NOT NULL DEFAULT '0',
  `created_by` varchar(50) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`inventory_id`),
  UNIQUE KEY `inventoryName` (`inventory_model_id`,`inventory_name`)
) ENGINE=MyISAM AUTO_INCREMENT=32250 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventory_category_rel`
--

DROP TABLE IF EXISTS `inventory_category_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_category_rel` (
  `inventory_id` int(10) NOT NULL,
  `category_id` varchar(100) NOT NULL,
  `category_version` varchar(100) NOT NULL,
  `softwarename` varchar(255) NOT NULL,
  `flag` varchar(10) NOT NULL,
  KEY `categoryid` (`category_id`),
  KEY `inventoryid` (`inventory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventory_model`
--

DROP TABLE IF EXISTS `inventory_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_model` (
  `inventory_model_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_model_code` varchar(50) DEFAULT NULL,
  `inventory_model_name` varchar(255) NOT NULL,
  `long_description` text DEFAULT NULL,
  `brand_model_id` int(10) DEFAULT NULL,
  `created_by` varchar(50) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`inventory_model_id`),
  UNIQUE KEY `inventory_model_name` (`inventory_model_name`),
  UNIQUE KEY `inventory_model_code` (`inventory_model_code`),
  UNIQUE KEY `inventory_model_code_2` (`inventory_model_code`),
  KEY `asset_model_fkindex3` (`created_by`),
  KEY `asset_model_fkindex4` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventory_model_category`
--

DROP TABLE IF EXISTS `inventory_model_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_model_category` (
  `inventory_model_id` int(10) NOT NULL,
  `category_id` int(10) DEFAULT NULL,
  `position` int(11) NOT NULL,
  UNIQUE KEY `inventory_model_id` (`inventory_model_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventory_network_hardware_list`
--

DROP TABLE IF EXISTS `inventory_network_hardware_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_network_hardware_list` (
  `inventory_id` int(10) NOT NULL,
  `cid_101` text DEFAULT NULL,
  `cid_106` text DEFAULT NULL,
  `cid_111` text DEFAULT NULL,
  `cid_122` text DEFAULT NULL,
  `cid_123` text DEFAULT NULL,
  `cid_124` text DEFAULT NULL,
  `cid_126` text DEFAULT NULL,
  `cid_127` text DEFAULT NULL,
  `cid_125` text DEFAULT NULL,
  `cid_115` text DEFAULT NULL,
  `cid_116` text DEFAULT NULL,
  `cid_117` text DEFAULT NULL,
  `cid_118` text DEFAULT NULL,
  `cid_119` text DEFAULT NULL,
  `cid_120` text DEFAULT NULL,
  `cid_121` text DEFAULT NULL,
  `cid_1163` text DEFAULT NULL,
  `cid_1165` text DEFAULT NULL,
  `cid_1164` text DEFAULT NULL,
  `cid_1166` text DEFAULT NULL,
  `cid_1167` text DEFAULT NULL,
  `cid_1168` text DEFAULT NULL,
  `cid_1169` text DEFAULT NULL,
  `cid_1170` text DEFAULT NULL,
  `cid_107` text DEFAULT NULL,
  `cid_1171` text DEFAULT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `inventory_os_list`
--

DROP TABLE IF EXISTS `inventory_os_list`;
/*!50001 DROP VIEW IF EXISTS `inventory_os_list`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `inventory_os_list` (
  `inventory_id` tinyint NOT NULL,
  `os` tinyint NOT NULL,
  `kernel` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `inventory_server_hardware_list`
--

DROP TABLE IF EXISTS `inventory_server_hardware_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_server_hardware_list` (
  `inventory_id` int(10) NOT NULL AUTO_INCREMENT,
  `cid_101` text DEFAULT NULL,
  `cid_102` text DEFAULT NULL,
  `cid_103` text DEFAULT NULL,
  `cid_104` text DEFAULT NULL,
  `cid_105` text DEFAULT NULL,
  `cid_106` text DEFAULT NULL,
  `cid_107` text DEFAULT NULL,
  `cid_108` text DEFAULT NULL,
  `cid_109` text DEFAULT NULL,
  `cid_110` text DEFAULT NULL,
  `cid_111` text DEFAULT NULL,
  `cid_112` text DEFAULT NULL,
  `cid_113` text DEFAULT NULL,
  `cid_114` text DEFAULT NULL,
  `cid_115` text DEFAULT NULL,
  `cid_116` text DEFAULT NULL,
  `cid_117` text DEFAULT NULL,
  `cid_118` text DEFAULT NULL,
  `cid_119` text DEFAULT NULL,
  `cid_120` text DEFAULT NULL,
  `cid_121` text DEFAULT NULL,
  `cid_122` text DEFAULT NULL,
  `cid_123` text DEFAULT NULL,
  `cid_124` text DEFAULT NULL,
  `cid_125` text DEFAULT NULL,
  `cid_126` text DEFAULT NULL,
  `cid_127` text DEFAULT NULL,
  `cid_128` text DEFAULT NULL,
  `cid_1160` text DEFAULT NULL,
  `cid_1161` text DEFAULT NULL,
  `cid_10142` text DEFAULT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32242 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventory_vm_hardware_list`
--

DROP TABLE IF EXISTS `inventory_vm_hardware_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_vm_hardware_list` (
  `inventory_id` int(10) NOT NULL,
  `cid_101` text DEFAULT NULL,
  `cid_102` text DEFAULT NULL,
  `cid_104` text DEFAULT NULL,
  `cid_105` text DEFAULT NULL,
  `cid_111` text DEFAULT NULL,
  `cid_112` text DEFAULT NULL,
  `cid_113` text DEFAULT NULL,
  `cid_1152` text DEFAULT NULL,
  `cid_1153` text DEFAULT NULL,
  `cid_1154` text DEFAULT NULL,
  `cid_1155` text DEFAULT NULL,
  `cid_1159` text DEFAULT NULL,
  `cid_10142` text DEFAULT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `location_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_name` varchar(100) NOT NULL,
  `currency_code` varchar(30) NOT NULL,
  `long_description` text DEFAULT NULL,
  `created_by` varchar(50) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`location_id`),
  UNIQUE KEY `location_name` (`location_name`),
  KEY `location_fkindex1` (`created_by`),
  KEY `location_fkindex2` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manufacturer`
--

DROP TABLE IF EXISTS `manufacturer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manufacturer` (
  `manufacturer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `manufacturer_name` varchar(100) NOT NULL,
  `long_description` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`manufacturer_id`),
  KEY `manufacturer_fkindex1` (`created_by`),
  KEY `manufacturer_fkindex2` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(100) NOT NULL,
  `permission_desc` text DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rack_upload_list`
--

DROP TABLE IF EXISTS `rack_upload_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rack_upload_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `filename` varchar(300) NOT NULL,
  `uploadtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `report_appls_list`
--

DROP TABLE IF EXISTS `report_appls_list`;
/*!50001 DROP VIEW IF EXISTS `report_appls_list`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `report_appls_list` (
  `inventory_id` tinyint NOT NULL,
  `name` tinyint NOT NULL,
  `application` tinyint NOT NULL,
  `brand` tinyint NOT NULL,
  `os` tinyint NOT NULL,
  `kernel` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `report_overall_condition_list`
--

DROP TABLE IF EXISTS `report_overall_condition_list`;
/*!50001 DROP VIEW IF EXISTS `report_overall_condition_list`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `report_overall_condition_list` (
  `inventory_id` tinyint NOT NULL,
  `category_id` tinyint NOT NULL,
  `category_version` tinyint NOT NULL,
  `softwarename` tinyint NOT NULL,
  `flag` tinyint NOT NULL,
  `category_name` tinyint NOT NULL,
  `inventory_name` tinyint NOT NULL,
  `location_id` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `report_score_list`
--

DROP TABLE IF EXISTS `report_score_list`;
/*!50001 DROP VIEW IF EXISTS `report_score_list`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `report_score_list` (
  `category_id` tinyint NOT NULL,
  `category_version` tinyint NOT NULL,
  `category_name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sg-vm`
--

DROP TABLE IF EXISTS `sg-vm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sg-vm` (
  `inventory_id` int(10) NOT NULL,
  `category_id` varchar(100) NOT NULL,
  `category_version` varchar(100) NOT NULL,
  `softwarename` varchar(255) NOT NULL,
  `flag` varchar(10) NOT NULL,
  KEY `categoryid` (`category_id`),
  KEY `inventoryid` (`inventory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_login`
--

DROP TABLE IF EXISTS `system_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_login` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `target` varchar(100) NOT NULL,
  `target_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_setting`
--

DROP TABLE IF EXISTS `system_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_name` (`category`,`value`),
  UNIQUE KEY `value` (`value`),
  UNIQUE KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `location_id` text NOT NULL,
  `permission_list` text DEFAULT NULL,
  `status` enum('active','inactive','deleted') NOT NULL COMMENT 'User account enabled/disabled',
  `created_by` varchar(50) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='User accounts are stored in this table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_log`
--

DROP TABLE IF EXISTS `user_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `target` varchar(100) NOT NULL,
  `target_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2309 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `category_inv_view`
--

/*!50001 DROP TABLE IF EXISTS `category_inv_view`*/;
/*!50001 DROP VIEW IF EXISTS `category_inv_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `category_inv_view` AS select `rel`.`inventory_id` AS `inventory_id`,`rel`.`category_id` AS `category_id`,`rel`.`category_version` AS `category_version`,`rel`.`softwarename` AS `softwarename`,`rel`.`flag` AS `flag`,`inv`.`inventory_name` AS `inventory_name`,`inv`.`location_id` AS `location_id`,concat(trim(`cal`.`category_name`),'-',trim(`rel`.`category_version`)) AS `category`,trim(`cal`.`category_name`) AS `category_name`,`inv`.`inventory_model_id` AS `inventory_model_id`,`inv`.`status` AS `status` from ((`category_rel` `rel` left join `category_field` `cal` on(`cal`.`category_id` = `rel`.`category_id`)) left join `inventory` `inv` on(`inv`.`inventory_id` = `rel`.`inventory_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `inventory_os_list`
--

/*!50001 DROP TABLE IF EXISTS `inventory_os_list`*/;
/*!50001 DROP VIEW IF EXISTS `inventory_os_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `inventory_os_list` AS select `server`.`inventory_id` AS `inventory_id`,`server`.`cid_111` AS `os`,`server`.`cid_112` AS `kernel` from `inventory_server_hardware_list` `server` union all select `vm`.`inventory_id` AS `inventory_id`,`vm`.`cid_111` AS `os`,`vm`.`cid_112` AS `kernel` from `inventory_vm_hardware_list` `vm` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `report_appls_list`
--

/*!50001 DROP TABLE IF EXISTS `report_appls_list`*/;
/*!50001 DROP VIEW IF EXISTS `report_appls_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `report_appls_list` AS select `server`.`inventory_id` AS `inventory_id`,`server`.`cid_101` AS `name`,`server`.`cid_102` AS `application`,`server`.`cid_103` AS `brand`,`server`.`cid_111` AS `os`,`server`.`cid_112` AS `kernel` from `inventory_server_hardware_list` `server` union all select `vm`.`inventory_id` AS `inventory_id`,`vm`.`cid_101` AS `name`,`vm`.`cid_102` AS `application`,NULL AS `brand`,`vm`.`cid_111` AS `os`,`vm`.`cid_112` AS `kernel` from `inventory_vm_hardware_list` `vm` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `report_overall_condition_list`
--

/*!50001 DROP TABLE IF EXISTS `report_overall_condition_list`*/;
/*!50001 DROP VIEW IF EXISTS `report_overall_condition_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `report_overall_condition_list` AS select `rel`.`inventory_id` AS `inventory_id`,`rel`.`category_id` AS `category_id`,`rel`.`category_version` AS `category_version`,`rel`.`softwarename` AS `softwarename`,`rel`.`flag` AS `flag`,`cat`.`category_name` AS `category_name`,`inv`.`inventory_name` AS `inventory_name`,`inv`.`location_id` AS `location_id` from ((`category_rel` `rel` left join `inventory` `inv` on(`inv`.`inventory_id` = `rel`.`inventory_id`)) left join `category_field` `cat` on(`cat`.`category_id` = `rel`.`category_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `report_score_list`
--

/*!50001 DROP TABLE IF EXISTS `report_score_list`*/;
/*!50001 DROP VIEW IF EXISTS `report_score_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `report_score_list` AS select `rel`.`category_id` AS `category_id`,`rel`.`category_version` AS `category_version`,`cat`.`category_name` AS `category_name` from ((`category_rel` `rel` left join `inventory` `inv` on(`inv`.`inventory_id` = `rel`.`inventory_id`)) left join `category_field` `cat` on(`cat`.`category_id` = `rel`.`category_id`)) group by `rel`.`category_id`,`rel`.`category_version` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-10-23  0:52:58
