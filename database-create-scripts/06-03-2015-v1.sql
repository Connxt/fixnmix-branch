-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.5.27 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for fixnmix_branch
CREATE DATABASE IF NOT EXISTS `fixnmix_branch` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `fixnmix_branch`;


-- Dumping structure for table fixnmix_branch.delivered_items
CREATE TABLE IF NOT EXISTS `delivered_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `delivered_items_delivery_id_to_deliveries_id` (`delivery_id`),
  KEY `delivered_items_item_id_to_items_id` (`item_id`),
  CONSTRAINT `delivered_items_delivery_id_to_deliveries_id` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `delivered_items_item_id_to_items_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.delivered_items: ~3 rows (approximately)
/*!40000 ALTER TABLE `delivered_items` DISABLE KEYS */;
INSERT INTO `delivered_items` (`id`, `delivery_id`, `item_id`, `quantity`) VALUES
	(16, 10, 1, 12),
	(17, 10, 2, 13),
	(18, 10, 3, 14),
	(19, 11, 1, 12),
	(20, 11, 2, 13),
	(21, 11, 3, 14);
/*!40000 ALTER TABLE `delivered_items` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.deliveries
CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_id_from_main` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.deliveries: ~1 rows (approximately)
/*!40000 ALTER TABLE `deliveries` DISABLE KEYS */;
INSERT INTO `deliveries` (`id`, `delivery_id_from_main`, `created_at`, `updated_at`) VALUES
	(10, 3, '2015-06-02 21:10:36', '2015-06-02 21:10:36'),
	(11, 4, '2015-06-02 22:49:11', '2015-06-02 22:49:11');
/*!40000 ALTER TABLE `deliveries` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.items
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.items: ~3 rows (approximately)
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` (`id`, `description`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
	(1, 'ssdasd', '31', 1200, '2015-06-02 16:12:23', '2015-06-02 22:49:11'),
	(2, 'dddddasd', '38', 1030, '2015-06-02 16:12:23', '2015-06-02 22:49:11'),
	(3, 'aaaasd', '43', 1004, '2015-06-02 16:12:23', '2015-06-02 22:49:11');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.receipts
CREATE TABLE IF NOT EXISTS `receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sales_report_id` int(11) DEFAULT NULL,
  `is_reported` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receipts_user_id_to_users_id` (`user_id`),
  KEY `receipts_sales_report_id_to_sales_reports_id` (`sales_report_id`),
  CONSTRAINT `receipts_sales_report_id_to_sales_reports_id` FOREIGN KEY (`sales_report_id`) REFERENCES `sales_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `receipts_user_id_to_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.receipts: ~10 rows (approximately)
/*!40000 ALTER TABLE `receipts` DISABLE KEYS */;
INSERT INTO `receipts` (`id`, `user_id`, `sales_report_id`, `is_reported`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(2, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(3, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(4, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(5, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(6, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(7, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(8, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(9, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(10, 1, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(11, 1, NULL, 0, '2015-06-02 22:22:17', '2015-06-02 22:22:17');
/*!40000 ALTER TABLE `receipts` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.receipt_items
CREATE TABLE IF NOT EXISTS `receipt_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sold_items_item_id_to_items_id` (`item_id`),
  KEY `sold_items_receipt_id_to_receipts_id` (`receipt_id`),
  CONSTRAINT `sold_items_item_id_to_items_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sold_items_receipt_id_to_receipts_id` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.receipt_items: ~15 rows (approximately)
/*!40000 ALTER TABLE `receipt_items` DISABLE KEYS */;
INSERT INTO `receipt_items` (`id`, `item_id`, `receipt_id`, `price`, `quantity`) VALUES
	(2, 1, 1, 1200, 1),
	(4, 1, 2, 1200, 1),
	(6, 1, 3, 1200, 1),
	(8, 1, 4, 1200, 1),
	(10, 1, 6, 1200, 1),
	(11, 1, 7, 1200, 1),
	(12, 1, 8, 1200, 2),
	(13, 1, 9, 1200, 1),
	(14, 1, 10, 1200, 1),
	(15, 2, 10, 1030, 2),
	(16, 1, 11, 1200, 1);
/*!40000 ALTER TABLE `receipt_items` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.returned_items
CREATE TABLE IF NOT EXISTS `returned_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `return_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `returned_items_return_id_to_returns_id` (`return_id`),
  KEY `returned_Items_item_id_to_items_id` (`item_id`),
  CONSTRAINT `returned_Items_item_id_to_items_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `returned_items_return_id_to_returns_id` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.returned_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `returned_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `returned_items` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.returns
CREATE TABLE IF NOT EXISTS `returns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.returns: ~0 rows (approximately)
/*!40000 ALTER TABLE `returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `returns` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.sales_reports
CREATE TABLE IF NOT EXISTS `sales_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.sales_reports: ~0 rows (approximately)
/*!40000 ALTER TABLE `sales_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_reports` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `app_id` varchar(50) DEFAULT NULL,
  `main_id` varchar(50) DEFAULT NULL,
  `default_save_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.settings: ~1 rows (approximately)
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`app_id`, `main_id`, `default_save_path`) VALUES
	('KAB001', 'MAIN001', ' e:\\');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(120) DEFAULT NULL,
  `user_level_id` int(11) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_user_level_id_to_user_levels_id` (`user_level_id`),
  CONSTRAINT `users_user_level_id_to_user_levels_id` FOREIGN KEY (`user_level_id`) REFERENCES `user_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `user_level_id`, `last_name`, `first_name`, `middle_name`, `created_at`, `updated_at`) VALUES
	(1, 'asd', 'asd', 1, 'Felipe', 'Jan Ryan', 'Malicay', '2015-05-04 21:39:28', '2015-05-04 21:39:30');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table fixnmix_branch.user_levels
CREATE TABLE IF NOT EXISTS `user_levels` (
  `id` int(11) NOT NULL,
  `user_level` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table fixnmix_branch.user_levels: ~2 rows (approximately)
/*!40000 ALTER TABLE `user_levels` DISABLE KEYS */;
INSERT INTO `user_levels` (`id`, `user_level`) VALUES
	(1, 'Administrator'),
	(2, 'Cashier');
/*!40000 ALTER TABLE `user_levels` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
