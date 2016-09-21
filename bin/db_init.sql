SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
CREATE DATABASE IF NOT EXISTS `habitatswim` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `habitatswim`;

CREATE TABLE IF NOT EXISTS `sponsorships` (
  `sponsorship_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `swimmer_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `sponsor_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `sponsor_type` tinyint(3) unsigned NOT NULL COMMENT '0=per meter; 1=fixed',
  `sponsor_amount` mediumint(8) unsigned NOT NULL,
  `sponsor_total_amount` int(10) unsigned NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `invoice` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `amount_paid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sponsorship_id`),
  KEY `swimmer_id` (`swimmer_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=722 ;

CREATE TABLE IF NOT EXISTS `swimmers` (
  `swimmer_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `swimmer_last_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `swimmer_first_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `jis` tinyint(1) NOT NULL DEFAULT '1',
  `swimmer_email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `distance_swum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `swimmer_total_amount` int(11) NOT NULL,
  `email_sent` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`swimmer_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `sponsorships`
ADD CONSTRAINT `sponsorships_ibfk_1` FOREIGN KEY (`swimmer_id`) REFERENCES `swimmers` (`swimmer_id`);