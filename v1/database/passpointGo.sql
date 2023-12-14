-- -------------------------------------------------------------
-- TablePlus 5.6.2(516)
--
-- https://tableplus.com/
--
-- Database: passpointGo
-- Generation Time: 2023-12-13 9:36:24.1450 AM
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `AccountSecurity` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `activationCode` varchar(255) DEFAULT NULL,
  `activationCodeExpirationDate` varchar(255) DEFAULT NULL,
  `authType` varchar(255) DEFAULT NULL,
  `createdBy` varchar(255) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otpExpiration` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `uniqueId` varchar(255) DEFAULT NULL,
  `updatedBy` varchar(255) DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;;

CREATE TABLE `activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  `formattted_date` varchar(55) DEFAULT NULL,
  `userid` int DEFAULT NULL,
  `ipAddress` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=498 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `FraudReport` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `createdBy` varchar(255) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `message` longtext,
  `status` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `uniqueId` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;;

CREATE TABLE `kyc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userid` int DEFAULT NULL,
  `identityDocumentFile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `identityDocumentType` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `addressDocumentType` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `addressDocumentFile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `companyAddress` text,
  `openingDay` varchar(10) DEFAULT NULL,
  `closingDay` varchar(10) DEFAULT NULL,
  `openingHour` varchar(10) DEFAULT NULL,
  `closingHour` varchar(10) DEFAULT NULL,
  `status` int DEFAULT '0',
  `websiteURL` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`) USING BTREE,
  CONSTRAINT `kyc_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `kycDocs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kycId` int NOT NULL,
  `docName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `docFile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `docType` text,
  PRIMARY KEY (`id`),
  KEY `kycid` (`kycId`) USING BTREE,
  CONSTRAINT `kycDocs_ibfk_1` FOREIGN KEY (`kycId`) REFERENCES `kyc` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `MainServices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text,
  `category_id` text,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `Notifications` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `createdDate` datetime DEFAULT NULL,
  `message` longtext,
  `messageId` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `uniqueId` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;;

CREATE TABLE `Permission` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `createdBy` bigint DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `menuName` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `permissionType` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `updatedBy` bigint DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;;

CREATE TABLE `PreviousPasswords` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `createdDate` datetime DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `userId` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;;

CREATE TABLE `profileContact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `profile_id` int NOT NULL,
  `social_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `social_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `profileDesc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `profile_id` int NOT NULL,
  `service_description` text,
  `headline` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`),
  CONSTRAINT `profileDesc_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `publicProfile` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `profileimg` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userid` int DEFAULT NULL,
  `logo` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `publicProfile` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userid` int DEFAULT NULL,
  `logo` longtext,
  `aboutBusiness` text,
  `companyEmail` varchar(55) DEFAULT NULL,
  `companyPhone` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `companyAddress` text,
  `openingDay` varchar(10) DEFAULT NULL,
  `closingDay` varchar(10) DEFAULT NULL,
  `openingHour` varchar(10) DEFAULT NULL,
  `closingHour` varchar(10) DEFAULT NULL,
  `hasWebContact` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`) USING BTREE,
  CONSTRAINT `publicprofile_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `Role` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `createdBy` bigint DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `merchantId` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `updatedBy` bigint DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;;

CREATE TABLE `RolePermission` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `createdDate` datetime DEFAULT NULL,
  `permissionId` bigint DEFAULT NULL,
  `roleId` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;;

CREATE TABLE `servicePricing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `service_id` int NOT NULL,
  `priceName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `price` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`) USING BTREE,
  CONSTRAINT `servicePricing_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `profile_id` int NOT NULL,
  `service_name` varchar(100) DEFAULT NULL,
  `service_description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `service_banner_url` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `isFeatured` int DEFAULT '0',
  `priceType` varchar(55) DEFAULT NULL,
  `addVat` int DEFAULT '0',
  `priceModel` varchar(55) DEFAULT NULL,
  `serviceType` varchar(55) DEFAULT NULL,
  `serviceCurrency` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`),
  CONSTRAINT `services_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `publicProfile` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `UserRole` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `createdDate` datetime DEFAULT NULL,
  `roleId` bigint DEFAULT NULL,
  `userId` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bussinesName` varchar(255) NOT NULL DEFAULT '',
  `businessType` varchar(55) NOT NULL DEFAULT '',
  `businessIndustry` varchar(55) NOT NULL DEFAULT '',
  `rcNumber` varchar(255) DEFAULT '',
  `firstname` varchar(255) NOT NULL DEFAULT '',
  `lastname` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(15) NOT NULL DEFAULT '',
  `userType` varchar(255) NOT NULL DEFAULT '0',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `state` varchar(255) DEFAULT '',
  `lga` varchar(255) DEFAULT '',
  `country` varchar(255) DEFAULT '',
  `date_created` varchar(255) DEFAULT '',
  `status` varchar(1) NOT NULL DEFAULT '0',
  `regStage` varchar(1) NOT NULL DEFAULT '0',
  `otp` varchar(255) DEFAULT '',
  `otpStatus` int NOT NULL DEFAULT '0',
  `api_key` varchar(100) NOT NULL DEFAULT '',
  `merchant_id` varchar(100) NOT NULL DEFAULT '',
  `kycStatus` int DEFAULT '0',
  `2fa` tinyint(1) NOT NULL DEFAULT '0',
  `2faSecret` varchar(55) DEFAULT NULL,
  `2faLink` varchar(255) DEFAULT NULL,
  `passwordChanged` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_email_idx` (`email`) USING BTREE,
  KEY `username_idx` (`bussinesName`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



/*! Tables Update */;


CREATE TABLE `team_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `team_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_title` varchar(255) DEFAULT '',
  `role_desc` varchar(255) DEFAULT '',
  `role_permission` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;