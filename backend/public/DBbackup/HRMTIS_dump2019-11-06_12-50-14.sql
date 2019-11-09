-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: hrm_tis_dev
-- ------------------------------------------------------
-- Server version 	5.5.5-10.3.16-MariaDB
-- Date: Wed, 06 Nov 2019 12:50:14 +0600

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
-- Table structure for table `activationstatus`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activationstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL COMMENT 'Active, In-active, Deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activationstatus`
--

LOCK TABLES `activationstatus` WRITE;
/*!40000 ALTER TABLE `activationstatus` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `activationstatus` VALUES (1,'Active');
/*!40000 ALTER TABLE `activationstatus` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `activationstatus` with 1 row(s)
--

--
-- Table structure for table `adjustment_details`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adjustment_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `inTime` time DEFAULT NULL,
  `outTime` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `minimumHour` double DEFAULT NULL,
  `fkEmpId` int(11) NOT NULL,
  `seenStatus` varchar(10) DEFAULT NULL COMMENT 'seen=S,unSeen = N',
  `status` varchar(50) DEFAULT NULL COMMENT 'Pending,Taken,Unavailable',
  `remarks` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adjustment_details`
--

LOCK TABLES `adjustment_details` WRITE;
/*!40000 ALTER TABLE `adjustment_details` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `adjustment_details` VALUES (1,'2019-09-30','2019-09-30','18:00:00','21:00:00','2019-09-30 13:57:15',NULL,116,'N','Pending',NULL);
/*!40000 ALTER TABLE `adjustment_details` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `adjustment_details` with 1 row(s)
--

--
-- Table structure for table `attemployeemap`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attemployeemap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attDeviceUserId` varchar(45) NOT NULL,
  `employeeId` varchar(45) NOT NULL,
  `fkCompanyId` int(11) DEFAULT NULL,
  `fkDepartmentId` int(11) DEFAULT NULL,
  `attemployeemapcol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attemployeemap`
--

LOCK TABLES `attemployeemap` WRITE;
/*!40000 ALTER TABLE `attemployeemap` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `attemployeemap` VALUES (1,'1','1',0,0,''),(2,'2','2',0,0,''),(3,'3','3',0,0,''),(4,'4','4',0,0,''),(5,'5','5',0,0,''),(6,'6','6',0,0,''),(7,'7','7',0,0,''),(8,'8','8',0,0,''),(9,'9','9',0,0,''),(10,'10','10',0,0,''),(11,'12','11',0,0,''),(12,'14','12',0,0,''),(13,'17','13',0,0,''),(14,'19','14',0,0,''),(15,'20','15',0,0,''),(16,'23','16',0,0,''),(17,'25','17',0,0,''),(18,'26','18',0,0,''),(19,'27','19',0,0,''),(20,'28','20',0,0,''),(21,'30','21',0,0,''),(22,'31','22',0,0,''),(23,'32','23',0,0,''),(24,'33','24',0,0,''),(25,'34','25',0,0,''),(26,'38','26',0,0,''),(27,'39','27',0,0,''),(28,'40','28',0,0,''),(29,'43','29',0,0,''),(30,'52','30',0,0,''),(31,'55','31',0,0,''),(32,'60','32',0,0,''),(33,'61','33',0,0,''),(34,'62','34',0,0,''),(35,'64','35',0,0,''),(36,'65','36',0,0,''),(37,'66','37',0,0,''),(38,'75','38',0,0,''),(39,'80','39',0,0,''),(40,'85','40',0,0,''),(41,'95','41',0,0,''),(42,'98','42',0,0,''),(43,'104','43',0,0,''),(44,'109','44',0,0,''),(45,'110','45',0,0,''),(46,'116','46',0,0,''),(47,'121','47',0,0,''),(48,'127','48',0,0,''),(49,'129','49',0,0,''),(50,'131','50',0,0,''),(51,'132','51',0,0,''),(52,'133','52',0,0,''),(53,'134','53',0,0,''),(54,'136','54',0,0,''),(55,'137','55',0,0,''),(56,'138','56',0,0,''),(57,'140','57',0,0,''),(58,'141','58',0,0,''),(59,'143','59',0,0,''),(60,'144','60',0,0,''),(61,'145','61',0,0,''),(62,'147','62',0,0,''),(63,'148','63',0,0,''),(64,'149','64',0,0,''),(65,'150','65',0,0,''),(66,'154','66',0,0,''),(67,'155','67',0,0,''),(68,'156','68',0,0,''),(69,'157','69',0,0,''),(70,'159','70',0,0,''),(71,'160','71',0,0,''),(72,'161','72',0,0,''),(73,'162','73',0,0,''),(74,'163','74',0,0,''),(75,'165','75',0,0,''),(76,'167','76',0,0,''),(77,'170','77',0,0,''),(78,'173','78',0,0,''),(79,'174','79',0,0,''),(80,'175','80',0,0,''),(81,'176','81',0,0,''),(82,'177','82',0,0,''),(83,'178','83',0,0,''),(84,'179','84',0,0,''),(85,'181','85',0,0,''),(86,'182','86',0,0,''),(87,'184','87',0,0,''),(88,'185','88',0,0,''),(89,'186','89',0,0,''),(90,'187','90',0,0,''),(91,'188','91',0,0,''),(92,'189','92',0,0,''),(93,'190','93',0,0,''),(94,'192','94',0,0,''),(95,'194','95',0,0,''),(96,'197','96',0,0,''),(97,'198','97',0,0,''),(98,'200','98',0,0,''),(99,'201','99',0,0,''),(100,'202','100',0,0,''),(101,'204','101',0,0,''),(102,'205','102',0,0,''),(103,'207','103',0,0,''),(104,'210','104',0,0,''),(105,'211','105',0,0,''),(106,'212','106',0,0,''),(107,'213','107',0,0,''),(108,'215','108',0,0,''),(109,'217','109',0,0,''),(110,'218','110',0,0,''),(111,'219','111',0,0,''),(112,'221','112',0,0,''),(113,'224','113',0,0,''),(114,'225','114',0,0,''),(115,'226','115',0,0,''),(116,'227','116',0,0,''),(118,'300','118',0,0,''),(119,'301','119',0,0,''),(120,'302','120',0,0,''),(121,'303','121',0,0,''),(122,'304','122',0,0,''),(123,'305','123',0,0,''),(124,'306','124',1,8,''),(125,'307','125',1,8,''),(126,'309','126',1,8,''),(127,'234234','127',NULL,NULL,NULL);
/*!40000 ALTER TABLE `attemployeemap` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `attemployeemap` with 126 row(s)
--

--
-- Table structure for table `attendancedata`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendancedata` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `attDeviceUserId` int(45) NOT NULL,
  `accessTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fkAttDevice` int(11) DEFAULT NULL,
  `details` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `attDeviceUserId` (`attDeviceUserId`,`accessTime`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendancedata`
--

LOCK TABLES `attendancedata` WRITE;
/*!40000 ALTER TABLE `attendancedata` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `attendancedata` VALUES (2,227,'2019-10-21 08:19:30',3,'2019-10-21 08:19:22'),(4,227,'2019-10-21 08:30:00',4,'2019-10-21 08:19:22'),(5,227,'2019-10-21 10:19:30',3,'2019-10-21 08:19:22'),(6,227,'2019-10-21 16:45:30',4,'2019-10-21 08:19:22'),(7,227,'2019-10-21 08:51:00',4,'2019-10-21 08:19:22');
/*!40000 ALTER TABLE `attendancedata` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `attendancedata` with 5 row(s)
--

--
-- Table structure for table `companyinformation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companyinformation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyName` varchar(150) NOT NULL,
  `companyAddress` varchar(255) DEFAULT NULL,
  `phone` varchar(18) DEFAULT NULL,
  `fax` varchar(18) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `webSite` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `logoUrl` varchar(255) DEFAULT NULL,
  `fkActivationStatus` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activationStatus_company_fk_idx` (`fkActivationStatus`),
  CONSTRAINT `activationStatus_company_fk` FOREIGN KEY (`fkActivationStatus`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companyinformation`
--

LOCK TABLES `companyinformation` WRITE;
/*!40000 ALTER TABLE `companyinformation` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `companyinformation` VALUES (1,'Total IT Solutions',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(2,'TBN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `companyinformation` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `companyinformation` with 2 row(s)
--

--
-- Table structure for table `departments`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departmentName` varchar(255) NOT NULL,
  `deptHead` varchar(255) DEFAULT NULL,
  `fkDeptParent` int(11) DEFAULT NULL,
  `fkCompany` int(11) DEFAULT NULL,
  `deptLevel` tinyint(6) DEFAULT NULL,
  `createdBy` bigint(20) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL,
  `rosterType` int(1) DEFAULT NULL COMMENT '1=single , 2 = multiple',
  `createdAt` timestamp NULL DEFAULT NULL,
  `orderBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_fk_idx` (`fkCompany`),
  KEY `createdBy_fk_idx` (`createdBy`),
  KEY `departmentParent1_fk_idx` (`fkDeptParent`),
  CONSTRAINT `company1_fk` FOREIGN KEY (`fkCompany`) REFERENCES `companyinformation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `createdBy_fk` FOREIGN KEY (`createdBy`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `departmentParent1_fk` FOREIGN KEY (`fkDeptParent`) REFERENCES `departments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `departments` VALUES (1,'TBN',NULL,NULL,NULL,0,NULL,'active',2,NULL,NULL),(2,'TIS',NULL,NULL,NULL,0,NULL,'active',1,NULL,NULL),(3,'Go And Gift',NULL,NULL,1,0,NULL,'active',1,NULL,NULL),(4,'Broad Cast',NULL,1,1,1,NULL,'active',1,NULL,3),(5,'News Dept',NULL,1,1,1,NULL,'active',1,NULL,1),(6,'Camera Man',NULL,1,1,1,NULL,'active',1,NULL,2),(7,'Graphics',NULL,1,1,1,NULL,'active',1,NULL,4),(8,'Vedio Editors',NULL,1,1,1,NULL,'active',1,NULL,5),(9,'I P TV',NULL,1,1,1,NULL,'active',1,NULL,6),(10,'Program',NULL,1,1,1,NULL,'active',1,NULL,7),(11,'GM',NULL,2,1,1,NULL,'active',1,NULL,9),(12,'AGM',NULL,2,1,1,NULL,'active',1,NULL,10),(13,'Accounts',NULL,2,1,1,NULL,'active',1,NULL,11),(14,'HR',NULL,2,1,1,NULL,'active',1,NULL,12),(15,'Web Dev','95',2,1,1,NULL,'active',1,NULL,13),(16,'Call Center',NULL,2,NULL,1,NULL,'active',2,NULL,14),(17,'Channel Mang',NULL,2,1,1,NULL,'active',1,NULL,15),(18,'Office Asstt',NULL,2,1,1,NULL,'active',1,NULL,16),(19,'G&G',NULL,3,1,1,NULL,'active',1,NULL,17),(20,'Social Media',NULL,1,1,1,NULL,'active',1,NULL,8),(23,'Graphics',NULL,7,NULL,2,1,'active',1,NULL,NULL);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `departments` with 21 row(s)
--

--
-- Table structure for table `designations`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `shortName` varchar(20) DEFAULT NULL,
  `salaryGrade` varchar(10) DEFAULT NULL,
  `desigLevel` tinyint(4) DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designations`
--

LOCK TABLES `designations` WRITE;
/*!40000 ALTER TABLE `designations` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `designations` VALUES (1,'test','test Designation','A2',2,'1','active','2019-09-04 13:26:12'),(2,'test1','t1','A1',0,'1','active','2019-09-04 13:25:48'),(3,'Jr. Excutive','Jr. Excutive',NULL,NULL,'1','active','2019-09-23 12:01:32'),(4,'Manager','manager','test',1,'1','active','2019-10-09 13:24:15'),(5,'Hr','Hr',NULL,NULL,NULL,'active','2019-10-10 12:35:11');
/*!40000 ALTER TABLE `designations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `designations` with 5 row(s)
--

--
-- Table structure for table `employeeinfo`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employeeinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employeeId` varchar(45) DEFAULT NULL,
  `fkDepartmentId` varchar(45) DEFAULT NULL,
  `workingLocation` varchar(55) NOT NULL,
  `fkDesignation` int(11) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `nickName` varchar(50) DEFAULT NULL,
  `fkEmployeeType` int(11) DEFAULT NULL,
  `email_off` varchar(75) NOT NULL,
  `bloodGroup` varchar(25) NOT NULL,
  `salary` double NOT NULL,
  `supervisor` varchar(255) DEFAULT NULL,
  `probationPeriod` int(11) DEFAULT NULL,
  `actualJoinDate` date DEFAULT NULL,
  `recentJoinDate` date DEFAULT NULL,
  `resignDate` date DEFAULT NULL,
  `weekend` varchar(255) DEFAULT NULL,
  `pfAccountNo` varchar(45) DEFAULT NULL,
  `bankAccountNo` varchar(45) DEFAULT NULL,
  `tinId` varchar(45) DEFAULT NULL,
  `fkReportsTo` int(11) DEFAULT NULL,
  `scheduleInTime` time DEFAULT NULL,
  `scheduleOutTime` time DEFAULT NULL,
  `accessPin` int(11) DEFAULT NULL,
  `fkSalaryGrade` int(11) DEFAULT NULL,
  `consolidatedSalary` float DEFAULT NULL,
  `specialAllowance` int(11) DEFAULT NULL,
  `payroll` tinyint(1) DEFAULT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `fatherName` varchar(255) DEFAULT NULL,
  `motherName` varchar(255) DEFAULT NULL,
  `spouseName` varchar(255) DEFAULT NULL,
  `fkReligion` int(11) DEFAULT NULL,
  `fkNationality` char(3) DEFAULT NULL,
  `presentStreet` mediumtext DEFAULT NULL,
  `presentPS` varchar(45) DEFAULT NULL,
  `presentZipcod` varchar(45) DEFAULT NULL,
  `permanentStreet` mediumtext DEFAULT NULL,
  `permanentPS` varchar(45) DEFAULT NULL,
  `permanentZipcod` varchar(45) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactNo` varchar(18) DEFAULT NULL,
  `alterContactNo` varchar(18) DEFAULT NULL,
  `streetAddress` mediumtext NOT NULL,
  `apartmentUnit` varchar(51) NOT NULL,
  `city` varchar(75) NOT NULL,
  `state` varchar(75) NOT NULL,
  `zipCode` int(11) NOT NULL,
  `homePhone` varchar(15) NOT NULL,
  `alternatePhone` int(15) NOT NULL,
  `nationalId` varchar(45) DEFAULT NULL,
  `maritalStatus` enum('married','unmarried') NOT NULL,
  `fkCompany` int(11) DEFAULT NULL,
  `fkActivationStatus` int(11) DEFAULT NULL,
  `fkUserId` int(11) DEFAULT NULL,
  `fkTeamId` int(11) DEFAULT NULL,
  `practice` int(11) DEFAULT NULL,
  `noOfIncrement` int(11) DEFAULT NULL,
  `fkleaveTeam` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `reportingDeviceId` int(11) DEFAULT NULL,
  `inDeviceNo` int(11) DEFAULT NULL,
  `outDeviceNo` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `log` varchar(255) DEFAULT NULL,
  `e_name` varchar(75) NOT NULL,
  `e_street_address` varchar(75) NOT NULL,
  `e_apartment_unit` varchar(75) NOT NULL,
  `e_city` varchar(45) NOT NULL,
  `e_state` varchar(45) NOT NULL,
  `e_zip_code` varchar(7) NOT NULL,
  `e_phone` varchar(15) NOT NULL,
  `e_alternate_phone` varchar(15) NOT NULL,
  `e_relationship` varchar(115) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employeeinfo`
--

LOCK TABLES `employeeinfo` WRITE;
/*!40000 ALTER TABLE `employeeinfo` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `employeeinfo` VALUES (1,'null','16','7TH Floor Broadcast',10,'Syed','Mohiuddin','Ahmed',NULL,3,'ammy@yahoo.com','O+',50000,'Amir Hossain',3,'2019-10-01',NULL,'2019-10-31','monday',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1988-10-31',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1572499304.amy jackson.jpg','hran@gmail.com','852369','85963214','3719 57th st','84TH AVN 5C','woodside','New York',11377,'6318977690',0,'852147','unmarried',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'Zahir khan','DAHIL ROAD 1ST FLOOR','','BROOKLYN','NY','11218','6464361389','6464740418','Uncle'),(2,NULL,'12','',NULL,'Md. Nurul Muttalib Chowdhury',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(3,NULL,'16','',NULL,'Ashraf Uddin Mahmud',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(4,NULL,'11','',NULL,'Md. Asad Maymuny',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(5,'5','10','',0,'Sayed','Ismat','Toha','Toha',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1952-07-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ismarroha007@gmail.com','01711337276','01726803686','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(6,NULL,'16','',NULL,'Najmul Hasan (Niaj)',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(7,'07','8','',0,'Chinmoy',NULL,'Roy','Chinmoy',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1992-07-18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'chinmoyroy.ete@gmail.com','01723242726','01722412928','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(8,NULL,NULL,'',NULL,'AHMODUL BAROVHUIYA',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(9,NULL,NULL,'',NULL,'MD. HABIBUR RAHMAN',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(10,'10',NULL,'',NULL,'Md.','Raisul','Islam',NULL,NULL,'','',0,'fghgfh',5,'2019-09-01',NULL,'2019-10-08','sunday',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1999-10-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1570697982.Untitled.jpg','ghj@yahoo.com','0174545454','null','gdfgg','rdr','cooo','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(11,'12','16','',0,'Raihan','Ahmed','Chowdhury','Ziko',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1986-09-11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ziko.c4@gmail.com','01780030300','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(12,'14','17','',0,'Mir',NULL,'Ali','Shaukat',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1984-04-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mshaukat_07@yahoo.com','01716333638','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(13,'17','17','',0,'Md. Mushfiqur',NULL,'Rahman','Mushfiq',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1989-11-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mushfiqur.office@gmail.com','01756179081','01680790929','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(14,'19','17','',0,'Shek','Md. Mijanur','Rahman','Mijan',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1987-11-17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mijanur355@yahoo.com','01719448288','0174869166','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(15,'20','19','',0,'Mita','Rane','Das','Mita',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1983-10-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mitarane.totaltvs@gmail.com','01719802284','01735088530','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,8,NULL,NULL,'','','','','','','','',''),(16,'23','17','',0,'Md.','Shamim','Raja','Shamim',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1987-10-18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'sr.shamim87@gmail.com','01921450095','01998905104','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(17,'25','8','',0,'Md.','Niamot','Hasan',NULL,1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1991-11-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shohag.tbn24@gmail.com','0178282731','01920562802','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(18,'26','8','',0,'Md.','Taef','Mia','Taef',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1990-11-30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'taif.totaltvs@gmail.com','01722507043','01922582786','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(19,'27','4','',0,'Sohel','Bin','Sattar','Sohel',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1985-08-25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'sohel.totaltvs@gmail.com','01914118988','01715140650','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(20,'28','4','',0,'Md.','Nur','Alam','Nur',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1996-03-26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nuralam.tbn24@gmail.com','01934953835','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(21,'30','17','',0,'Md.','Arif','Hossain','Arif',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1995-09-23',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'arifhossain24@gmail.com','01751342777','01737188055','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(22,'31','4','',0,'Showrav',NULL,'Mazumder','Showrav',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1989-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'showrav.tbn24@gmail.com','01705347495','01715837476','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(23,'32','16','',0,'Md.','Saadman','Kabir','Saadman',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1994-05-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'md.saadman.kabir@gmail.com','01711083781','01911758617','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(24,'33','19','',0,'Gazi','Rehab','Hossain','Rehab',1,'','',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1991-11-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rehab.eaglebirds9@gmail.com','01786653993','01689577836','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,4,NULL,NULL,'','','','','','','','',''),(25,'34','16','',0,'Kamrul','Islam','Saleh','Saleh',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1984-01-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'k.islamsaleh@gmail.com','01619255858','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(26,'38','16','',0,'Md. Ahasanul','Huq','Bhuiyan','Ahasan',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1985-12-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ahasan.bd123@gmail.com','01972663715','01620465256','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(27,NULL,'16','',NULL,'Antar Shaha',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(28,'40','16','',0,'Sk','Hasanur','Rashid','Rashid',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1984-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rashidhasanur@gmail.com','01675747515','01717401925','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(29,'43','16','',0,'Gulam','Mujtaba','Khan','Mujtaba',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1993-01-25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'khan.phsb@gmail.com','01751823922','01711909631','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(30,'52','18','',0,'Md. Lalon',NULL,NULL,'Lalon',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1968-08-14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'null','01621207474','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(31,NULL,'7','',NULL,'T M SOWROV ISLAM',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(32,'60','5','',0,'Beauty',NULL,'Akter','Beauty',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1986-05-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'beautyakbar@gmail.com','0191667780','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(33,NULL,NULL,'',NULL,'Hurerzahan Bithi',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(34,NULL,NULL,'',NULL,'Sarna Lata Debnath',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(35,'64','5','',0,'Md.','Sagor','Hossain','Sagor',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1986-10-25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rsagor09@gmail.com','01719626019','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(36,'65','8','',0,'Md. Khalid','Walid','Tanvi','Tanvir',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1992-11-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kwtanvir@gmail.com','01911726699',NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(37,'66','8','',0,'Md. Tanjir','Ahamed','Tonmoy','Tonmoy',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1996-09-08',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'tanjir.tonmoy@gmail.com','01911748449','01710171138','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(38,'75','9','',0,'Md.',NULL,'Nurullah','Nurullah',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1992-12-31',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nurullahmahdi@gmail.com','01723423565','01719390873','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(39,NULL,NULL,'',NULL,'Md. Raju',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(40,'85','8','',0,'Faysal','Islam','Bijoy','Bijoy',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1988-12-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'bijoykhan1612@gmail.com','01911812833','01826679370','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(41,'95','15','',0,'Md.','Omar','Faruk','Faruk',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1984-05-22',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'farukmscse@gmail.com','01818125469','01737886726','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(42,'98','4','',0,'Sakhawat','Hosain','Takur','Sakhawat',1,'','',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1991-04-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'sakhwarhosaintakur@gmail.com','01740253161','01787052402','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,4,NULL,NULL,'','','','','','','','',''),(43,'104','14','comilla',16,'Fatematuz','Johora','Tuli','Tuli',1,'borhanyunus@yahoo.com','B-',75555,'AAAAAAA',3,'2019-10-22',NULL,'2019-10-01','saturday',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1991-10-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1572498109.Borhani.jpg','fatematuzjohora804@gmail.com',NULL,'01717172501','fgh','654ud','Epp','ny',1528,'85693214',0,'654564313123','married',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'BORHAN71 YUNUS','DAHIL ROAD 1ST FLOOR','sdfsdfsfd','BROOKLYN','NY','77777','6464740418','6464740418','Brother'),(44,'109','14','7th floor Brodcast',11,'H M A','RD','Sina','Sina',1,'man01@gmail.com','AB+',70000,'AAA',3,'2019-10-22',NULL,NULL,'sunday,tuesday',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1968-10-31',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1572680312.amy jackson.jpg','abusina2015@gmail.com',NULL,'955115','57st','58/xs','Comilla','NY',1256,'852693',0,'9461619849874941541','married',NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(45,'110','8','',0,'Shanzida','Sawly','Shantu','Shanzida',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1994-03-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shanzidatbn24@gmail.com','01717408839','0171450966','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(46,NULL,'16','',NULL,'Sarwar Alam Ridoy',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(47,'121','19','',0,'Md.','Mashuk Ali','Khan','Mashuk',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1930-10-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1570095281.Screenshot (2).png','mmashuk.goandgift.com@','017297865132133','017825444656','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,8,NULL,NULL,'','','','','','','','',''),(48,'127','13','',0,'Md. Rahat',NULL,'Pathan','Rahat',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1986-12-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rahat-pathan@gmail.com','01729700001','01738529706','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(49,'129','8','',0,'Md.','Arafat','Ali','Arafat',1,'','',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1995-10-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'arafatsrdr@gmail.com','01759582858','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,4,NULL,NULL,'','','','','','','','',''),(50,'131','4','',0,'Mulla','Main Uddin','Sumon','Sumon',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1980-03-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'maintotaltvs@gmail.com','01714506966',NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(51,NULL,'16','',NULL,'Imran Khan',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(52,NULL,'16','',NULL,'Arnnab Dutta',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(53,NULL,'16','',NULL,'MD. Russel Shikder Maffel',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(54,'136','14','',0,'Md. Sazzad','Hossain','Rasel','Rasel',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1991-10-29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'sazzad.2910@gmail.com','01742785287',NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(55,NULL,NULL,'',NULL,'Md. Mojnu Alam',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(56,NULL,'19','',NULL,'NAHIDA AKTER',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,8,NULL,NULL,'','','','','','','','',''),(57,'140','15','',0,'Shathi',NULL,'Akter','Shathi',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1994-08-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shathiakter1212@gmail.com','01940172293','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(58,NULL,'16','',NULL,'Joynab Sultana',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(59,'143','17','',1,'Uzzal','Chandra','Sarker','Uzzal',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1999-09-24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'uzzalsarker3@gmail.com','0178526344','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(60,NULL,'16','',NULL,'Khandaker Affan Ahmed',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(61,NULL,'16','',NULL,'Pavel Hassan',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(62,NULL,'16','',NULL,'Md Faisal Bin Aziz',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(63,NULL,'16','',NULL,'Ashsanul Alam',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(64,NULL,'4','',NULL,'AMAN ULLAH',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(65,'150','5','',0,'Jannatul','Fardous','Munny','Munny',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1994-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'munnash680@gmail.com','01748140663','01713047533','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(66,'154','4','',0,'Md.',NULL,'Anis','Anis',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1997-12-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'null','01986759487','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(67,'115','17','',0,'Md.','Abu','salam','Salam',1,'','',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1995-03-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mas.bd100@gmail.com','01764695621','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(68,'156','5','',0,'Md.','Faisal','Siddique','Borno',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1987-11-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shuvoborno@gmail.com','01617170317','01712903907','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(69,'157','5','',0,'Md.',NULL,'Alamin','Alamin',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1987-04-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'khanalamin1987@gmail.com','01711870293','01914099970','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(70,'159','9','',0,'Mowlana','Mahmudul','Hasan','Mahmudul',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1982-03-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mahmud.tbn24@gmail.com','01712646213','01723423565','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(71,'160','5','',0,'Khadiza','Akter','Sakila','Sakila',2,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1997-06-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ialisha345@ymail.com','01624354707','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(72,'161','5','',0,'Ayesha','Ahsan','Aurpa','Aurpa',2,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1998-11-09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ahsanaurpa77@gmail.com','01911625661','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(73,'null','null','',0,'Saifuddin',NULL,'Faysal','Faysal',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1989-11-26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'saifuddinaysal@gmail.com','01795326402','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(74,'163','5','',0,'Md. Forkan',NULL,'Alam','Forkan',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1988-01-30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'forkan.letter2@gmail.com','01848370524','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(75,'165','16','',0,'Rabeya',NULL,'Sarker','Rabu',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1984-08-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rabeyasarker99@gmail.com','01921535572','01915537172','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(76,'167','4','',0,'Md.',NULL,'Hadiuzzaman','Hadi',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1997-07-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'hadiuzzaman.cs@gmail.com','01854264591','01932622914','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(77,'170','7','',0,'Takiya','Nousin','Chowdhury','Takiya',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1997-12-22',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'takiya.nousin5202@gmail.com','01600193474','0703601378','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(78,'173','4','',0,'Farhan','Hasin','Chowdhury','Farhan',0,'','',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1996-11-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'farhan.hasin007@gmail.com','01941900663','01703601378','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,4,NULL,NULL,'','','','','','','','',''),(79,'174','5','',0,'Azimur','Rasid','Kanak','Kanak',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1990-08-21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ar_kanak@yahoo.com','01674176598','01928391393','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(80,'175','10','',0,'Md.Salam','Pathan','Rasel','Rasel',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1990-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'salamrassel@gmail.com','01963890929','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(81,'176','5','',0,'Md.','Nafisul','Imran','Nafis',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1986-07-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nafis806@gmail.com','01783801499',NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(82,NULL,NULL,'',NULL,'Istiaq Ahmed',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(83,'178','5','',0,'Rizwan','Quader','Kachi','Kachi',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1981-08-22',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rq.kachi@gmail.com','01715297120','01715297120','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(84,'179','8','',0,'Pinto',NULL,'Dey','Pinto',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1995-08-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pintodey441@gmail.com','01830328441','01822693344','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(85,'181','5','',0,'Sumya',NULL,'Tabassum','Sumya',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1990-12-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'sumya.to30@gmail.com','01755513081','01713364857','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(86,'182','6','',0,'Md.','Abdul','Baki','Baki',0,'','',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1992-12-31',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'md.baki@hotmail.com','01740958555','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(87,'184','10','',0,'Lutfor',NULL,'Rahman','Lutfor',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1989-07-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'lutforrahman18@gmail.com','01918457642','01911401286','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(88,'185','5','',0,'Md. Rana',NULL,'Raihan','Rana',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1982-04-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ranaraihan82@gmail.com','01778851809','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(89,'186','4','',0,'Kamrul','Hasan','Imran','Imran',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1993-12-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'imrankamrulhasan@gmail.com','01881719099','01921508003','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(90,'187','8','',0,'Md.','Shamimur','Rahman','Shamim',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1984-12-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shamim.itv@gmail.com','01856798393','01556437210','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(91,NULL,'8','',NULL,'K M Mostafa Ali Mazumder',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(92,'null','null','',0,'Israt','Jahan','Mukti','Mukti',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1994-12-16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'isratmukti94@gmail.com','01771413282','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(93,'190','4','',0,'Md','Nurul','Alam','Sony',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1990-01-25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nasony068@gmail.com','01724513947','01787372309','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(94,'192','5','',0,'Parvin','Sultana','Kolly','Kolly',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1984-12-09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pr.lolly@gmail.com','01719120855','01616606141','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(95,'194','5','',0,'Ashik',NULL,'Ahamed','Ashik',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1994-01-07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ashik.tbn24@gmail.com','01737575127','01727380788','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(96,'197','17','',0,'Md.','Arif','Mia','Arif',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1996-11-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mdarifmia16@gmail.com','01950534618','01986759487','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(97,'198','13','',0,'Zillur','Rahman','Jonny','Jonny',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1985-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'jonybangla@yahoo.com','01912044032','01799665990','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(98,'200','4','',0,'Thofazzal','Hossain','Thakur','Topu',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1996-11-17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'toputhakur@gmail.com','01857966049','01857966049','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(99,'201','4','',0,'Md. Abu','Ahsan','Khan','Rakib',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1998-08-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rakib.khan2822@gmail.com','01521252822','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(100,'202','5','',0,'Nazmul',NULL,'Ashraf','Nazmul',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1965-03-08',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nazmul.ashraf@gmail.com','017130 47  533','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(101,'204','5','',0,'Sakil','Bin','Mustak','Sakil',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1976-06-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shakil.journalist@gmail.com','01740481860','0195224907','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(102,'205','5','',0,'Md Yousof',NULL,'Khaled','Khaled',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1979-07-25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'khaled.yousuf79@gmail.com','01716803880','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(103,'207','5','',0,'Rakibul',NULL,'Alam','Romi',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1985-07-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'rakibul.romi@gmail.com','01718526320','01793594320`','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(104,'210','5','',0,'Mohiuddin','Ahmed','Sagor','Sagor',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1992-11-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mohiuddinahmedsagor@gmail.com','01737991365','01772556334','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(105,'211','5','',0,'Mst.','Setara','Naznin','Setara',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1994-03-11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nazninsetara386@gmail.com','01783681781','01714928457','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(106,'212','10','',0,'Abu',NULL,'Hanif','Hanif',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1988-10-14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'abuhanif70044@gmail.com','01719467862','01713527536','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(107,'213','18','',1,'Md.','Abdul','Latif','Latif',1,'','',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1979-07-25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'latif','01716803880',NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(108,'215','4','',0,'Masum',NULL,'Khan','Masum',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1993-06-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'masum.tbn24@gmail.com','01747953122','01726021754','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(109,'217','6','',0,'Md Bappi',NULL,'Miah','Bappi',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1990-02-07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'bappi.magura@gmail.com','01675633934','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(110,'218','5','',0,'Md. Medul',NULL,'Islam','Mredul',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1992-11-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'newsmredul@gmail.com','01919480070','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(111,'219','4','',0,'Purnendu','Das','Bapon','Bapon',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1992-06-26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'bapondas017@gmail.com','01629783848','01624783848','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(112,'221','6','',0,'Md.','Abir','Hossain','Abir',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1989-12-31',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'abirhossain09@gmail.com','01913447419','01724780643','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(113,'224','10','',0,'Subot','Chandra','Roy','Subot',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1991-11-08',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'subotroy.2011@gmail.com','01728866557','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(114,NULL,'20','',NULL,'Syed Sadman Ahmed',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(115,'226','16','',0,'shohaly','Jahan','Noureen','Noureen',0,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'F','1993-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shohaly31@yahoo.com','01741161257','null','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(116,'TIS-227','15','asd',3,'MD . MUJTABA','RAFID','RUMI',NULL,1,'asdasd','O+',300,NULL,3,'2019-07-26',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1994-12-30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1572697054.73120120_107071117400882_8736822791350779904_n.jpg','mujtaba.rumi1@gmail.com',NULL,'null','','','','',0,'',0,'null','',NULL,1,3,NULL,NULL,NULL,NULL,NULL,NULL,3,3,4,NULL,NULL,'dsasd','asdasd','asd','asd','asd','asd','asd','asd','asd'),(117,'242','10','',6,'Md. Ershad','Prodhan','Rahat',NULL,1,'','',0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1987-10-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1571653757.b737a96b-3f3f-417e-9bf6-c0dadd8cfdf1-large16x9_Fallfoliage.jpg','ers@gmail.com','01731301714',NULL,'','','','',0,'',0,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(118,NULL,'5','',NULL,'Minthal Ahmed Masum',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,4,NULL,NULL,'','','','','','','','',''),(119,NULL,NULL,'',NULL,'Farzana Yeasmin Nesa',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(120,'302','5','',0,'Mohammed',NULL,'Mostofa','Adib',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1978-07-30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'adibmostofa@gmail.com','01552540048','01711074507','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(121,NULL,'19','',NULL,'Jesmin Jahan ( Jhuma)',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,8,NULL,NULL,'','','','','','','','',''),(122,NULL,NULL,'',NULL,'Sheikh Md. Osman Goni',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(123,NULL,NULL,'',NULL,'Shafat Bin Farid',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',''),(124,NULL,'8','',NULL,'Lotus Bhowmik',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(125,'307','8','',0,'Md.','Shariar','Ahmed','Badhon',1,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1995-04-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shariar_badhon@gmail.com','01671997823','01971619370','','','','',0,'',0,NULL,'',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(126,NULL,'8','',NULL,'Tusher Sarker',NULL,NULL,NULL,NULL,'','',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','',0,'',0,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,6,NULL,NULL,'','','','','','','','',''),(127,'10001',NULL,'',NULL,'Md.','Amir','Hossain',NULL,NULL,'','',0,'aaaa',6,'2019-10-09',NULL,'2019-10-15','saturday',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'M','1987-10-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1572089698.Untitled.jpg','amir@gmail.com','0172985467','0123456789','Comilla','7854','Coo','TG',1245,'456456',0,'123456789','unmarried',NULL,1,4,NULL,NULL,NULL,NULL,1,NULL,NULL,11,12,NULL,NULL,'','','','','','','','','');
/*!40000 ALTER TABLE `employeeinfo` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `employeeinfo` with 127 row(s)
--

--
-- Table structure for table `employeetypes`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employeetypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeTitle` varchar(50) NOT NULL,
  `createdBy` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `typeTitle` (`typeTitle`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employeetypes`
--

LOCK TABLES `employeetypes` WRITE;
/*!40000 ALTER TABLE `employeetypes` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `employeetypes` VALUES (1,'Fulltime',1,'2018-07-18 18:00:00'),(2,'Part TIme',1,'2018-11-02 06:38:58'),(3,'Contractual',1,'2018-11-02 06:38:58');
/*!40000 ALTER TABLE `employeetypes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `employeetypes` with 3 row(s)
--

--
-- Table structure for table `holidaycategories`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holidaycategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryCode` varchar(5) NOT NULL,
  `categoryName` varchar(20) NOT NULL,
  `dayAllow` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `categoryName` (`categoryName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holidaycategories`
--

LOCK TABLES `holidaycategories` WRITE;
/*!40000 ALTER TABLE `holidaycategories` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `holidaycategories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `holidaycategories` with 0 row(s)
--

--
-- Table structure for table `holidays`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkEmployeeId` int(20) NOT NULL,
  `applicationDate` date NOT NULL,
  `fkHolidayCategory` int(11) NOT NULL,
  `applicationStatus` varchar(10) DEFAULT NULL COMMENT 'Pending, Approved, Rejected',
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `noOfDays` int(11) DEFAULT NULL,
  `fkRecommendedBy` int(20) DEFAULT NULL,
  `fkApproveBy` int(20) DEFAULT NULL,
  `remarks` tinytext DEFAULT NULL,
  `rejectCause` mediumtext DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_hrmLeavesApprovedBt` (`fkApproveBy`),
  KEY `FK_hrmLeavesLeaveCategory` (`fkHolidayCategory`),
  KEY `FK_hrmLeavesRecommendedBy` (`fkRecommendedBy`),
  KEY `UK_hrmLeaves` (`fkEmployeeId`,`applicationDate`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holidays`
--

LOCK TABLES `holidays` WRITE;
/*!40000 ALTER TABLE `holidays` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `holidays` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `holidays` with 0 row(s)
--

--
-- Table structure for table `holiday_calander`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holiday_calander` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holidayName` varchar(255) DEFAULT 'NULL',
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `noOfDays` int(11) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL COMMENT 'Pending, Approved, Rejected',
  `createdBy` int(11) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `modified_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holiday_calander`
--

LOCK TABLES `holiday_calander` WRITE;
/*!40000 ALTER TABLE `holiday_calander` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `holiday_calander` VALUES (15,'NULL','2019-10-06','2019-10-07',1,'test','Approved',1,'2019-10-06 12:26:43',NULL,NULL);
/*!40000 ALTER TABLE `holiday_calander` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `holiday_calander` with 1 row(s)
--

--
-- Table structure for table `leavecategories`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leavecategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryCode` varchar(5) NOT NULL,
  `categoryName` varchar(20) NOT NULL,
  `dayAllow` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `categoryName` (`categoryName`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leavecategories`
--

LOCK TABLES `leavecategories` WRITE;
/*!40000 ALTER TABLE `leavecategories` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `leavecategories` VALUES (1,'cs','Casual',NULL,NULL,1,'2019-08-16 11:47:30'),(2,'sick','Sick',NULL,NULL,1,'2019-08-16 11:47:33'),(3,'marri','Marriage',NULL,NULL,1,'2019-08-16 11:47:36'),(4,'LWP','Leave with out pay',NULL,NULL,1,'2019-08-16 11:47:39'),(6,'earn','EarnLeave',NULL,NULL,1,'2019-09-05 13:14:43'),(7,'mater','Maternity',NULL,NULL,1,'2019-09-05 13:14:48'),(8,'pater','Paternity',NULL,NULL,1,'2019-09-05 13:16:23'),(9,'anual','AnualLeave',NULL,NULL,1,'2019-09-05 13:16:27');
/*!40000 ALTER TABLE `leavecategories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `leavecategories` with 8 row(s)
--

--
-- Table structure for table `leavelimit`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leavelimit` (
  `leavelimitId` int(11) NOT NULL AUTO_INCREMENT,
  `fkemployeeId` int(11) NOT NULL,
  `totalLeave` int(11) NOT NULL,
  `leaveTaken` int(11) NOT NULL,
  `year` int(11) DEFAULT NULL,
  PRIMARY KEY (`leavelimitId`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leavelimit`
--

LOCK TABLES `leavelimit` WRITE;
/*!40000 ALTER TABLE `leavelimit` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `leavelimit` VALUES (1,116,23,0,2019),(2,86,0,0,2019),(3,98,0,0,2019),(4,50,0,0,2019),(5,66,0,0,2019),(6,4,0,0,2019),(7,2,0,0,2019),(8,42,0,0,2019),(9,78,0,0,2019),(10,49,0,0,2019),(11,24,0,0,2019),(12,117,0,0,2019),(13,41,0,0,2019),(14,118,0,0,2019),(15,6,0,0,2019),(16,53,0,0,2019),(17,115,0,0,2019),(18,127,0,0,2019),(19,10,0,0,2019);
/*!40000 ALTER TABLE `leavelimit` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `leavelimit` with 19 row(s)
--

--
-- Table structure for table `leaves`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkEmployeeId` int(20) NOT NULL,
  `applicationDate` date NOT NULL,
  `fkLeaveCategory` int(11) NOT NULL,
  `applicationStatus` varchar(10) DEFAULT NULL COMMENT 'Pending, Approved, Rejected',
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `noOfDays` int(11) DEFAULT NULL,
  `fkRecommendedBy` int(20) DEFAULT NULL,
  `fkApproveBy` int(20) DEFAULT NULL,
  `remarks` tinytext DEFAULT NULL,
  `rejectCause` mediumtext DEFAULT NULL,
  `departmentHeadApproval` int(11) DEFAULT NULL COMMENT '	0=rejected,id=empId(accepted))',
  `HR_adminApproval` int(11) DEFAULT NULL COMMENT '0=rejected,id=empId(accepted))	',
  `createdBy` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_hrmLeavesApprovedBt` (`fkApproveBy`),
  KEY `FK_hrmLeavesLeaveCategory` (`fkLeaveCategory`),
  KEY `FK_hrmLeavesRecommendedBy` (`fkRecommendedBy`),
  KEY `UK_hrmLeaves` (`fkEmployeeId`,`applicationDate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaves`
--

LOCK TABLES `leaves` WRITE;
/*!40000 ALTER TABLE `leaves` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `leaves` VALUES (24,2,'2019-11-04',1,'Approved','2019-10-22','2019-10-23',1,NULL,NULL,NULL,NULL,1,1,1,'2019-11-04 09:59:37'),(25,3,'2019-11-04',6,'Approved','2019-11-12','2019-12-06',2,NULL,NULL,NULL,NULL,1,1,1,'2019-11-04 10:00:03'),(26,116,'2019-11-04',1,'Approved','2019-11-01','2019-11-01',1,NULL,NULL,NULL,NULL,1,1,1,'2019-11-04 10:08:07'),(27,116,'2019-11-05',1,'Approved','2019-11-01','2019-11-01',1,NULL,NULL,NULL,NULL,1,1,1,'2019-11-05 07:46:05');
/*!40000 ALTER TABLE `leaves` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `leaves` with 4 row(s)
--

--
-- Table structure for table `organizationcalander`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organizationcalander` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `purpose` varchar(128) DEFAULT NULL,
  `noOfDays` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organizationcalander`
--

LOCK TABLES `organizationcalander` WRITE;
/*!40000 ALTER TABLE `organizationcalander` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `organizationcalander` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `organizationcalander` with 0 row(s)
--

--
-- Table structure for table `shift`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shift` (
  `shiftId` int(11) NOT NULL AUTO_INCREMENT,
  `inTime` time DEFAULT NULL,
  `outTime` time DEFAULT NULL,
  `shiftName` varchar(100) DEFAULT NULL,
  `crateBy` int(11) DEFAULT NULL,
  `fkcompanyId` int(11) DEFAULT NULL,
  `fkDepartmentId` int(11) DEFAULT NULL,
  `originalInTime` time DEFAULT NULL,
  `originalOutTime` time DEFAULT NULL,
  PRIMARY KEY (`shiftId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shift`
--

LOCK TABLES `shift` WRITE;
/*!40000 ALTER TABLE `shift` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `shift` VALUES (1,'12:00:00','15:00:00','web dev-1',1,NULL,15,NULL,NULL),(2,'16:00:00','23:00:00','web dev - 2',1,NULL,15,NULL,NULL),(3,'01:00:00','17:00:00','call-1',1,NULL,16,NULL,NULL);
/*!40000 ALTER TABLE `shift` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `shift` with 3 row(s)
--

--
-- Table structure for table `shiftlog`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shiftlog` (
  `shiftlogId` int(11) NOT NULL AUTO_INCREMENT,
  `fkemployeeId` int(11) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `fkshiftId` int(11) DEFAULT NULL,
  `multipleShift` datetime DEFAULT NULL,
  `weekend` varchar(255) DEFAULT NULL,
  `inTime` time DEFAULT NULL,
  `outTime` time DEFAULT NULL,
  `adjustmentDate` date DEFAULT NULL,
  `holiday` date DEFAULT NULL,
  `swapWtihDate` date DEFAULT NULL,
  PRIMARY KEY (`shiftlogId`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shiftlog`
--

LOCK TABLES `shiftlog` WRITE;
/*!40000 ALTER TABLE `shiftlog` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `shiftlog` VALUES (6,26,'2019-11-01','2019-11-01',3,NULL,'2019-11-01','01:00:00','17:00:00',NULL,NULL,NULL),(8,11,'2019-11-01','2019-11-01',3,NULL,'2019-11-01','01:00:00','17:00:00',NULL,NULL,NULL),(9,3,'2019-11-01','2019-11-01',3,NULL,NULL,'01:00:00','17:00:00',NULL,NULL,NULL),(10,25,'2019-11-01','2019-11-01',3,NULL,'2019-11-01','01:00:00','17:00:00',NULL,NULL,NULL),(11,3,'2019-11-01','2019-11-01',3,NULL,'2019-11-01','01:00:00','17:00:00',NULL,NULL,NULL),(15,3,'2019-11-04','2019-11-04',3,NULL,NULL,'01:00:00','17:00:00',NULL,NULL,NULL),(17,3,'2019-11-04','2019-11-04',3,NULL,'2019-11-04','01:00:00','17:00:00',NULL,NULL,NULL),(18,23,'2019-11-04','2019-11-04',3,NULL,NULL,'01:00:00','17:00:00',NULL,NULL,NULL),(19,3,'2019-11-04','2019-11-04',3,NULL,NULL,'01:00:00','17:00:00',NULL,NULL,NULL),(20,23,'2019-11-04','2019-11-04',3,NULL,NULL,'01:00:00','17:00:00',NULL,NULL,NULL),(21,25,'2019-11-04','2019-11-04',3,NULL,'2019-11-04','01:00:00','17:00:00',NULL,NULL,NULL);
/*!40000 ALTER TABLE `shiftlog` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `shiftlog` with 11 row(s)
--

--
-- Table structure for table `static_rosterlog`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `static_rosterlog` (
  `rosterLogId` int(11) NOT NULL AUTO_INCREMENT,
  `fkemployeeId` int(11) NOT NULL,
  `day` varchar(255) NOT NULL,
  `fkshiftId` int(11) DEFAULT NULL,
  `weekend` varchar(255) DEFAULT NULL,
  `inTime` time DEFAULT NULL,
  `outTime` time DEFAULT NULL,
  PRIMARY KEY (`rosterLogId`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `static_rosterlog`
--

LOCK TABLES `static_rosterlog` WRITE;
/*!40000 ALTER TABLE `static_rosterlog` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `static_rosterlog` VALUES (34,3,'Sunday',3,NULL,'01:00:00','17:00:00'),(35,6,'Sunday',3,NULL,'01:00:00','17:00:00'),(36,3,'Friday',3,NULL,'01:00:00','17:00:00'),(37,11,'Friday',3,NULL,'01:00:00','17:00:00'),(40,25,'Monday',3,'Monday','01:00:00','17:00:00'),(43,3,'Monday',3,NULL,'01:00:00','17:00:00'),(44,23,'Monday',3,NULL,'01:00:00','17:00:00');
/*!40000 ALTER TABLE `static_rosterlog` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `static_rosterlog` with 7 row(s)
--

--
-- Table structure for table `swap_details`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `swap_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `swap_by` int(11) DEFAULT NULL COMMENT 'who applied',
  `swap_by_date` date DEFAULT NULL,
  `swap_by_shift` int(11) DEFAULT NULL,
  `swap_by_inTime` time DEFAULT NULL,
  `swap_by_outTime` time DEFAULT NULL,
  `swap_for` int(11) DEFAULT NULL COMMENT 'who accepted to swap',
  `swap_for_date` date DEFAULT NULL,
  `swap_for_shift` int(11) DEFAULT NULL,
  `swap_for_inTime` time DEFAULT NULL,
  `swap_for_outTime` time DEFAULT NULL,
  `departmentHeadApproval` int(11) DEFAULT NULL COMMENT '0=rejected,id=empId(accepted))',
  `HR_adminApproval` int(11) DEFAULT NULL COMMENT '0=rejected,id=empId(accepted))',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `swap_details`
--

LOCK TABLES `swap_details` WRITE;
/*!40000 ALTER TABLE `swap_details` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `swap_details` VALUES (8,116,'2019-10-05',4,'12:00:00','20:00:00',41,'2019-10-06',4,'12:00:00','20:00:00',116,0,116,'2019-10-09 09:46:07',NULL,NULL),(9,116,'2019-10-06',4,'12:00:00','20:00:00',57,'2019-10-07',4,'12:00:00','20:00:00',0,NULL,116,'2019-10-09 09:47:09',NULL,NULL),(10,116,'2019-10-20',4,'12:00:00','20:00:00',116,'2019-10-10',4,'12:00:00','20:00:00',116,116,116,'2019-10-09 09:53:09',NULL,NULL),(11,116,'2019-10-19',4,'12:00:00','20:00:00',33,'2019-10-13',7,'08:00:00','15:00:00',116,116,116,'2019-10-17 09:51:36',NULL,NULL);
/*!40000 ALTER TABLE `swap_details` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `swap_details` with 4 row(s)
--

--
-- Table structure for table `team`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `teamId` int(11) NOT NULL AUTO_INCREMENT,
  `fkCompanyId` int(11) NOT NULL,
  `fkDepartmentId` int(11) DEFAULT NULL,
  `teamName` varchar(255) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`teamId`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `team` with 0 row(s)
--

--
-- Table structure for table `team_members`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_members` (
  `teamMemberId` int(11) NOT NULL AUTO_INCREMENT,
  `fkTeamId` int(11) NOT NULL,
  `fkemployeeId` int(11) NOT NULL,
  `assignBy` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`teamMemberId`)
) ENGINE=InnoDB AUTO_INCREMENT=5449 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `team_members` with 0 row(s)
--

--
-- Table structure for table `time_swap`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_swap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkEmployeeId` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `old_inTime` time DEFAULT NULL,
  `accessTime` time DEFAULT NULL,
  `new_inTime` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `departmentHeadApproval` int(11) DEFAULT NULL COMMENT '0=rejected,id=empId(accepted))',
  `HR_adminApproval` int(11) DEFAULT NULL COMMENT '0=rejected,id=empId(accepted))',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`fkEmployeeId`,`date`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_swap`
--

LOCK TABLES `time_swap` WRITE;
/*!40000 ALTER TABLE `time_swap` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `time_swap` VALUES (20,116,'2019-10-15','12:00:00','16:12:00',NULL,'2019-10-15 10:10:08',NULL,NULL,NULL,116,116);
/*!40000 ALTER TABLE `time_swap` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `time_swap` with 1 row(s)
--

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userName` varchar(100) DEFAULT NULL,
  `contactNo` varchar(18) DEFAULT NULL,
  `fkUserType` varchar(12) DEFAULT NULL,
  `fkCompany` int(11) DEFAULT NULL,
  `fkActivationStatus` int(11) DEFAULT NULL,
  `registrationdate` timestamp NULL DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `rememberToken` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activationStatus_fk_idx` (`fkActivationStatus`),
  KEY `userTypes_fk_idx` (`fkUserType`),
  CONSTRAINT `activationStatus_fk` FOREIGN KEY (`fkActivationStatus`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `userTypes_fk` FOREIGN KEY (`fkUserType`) REFERENCES `usertypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'admin@gmail.com','$2y$10$QvZbuhrxS/Lme/7BVJKctupS1mZ34qmcqLpCEG6ne0BxZ5DLY4bX2','admin',NULL,'admin',NULL,1,NULL,NULL,NULL),(3,'mujtaba.rumi1@gmail.com','$2y$10$Z/4ke36BN3cSjd.LGxCCkOhM/yOV23XYRjPV9L664nUkmQn2..CYK','md.mujtaba rafid rumi','01680674598','emp',NULL,1,'2019-07-29 18:00:00',NULL,NULL),(4,'r@r.com','$2y$10$EJOAVdhggplD8qX0YyJqvugDEcILzse0XoegWtCvUMO46WRFhCCoO','tesr',NULL,'emp',NULL,1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 3 row(s)
--

--
-- Table structure for table `usertypes`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usertypes` (
  `id` varchar(12) NOT NULL,
  `typeTitle` varchar(45) NOT NULL COMMENT 'admin = Administrative, operat = Operational, guest = Guest , emp = Employee',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usertypes`
--

LOCK TABLES `usertypes` WRITE;
/*!40000 ALTER TABLE `usertypes` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `usertypes` VALUES ('admin','Administrator'),('emp','Employee');
/*!40000 ALTER TABLE `usertypes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `usertypes` with 2 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Wed, 06 Nov 2019 12:50:15 +0600
