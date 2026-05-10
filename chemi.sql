/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 8.4.3 : Database - raah_muktab
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`raah_muktab` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `raah_muktab`;

/*Table structure for table `author_book` */

DROP TABLE IF EXISTS `author_book`;

CREATE TABLE `author_book` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `author_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_books_author_id_foreign` (`author_id`),
  KEY `author_books_book_id_foreign` (`book_id`),
  CONSTRAINT `author_books_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `author_books_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `author_book` */

insert  into `author_book`(`id`,`author_id`,`book_id`,`created_at`,`updated_at`) values
(3,2,4,NULL,NULL),
(4,3,5,NULL,NULL),
(5,3,6,NULL,NULL),
(6,4,7,NULL,NULL),
(7,3,8,NULL,NULL),
(8,4,9,NULL,NULL),
(9,3,10,NULL,NULL),
(10,3,11,NULL,NULL),
(11,3,12,NULL,NULL),
(12,4,13,NULL,NULL),
(13,3,14,NULL,NULL),
(14,4,15,NULL,NULL),
(15,4,16,NULL,NULL),
(16,4,17,NULL,NULL),
(17,3,18,NULL,NULL);

/*Table structure for table `authors` */

DROP TABLE IF EXISTS `authors`;

CREATE TABLE `authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `authors` */

insert  into `authors`(`id`,`name`,`bio`,`created_at`,`updated_at`) values
(2,'Rachel Bryan','Eiusmod non cum aut','2025-11-13 07:35:06','2025-11-13 07:35:06'),
(3,'Marah Jenkins','Eveniet porro enim','2025-11-13 12:14:59','2025-11-13 12:14:59'),
(4,'Brady Erickson','Officia provident o','2025-11-13 12:15:09','2025-11-13 12:15:09'),
(5,'Kenyon Cherry','Adipisci qui nihil p','2025-11-13 12:15:16','2025-11-13 12:15:16'),
(6,'Bruno Holder','Cum incidunt et com','2025-11-13 12:15:26','2025-11-13 12:15:26'),
(7,'Brenna Burt','Et autem quia magnam','2025-11-13 12:15:33','2025-11-13 12:15:33'),
(8,'Steven Walters','Nihil minus numquam','2025-11-13 12:15:40','2025-11-13 12:15:40'),
(9,'Aimee Gamble','Quam facilis quidem','2025-11-13 12:15:53','2025-11-13 12:15:53'),
(10,'Nerea Wiggins','Facere perspiciatis','2025-11-13 12:16:01','2025-11-13 12:16:01'),
(11,'Risa Serrano','Natus sunt ratione i','2025-11-13 12:16:07','2025-11-13 12:16:07'),
(12,'Evan Castaneda','Eius temporibus omni','2025-11-13 12:16:30','2025-11-13 12:16:30'),
(13,'Karyn Watts','Quidem deserunt dolo','2025-11-13 12:17:15','2025-11-13 12:17:15'),
(14,'Catherine Little','Labore iste autem te','2025-11-13 12:17:28','2025-11-13 12:17:28'),
(15,'Cheyenne Vaughn','Aut itaque corrupti','2025-11-13 12:17:38','2025-11-13 12:17:38');

/*Table structure for table `books` */

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bar_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publisher_id` bigint unsigned DEFAULT NULL,
  `published_at` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cost_price` decimal(12,2) DEFAULT NULL,
  `sell_price` decimal(12,2) DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pages` int DEFAULT NULL,
  `language` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_sku_unique` (`sku`),
  KEY `books_publisher_id_foreign` (`publisher_id`),
  KEY `bar_code` (`bar_code`),
  KEY `idx_books_title` (`title`),
  CONSTRAINT `books_publisher_id_foreign` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `books` */

insert  into `books`(`id`,`sku`,`bar_code`,`title`,`subtitle`,`publisher_id`,`published_at`,`description`,`cost_price`,`sell_price`,`cover_image`,`pages`,`language`,`created_at`,`updated_at`) values
(4,'BK-1001','9781234567011','The Lost Kingdom','Chronicles of the Ancient Realm',1,'2025-10-14','An epic fantasy adventure exploring forgotten civilizations and hidden magic.',450.00,750.00,'book_covers/1763128345.jpg',384,'English','2025-11-14 13:52:25','2025-11-14 13:52:25'),
(5,'BK-1002','9781234567012','Mastering Laravel','From Beginner to Expert',3,'2025-10-28','A complete guide to Laravel framework with real-world examples and best practices.',150.00,900.00,'book_covers/1763128407.jpg',520,'English','2025-11-14 13:53:27','2025-12-01 12:36:43'),
(6,'BK-1003','9781234567013','Secrets of the Ocean','A Deep Dive into Marine Life',4,'2025-10-26','A detailed study on ocean species with stunning illustrations and facts.',300.00,650.00,'book_covers/1763128471.jpg',290,'English','2025-11-14 13:54:31','2025-11-14 13:54:31'),
(7,'BK-1004','9781234567014','The Art of Mindfulness','Living in the Present',5,'2025-11-09','A practical guide to mindfulness, meditation, and mental clarity.',280.00,500.00,'book_covers/1763128559.jpg',210,'English','2025-11-14 13:55:59','2025-11-14 13:55:59'),
(8,'BK-1005','9781234567015','Space Explorers','Journey Beyond the Stars',4,'2025-10-29','A futuristic sci-fi novel about humanity\'s first interstellar mission.',120.00,820.00,'book_covers/1763128608.jpg',412,'English','2025-11-14 13:56:48','2025-12-01 12:44:43'),
(9,'BK-1006','9781234567016','History of the Mughal Empire','Rise and Fall',4,'2025-11-12','A complete history of the Mughal dynasty with rare insights and research.',600.00,1000.00,'book_covers/1763128670.jpg',640,'English','2025-11-14 13:57:50','2025-11-14 13:57:50'),
(10,'BK-1007','9781234567017','Programming with Python','A Hands-On Guide',4,'2025-11-12','Learn Python programming through real examples and practical exercises.',150.00,750.00,'book_covers/1763128730.jpg',500,'English','2025-11-14 13:58:50','2025-12-01 12:46:00'),
(11,'BK-1008','9781234567018','The Silent Forest','A Mystery Novel',3,'2025-11-02','A suspenseful mystery thriller set deep inside an isolated forest.',350.00,680.00,'book_covers/1763128789.jpg',330,'English','2025-11-14 13:59:49','2025-11-14 13:59:49'),
(12,'BK-1009','9781234567019','Digital Marketing Essentials','Grow Your Brand Online',3,'2025-05-06','A complete handbook for modern digital marketing strategies',500.00,850.00,'book_covers/1763128845.jpg',475,'English','2025-11-14 14:00:45','2025-11-14 14:00:45'),
(13,'BK-1010','9781234567020','Healthy Living','Nutrition and Wellness',4,'2025-08-05','\"A scientific approach to healthy eating, lifestyle, and fitness',260.00,480.00,'book_covers/1763128902.jpg',240,'English','2025-11-14 14:01:42','2025-11-14 14:01:42'),
(14,'BK-1011','9781234567021','Adventures in Coding','A Kid\'s Guide',5,'2025-09-02','An engaging book to introduce children to coding through fun activities',300.00,550.00,'book_covers/1763128984.jpg',200,'English','2025-11-14 14:03:04','2025-11-14 14:03:04'),
(15,'BK-1012','9781234567022','Gardening Magic','Grow Anything Anywhere',4,'2025-11-04','A comprehensive guide to home gardening for beginners',180.00,350.00,'book_covers/1763129038.jpg',150,'English','2025-11-14 14:03:58','2025-11-14 14:03:58'),
(16,'BK-1013','9781234567023','AI Revolution','Future of Technology',5,'2025-10-07','How artificial intelligence is shaping the future of humanity',650.00,1200.00,'book_covers/1763129098.jpg',550,'English','2025-11-14 14:04:58','2025-11-14 14:04:58'),
(17,'BK-1014','9781234567024','الاحکام الشریعۃ فی الاحکام القدریۃ','Stories of Compassion',3,'2025-07-09','A collection of emotional and inspiring real-life stories',200.00,420.00,'book_covers/1763129152.jpg',260,'English','2025-11-14 14:05:52','2025-11-14 14:05:52'),
(18,'BK-1015','9781234567025','Husām-ul-Haramayn (حسام الحرمین)','HTML, CSS, JavaScript, PHP',4,'2025-09-02','A complete web development book for beginners and intermediates.',480.00,820.00,'book_covers/1763129206.jpg',590,'English','2025-11-14 14:06:46','2025-11-14 14:06:46');

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categories` */

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `customers` */

insert  into `customers`(`id`,`name`,`phone`,`address`,`created_at`,`updated_at`) values
(3,'Sigourney Kline','+1 (216) 145-8508','+1 (273) 427-1514','2025-12-01 14:20:45','2025-12-01 14:20:45'),
(4,'Bertha Nicholson','+1 (644) 375-5474','+1 (603) 374-5761','2025-12-01 14:20:54','2025-12-01 14:20:54');

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `inventories` */

DROP TABLE IF EXISTS `inventories`;

CREATE TABLE `inventories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `location` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reorder_level` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventories_book_id_foreign` (`book_id`),
  CONSTRAINT `inventories_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `inventories` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `price_histories` */

DROP TABLE IF EXISTS `price_histories`;

CREATE TABLE `price_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint unsigned NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `new_price` decimal(12,2) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `changed_by` bigint unsigned DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_histories_changed_by_foreign` (`changed_by`),
  KEY `idx_price_hist_book` (`book_id`),
  CONSTRAINT `price_histories_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `price_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `price_histories` */

/*Table structure for table `publishers` */

DROP TABLE IF EXISTS `publishers`;

CREATE TABLE `publishers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `publishers` */

insert  into `publishers`(`id`,`name`,`contact`,`address`,`created_at`,`updated_at`) values
(1,'Cullen Monroe','Laborum Explicabo','Ut repellendus Id','2025-11-13 12:55:42','2025-11-13 12:55:42'),
(3,'Gisela Whitehead','Sunt qui iste iure l','Non perferendis et r','2025-11-13 13:04:09','2025-11-13 13:04:09'),
(4,'Clarke Knox','Aut odit nemo proide','Id placeat consequ','2025-11-13 13:04:16','2025-11-13 13:04:16'),
(5,'Holmes Hunter','Dolor dolore quis in','Quo voluptas debitis','2025-11-13 13:04:22','2025-11-13 13:04:22'),
(6,'Linus Shields','Ea eum minus aliquip','Tenetur natus conseq','2025-11-13 13:04:28','2025-11-13 13:04:28'),
(7,'Dennis Mckee','A libero dolor neque','Dolor nesciunt cum','2025-11-13 13:04:34','2025-11-13 13:04:34'),
(8,'Murphy Rose','Explicabo Atque eiu','Eum est consequatur','2025-11-13 13:04:39','2025-11-13 13:04:39'),
(9,'Jaquelyn Romero','Voluptas iusto minus','Molestias dicta expe','2025-11-13 13:04:45','2025-11-13 13:04:45'),
(10,'Debra Palmer','Omnis mollitia exped','Omnis laborum iste c','2025-11-13 13:04:50','2025-11-13 13:04:50'),
(11,'Colorado Briggs','Laudantium laborum','Iure architecto atqu','2025-11-13 13:04:56','2025-11-13 13:04:56'),
(12,'Jael Simmons','Ullam in est lorem c','Id consequat Recus','2025-11-13 13:05:02','2025-11-13 13:05:02'),
(13,'Ivor Faulkner','Quo ipsum nihil reru','Velit est odio velit','2025-11-13 13:05:07','2025-11-13 13:05:07'),
(14,'Germaine Mann','Ipsa anim consequat','Id dolorem doloribu','2025-11-13 13:05:13','2025-11-13 13:05:13'),
(15,'Blake Soto','Sapiente vero ut eiu','Proident est molest','2025-11-13 13:05:18','2025-11-13 13:05:18'),
(16,'Olympia Guthrie','Dolorem mollit nulla','Qui id eum reprehen','2025-11-13 13:05:24','2025-11-13 13:05:24'),
(17,'Althea Diaz','Necessitatibus ut ad','Iusto minima archite','2025-11-13 13:05:29','2025-11-13 13:05:29'),
(18,'Serina Good','Labore temporibus an','Cum omnis voluptas p','2025-11-13 13:05:35','2025-11-13 13:05:35'),
(19,'Gareth Maynard','Corporis in necessit','Ullamco magnam in te','2025-11-13 13:05:41','2025-11-13 13:05:41'),
(20,'Eve Mathews','Sed eius et distinct','Id qui reprehenderit','2025-11-13 13:05:48','2025-11-13 13:05:48'),
(21,'Dennis Bullock','Nostrud veniam pari','Anim porro officia q','2025-11-13 13:05:53','2025-11-13 13:05:53');

/*Table structure for table `purchase_items` */

DROP TABLE IF EXISTS `purchase_items`;

CREATE TABLE `purchase_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_cost` decimal(12,2) NOT NULL,
  `line_total` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_book_id_foreign` (`book_id`),
  KEY `idx_purchase_items_purchase` (`purchase_id`),
  CONSTRAINT `purchase_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `purchase_items` */

/*Table structure for table `purchases` */

DROP TABLE IF EXISTS `purchases`;

CREATE TABLE `purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `invoice_no` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_invoice_no_unique` (`invoice_no`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  KEY `purchases_created_by_foreign` (`created_by`),
  CONSTRAINT `purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `purchases` */

/*Table structure for table `sale_items` */

DROP TABLE IF EXISTS `sale_items`;

CREATE TABLE `sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `sale_price` decimal(14,2) NOT NULL DEFAULT (0),
  `line_total` decimal(14,2) NOT NULL,
  `discount` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_book_id_foreign` (`book_id`),
  CONSTRAINT `sale_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sale_items` */

/*Table structure for table `sales` */

DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `invoice_no` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_date` datetime NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('open','paid','refunded','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sales` */

/*Table structure for table `suppliers` */

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `suppliers` */

insert  into `suppliers`(`id`,`name`,`contact_person`,`phone`,`email`,`address`,`created_at`,`updated_at`) values
(1,'Ella Parsons','Libero deserunt beat','+1 (681) 557-2603','nurudi@mailinator.com','Doloremque voluptas','2025-11-13 13:35:34','2025-11-13 13:35:34'),
(2,'Chastity Kerr','Dolorem officia et f','+1 (137) 468-9271','qomubud@mailinator.com','Maiores sunt sed eo','2025-11-13 13:35:42','2025-11-13 13:35:42'),
(3,'Adara Rush','Veniam odit dolores','+1 (516) 812-7197','ponagin@mailinator.com','Alias ullamco autem','2025-11-13 13:35:48','2025-11-13 13:35:48'),
(4,'Ivory Clayton','Et doloribus minima','+1 (913) 726-1048','vevemu@mailinator.com','Laudantium dolor re','2025-11-13 13:35:54','2025-11-13 13:35:54'),
(5,'Stewart Jacobson','Rerum rerum commodo','+1 (173) 767-9846','qabusuli@mailinator.com','Quam quia distinctio','2025-11-13 13:35:58','2025-11-13 13:35:58'),
(6,'Jennifer Villarreal','Sed nihil sed quia d','+1 (819) 823-6785','zerabadyn@mailinator.com','Et natus nisi cupidi','2025-11-13 13:36:03','2025-11-13 13:36:03'),
(7,'Pamela Hughes','Culpa deserunt moles','+1 (615) 977-7008','celu@mailinator.com','Irure qui quis nulla','2025-11-13 13:36:08','2025-11-13 13:36:08'),
(8,'Stephanie Fuller','Veritatis tenetur ma','+1 (674) 844-3768','ragy@mailinator.com','Repellendus Dolorum','2025-11-13 13:36:12','2025-11-13 13:36:12'),
(9,'Sylvia Ochoa','Eos tempore rerum','+1 (782) 271-6523','tuhy@mailinator.com','Fuga Consequatur N','2025-11-13 13:36:17','2025-11-13 13:36:17'),
(10,'Vance Guzman','Distinctio Ex praes','+1 (634) 647-9874','zivohyby@mailinator.com','Nulla ut ad eius qui','2025-11-13 13:36:22','2025-11-13 13:36:22'),
(11,'Tatiana May','Dolor aute qui occae','+1 (476) 657-7529','fyseled@mailinator.com','Accusamus molestias','2025-11-13 13:36:30','2025-11-13 13:36:30'),
(12,'Quinn Barry','Quibusdam rerum cons','+1 (751) 494-4616','visyqyqus@mailinator.com','Et impedit corrupti','2025-11-13 13:36:34','2025-11-13 13:36:34'),
(13,'Edan Waters','Consequatur dolore t','+1 (397) 561-7412','cehijalyma@mailinator.com','Consequuntur adipisi','2025-11-13 13:36:39','2025-11-13 13:36:39'),
(14,'Knox Bruce','Pariatur Et archite','+1 (935) 705-6292','bimyb@mailinator.com','Dolores tenetur est','2025-11-13 13:36:44','2025-11-13 13:36:44'),
(15,'Courtney Andrews','Consequatur id eius','+1 (665) 886-7194','vovusodo@mailinator.com','Accusantium quidem a','2025-11-13 13:36:48','2025-11-13 13:36:48'),
(16,'Dale Burt','Quia voluptas amet','+1 (419) 979-3896','hotef@mailinator.com','Ullamco est laboris','2025-11-13 13:36:54','2025-11-13 13:36:54'),
(17,'Amena Lindsey','Non et ut recusandae','+1 (123) 103-1977','cybyxemuqy@mailinator.com','Natus cupiditate aut','2025-11-13 13:36:58','2025-11-13 13:36:58'),
(18,'Jayme Chavez','Sit aperiam magnam','+1 (867) 738-2264','pajukeg@mailinator.com','Omnis non ea at nihi','2025-11-13 13:37:03','2025-11-13 13:37:03'),
(19,'Nayda Galloway test','Qui aut earum sapien test','+1 (181) 725-454','wesetestqozuga@mailinator.com','Sint voluptas iure  test','2025-11-13 13:37:08','2025-11-13 13:42:25');

/*Table structure for table `user_cart` */

DROP TABLE IF EXISTS `user_cart`;

CREATE TABLE `user_cart` (
  `user_id` bigint DEFAULT NULL,
  `book_id` bigint DEFAULT NULL,
  `quantity` bigint DEFAULT NULL,
  `discount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `user_cart` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values
(1,'Aubrey Padberg','otha.leuschke@example.com','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','4JNvrGApS1','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(2,'Jeffry Witting','halvorson.robb@example.net','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','l4p4KTa7mBnHoONDrANHQgKQZfH8cGyzsf8IrV6ILI75P9wkDPbaP8Eu60uU','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(3,'Jaydon Ullrich','lfeil@example.net','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','sefxKAFH9E','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(4,'Junior Hegmann','skonopelski@example.com','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','I59DAk9hvQ','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(5,'Willy Hermiston','obeier@example.net','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','C2ZwwMbcJP','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(6,'Sydnie Corkery','alexandrea.robel@example.com','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','jfPInpTuli','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(7,'Ms. Fiona Heidenreich','fkirlin@example.org','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','X4kxYxmISl','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(8,'Araceli DuBuque','lebsack.sylvan@example.net','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','AU8RnPGlTR','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(9,'Miss Twila Brakus I','tracey.goldner@example.org','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','gTj3iN7csg','2025-11-11 10:20:34','2025-11-11 10:20:34'),
(10,'Ms. Dulce Little','abernathy.cindy@example.org','2025-11-11 10:20:34','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','D7ZJSYIVZk','2025-11-11 10:20:34','2025-11-11 10:20:34');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
