/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50622
Source Host           : localhost:3306
Source Database       : online_pay

Target Server Type    : MYSQL
Target Server Version : 50622
File Encoding         : 65001

Date: 2016-06-13 21:03:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `auditor`
-- ----------------------------
DROP TABLE IF EXISTS `auditor`;
CREATE TABLE `auditor` (
  `Auditor_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(11) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`Auditor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auditor
-- ----------------------------

-- ----------------------------
-- Table structure for `booking_admin`
-- ----------------------------
DROP TABLE IF EXISTS `booking_admin`;
CREATE TABLE `booking_admin` (
  `admin_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(11) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `booking_admin_name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of booking_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `cancel`
-- ----------------------------
DROP TABLE IF EXISTS `cancel`;
CREATE TABLE `cancel` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `buyer` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `reason` varchar(400) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cancel
-- ----------------------------

-- ----------------------------
-- Table structure for `card`
-- ----------------------------
DROP TABLE IF EXISTS `card`;
CREATE TABLE `card` (
  `user_name` varchar(40) NOT NULL,
  `card_id` char(20) NOT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of card
-- ----------------------------
INSERT INTO `card` VALUES ('yyh951102', '12345678912345678011');
INSERT INTO `card` VALUES ('yyh951102', '12345678912345678901');
INSERT INTO `card` VALUES ('yyh951102', '12345678912345678910');

-- ----------------------------
-- Table structure for `comment`
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `comment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(11) NOT NULL,
  `room_id` bigint(20) NOT NULL,
  `score` decimal(2,1) NOT NULL,
  `comment` varchar(250) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment
-- ----------------------------

-- ----------------------------
-- Table structure for `commodity`
-- ----------------------------
DROP TABLE IF EXISTS `commodity`;
CREATE TABLE `commodity` (
  `commodity_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `commodity_type` varchar(10) NOT NULL,
  `original_id` bigint(20) NOT NULL,
  PRIMARY KEY (`commodity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10101 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of commodity
-- ----------------------------

-- ----------------------------
-- Table structure for `complaint`
-- ----------------------------
DROP TABLE IF EXISTS `complaint`;
CREATE TABLE `complaint` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `buyer` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `reason` varchar(400) NOT NULL,
  `state` varchar(1) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of complaint
-- ----------------------------

-- ----------------------------
-- Table structure for `flight`
-- ----------------------------
DROP TABLE IF EXISTS `flight`;
CREATE TABLE `flight` (
  `flight_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `flight_number` varchar(6) NOT NULL,
  `airline_name` varchar(20) NOT NULL,
  `begin_city` varchar(20) NOT NULL,
  `end_city` varchar(20) NOT NULL,
  `begin_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `begin_airport` varchar(20) NOT NULL,
  `end_airport` varchar(20) NOT NULL,
  `if_stop` varchar(20) NOT NULL,
  `user_discount` tinyint(4) NOT NULL,
  `vip_discount` tinyint(4) NOT NULL,
  `price` smallint(6) NOT NULL,
  `amount` smallint(6) NOT NULL,
  PRIMARY KEY (`flight_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of flight
-- ----------------------------

-- ----------------------------
-- Table structure for `hotel`
-- ----------------------------
DROP TABLE IF EXISTS `hotel`;
CREATE TABLE `hotel` (
  `hotel_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hotel_name` varchar(50) NOT NULL,
  `place` varchar(100) NOT NULL,
  `star` tinyint(4) NOT NULL,
  `hot` int(11) NOT NULL,
  `score` decimal(2,1) NOT NULL,
  `lowest_price` smallint(6) NOT NULL DEFAULT '32767',
  PRIMARY KEY (`hotel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hotel
-- ----------------------------

-- ----------------------------
-- Table structure for `logistics`
-- ----------------------------
DROP TABLE IF EXISTS `logistics`;
CREATE TABLE `logistics` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `buyer` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `state` varchar(1) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logistics
-- ----------------------------

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification` (
  `notification_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `timestamp` date DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `body` text,
  `is_read` bit(1) DEFAULT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `order_records`
-- ----------------------------
DROP TABLE IF EXISTS `order_records`;
CREATE TABLE `order_records` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` bigint(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `buyer` bigint(20) NOT NULL,
  `seller` bigint(20) NOT NULL,
  `state` varchar(1) NOT NULL,
  `start_time` datetime NOT NULL,
  `close_time` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order_records
-- ----------------------------

-- ----------------------------
-- Table structure for `payment`
-- ----------------------------
DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `buyer` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `state` varchar(1) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of payment
-- ----------------------------

-- ----------------------------
-- Table structure for `prepaid_card`
-- ----------------------------
DROP TABLE IF EXISTS `prepaid_card`;
CREATE TABLE `prepaid_card` (
  `card_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `amount` int(10) unsigned NOT NULL,
  `password` varchar(11) NOT NULL,
  `is_used` bit(1) DEFAULT b'0',
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of prepaid_card
-- ----------------------------

-- ----------------------------
-- Table structure for `refund`
-- ----------------------------
DROP TABLE IF EXISTS `refund`;
CREATE TABLE `refund` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `buyer` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `reason` varchar(400) NOT NULL,
  `state` varchar(1) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of refund
-- ----------------------------

-- ----------------------------
-- Table structure for `room`
-- ----------------------------
DROP TABLE IF EXISTS `room`;
CREATE TABLE `room` (
  `room_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint(20) NOT NULL,
  `room_type` varchar(20) NOT NULL,
  `user_discount` tinyint(4) NOT NULL,
  `vip_discount` tinyint(4) NOT NULL,
  `price` smallint(6) NOT NULL,
  `amount` smallint(6) NOT NULL,
  PRIMARY KEY (`room_id`),
  KEY `hotel_id` (`hotel_id`),
  CONSTRAINT `room_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`hotel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of room
-- ----------------------------

-- ----------------------------
-- Table structure for `room_time`
-- ----------------------------
DROP TABLE IF EXISTS `room_time`;
CREATE TABLE `room_time` (
  `room_id` bigint(20) NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  KEY `room_id` (`room_id`),
  CONSTRAINT `room_time_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of room_time
-- ----------------------------

-- ----------------------------
-- Table structure for `system_admin`
-- ----------------------------
DROP TABLE IF EXISTS `system_admin`;
CREATE TABLE `system_admin` (
  `admin_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(11) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `system_admin_name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of system_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `transact_flow`
-- ----------------------------
DROP TABLE IF EXISTS `transact_flow`;
CREATE TABLE `transact_flow` (
  `flow_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) NOT NULL,
  `type` varchar(1) NOT NULL,
  `event_time` datetime NOT NULL,
  `state` varchar(1) NOT NULL,
  PRIMARY KEY (`flow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of transact_flow
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(40) NOT NULL,
  `login_password` varchar(32) NOT NULL,
  `transaction_password` varchar(32) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `is_buyer` varchar(1) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone_number` char(11) DEFAULT NULL,
  `name` varchar(15) DEFAULT NULL,
  `identity_card` char(18) DEFAULT NULL,
  `is_name_verified` varchar(1) DEFAULT NULL,
  `is_mail_verified` varchar(1) DEFAULT NULL,
  `is_in_blacklist` varchar(1) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL,
  `vip_exp` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10010 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('10008', 'yyh951102', 'e10adc3949ba59abbe56e057f20f883e', 'e10adc3949ba59abbe56e057f20f883e', 'M', '1', '234347589@qq.com', '17816890209', 'sdfdsfdsfds', 'sdfdsfdsfsdf', '1', '0', null, '0', '0');
INSERT INTO `user` VALUES ('10009', 'yyh9511022', 'c92f606717d8826c16893e9bacdd6c47', null, 'M', '1', '234347589@qqv.com', null, null, null, null, null, null, '0', '0');
