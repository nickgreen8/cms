SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `cms` ;
CREATE SCHEMA IF NOT EXISTS `cms` DEFAULT CHARACTER SET utf8 ;
USE `cms` ;

-- -----------------------------------------------------
-- Table `cms`.`Element`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Element` ;

CREATE TABLE IF NOT EXISTS `cms`.`Element` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `identifier` VARCHAR(45) NULL DEFAULT NULL,
  `cms_class` VARCHAR(45) NOT NULL,
  `usable` TINYINT(1) NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`Attribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Attribute` ;

CREATE TABLE IF NOT EXISTS `cms`.`Attribute` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `label` VARCHAR(45) NULL DEFAULT NULL,
  `required` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`ElementAttribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`ElementAttribute` ;

CREATE TABLE IF NOT EXISTS `cms`.`ElementAttribute` (
  `element` INT NOT NULL,
  `attribute` INT NOT NULL,
  PRIMARY KEY (`element`, `attribute`),
  CONSTRAINT `elementElementAttributeKey`
    FOREIGN KEY (`element`)
    REFERENCES `cms`.`Element` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `attributeElementAttributeKey`
    FOREIGN KEY (`attribute`)
    REFERENCES `cms`.`Attribute` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `attributeTagAttributeKey_idx` ON `cms`.`ElementAttribute` (`attribute` ASC);


-- -----------------------------------------------------
-- Table `cms`.`Class`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Class` ;

CREATE TABLE IF NOT EXISTS `cms`.`Class` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `style` LONGTEXT NOT NULL,
  `desciption` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`Content`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Content` ;

CREATE TABLE IF NOT EXISTS `cms`.`Content` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `value` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`ElementContent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`ElementContent` ;

CREATE TABLE IF NOT EXISTS `cms`.`ElementContent` (
  `element` INT NOT NULL,
  `content` INT NOT NULL,
  PRIMARY KEY (`element`, `content`),
  CONSTRAINT `elementElementContentKey`
    FOREIGN KEY (`element`)
    REFERENCES `cms`.`Element` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `contentElementContentKey`
    FOREIGN KEY (`content`)
    REFERENCES `cms`.`Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `contentElementContentKey_idx` ON `cms`.`ElementContent` (`content` ASC);


-- -----------------------------------------------------
-- Table `cms`.`ContentAttribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`ContentAttribute` ;

CREATE TABLE IF NOT EXISTS `cms`.`ContentAttribute` (
  `content` INT NOT NULL,
  `attribute` INT NOT NULL,
  PRIMARY KEY (`content`, `attribute`),
  CONSTRAINT `contentContentAttributeKey`
    FOREIGN KEY (`content`)
    REFERENCES `cms`.`Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `attribute`
    FOREIGN KEY (`attribute`)
    REFERENCES `cms`.`Attribute` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `attribute_idx` ON `cms`.`ContentAttribute` (`attribute` ASC);


-- -----------------------------------------------------
-- Table `cms`.`ContentClass`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`ContentClass` ;

CREATE TABLE IF NOT EXISTS `cms`.`ContentClass` (
  `content` INT NOT NULL,
  `class` INT NOT NULL,
  PRIMARY KEY (`content`, `class`),
  CONSTRAINT `contentContentClassKey`
    FOREIGN KEY (`content`)
    REFERENCES `cms`.`Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `classContentClassKey`
    FOREIGN KEY (`class`)
    REFERENCES `cms`.`Class` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `classContentClassKey_idx` ON `cms`.`ContentClass` (`class` ASC);


-- -----------------------------------------------------
-- Table `cms`.`Page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Page` ;

CREATE TABLE IF NOT EXISTS `cms`.`Page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `title` VARCHAR(45) NULL DEFAULT NULL,
  `content` LONGTEXT NULL,
  `parent` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`PageContent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`PageContent` ;

CREATE TABLE IF NOT EXISTS `cms`.`PageContent` (
  `page` INT NOT NULL,
  `content` INT NOT NULL,
  PRIMARY KEY (`page`, `content`),
  CONSTRAINT `pagePageContentKey`
    FOREIGN KEY (`page`)
    REFERENCES `cms`.`Page` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `contentPageContentKey`
    FOREIGN KEY (`content`)
    REFERENCES `cms`.`Content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `contentPageContentKey_idx` ON `cms`.`PageContent` (`content` ASC);


-- -----------------------------------------------------
-- Table `cms`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`User` ;

CREATE TABLE IF NOT EXISTS `cms`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `firstName` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `securityAnswer` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`SecurityQuestion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`SecurityQuestion` ;

CREATE TABLE IF NOT EXISTS `cms`.`SecurityQuestion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`UserSecurityQuestion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`UserSecurityQuestion` ;

CREATE TABLE IF NOT EXISTS `cms`.`UserSecurityQuestion` (
  `user` INT NOT NULL,
  `securityQuestion` INT NOT NULL,
  PRIMARY KEY (`user`, `securityQuestion`),
  CONSTRAINT `userUserSecurityQuestionKey`
    FOREIGN KEY (`user`)
    REFERENCES `cms`.`User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `securityQuestionUserSecurityQuestionKey`
    FOREIGN KEY (`securityQuestion`)
    REFERENCES `cms`.`SecurityQuestion` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `securityQuestionUserSecurityQuestionKey_idx` ON `cms`.`UserSecurityQuestion` (`securityQuestion` ASC);


-- -----------------------------------------------------
-- Table `cms`.`UserGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`UserGroup` ;

CREATE TABLE IF NOT EXISTS `cms`.`UserGroup` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`AccessRight`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`AccessRight` ;

CREATE TABLE IF NOT EXISTS `cms`.`AccessRight` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `mandatory` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`UserUserGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`UserUserGroup` ;

CREATE TABLE IF NOT EXISTS `cms`.`UserUserGroup` (
  `user` INT NOT NULL,
  `userGroup` INT NOT NULL,
  PRIMARY KEY (`user`, `userGroup`),
  CONSTRAINT `userUserUserGroupKey`
    FOREIGN KEY (`user`)
    REFERENCES `cms`.`User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `userGroupUserUserGroupKey`
    FOREIGN KEY (`userGroup`)
    REFERENCES `cms`.`UserGroup` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `userGroupUserUserGroupKey_idx` ON `cms`.`UserUserGroup` (`userGroup` ASC);


-- -----------------------------------------------------
-- Table `cms`.`UserGroupAccessRight`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`UserGroupAccessRight` ;

CREATE TABLE IF NOT EXISTS `cms`.`UserGroupAccessRight` (
  `userGroup` INT NOT NULL,
  `accessRight` INT NOT NULL,
  PRIMARY KEY (`userGroup`, `accessRight`),
  CONSTRAINT `userGroupUserGroupAccessRightKey`
    FOREIGN KEY (`userGroup`)
    REFERENCES `cms`.`UserGroup` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `accessRightUserGroupAccessRightKey`
    FOREIGN KEY (`accessRight`)
    REFERENCES `cms`.`AccessRight` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `accessRightUserGroupAccessRightKey_idx` ON `cms`.`UserGroupAccessRight` (`accessRight` ASC);


-- -----------------------------------------------------
-- Table `cms`.`Salt`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Salt` ;

CREATE TABLE IF NOT EXISTS `cms`.`Salt` (
  `saltStr1` VARCHAR(10) NOT NULL,
  `saltStr2` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`saltStr1`, `saltStr2`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`Config`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Config` ;

CREATE TABLE IF NOT EXISTS `cms`.`Config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL COMMENT '	',
  `value` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`Type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Type` ;

CREATE TABLE IF NOT EXISTS `cms`.`Type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`PageType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`PageType` ;

CREATE TABLE IF NOT EXISTS `cms`.`PageType` (
  `page` INT NOT NULL,
  `type` INT NOT NULL,
  PRIMARY KEY (`page`, `type`),
  CONSTRAINT `PagePageTypeKey`
    FOREIGN KEY (`page`)
    REFERENCES `cms`.`Page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TypePageTypeKey`
    FOREIGN KEY (`type`)
    REFERENCES `cms`.`Type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `TypePageTypeKey_idx` ON `cms`.`PageType` (`type` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
