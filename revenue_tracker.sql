-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for revenue_tracker
DROP DATABASE IF EXISTS `revenue_tracker`;
CREATE DATABASE IF NOT EXISTS `revenue_tracker` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `revenue_tracker`;

-- Dumping structure for table revenue_tracker.access
DROP TABLE IF EXISTS `access`;
CREATE TABLE IF NOT EXISTS `access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `products_page` tinyint(1) NOT NULL,
  `calculator_page` tinyint(1) NOT NULL,
  `reports_page` tinyint(1) NOT NULL,
  `fees_page` tinyint(1) NOT NULL,
  `settings_page` tinyint(1) NOT NULL,
  `index_page` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.access: ~5 rows (approximately)
/*!40000 ALTER TABLE `access` DISABLE KEYS */;
REPLACE INTO `access` (`id`, `user_id`, `products_page`, `calculator_page`, `reports_page`, `fees_page`, `settings_page`, `index_page`, `created_at`) VALUES
	(4, 3, 1, 1, 1, 1, 1, 1, '2020-05-03 09:07:56'),
	(7, 13, 1, 0, 0, 0, 0, 0, '2020-05-04 05:36:14'),
	(8, 14, 0, 1, 0, 0, 0, 0, '2020-05-04 05:37:58'),
	(10, 16, 0, 0, 1, 0, 0, 0, '2020-05-05 02:56:37'),
	(11, 17, 0, 0, 0, 0, 0, 1, '2020-05-20 06:27:50');
/*!40000 ALTER TABLE `access` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.calculator
DROP TABLE IF EXISTS `calculator`;
CREATE TABLE IF NOT EXISTS `calculator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `all_orders` int(11) NOT NULL,
  `total_products` int(11) NOT NULL,
  `total_delivered` int(11) NOT NULL,
  `Ads` varchar(255) NOT NULL,
  `sale_price` float NOT NULL,
  `confirmation_fee` float NOT NULL,
  `delivery_fee` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.calculator: ~43 rows (approximately)
/*!40000 ALTER TABLE `calculator` DISABLE KEYS */;
REPLACE INTO `calculator` (`id`, `date_id`, `product_id`, `all_orders`, `total_products`, `total_delivered`, `Ads`, `sale_price`, `confirmation_fee`, `delivery_fee`) VALUES
	(21, 8, 3, 11, 11, 6, '470', 700, 11, 58),
	(23, 8, 4, 1, 1, 1, '34', 299, 11, 58),
	(27, 12, 3, 13, 10, 6, '382', 700, 10, 58),
	(28, 13, 3, 2, 0, 0, '5', 700, 10, 58),
	(29, 14, 4, 2, 2, 2, '0', 299, 10, 58),
	(31, 14, 3, 8, 6, 4, '58', 700, 10, 58),
	(32, 14, 5, 4, 3, 2, '22', 479, 10, 58),
	(33, 15, 3, 2, 1, 1, '97', 700, 10, 58),
	(35, 17, 5, 9, 6, 3, '50', 479, 10, 58),
	(36, 17, 3, 8, 5, 4, '50', 700, 10, 58),
	(37, 18, 3, 3, 2, 2, '108', 700, 10, 58),
	(38, 18, 5, 3, 3, 2, '49', 479, 10, 58),
	(39, 19, 3, 8, 8, 7, '108', 700, 10, 58),
	(40, 19, 5, 3, 2, 1, '49', 479, 10, 58),
	(41, 20, 3, 5, 1, 0, '61', 700, 10, 58),
	(42, 20, 5, 1, 0, 0, '12', 479, 10, 58),
	(43, 21, 3, 2, 1, 1, '57', 700, 10, 58),
	(44, 22, 3, 10, 8, 5, '100', 700, 10, 58),
	(45, 22, 5, 5, 4, 3, '29', 479, 10, 58),
	(46, 23, 3, 9, 6, 3, '213', 700, 10, 58),
	(47, 23, 5, 4, 3, 2, '100', 479, 10, 58),
	(48, 24, 3, 26, 19, 11, '371', 700, 10, 58),
	(49, 25, 3, 6, 5, 3, '368', 700, 10, 58),
	(50, 26, 3, 13, 12, 7, '445', 700, 10, 58),
	(51, 27, 3, 11, 8, 5, '540', 700, 10, 58),
	(52, 27, 7, 2, 2, 0, '58', 650, 10, 58),
	(54, 27, 9, 6, 3, 1, '58', 249, 10, 58),
	(55, 27, 4, 5, 4, 3, '58', 299, 10, 58),
	(56, 27, 6, 2, 2, 2, '0', 349, 10, 58),
	(57, 28, 3, 15, 10, 8, '400', 700, 10, 58),
	(58, 28, 9, 6, 6, 6, '50', 249, 10, 58),
	(59, 28, 4, 4, 3, 1, '49', 299, 10, 58),
	(60, 28, 7, 3, 2, 1, '48', 650, 10, 58),
	(61, 28, 10, 5, 3, 3, '50', 199, 10, 58),
	(62, 29, 3, 1, 1, 1, '0', 700, 10, 58),
	(63, 30, 3, 12, 12, 8, '290', 700, 10, 58),
	(64, 31, 3, 14, 9, 6, '420', 700, 10, 58),
	(65, 32, 3, 5, 3, 1, '300', 700, 10, 58),
	(66, 33, 3, 44, 31, 16, '1020', 700, 10, 58),
	(67, 34, 3, 37, 28, 15, '1013', 700, 10, 58),
	(68, 35, 3, 1, 1, 1, '0', 700, 10, 58),
	(70, 37, 3, 13, 5, 4, '472', 700, 10, 58),
	(71, 38, 3, 25, 17, 7, '869', 700, 10, 58);
/*!40000 ALTER TABLE `calculator` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.dates
DROP TABLE IF EXISTS `dates`;
CREATE TABLE IF NOT EXISTS `dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(11) NOT NULL,
  `total_products` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.dates: ~26 rows (approximately)
/*!40000 ALTER TABLE `dates` DISABLE KEYS */;
REPLACE INTO `dates` (`id`, `date`, `total_products`, `created_at`) VALUES
	(8, '2020-04-05', 0, '2020-05-21 18:34:05'),
	(12, '2020-04-08', 0, '2020-05-22 09:58:15'),
	(13, '2020-04-09', 0, '2020-05-22 10:00:58'),
	(14, '2020-04-11', 0, '2020-05-22 15:37:45'),
	(15, '2020-04-12', 0, '2020-05-22 15:43:12'),
	(17, '2020-04-13', 0, '2020-05-22 15:47:33'),
	(18, '2020-04-14', 0, '2020-05-22 15:53:42'),
	(19, '2020-04-15', 0, '2020-05-22 15:58:18'),
	(20, '2020-04-16', 0, '2020-05-22 16:17:55'),
	(21, '2020-04-18', 0, '2020-05-22 16:29:48'),
	(22, '2020-04-19', 0, '2020-05-22 17:40:50'),
	(23, '2020-04-21', 0, '2020-05-22 17:44:52'),
	(24, '2020-04-22', 0, '2020-05-22 17:56:23'),
	(25, '2020-04-23', 0, '2020-05-22 18:00:59'),
	(26, '2020-04-24', 0, '2020-05-22 18:05:50'),
	(27, '2020-04-25', 0, '2020-05-22 18:24:54'),
	(28, '2020-04-26', 0, '2020-05-24 06:46:49'),
	(29, '2020-04-29', 0, '2020-05-24 07:27:00'),
	(30, '2020-05-01', 0, '2020-05-24 07:32:28'),
	(31, '2020-05-02', 0, '2020-05-24 07:39:29'),
	(32, '2020-04-06', 0, '2020-05-30 12:30:49'),
	(33, '2020-05-03', 0, '2020-06-08 13:32:46'),
	(34, '2020-05-04', 0, '2020-06-08 14:00:45'),
	(35, '2020-05-05', 0, '2020-06-08 14:01:33'),
	(37, '2020-05-07', 0, '2020-06-08 14:05:11'),
	(38, '2020-05-08', 0, '2020-06-08 14:19:50');
/*!40000 ALTER TABLE `dates` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.date_category
DROP TABLE IF EXISTS `date_category`;
CREATE TABLE IF NOT EXISTS `date_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.date_category: ~2 rows (approximately)
/*!40000 ALTER TABLE `date_category` DISABLE KEYS */;
REPLACE INTO `date_category` (`id`, `category`, `slug`, `created_at`) VALUES
	(1, 'All', 'all', '2020-05-03 13:52:51'),
	(2, 'Specific', 'specific', '2020-05-03 13:52:51');
/*!40000 ALTER TABLE `date_category` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.fees
DROP TABLE IF EXISTS `fees`;
CREATE TABLE IF NOT EXISTS `fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_name` varchar(80) NOT NULL,
  `fee_price` int(11) DEFAULT NULL,
  `fee_type_id` varchar(80) NOT NULL,
  `product_type` varchar(80) NOT NULL,
  `date_type` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.fees: ~2 rows (approximately)
/*!40000 ALTER TABLE `fees` DISABLE KEYS */;
REPLACE INTO `fees` (`id`, `fee_name`, `fee_price`, `fee_type_id`, `product_type`, `date_type`, `slug`) VALUES
	(1, 'Confirmation Fee', 10, '2', '1', '1', 'confirmation_fee'),
	(2, 'Delivery Fee', 58, '1', '2', '2', 'delivery_fee');
/*!40000 ALTER TABLE `fees` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.fee_category
DROP TABLE IF EXISTS `fee_category`;
CREATE TABLE IF NOT EXISTS `fee_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.fee_category: ~3 rows (approximately)
/*!40000 ALTER TABLE `fee_category` DISABLE KEYS */;
REPLACE INTO `fee_category` (`id`, `category`, `created_at`, `slug`) VALUES
	(1, 'Regular', '2020-05-03 13:47:19', 'regular'),
	(2, 'Monthly', '2020-05-03 13:47:19', 'monthly'),
	(3, 'Exceptional', '2020-05-03 13:47:19', 'exceptional');
/*!40000 ALTER TABLE `fee_category` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.products
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `fournisseur` varchar(255) DEFAULT NULL,
  `all_orders` int(11) NOT NULL,
  `total_products` int(11) NOT NULL,
  `total_delivered` int(11) NOT NULL,
  `Ads` int(11) NOT NULL,
  `sale_price` float NOT NULL,
  `cost_price` float NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.products: ~8 rows (approximately)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
REPLACE INTO `products` (`id`, `product_name`, `fournisseur`, `all_orders`, `total_products`, `total_delivered`, `Ads`, `sale_price`, `cost_price`, `created_at`) VALUES
	(3, 'Memory Comfort Pillow', 'AMON 23', 25, 17, 7, 869, 700, 180, '0000-00-00'),
	(4, 'SlapChop', 'Ali', 4, 3, 1, 49, 299, 35, '0000-00-00'),
	(5, 'Fer Ã  repasser', 'As seen On Tv Shop', 4, 3, 2, 100, 479, 170, '0000-00-00'),
	(6, 'Pure Posture', 'Ali', 2, 2, 2, 0, 349, 95, '0000-00-00'),
	(7, 'Machine Ã  Pop-Corn Sans Huile', 'As Seen On Tv Shop', 3, 2, 1, 48, 650, 120, '0000-00-00'),
	(9, 'Pack De 6 Couvercle Pour Conserver Les Aliments', 'Ali', 6, 6, 6, 50, 249, 35, '0000-00-00'),
	(10, '8 PCS Moule a gÃ¢teau en Silicone', 'Ali', 5, 3, 3, 50, 199, 45, '0000-00-00'),
	(11, 'Appareil De Massage De Nuque', 'Ali', 0, 0, 0, 0, 499, 200, '0000-00-00');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.product_type
DROP TABLE IF EXISTS `product_type`;
CREATE TABLE IF NOT EXISTS `product_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.product_type: ~2 rows (approximately)
/*!40000 ALTER TABLE `product_type` DISABLE KEYS */;
REPLACE INTO `product_type` (`id`, `type`, `slug`, `created_at`) VALUES
	(1, 'Total Delivered', 'total_delivered', '2020-05-03 14:00:57'),
	(2, 'Total Confirmed', 'total_confirmed', '2020-05-03 14:00:57');
/*!40000 ALTER TABLE `product_type` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.tmp_calc
DROP TABLE IF EXISTS `tmp_calc`;
CREATE TABLE IF NOT EXISTS `tmp_calc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `orders` int(11) NOT NULL,
  `confirmed` int(11) NOT NULL,
  `delivered` int(11) NOT NULL,
  `ads` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table revenue_tracker.tmp_calc: ~0 rows (approximately)
/*!40000 ALTER TABLE `tmp_calc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp_calc` ENABLE KEYS */;

-- Dumping structure for table revenue_tracker.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `users_id_idx` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COMMENT='This table stores users credentials.';

-- Dumping data for table revenue_tracker.users: ~4 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `fullname`, `email`, `username`, `password`, `role`) VALUES
	(3, 'admin', 'admin@mail.com', 'admin', 'admin', 1),
	(13, 'Product Manager', 'user01@space.com', 'user01', 'user01', 0),
	(14, 'Calculator Manager', 'user02@space.com', 'user02', 'user02', 0),
	(16, 'Report Manager', 'user03@space.com', 'user03', 'user03', 0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
