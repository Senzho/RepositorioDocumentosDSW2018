-- MySQL dump 10.13  Distrib 5.5.60, for Win64 (AMD64)
--
-- Host: localhost    Database: repositoriouv
-- ------------------------------------------------------
-- Server version	5.5.60-log

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
-- Table structure for table `academico`
--

DROP TABLE IF EXISTS `academico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `academico` (
  `idAcademico` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `contrasena` varchar(64) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idAcademico`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academico`
--

LOCK TABLES `academico` WRITE;
/*!40000 ALTER TABLE `academico` DISABLE KEYS */;
INSERT INTO `academico` VALUES (74,'sddf','asd','688787d8ff144c502c7f5cffaafe2cc588d86079f9de88304c','marioolopez21@gmail.com');
/*!40000 ALTER TABLE `academico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documento` (
  `idDocumento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `fechaRegistro` date DEFAULT NULL,
  `idAcademico` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDocumento`),
  KEY `idAcademico` (`idAcademico`),
  CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`idAcademico`) REFERENCES `academico` (`idAcademico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentocompartido`
--

DROP TABLE IF EXISTS `documentocompartido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentocompartido` (
  `idDocumentoCompartido` int(11) NOT NULL AUTO_INCREMENT,
  `idAcademicoEmisor` int(11) DEFAULT NULL,
  `idAcademicoReceptor` int(11) DEFAULT NULL,
  `idDocumento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDocumentoCompartido`),
  KEY `idAcademicoEmisor` (`idAcademicoEmisor`),
  KEY `idAcademicoReceptor` (`idAcademicoReceptor`),
  KEY `idDocumento` (`idDocumento`),
  CONSTRAINT `documentocompartido_ibfk_1` FOREIGN KEY (`idAcademicoEmisor`) REFERENCES `academico` (`idAcademico`),
  CONSTRAINT `documentocompartido_ibfk_2` FOREIGN KEY (`idAcademicoReceptor`) REFERENCES `academico` (`idAcademico`),
  CONSTRAINT `documentocompartido_ibfk_3` FOREIGN KEY (`idDocumento`) REFERENCES `documento` (`idDocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentocompartido`
--

LOCK TABLES `documentocompartido` WRITE;
/*!40000 ALTER TABLE `documentocompartido` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentocompartido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `firma`
--

DROP TABLE IF EXISTS `firma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `firma` (
  `idFirma` int(11) NOT NULL AUTO_INCREMENT,
  `firmaPublica` varchar(200) DEFAULT NULL,
  `firmaPrivada` varchar(200) DEFAULT NULL,
  `idAcademico` int(11) DEFAULT NULL,
  PRIMARY KEY (`idFirma`),
  KEY `idAcademico` (`idAcademico`),
  CONSTRAINT `firma_ibfk_1` FOREIGN KEY (`idAcademico`) REFERENCES `academico` (`idAcademico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `firma`
--

LOCK TABLES `firma` WRITE;
/*!40000 ALTER TABLE `firma` DISABLE KEYS */;
/*!40000 ALTER TABLE `firma` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-27 13:48:23
