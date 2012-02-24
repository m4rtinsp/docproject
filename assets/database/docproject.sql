# --------------------------------------------------------
# Host:                         127.0.0.1
# Server version:               5.5.8
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-07-19 15:44:10
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping database structure for docproject
DROP DATABASE IF EXISTS `docproject`;
CREATE DATABASE IF NOT EXISTS `docproject` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `docproject`;


# Dumping structure for table docproject.dp_config
DROP TABLE IF EXISTS `dp_config`;
CREATE TABLE IF NOT EXISTS `dp_config` (
  `app_name` varchar(125) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `theme` varchar(125) NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table docproject.dp_config: ~1 rows (approximately)
/*!40000 ALTER TABLE `dp_config` DISABLE KEYS */;
INSERT INTO `dp_config` (`app_name`, `image`, `theme`) VALUES
	('Doc Project', 'logo.png', 'default');
/*!40000 ALTER TABLE `dp_config` ENABLE KEYS */;


# Dumping structure for table docproject.dp_language
DROP TABLE IF EXISTS `dp_language`;
CREATE TABLE IF NOT EXISTS `dp_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(125) NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT '0',
  `code` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

# Dumping data for table docproject.dp_language: ~2 rows (approximately)
/*!40000 ALTER TABLE `dp_language` DISABLE KEYS */;
INSERT INTO `dp_language` (`id`, `description`, `default`, `code`) VALUES
	(1, 'Português', 1, 'pt-br'),
	(2, 'English', 0, 'en');
/*!40000 ALTER TABLE `dp_language` ENABLE KEYS */;


# Dumping structure for table docproject.dp_link
DROP TABLE IF EXISTS `dp_link`;
CREATE TABLE IF NOT EXISTS `dp_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

# Dumping data for table docproject.dp_link: ~2 rows (approximately)
/*!40000 ALTER TABLE `dp_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `dp_link` ENABLE KEYS */;


# Dumping structure for table docproject.dp_topic
DROP TABLE IF EXISTS `dp_topic`;
CREATE TABLE IF NOT EXISTS `dp_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text,
  `dp_language_id` int(11) NOT NULL,
  `dp_version_id` int(11) NOT NULL,
  `dp_topic_id` int(11) DEFAULT NULL,
  `principal` tinyint(4) NOT NULL DEFAULT '0',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `position` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_dp_topic_dp_language` (`dp_language_id`),
  KEY `fk_dp_topic_dp_version1` (`dp_version_id`),
  KEY `fk_dp_topic_dp_topic1` (`dp_topic_id`),
  CONSTRAINT `fk_dp_topic_dp_language` FOREIGN KEY (`dp_language_id`) REFERENCES `dp_language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dp_topic_dp_topic1` FOREIGN KEY (`dp_topic_id`) REFERENCES `dp_topic` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dp_topic_dp_version1` FOREIGN KEY (`dp_version_id`) REFERENCES `dp_version` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table docproject.dp_topic: ~0 rows (approximately)
/*!40000 ALTER TABLE `dp_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `dp_topic` ENABLE KEYS */;


# Dumping structure for table docproject.dp_user
DROP TABLE IF EXISTS `dp_user`;
CREATE TABLE IF NOT EXISTS `dp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(125) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

# Dumping data for table docproject.dp_user: ~1 rows (approximately)
/*!40000 ALTER TABLE `dp_user` DISABLE KEYS */;
INSERT INTO `dp_user` (`id`, `username`, `password`, `email`) VALUES
	(5, 'administrator', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.com');
/*!40000 ALTER TABLE `dp_user` ENABLE KEYS */;


# Dumping structure for table docproject.dp_version
DROP TABLE IF EXISTS `dp_version`;
CREATE TABLE IF NOT EXISTS `dp_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

# Dumping data for table docproject.dp_version: ~1 rows (approximately)
/*!40000 ALTER TABLE `dp_version` DISABLE KEYS */;
INSERT INTO `dp_version` (`id`, `description`, `default`) VALUES
	(1, '1.0', 1);
/*!40000 ALTER TABLE `dp_version` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
