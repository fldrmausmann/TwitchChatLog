/*
Navicat MySQL Data Transfer

Source Server         : greyzer
Source Server Version : 50554
Source Host           : 51.255.133.141:3306
Source Database       : twitchchat

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-02-26 19:57:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for chat
-- ----------------------------
DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
  `time` datetime DEFAULT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
