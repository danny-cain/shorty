-- MySQL dump 10.13  Distrib 5.5.24, for Win64 (x86)
--
-- Host: localhost    Database: shorty
-- ------------------------------------------------------
-- Server version	5.5.24-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `basketitems`
--

DROP TABLE IF EXISTS `basketitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basketitems` (
  `id` int(11) NOT NULL DEFAULT '0',
  `basket` int(11) DEFAULT NULL,
  `product` varchar(250) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `tax` decimal(6,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basketitems`
--

LOCK TABLES `basketitems` WRITE;
/*!40000 ALTER TABLE `basketitems` DISABLE KEYS */;
/*!40000 ALTER TABLE `basketitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `baskets`
--

DROP TABLE IF EXISTS `baskets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `baskets` (
  `id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `discountCode` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `baskets`
--

LOCK TABLES `baskets` WRITE;
/*!40000 ALTER TABLE `baskets` DISABLE KEYS */;
INSERT INTO `baskets` VALUES (1,'2013-07-03 16:19:00','');
/*!40000 ALTER TABLE `baskets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogarticles`
--

DROP TABLE IF EXISTS `blogarticles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blogarticles` (
  `id` int(11) NOT NULL DEFAULT '0',
  `blog` int(11) DEFAULT NULL,
  `uri` varchar(250) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `posted` datetime DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `content` tinytext,
  `tags` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogarticles`
--

LOCK TABLES `blogarticles` WRITE;
/*!40000 ALTER TABLE `blogarticles` DISABLE KEYS */;
INSERT INTO `blogarticles` VALUES (1,1,'welcome',1,'2013-06-25 12:00:00','Welcome to the Shorty Blog','<p>This is the first blog post</p>','[\"welcome\",\"blog\",\"shorty\"]'),(2,1,'progress-is-made',1,'2013-06-26 12:00:00','Progress Happens','<p>Progress is being made on the Shorty Blog and we will hopefully have a release available soon!</p>','[\"progress\",\"shorty\"]'),(3,1,'yay-for-posts',0,'2013-06-26 20:34:03','Yay for blog posts!','<p>So I\'m posting a new blog article (via the website no less - no more direct database modification!) and it is awesome! I am teh blogger extraordinaire!</p>','[\"yay\",\"blog\",\"post\",\"shorty\"]'),(4,1,'another-blog-post',0,'2013-06-29 13:35:32','Another blog post!','So I\'m knocking Stories off my project list at a rate of knots (unfortunately I am also adding stories at a not much slower rate).  Blogs now have friendly URI\'s which is nice and tasty (yay for /shorty/another-blog-post)  As soon as I\'ve written the rout','[\"blog\",\"progress\",\"update\",\"shorty\"]'),(5,1,'tag-improvements',0,'2013-07-05 21:50:12','Tag Improvements','<p>Tags are now bigger and better.  With the ability to easily add multiple tags (including tags with comma\'s) and easily remove previous tags - tagging has become a dream!</p>','[\"shorty\",\"blog\",\"tags\",\"impovements\",\"\\\"awesome-sauce\\\"\"]');
/*!40000 ALTER TABLE `blogarticles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blogs` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(25) DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `tagline` varchar(250) DEFAULT NULL,
  `uri` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'Shorty Blog',1,'Everything Shorty!','shorty');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL DEFAULT '0',
  `object` varchar(250) DEFAULT NULL,
  `authorName` varchar(250) DEFAULT NULL,
  `authorEmail` varchar(250) DEFAULT NULL,
  `subject` varchar(250) DEFAULT NULL,
  `comment` text,
  `posted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `im_basetype`
--

DROP TABLE IF EXISTS `im_basetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `im_basetype` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(250) DEFAULT NULL,
  `friendly` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `im_basetype`
--

LOCK TABLES `im_basetype` WRITE;
/*!40000 ALTER TABLE `im_basetype` DISABLE KEYS */;
INSERT INTO `im_basetype` VALUES (1,'\\CannyDain\\Shorty\\Modules\\Interfaces\\ModuleInterface','Module'),(2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\TemplatedDocumentElement','Template Element');
/*!40000 ALTER TABLE `im_basetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `im_instances`
--

DROP TABLE IF EXISTS `im_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `im_instances` (
  `id` int(11) NOT NULL DEFAULT '0',
  `baseType` int(11) DEFAULT NULL,
  `class` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `im_instances`
--

LOCK TABLES `im_instances` WRITE;
/*!40000 ALTER TABLE `im_instances` DISABLE KEYS */;
INSERT INTO `im_instances` VALUES (1,1,'\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\SimpleContentModule'),(2,1,'\\CannyDain\\ShortyCoreModules\\UserModule\\UserModule'),(3,1,'\\CannyDain\\ShortyCoreModules\\ModuleManagement\\ModuleManagement'),(5,1,'\\CannyDain\\ShortyCoreModules\\SimpleBlog\\SimpleBlogModule'),(6,1,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\ProjectManagementModule'),(7,1,'\\CannyDain\\ShortyCoreModules\\ShortyNavigation\\ShortyNavigationModule'),(8,1,'\\CannyDain\\ShortyCoreModules\\SimpleShop\\SimpleShopModule'),(9,1,'\\CannyDain\\ShortyCoreModules\\payment_invoice\\payment_invoiceModule'),(11,1,'\\CannyDain\\ShortyCoreModules\\AdminModule\\AdminModuleModule'),(12,1,'\\CannyDain\\ShortyCoreModules\\URIManager\\URIManagerModule'),(13,1,'\\CannyDain\\ShortyCoreModules\\TemplateManager\\TemplateManagerModule'),(14,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\ContentElement'),(15,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\BlockLevelElement'),(16,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\BodyElement'),(17,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\EnhancedElements\\SimpleHeroSpot'),(18,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\HeadElement'),(19,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\HyperlinkElement'),(20,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\ImageElement'),(21,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\ScriptElement'),(22,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\StylesheetElement'),(23,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\HTML\\TitleElement'),(24,2,'\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\Elements\\RawHTMLElement'),(25,2,'\\CannyDain\\Shorty\\Skinnable\\Themes\\TemplateElements\\ThemeSelectorElement'),(26,2,'\\CannyDain\\Shorty\\Skinnable\\Themes\\TemplateElements\\ThemeStylesElement'),(27,2,'\\CannyDain\\Shorty\\UI\\Response\\Templated\\Elements\\NavigationElement'),(28,2,'\\CannyDain\\Shorty\\UI\\Response\\Templated\\Elements\\SidebarElement');
/*!40000 ALTER TABLE `im_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoiceitems`
--

DROP TABLE IF EXISTS `invoiceitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoiceitems` (
  `id` int(11) NOT NULL DEFAULT '0',
  `invoice` int(11) DEFAULT NULL,
  `item` varchar(250) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `tax` decimal(6,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoiceitems`
--

LOCK TABLES `invoiceitems` WRITE;
/*!40000 ALTER TABLE `invoiceitems` DISABLE KEYS */;
INSERT INTO `invoiceitems` VALUES (1,1,'Simple Website',20000,1,0.1750),(2,2,'Blog Module',2500,1,0.1750),(3,2,'ECommerce Module',5000,1,0.1750),(4,2,'Simple Website',20000,1,0.2000),(5,3,'Blog Module',2500,1,0.1750);
/*!40000 ALTER TABLE `invoiceitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `address1` varchar(250) DEFAULT NULL,
  `address2` varchar(250) DEFAULT NULL,
  `address3` varchar(250) DEFAULT NULL,
  `town` varchar(250) DEFAULT NULL,
  `county` varchar(250) DEFAULT NULL,
  `country` varchar(250) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `placed` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `shipping` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,'Danny Cain','23 Temple Road','Cowley','','Oxford','Oxfordshire','UK','OX4 2ET','2013-07-02 12:31:15',3,500,NULL),(2,'Danny Cain','23 Temple Road','Cowley','','Oxford','Oxfordshire','UK','OX4 2ET','2013-07-03 15:14:21',2,0,0),(3,'Danny Cain - DnS','23 Temple Road','Cowley','','Oxford','Oxfordshire','UK','OX4 2ET','2013-07-03 16:18:59',2,500,1000);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iterations`
--

DROP TABLE IF EXISTS `iterations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iterations` (
  `id` int(11) NOT NULL DEFAULT '0',
  `project` int(11) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iterations`
--

LOCK TABLES `iterations` WRITE;
/*!40000 ALTER TABLE `iterations` DISABLE KEYS */;
INSERT INTO `iterations` VALUES (1,1,'2013-07-08 00:00:00','2013-07-12 00:00:00');
/*!40000 ALTER TABLE `iterations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\SimpleContentModule',2),(2,'\\CannyDain\\ShortyCoreModules\\UserModule\\UserModule',2),(3,'\\CannyDain\\ShortyCoreModules\\ModuleManagement\\ModuleManagement',2),(5,'\\CannyDain\\ShortyCoreModules\\SimpleBlog\\SimpleBlogModule',2),(7,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\ProjectManagementModule',2),(8,'\\CannyDain\\ShortyCoreModules\\ShortyNavigation\\ShortyNavigationModule',2),(9,'\\CannyDain\\ShortyCoreModules\\SimpleShop\\SimpleShopModule',2),(10,'\\CannyDain\\ShortyCoreModules\\payment_invoice\\payment_invoiceModule',2),(12,'\\CannyDain\\ShortyCoreModules\\AdminModule\\AdminModuleModule',2),(13,'\\CannyDain\\ShortyCoreModules\\URIManager\\URIManagerModule',2),(14,'\\CannyDain\\ShortyCoreModules\\TemplateManager\\TemplateManagerModule',2);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `navigation`
--

DROP TABLE IF EXISTS `navigation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `navigation` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) DEFAULT NULL,
  `orderIndex` int(11) DEFAULT NULL,
  `caption` varchar(250) DEFAULT NULL,
  `uri` varchar(250) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `navigation`
--

LOCK TABLES `navigation` WRITE;
/*!40000 ALTER TABLE `navigation` DISABLE KEYS */;
INSERT INTO `navigation` VALUES (1,0,1,'Home','/',''),(2,0,1,'Content','/cannydain-shortycoremodules-simplecontentmodule-controllers-contentcontroller',''),(3,0,3,'Login','/cannydain-shortycoremodules-usermodule-controllers-usercontroller/login',''),(4,0,4,'Shorty Blog','/cannydain-shortycoremodules-simpleblog-controllers-simpleblogcontroller/view/1',''),(5,0,5,'Admin','/cannydain-shortycoremodules-adminmodule-controllers-admincontroller',''),(6,5,1,'Content','/cannydain-shortycoremodules-simplecontentmodule-controllers-contentadmincontroller',''),(7,5,2,'Modules','/cannydain-shortycoremodules-modulemanagement-controllers-modulemanagementcontroller',''),(8,5,3,'Projects','/cannydain-shortycoremodules-projectmanagement-controllers-projectmanagementcontroller',''),(9,5,4,'Users','/cannydain-shortycoremodules-usermodule-controllers-useradmincontroller',''),(10,5,5,'Navigation','/CannyDain-ShortyCoreModules-ShortyNavigation-Controllers-ShortyNavigationAdminController',''),(11,0,6,'Shop','',''),(12,11,1,'View Products','/cannydain-shortycoremodules-simpleshop-controllers-simpleshopcontroller',''),(13,11,2,'Basket','/cannydain-shortycoremodules-simpleshop-controllers-simpleshopcontroller/basket',''),(14,5,6,'Create Template','/CannyDain-ShortyCoreModules-TemplateManager-Controllers-TemplateEditorController/Create','');
/*!40000 ALTER TABLE `navigation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL DEFAULT '0',
  `owner` int(11) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,0,'Shorty','The basic Shorty System'),(2,0,'Canny Dain','The Canny Dain website - selling myself as a builder of websites and software');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` int(11) NOT NULL DEFAULT '0',
  `user` int(11) DEFAULT NULL,
  `started` datetime DEFAULT NULL,
  `lastactive` datetime DEFAULT NULL,
  `valid` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (1,0,'2013-06-18 12:35:22','2013-06-18 12:35:26',0),(2,0,'2013-06-19 08:45:18','2013-06-19 09:08:38',0),(3,1,'2013-06-19 11:43:29','2013-06-21 17:00:50',0),(4,0,'2013-06-22 13:45:33','2013-06-22 13:45:35',0),(5,1,'2013-06-23 07:56:07','2013-06-25 22:42:28',0),(6,2,'2013-06-26 12:48:03','2013-06-28 16:25:52',0),(7,0,'2013-06-28 14:43:48','2013-06-28 14:43:48',0),(8,0,'2013-06-28 14:43:55','2013-06-28 14:43:55',0),(9,1,'2013-06-28 16:33:18','2013-07-07 14:49:16',0),(10,0,'2013-07-01 14:54:41','2013-07-01 14:54:41',0),(11,0,'2013-07-01 14:55:08','2013-07-01 14:55:08',0),(12,0,'2013-07-01 15:00:03','2013-07-01 15:00:03',0),(13,0,'2013-07-01 15:00:59','2013-07-01 15:00:59',0),(14,0,'2013-07-01 15:01:55','2013-07-01 15:01:55',0),(15,0,'2013-07-01 15:12:13','2013-07-01 15:12:13',0),(16,0,'2013-07-01 15:13:41','2013-07-01 15:13:41',0),(17,0,'2013-07-01 15:14:10','2013-07-01 15:14:10',0),(18,0,'2013-07-01 16:56:14','2013-07-02 00:23:34',0),(19,0,'2013-07-01 22:10:18','2013-07-01 22:31:07',0),(20,0,'2013-07-02 12:11:38','2013-07-02 12:11:38',0),(21,0,'2013-07-02 12:57:40','2013-07-02 12:57:40',0),(22,0,'2013-07-03 10:58:53','2013-07-03 10:58:53',0),(23,0,'2013-07-04 08:33:28','2013-07-04 08:39:46',0),(24,0,'2013-07-04 11:23:45','2013-07-04 11:23:45',0),(25,0,'2013-07-04 11:38:29','2013-07-04 11:38:29',0),(26,0,'2013-07-04 11:38:53','2013-07-04 11:38:53',0),(27,1,'2013-07-08 16:55:01','2013-07-10 13:14:03',0),(28,0,'2013-07-10 12:56:00','2013-07-10 12:56:00',0),(29,0,'2013-07-10 12:56:14','2013-07-10 12:56:14',0),(30,0,'2013-07-10 12:56:45','2013-07-10 12:56:45',0),(31,0,'2013-07-10 13:04:52','2013-07-10 13:04:52',0),(32,1,'2013-07-10 13:16:52','2013-07-10 15:02:20',0),(33,0,'2013-07-10 13:54:09','2013-07-10 13:54:09',0),(34,0,'2013-07-10 14:40:02','2013-07-10 14:40:02',0),(35,1,'2013-07-10 18:22:04','2013-07-10 19:16:05',0),(36,0,'2013-07-10 18:23:34','2013-07-10 18:23:34',0),(37,1,'2013-07-11 09:13:18','2013-07-11 10:22:37',0),(38,0,'2013-07-11 09:15:19','2013-07-11 09:15:19',0),(39,0,'2013-07-11 09:16:02','2013-07-11 09:16:02',0),(40,0,'2013-07-11 11:30:28','2013-07-11 11:52:10',0),(41,1,'2013-07-11 13:02:01','2013-07-11 14:42:09',1);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simplecontent`
--

DROP TABLE IF EXISTS `simplecontent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `simplecontent` (
  `id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) DEFAULT NULL,
  `friendlyID` varchar(250) DEFAULT NULL,
  `author` varchar(250) DEFAULT NULL,
  `lastModified` datetime DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simplecontent`
--

LOCK TABLES `simplecontent` WRITE;
/*!40000 ALTER TABLE `simplecontent` DISABLE KEYS */;
INSERT INTO `simplecontent` VALUES (1,'About','about','Administrator','2013-07-10 13:06:02','<p>Shorty is a Website Development Framework designed to be easy to use and well written. It follows current good practice to the best of my abilities (SOLID etc) and is designed to be easy to extend.</p>\r\n\r\n<p>Shorty uses a Dependency Injection system I designed myself to allow for easy management of dependencies,no more having to pass objects around everywhere for no good reason or use a plethora of globals / god objects / singletons!</p>');
/*!40000 ALTER TABLE `simplecontent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simpleshopproducts`
--

DROP TABLE IF EXISTS `simpleshopproducts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `simpleshopproducts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `summary` text,
  `stock` int(11) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `tax` decimal(6,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simpleshopproducts`
--

LOCK TABLES `simpleshopproducts` WRITE;
/*!40000 ALTER TABLE `simpleshopproducts` DISABLE KEYS */;
INSERT INTO `simpleshopproducts` VALUES (1,'Simple Website','<p>A simple yet functional website that comes with a simple content edtior and a basic blog</p>',-1,0.00,20000,0.2000),(2,'ECommerce Module','<p>A Simple ECommerce Module for Shorty that allows you to manage your products</p>',9,0.00,5000,0.1750),(3,'Blog Module','<p>A simple little blogging module that will allow you to keep your site fresh and dynamic, and keep your visitor\'s coming back for more!</p>',200,0.00,2500,0.1750);
/*!40000 ALTER TABLE `simpleshopproducts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_name`
--

DROP TABLE IF EXISTS `table_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_name` (
  `id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_name`
--

LOCK TABLES `table_name` WRITE;
/*!40000 ALTER TABLE `table_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `table_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timeentry`
--

DROP TABLE IF EXISTS `timeentry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timeentry` (
  `id` int(11) NOT NULL DEFAULT '0',
  `object` varchar(250) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timeentry`
--

LOCK TABLES `timeentry` WRITE;
/*!40000 ALTER TABLE `timeentry` DISABLE KEYS */;
INSERT INTO `timeentry` VALUES (3,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-40','2013-07-09 14:00:00','2013-07-09 15:00:00','began work on time entry helper',1),(4,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-40','2013-07-10 12:46:00','2013-07-10 13:01:00','finished time entry helper',1),(5,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-12','2013-07-10 14:00:00','2013-07-10 14:30:00','fixed content controller not using short uri\'s for index - was because it was using a #id# based uri - need to change to having view using router and injecting routes rather than uri\'s',1),(6,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-42','2013-07-10 14:30:00','2013-07-10 14:40:00','refactored to remove dependency on UserControl, added ShortyExecutor which has dependency on UserControl',1),(7,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-13','2013-07-10 14:40:00','2013-07-10 14:50:00','created base controller and core dependencies - refactored SOME Classes to use it (although they override the dependency injection)',1),(8,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-41','2013-07-10 14:50:00','2013-07-10 14:55:00','fixed - the view wasn\'t updating the started and completed fields',1),(9,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-43','2013-07-10 14:55:00','2013-07-10 15:40:00','completed layout selector - created a \"new layout\" (same as original save with 4 \"hero spots\" with a simple link to /about)',1),(10,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-37','2013-07-10 15:40:00','2013-07-10 18:00:00','designed javascript objects to edit the JSON templates',1),(11,'\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory-37','2013-07-11 10:15:00','2013-07-11 11:20:00','created module, created edit template view and export to JSON functionality',1);
/*!40000 ALTER TABLE `timeentry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uri`
--

DROP TABLE IF EXISTS `uri`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uri` (
  `id` int(11) NOT NULL DEFAULT '0',
  `uri` varchar(500) DEFAULT NULL,
  `controller` varchar(500) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uri`
--

LOCK TABLES `uri` WRITE;
/*!40000 ALTER TABLE `uri` DISABLE KEYS */;
INSERT INTO `uri` VALUES (1,'blog','\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Controllers\\SimpleBlogController','view','[\"shorty\"]'),(2,'logout','\\CannyDain\\ShortyCoreModules\\UserModule\\Controllers\\UserController','logout','[]'),(3,'about','\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\Controllers\\ContentController','View','[\"about\"]'),(4,'says/about/shorty','\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\Controllers\\ContentController','View','[\"about\"]');
/*!40000 ALTER TABLE `uri` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `admin` int(1) DEFAULT NULL,
  `registered` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','18e45cf7860c7d609c52665f54020844',1,'2013-06-28 12:20:52'),(2,'danny','18e45cf7860c7d609c52665f54020844',0,'2013-06-28 16:21:18');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userstories`
--

DROP TABLE IF EXISTS `userstories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userstories` (
  `id` int(11) NOT NULL DEFAULT '0',
  `project` int(11) DEFAULT NULL,
  `section` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `target` text,
  `action` text,
  `reason` text,
  `status` varchar(10) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `estimate` int(11) DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `started` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userstories`
--

LOCK TABLES `userstories` WRITE;
/*!40000 ALTER TABLE `userstories` DISABLE KEYS */;
INSERT INTO `userstories` VALUES (7,1,'Instance Manager','Instance Manager Deletions','Admin','see base class instances that have been deleted removed from the list','they don\'t keep reappearing (ala deleted modules)','2',1,5,NULL,'2013-06-17 00:00:00'),(12,1,'Friendly URI Module','Friendly URI\'s','Admin','be able to assign specific url\'s to specific pages','I can build a search engine friendly website with end-user friendly links','7',5,5,'2013-07-10 00:00:00','2013-06-17 00:00:00'),(13,1,'Core','Create a BaseController','Developer','be able to quickly and easily add new controllers without having to add consumer code for common dependencies','I can be more productive and duplicate code less','7',3,5,'2013-07-10 00:00:00','2013-06-17 00:00:00'),(14,1,'DataMapper','Indexes and Unique Keys','Developer','be able to define indexes and unique fields in the object definition','I can optimise the database (for indexes) and save an extra `uniqueness` query for unique fields','2',1,5,NULL,'2013-06-17 00:00:00'),(15,1,'Core','Access Rights','Developer','be able to show a `manage access rights` form on admin pages','Access rights can be easily managed in the same manner across the site','2',3,5,NULL,'2013-06-17 00:00:00'),(19,1,'Users Module','Add User Groups','Administrator','be able to organise users into groups','it is easier to administrate them and to control access','2',1,3,'1970-01-01 00:00:00','2013-06-17 00:00:00'),(20,1,'Users Module','tidy up user sidebar','User','see a nicely laid out sidebar containing my user details and configurable bookmarks','I can easily see my account details and am able to easily access the parts of the website I use most commonly','2',1,3,'1970-01-01 00:00:00','2013-06-17 00:00:00'),(22,1,'Form Helper','Add Rich Text Editor','User/Administrator','be able to edit HTML without knowledge of HTML','I can pretty up pages with no technical knowledge','2',1,3,'1970-01-01 00:00:00','2013-06-17 00:00:00'),(23,1,'Project Management','User Story Usability','User of the project management module','be able to easily move stories between stages and alter priorities (possibly by dragging and dropping on the list screen)','I can spend less time performing administration and more time completing user stories','2',1,3,'1970-01-01 00:00:00','2013-06-17 00:00:00'),(24,1,'Blog Module','add validation to blog model','user','see an error and not have an item save if it is invalid','we do not end up with invalid/broken data','2',1,3,'1970-01-01 00:00:00','2013-06-17 00:00:00'),(36,1,'Users Module','Session Timeouts','User','see the system automatically log me out after a set period of time','my security is protected if I forget to log out','2',1,3,'1970-01-01 00:00:00','2013-06-17 00:00:00'),(37,1,'Templates Module','Template Management','Admin','be able to edit the site template easily','I can tweak the look of the site as and when needed','2',1,3,NULL,'2013-06-17 00:00:00'),(39,1,'Core','Provider Chooser','Administrator/Developer','be able to choose between different providers as modules make them available (for instance changing the navigation provider or the product provider)','I can easily upgrade and improve the website','2',3,5,'1970-01-01 00:00:00','1970-01-01 00:00:00'),(40,1,'Time Entry Helper','Time Entry','developer','be able to log time against tasks','I can monitor my performance, my estimates and determine profitability','7',5,5,'2013-07-10 14:00:00','2013-07-09 14:00:00'),(41,1,'Project Management','Start / Completion Date','developer','be able to set start and end dates on stories (at the moment the start and end date is not saving)','I can track what was completed when and determine which iteration it falls into','7',5,3,'2013-07-10 00:00:00','2013-07-10 00:00:00'),(42,1,'Core','Basic Executor','Developer','be able to use the basic executor without having a dependency on UserControl','I can easily build custom websites based on Shorty/Lib without tying myself into unneccesary dependencies','7',3,1,'2013-07-10 00:00:00','2013-07-10 00:00:00'),(43,1,'Shorty / Widgets','Layout Selector','User','be able to select between multiple different layouts','I can change the experience to suit my preferences','7',5,5,'2013-07-10 00:00:00','2013-07-10 00:00:00');
/*!40000 ALTER TABLE `userstories` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-11 15:47:34
