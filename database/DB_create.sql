SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `cms` ;
CREATE SCHEMA IF NOT EXISTS `cms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
SHOW WARNINGS;
USE `cms` ;

-- -----------------------------------------------------
-- Table `Element`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Element` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Element` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `identifier` VARCHAR(45) NULL,
  `cms_class` VARCHAR(45) NOT NULL,
  `usable` TINYINT(1) NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Attribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Attribute` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Attribute` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `label` VARCHAR(45) NULL,
  `required` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `ElementAttribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ElementAttribute` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `ElementAttribute` (
  `element` INT NOT NULL,
  `attribute` INT NOT NULL,
  PRIMARY KEY (`element`, `attribute`),
  CONSTRAINT `elementElementAttributeKey`
    FOREIGN KEY (`element`)
    REFERENCES `Element` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `attributeElementAttributeKey`
    FOREIGN KEY (`attribute`)
    REFERENCES `Attribute` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `attributeTagAttributeKey_idx` ON `ElementAttribute` (`attribute` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Class`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Class` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Class` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `style` LONGTEXT NOT NULL,
  `desciption` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Content`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Content` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Content` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `value` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `ElementContent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ElementContent` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `ElementContent` (
  `element` INT NOT NULL,
  `content` INT NOT NULL,
  PRIMARY KEY (`element`, `content`),
  CONSTRAINT `elementElementContentKey`
    FOREIGN KEY (`element`)
    REFERENCES `Element` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `contentElementContentKey`
    FOREIGN KEY (`content`)
    REFERENCES `Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `contentElementContentKey_idx` ON `ElementContent` (`content` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `ContentAttribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ContentAttribute` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `ContentAttribute` (
  `content` INT NOT NULL,
  `attribute` INT NOT NULL,
  PRIMARY KEY (`content`, `attribute`),
  CONSTRAINT `contentContentAttributeKey`
    FOREIGN KEY (`content`)
    REFERENCES `Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `attribute`
    FOREIGN KEY (`attribute`)
    REFERENCES `Attribute` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `attribute_idx` ON `ContentAttribute` (`attribute` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `ContentClass`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ContentClass` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `ContentClass` (
  `content` INT NOT NULL,
  `class` INT NOT NULL,
  PRIMARY KEY (`content`, `class`),
  CONSTRAINT `contentContentClassKey`
    FOREIGN KEY (`content`)
    REFERENCES `Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `classContentClassKey`
    FOREIGN KEY (`class`)
    REFERENCES `Class` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `classContentClassKey_idx` ON `ContentClass` (`class` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Page` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `title` VARCHAR(45) NULL,
  `parent` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `PageContent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `PageContent` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `PageContent` (
  `page` INT NOT NULL,
  `content` INT NOT NULL,
  PRIMARY KEY (`page`, `content`),
  CONSTRAINT `pagePageContentKey`
    FOREIGN KEY (`page`)
    REFERENCES `Page` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `contentPageContentKey`
    FOREIGN KEY (`content`)
    REFERENCES `Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `contentPageContentKey_idx` ON `PageContent` (`content` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `firstName` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `securityAnswer` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `SecurityQuestion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `SecurityQuestion` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `SecurityQuestion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `UserSecurityQuestion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `UserSecurityQuestion` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `UserSecurityQuestion` (
  `user` INT NOT NULL,
  `securityQuestion` INT NOT NULL,
  PRIMARY KEY (`user`, `securityQuestion`),
  CONSTRAINT `userUserSecurityQuestionKey`
    FOREIGN KEY (`user`)
    REFERENCES `User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `securityQuestionUserSecurityQuestionKey`
    FOREIGN KEY (`securityQuestion`)
    REFERENCES `SecurityQuestion` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `securityQuestionUserSecurityQuestionKey_idx` ON `UserSecurityQuestion` (`securityQuestion` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `UserGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `UserGroup` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `UserGroup` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `AccessRight`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AccessRight` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `AccessRight` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(255) NULL,
  `mandatory` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `UserUserGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `UserUserGroup` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `UserUserGroup` (
  `user` INT NOT NULL,
  `userGroup` INT NOT NULL,
  PRIMARY KEY (`user`, `userGroup`),
  CONSTRAINT `userUserUserGroupKey`
    FOREIGN KEY (`user`)
    REFERENCES `User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `userGroupUserUserGroupKey`
    FOREIGN KEY (`userGroup`)
    REFERENCES `UserGroup` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `userGroupUserUserGroupKey_idx` ON `UserUserGroup` (`userGroup` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `UserGroupAccessRight`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `UserGroupAccessRight` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `UserGroupAccessRight` (
  `userGroup` INT NOT NULL,
  `accessRight` INT NOT NULL,
  PRIMARY KEY (`userGroup`, `accessRight`),
  CONSTRAINT `userGroupUserGroupAccessRightKey`
    FOREIGN KEY (`userGroup`)
    REFERENCES `UserGroup` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `accessRightUserGroupAccessRightKey`
    FOREIGN KEY (`accessRight`)
    REFERENCES `AccessRight` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `accessRightUserGroupAccessRightKey_idx` ON `UserGroupAccessRight` (`accessRight` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `PageAccess`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `PageAccess` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `PageAccess` (
  `page` INT NOT NULL,
  `userGroup` INT NOT NULL,
  `view` TINYINT(1) NOT NULL DEFAULT TRUE,
  `add` TINYINT(1) NOT NULL DEFAULT TRUE COMMENT 'Add elements to the page',
  `edit` TINYINT(1) NOT NULL DEFAULT TRUE,
  `delete` TINYINT(1) NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`page`, `userGroup`),
  CONSTRAINT `pagePageAccessKey`
    FOREIGN KEY (`page`)
    REFERENCES `Page` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `userGroupPageAccessKey`
    FOREIGN KEY (`userGroup`)
    REFERENCES `UserGroup` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `userGroupPageAccessKey_idx` ON `PageAccess` (`userGroup` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Salt`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Salt` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Salt` (
  `saltStr1` VARCHAR(10) NOT NULL,
  `saltStr2` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`saltStr1`, `saltStr2`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Config`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Config` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL COMMENT '	',
  `value` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `Page`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `Page` (`id`, `name`, `title`, `parent`) VALUES (NULL, 'Home', NULL, NULL);
INSERT INTO `Page` (`id`, `name`, `title`, `parent`) VALUES (NULL, 'About Us', NULL, NULL);
INSERT INTO `Page` (`id`, `name`, `title`, `parent`) VALUES (NULL, 'Contact Us', NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `SecurityQuestion`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'When is your anniversary');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'Who was your childhood hero');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your father\'s middle name');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your mother\'s maiden name');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your favourite colour');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your favourite film');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your favourite pet\'s name');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your favourite sports team');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What was your favourite teacher\'s name');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your first child\'s middle name');
INSERT INTO `SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What was the name of your high school');

COMMIT;


-- -----------------------------------------------------
-- Data for table `Salt`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `Salt` (`saltStr1`, `saltStr2`) VALUES (';pqked2p3i', 'qwel;fnq;o');
INSERT INTO `Salt` (`saltStr1`, `saltStr2`) VALUES ('qi3m;oqi23', 'mqwd;2mqwl');
INSERT INTO `Salt` (`saltStr1`, `saltStr2`) VALUES ('im3;2oim3d', 'qw;edmq;wo');

COMMIT;

