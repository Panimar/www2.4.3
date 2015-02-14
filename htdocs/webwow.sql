/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50509
Source Host           : localhost:3306
Source Database       : webwow

Target Server Type    : MYSQL
Target Server Version : 50509
File Encoding         : 65001

Date: 2011-03-11 18:57:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `accounts_more`
-- ----------------------------
DROP TABLE IF EXISTS `accounts_more`;
CREATE TABLE `accounts_more` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `acc_login` varchar(55) COLLATE latin1_general_ci NOT NULL,
  `vp` bigint(55) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL,
  `answer` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `dp` bigint(55) NOT NULL DEFAULT '0',
  `gmlevel` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`acc_login`)
) ENGINE=MyISAM AUTO_INCREMENT=2591 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Account Information';

-- ----------------------------
-- Records of accounts_more
-- ----------------------------
INSERT INTO `accounts_more` VALUES ('2590', 'ADMIN', '9000', '2', 'yourmom', '9000', '3');

-- ----------------------------
-- Table structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `poster` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  `newsid` int(11) NOT NULL,
  `timepost` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `datepost` varchar(100) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='News Comments Database';

-- ----------------------------
-- Records of comments
-- ----------------------------

-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `content` longtext COLLATE latin1_general_ci NOT NULL,
  `iconid` int(11) NOT NULL,
  `timepost` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `datepost` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `author` varchar(50) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Website News';

-- ----------------------------
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES ('95', 'Welcome!', '<p><span style=\"color: white\">This is your new awesome website. Please be sure to LEAVE THE FOOTER ALONE! and +rep Jeutie on AC-Web if you like the release. Contact Evil! If you want custom logos or images. Enjoy!</span></p>', '0', '1299865425', 'March 11, 2011', 'admin');

-- ----------------------------
-- Table structure for `pages`
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `link` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `position` int(11) NOT NULL DEFAULT '1' COMMENT '1 for default 2 for below',
  `orderby` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Website URL database';

-- ----------------------------
-- Records of pages
-- ----------------------------

-- ----------------------------
-- Table structure for `paypal_data`
-- ----------------------------
DROP TABLE IF EXISTS `paypal_data`;
CREATE TABLE `paypal_data` (
  `id` bigint(21) NOT NULL AUTO_INCREMENT,
  `login` varchar(55) COLLATE latin1_general_ci NOT NULL,
  `txnid` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `amount` bigint(21) NOT NULL,
  `who` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `whendon` bigint(100) NOT NULL DEFAULT '0',
  `comment` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=222 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='PayPal Information';

-- ----------------------------
-- Records of paypal_data
-- ----------------------------

-- ----------------------------
-- Table structure for `shop`
-- ----------------------------
DROP TABLE IF EXISTS `shop`;
CREATE TABLE `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sep` varchar(3) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `name` text COLLATE latin1_general_ci NOT NULL,
  `itemid` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `color` tinytext COLLATE latin1_general_ci NOT NULL,
  `cat` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `sort` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `cost` varchar(11) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `charges` varchar(11) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `donateorvote` int(5) NOT NULL DEFAULT '0' COMMENT '0 is vote 1 is donation item',
  `description` varchar(255) COLLATE latin1_general_ci DEFAULT 'No Description',
  `custom` varchar(3) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `realm` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Donation Shop';

-- ----------------------------
-- Records of shop
-- ----------------------------
INSERT INTO `shop` VALUES ('46', '1', 'Mounts', '0', '', '2', '0', '0', '0', '1', 'No Description', '0', '1');
INSERT INTO `shop` VALUES ('48', '1', 'Companions', '0', '', '4', '0', '0', '0', '1', 'No Description', '0', '1');
INSERT INTO `shop` VALUES ('49', '0', 'Celestial Steed', '54811', '4', '2', '2', '10', '1', '1', 'This is a 280% speed horse mount.', '0', '1');
INSERT INTO `shop` VALUES ('50', '0', 'X-53 Touring Rocket', '54860', '4', '2', '3', '10', '1', '1', 'This is a 280% speed duo-seat rocket mount.', '0', '1');
INSERT INTO `shop` VALUES ('54', '0', 'Swift Zhevra', '37719', '4', '2', '1', '10', '1', '1', 'This is a 100% speed Zhevra mount.', '0', '1');
INSERT INTO `shop` VALUES ('55', '0', 'Lil\' Phylactery', '49693', '3', '4', '1', '5', '1', '1', 'This is a rare companion.', '0', '1');
INSERT INTO `shop` VALUES ('56', '0', 'Lil\' XT', '54847', '3', '4', '2', '5', '1', '1', 'This is a rare companion.', '0', '1');
INSERT INTO `shop` VALUES ('57', '0', 'Pandaren Monk', '49665', '3', '4', '3', '5', '1', '1', 'This is a rare companion.', '0', '1');
INSERT INTO `shop` VALUES ('58', '0', 'Wind Rider Cub', '49663', '3', '4', '4', '5', '1', '1', 'This is a rare companion.', '0', '1');
INSERT INTO `shop` VALUES ('59', '0', 'Gryphon Hatcheling', '49662', '3', '4', '5', '5', '1', '1', 'This is a rare companion.', '0', '1');
INSERT INTO `shop` VALUES ('64', '0', 'Reins of the Black Proto-Drake', '44164', '4', '2', '4', '15', '1', '1', 'This is a 310% speed Proto-Drake mount.', '0', '1');
INSERT INTO `shop` VALUES ('65', '0', 'Reins of the Plagued Proto-Drake', '44175', '4', '2', '5', '15', '1', '1', 'This is a 310% speed Proto-Drake mount.', '0', '1');
INSERT INTO `shop` VALUES ('66', '0', 'Deadly Gladiator\'s Frostwyrm', '46708', '4', '2', '6', '15', '1', '1', 'This is a 310% speed Frostwyrm mount.', '0', '1');
INSERT INTO `shop` VALUES ('67', '0', 'Black Qiraji Resonating Crystal', '21176', '5', '2', '7', '10', '1', '1', 'This is a 100% speed Qiraji mount.', '0', '1');

-- ----------------------------
-- Table structure for `shoutbox`
-- ----------------------------
DROP TABLE IF EXISTS `shoutbox`;
CREATE TABLE `shoutbox` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user` varchar(25) NOT NULL DEFAULT 'anonimous',
  `message` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=591 DEFAULT CHARSET=latin1 COMMENT='Shoutbox Database';

-- ----------------------------
-- Records of shoutbox
-- ----------------------------
INSERT INTO `shoutbox` VALUES ('590', '2011-03-11 18:54:29', 'admin', 'This is your shoutbox. GM\'s will have a GM tag.', '127.0.0.1');

-- ----------------------------
-- Table structure for `vote_costs`
-- ----------------------------
DROP TABLE IF EXISTS `vote_costs`;
CREATE TABLE `vote_costs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_itemlevel` int(10) NOT NULL DEFAULT '0',
  `end_itemlevel` int(10) NOT NULL,
  `points` int(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Vote Shop Costs';

-- ----------------------------
-- Records of vote_costs
-- ----------------------------
INSERT INTO `vote_costs` VALUES ('2', '50', '100', '20');
INSERT INTO `vote_costs` VALUES ('1', '1', '50', '10');
INSERT INTO `vote_costs` VALUES ('3', '100', '120', '30');
INSERT INTO `vote_costs` VALUES ('4', '120', '150', '40');
INSERT INTO `vote_costs` VALUES ('5', '150', '180', '50');
INSERT INTO `vote_costs` VALUES ('6', '180', '190', '60');
INSERT INTO `vote_costs` VALUES ('7', '190', '199', '70');
INSERT INTO `vote_costs` VALUES ('8', '200', '210', '80');
INSERT INTO `vote_costs` VALUES ('9', '210', '230', '90');
INSERT INTO `vote_costs` VALUES ('10', '230', '240', '100');
INSERT INTO `vote_costs` VALUES ('11', '240', '250', '110');
INSERT INTO `vote_costs` VALUES ('12', '250', '260', '120');
INSERT INTO `vote_costs` VALUES ('13', '260', '270', '130');
INSERT INTO `vote_costs` VALUES ('14', '271', '280', '140');

-- ----------------------------
-- Table structure for `vote_data`
-- ----------------------------
DROP TABLE IF EXISTS `vote_data`;
CREATE TABLE `vote_data` (
  `id` bigint(21) NOT NULL AUTO_INCREMENT,
  `userid` bigint(21) NOT NULL,
  `siteid` bigint(21) NOT NULL,
  `timevoted` bigint(21) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5456 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Voting Data';

-- ----------------------------
-- Records of vote_data
-- ----------------------------

-- ----------------------------
-- Table structure for `vote_items`
-- ----------------------------
DROP TABLE IF EXISTS `vote_items`;
CREATE TABLE `vote_items` (
  `entry` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `Quality` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ItemLevel` smallint(5) unsigned NOT NULL DEFAULT '0',
  `show` enum('yes','no') NOT NULL DEFAULT 'yes',
  `realm` tinyint(4) NOT NULL,
  `custom` tinyint(4) NOT NULL,
  PRIMARY KEY (`entry`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='Vote Shop Items';

-- ----------------------------
-- Records of vote_items
-- ----------------------------
INSERT INTO `vote_items` VALUES ('49646', 'Core Hound Pup', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('41133', 'Unhatched Mr. Chilly', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('34492', 'Rocket Chicken', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('32588', 'Banana Charm', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('23712', 'Hippogryph Hatchling', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('22114', 'Pink Murloc Egg', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('20371', 'Blue Murloc Egg', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('39286', 'Frosty\'s Collar', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('38050', 'Soul-Trader Beacon', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('35226', 'X-51 Nether-Rocket X-TREME', '4', '240', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('38576', 'Big Battle Bear', '4', '180', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('49284', 'Swift Spectral Tiger', '4', '180', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('23720', 'Riding Turtle', '4', '180', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('46802', 'Heavy Murloc Egg', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('54069', 'Blazing Hippogryph', '4', '240', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('13582', 'Zergling Leash', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('56806', 'Mini Thor', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('13584', 'Diablo Stone', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('25535', 'Netherwhelp\'s Collar', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('13583', 'Panda Collar', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('35227', 'Goblin Weather Machine - Prototype 01-B', '4', '100', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('34499', 'Paper Flying Machine Kit', '3', '100', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('33223', 'Fishing Chair', '3', '100', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('43599', 'Big Blizzard Bear', '3', '180', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('30360', 'Lurky\'s Egg', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('45180', 'Murkimus\' Little Spear', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('44819', 'Baby Blizzard Bear', '0', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('49362', 'Onyxian Whelpling', '0', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('46767', 'Warbot Ignition Key', '3', '120', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('46765', 'Blue War Fuel', '3', '100', 'yes', '1', '0');
INSERT INTO `vote_items` VALUES ('46766', 'Red War Fuel', '3', '100', 'yes', '1', '0');
