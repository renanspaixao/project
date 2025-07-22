-- MySQL dump 10.13  Distrib 8.0.34, for macos13 (arm64)
--
-- Host: 127.0.0.1    Database: montlink_erp
-- ------------------------------------------------------
-- Server version	9.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `coupon`
--

DROP TABLE IF EXISTS `coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `discountType` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discountValue` float DEFAULT NULL,
  `expirationDate` date NOT NULL,
  `usageLimit` int NOT NULL,
  `minimumValue` float NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon`
--

LOCK TABLES `coupon` WRITE;
/*!40000 ALTER TABLE `coupon` DISABLE KEYS */;
INSERT INTO `coupon` VALUES (1,'BEMVINDO10','10% de desconto',1,'P',10,'2025-09-01',5,100,'2025-07-21 20:11:37'),(2,'25','R$ 25 de desconto na sua compra',1,'V',25,'2025-09-01',50,189.99,'2025-07-21 20:13:06');
/*!40000 ALTER TABLE `coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderItems`
--

DROP TABLE IF EXISTS `orderItems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orderItems` (
  `id` int NOT NULL AUTO_INCREMENT,
  `orderId` int DEFAULT NULL,
  `productId` int DEFAULT NULL,
  `variationId` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `priceUnit` decimal(10,2) DEFAULT NULL,
  `priceTotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orderId` (`orderId`),
  CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderItems`
--

LOCK TABLES `orderItems` WRITE;
/*!40000 ALTER TABLE `orderItems` DISABLE KEYS */;
INSERT INTO `orderItems` VALUES (1,1,1,1,1,89.99,89.99),(2,2,3,4,3,9.99,29.97),(3,3,2,3,10,350.00,3500.00),(4,4,2,3,10,350.00,3500.00),(5,4,3,4,20,9.99,199.80),(6,4,1,2,40,99.99,3999.60),(7,4,1,1,20,89.99,1799.80),(8,5,3,4,20,9.99,199.80),(9,5,2,3,5,350.00,1750.00),(10,5,1,2,5,99.99,499.95),(11,5,1,1,5,89.99,449.95),(12,6,1,1,5,89.99,449.95),(13,6,1,2,7,99.99,699.93),(14,7,1,2,3,99.99,299.97);
/*!40000 ALTER TABLE `orderItems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clientName` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `shipping` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `couponId` int DEFAULT NULL,
  `status` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_coupon` (`couponId`),
  CONSTRAINT `fk_coupon` FOREIGN KEY (`couponId`) REFERENCES `coupon` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'Renan Souza Paixão','renan.souza1070@gmail.com','13473-000','Rua São Gabriel','Americana','SP',89.99,0.00,15.00,104.99,NULL,'D','2025-07-21 20:16:15'),(2,'Renan Souza Paixão','renan.souza1070@gmail.com','13473-000','Rua São Gabriel','Americana','SP',29.97,0.00,20.00,49.97,NULL,'C','2025-07-21 20:19:08'),(3,'Renan Souza Paixão','paixaosouzarenan@icloud.com','13473-000','Rua São Gabriel','Americana','SP',3500.00,25.00,0.00,3475.00,2,'C','2025-07-21 20:27:49'),(4,'Renan Souza Paixão','paixaosouzarenan@icloud.com','13473-000','Rua São Gabriel','Americana','SP',9499.20,949.92,0.00,8549.28,1,'C','2025-07-21 20:29:23'),(5,'Renan Souza Paixão','paixaosouzarenan@icloud.com','13473-000','Rua São Gabriel','Americana','SP',2899.70,25.00,0.00,2874.70,2,'D','2025-07-21 20:30:45'),(6,'Renan Souza Paixão','paixaosouzarenan@icloud.com','13473-000','Rua São Gabriel','Americana','SP',1149.88,114.99,0.00,1034.89,1,'P','2025-07-21 20:32:28'),(7,'Renan Souza Paixão','renan.souza1070@gmail.com','13473-000','Rua São Gabriel','Americana','SP',299.97,0.00,0.00,299.97,NULL,'C','2025-07-21 20:35:50');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'Camisa',89.99,'2025-07-21 20:13:59'),(2,'Calça',300,'2025-07-21 20:14:31'),(3,'Meia',9.99,'2025-07-21 20:18:23');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productVariations`
--

DROP TABLE IF EXISTS `productVariations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productVariations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `productId` int DEFAULT NULL,
  `color` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` float DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`),
  CONSTRAINT `productvariations_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `product` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productVariations`
--

LOCK TABLES `productVariations` WRITE;
/*!40000 ALTER TABLE `productVariations` DISABLE KEYS */;
INSERT INTO `productVariations` VALUES (1,1,'Preta','G',NULL,'2025-07-21 20:13:59'),(2,1,'Branca','G',99.99,'2025-07-21 20:13:59'),(3,2,'Preta','42',350,'2025-07-21 20:14:31'),(4,3,'Branca','',NULL,'2025-07-21 20:18:23');
/*!40000 ALTER TABLE `productVariations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `productId` int NOT NULL,
  `variationId` int NOT NULL,
  `amount` int NOT NULL DEFAULT '0',
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`),
  KEY `variationId` (`variationId`),
  CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`variationId`) REFERENCES `productVariations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
INSERT INTO `stock` VALUES (1,1,1,20,'2025-07-21 20:13:59'),(2,1,2,38,'2025-07-21 20:13:59'),(3,2,3,10,'2025-07-21 20:14:31'),(4,3,4,30,'2025-07-21 20:18:23');
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-21 21:11:38
