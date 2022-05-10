CREATE DATABASE  IF NOT EXISTS `db_contatos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_contatos`;
-- MySQL dump 10.13  Distrib 8.0.20, for Win64 (x86_64)
--
-- Host: localhost    Database: db_contatos
-- ------------------------------------------------------
-- Server version	8.0.20

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
-- Table structure for table `tbl_contatos`
--

DROP TABLE IF EXISTS `tbl_contatos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_contatos` (
  `id_contato` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) NOT NULL,
  `email` varchar(110) NOT NULL,
  `telefone` varchar(18) DEFAULT NULL,
  `celular` varchar(18) NOT NULL,
  `obs` text,
  `foto` varchar(50) DEFAULT NULL,
  `fotos` text,
  `idEstado` int NOT NULL,
  PRIMARY KEY (`id_contato`),
  KEY `FK_Estados_Contatos` (`idEstado`),
  CONSTRAINT `FK_Estados_Contatos` FOREIGN KEY (`idEstado`) REFERENCES `tblestados` (`idEstado`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_contatos`
--

LOCK TABLES `tbl_contatos` WRITE;
/*!40000 ALTER TABLE `tbl_contatos` DISABLE KEYS */;
INSERT INTO `tbl_contatos` VALUES (22,'thales','tales@email.com','2332321','33429243','rwreerwer','e4ef38d19434f43bdf5da9a0afae4ae6.png',NULL,1),(23,'carol','carol@email.com','31312','423243','dadsds adsda carioca','3238bfc8e8b3e886bc0088246f8c0c06.png',NULL,2);
/*!40000 ALTER TABLE `tbl_contatos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblestados`
--

DROP TABLE IF EXISTS `tblestados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblestados` (
  `idEstado` int NOT NULL AUTO_INCREMENT,
  `sigla` varchar(2) NOT NULL,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`idEstado`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblestados`
--

LOCK TABLES `tblestados` WRITE;
/*!40000 ALTER TABLE `tblestados` DISABLE KEYS */;
INSERT INTO `tblestados` VALUES (1,'SP','São Paulo'),(2,'RJ','Rio de Janeiro'),(3,'MG','Minas Gerais');
/*!40000 ALTER TABLE `tblestados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'db_contatos'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-10 16:59:43
