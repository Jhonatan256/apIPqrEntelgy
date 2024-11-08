-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: pqrentelgy
-- ------------------------------------------------------
-- Server version	8.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `area` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `idEncargado` int NOT NULL,
  `ans` int NOT NULL,
  `eliminado` varchar(2) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area`
--

LOCK TABLES `area` WRITE;
/*!40000 ALTER TABLE `area` DISABLE KEYS */;
INSERT INTO `area` VALUES (1,'Proceso de Dirección ',1,90,'N'),(2,'Proceso administración del SGC ',1,90,'N'),(3,'Proceso Delivery servicios STP\'S DBP',1,90,'N'),(4,'Proceso Delivery servicios STP\'S Ciberseguridad',1,90,'N'),(5,'Proceso Gestión de proyectos ',1,90,'N'),(6,'Proceso implementación de la solución ',1,90,'N'),(7,'Proceso servicio de ciberseguridad',1,90,'N'),(8,'Proceso Gestión Humana ',1,90,'N'),(9,'Proceso Gestión de Infraestructura Tecnológica ',1,90,'N'),(10,'Proceso Gestión de Compras ',1,90,'N'),(11,'Proceso de preventa DBP',1,90,'N'),(12,'Proceso de Preventa Ciberseguridad',1,90,'N');
/*!40000 ALTER TABLE `area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caso`
--

DROP TABLE IF EXISTS `caso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `caso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idInformador` int NOT NULL,
  `idResponsable` int NOT NULL,
  `tipoSolicitud` int NOT NULL,
  `idArea` int NOT NULL,
  `idPrioridad` int NOT NULL,
  `idGravedad` int NOT NULL,
  `idEstado` int NOT NULL,
  `asunto` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fechaCreacion` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `porcentaje` varchar(10) COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caso`
--

LOCK TABLES `caso` WRITE;
/*!40000 ALTER TABLE `caso` DISABLE KEYS */;
INSERT INTO `caso` VALUES (8,13,13,0,0,1,1,1,'PRUEBA DE CASO','descripcion','2024-11-08 04:51:19','0'),(9,13,13,0,0,1,1,1,'PRUEBA DE CASO','descripcion','2024-11-08 05:36:49','0'),(6,13,13,1,1,1,1,1,'PRUEBA DE CASO','descripcion','2024-11-08 02:48:55','0'),(5,13,13,1,1,1,1,1,'PRUEBA DE CASO','descripcion','2024-11-08 02:47:40','0'),(10,13,13,0,0,1,1,1,'PRUEBA DE CASO','descripcion','2024-11-08 06:14:16','0'),(11,13,13,0,0,1,1,1,'PRUEBA DE CASO','descripcion','2024-11-08 06:14:43','0');
/*!40000 ALTER TABLE `caso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado`
--

DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `eliminado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado`
--

LOCK TABLES `estado` WRITE;
/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
INSERT INTO `estado` VALUES (1,'Pendiente','N'),(2,'Solucionado','N'),(3,'Anulado','N'),(4,'Aplazado','N'),(5,'Reabrierto','N'),(6,'Reasignado','N');
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gravedad`
--

DROP TABLE IF EXISTS `gravedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gravedad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `eliminado` varchar(2) COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gravedad`
--

LOCK TABLES `gravedad` WRITE;
/*!40000 ALTER TABLE `gravedad` DISABLE KEYS */;
INSERT INTO `gravedad` VALUES (1,'Fallo','N'),(2,'Bloqueo','N'),(3,'Normal','N');
/*!40000 ALTER TABLE `gravedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial`
--

DROP TABLE IF EXISTS `historial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCaso` int NOT NULL,
  `idResponsable` int NOT NULL,
  `idEncargado` int NOT NULL,
  `cambioEstado` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish2_ci NOT NULL,
  `accionesRealizadas` text COLLATE utf8mb4_spanish2_ci NOT NULL,
  `porcentaje` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial`
--

LOCK TABLES `historial` WRITE;
/*!40000 ALTER TABLE `historial` DISABLE KEYS */;
INSERT INTO `historial` VALUES (2,5,13,0,1,'Asignación automática del sistema.','Asignación','0','2024-11-08 02:47:40'),(3,6,13,13,1,'Asignación automática del sistema.','Asignación','0','2024-11-08 02:48:55'),(4,8,13,13,1,'Asignación automática del sistema.','Asignación','0','2024-11-08 04:51:19'),(5,9,13,13,1,'Asignación automática del sistema.','Asignación','0','2024-11-08 05:36:49'),(6,10,13,13,1,'Asignación automática del sistema.','Asignación','0','2024-11-08 06:14:16'),(7,11,13,13,1,'Asignación automática del sistema.','Asignación','0','2024-11-08 06:14:43');
/*!40000 ALTER TABLE `historial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prioridad`
--

DROP TABLE IF EXISTS `prioridad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prioridad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `eliminado` varchar(2) COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prioridad`
--

LOCK TABLES `prioridad` WRITE;
/*!40000 ALTER TABLE `prioridad` DISABLE KEYS */;
INSERT INTO `prioridad` VALUES (1,'Baja','N'),(2,'Normal','N'),(3,'Alta','N'),(4,'Urgente','N'),(5,'Inmediata','N'),(6,'Ninguna','N');
/*!40000 ALTER TABLE `prioridad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soporte`
--

DROP TABLE IF EXISTS `soporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `soporte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCaso` int DEFAULT NULL,
  `idHistorial` int DEFAULT NULL,
  `nombre` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `eliminado` varchar(2) COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soporte`
--

LOCK TABLES `soporte` WRITE;
/*!40000 ALTER TABLE `soporte` DISABLE KEYS */;
/*!40000 ALTER TABLE `soporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipocaso`
--

DROP TABLE IF EXISTS `tipocaso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipocaso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `eliminado` varchar(2) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipocaso`
--

LOCK TABLES `tipocaso` WRITE;
/*!40000 ALTER TABLE `tipocaso` DISABLE KEYS */;
INSERT INTO `tipocaso` VALUES (1,'Solicitud','N'),(2,'Queja','N'),(3,'Reclamo','N'),(4,'Sugerencia','N');
/*!40000 ALTER TABLE `tipocaso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipousuario`
--

DROP TABLE IF EXISTS `tipousuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipousuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `eliminado` varchar(2) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipousuario`
--

LOCK TABLES `tipousuario` WRITE;
/*!40000 ALTER TABLE `tipousuario` DISABLE KEYS */;
INSERT INTO `tipousuario` VALUES (1,'Interno','N'),(2,'Empleado','N'),(3,'Administrador','N'),(4,'Externo','N');
/*!40000 ALTER TABLE `tipousuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `identificacion` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `celular` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `password` text COLLATE utf8mb4_spanish2_ci NOT NULL,
  `tipoUsuario` int NOT NULL,
  `cargo` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `area` int NOT NULL,
  `genero` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fechaCreacion` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fechaUltimoAcceso` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `eliminado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'N',
  `fechaEliminacion` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'RUTH YISELA','VELA LEON','1111111111','gisela.vela@entelgy.com','1111111111','$2y$10$7wXFhpEmuHcdmeJElP4N8OtvjU0GFizzkCGRFwvzBbdUJxR7FWdTa',1,'GESTOR OPERATIVO',1,'F','2024-11-02 05:58:01','2024-11-08 19:08:23','N',NULL),(2,'JESUS','SANCHEZ','1007306773','jesus.sanchez@entelgy.com','3105265656','$2y$10$m4knxoufP7hVLufqfBV3XOvOtdZ7xORaWVaA1wCNUr4yZLuBNJkcq',1,'DESARROLLADOR',1,'M','2024-11-08 06:00:09',NULL,'N',NULL),(3,'JHONATAN ANDRES','RONCANCIO PINZON','1049654252','jhonatan.roncancio@entelgy.com','3166215124','$2y$10$ZswL8n2waPxGTevddNWdlu9/Rug/ZKlaCv3l7j1oXqa83PQCUV7Nu',1,'DESARROLLADOR',1,'M','2024-11-08 18:45:39','2024-11-08 21:27:12','N',NULL);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'pqrentelgy'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-08 17:54:36
