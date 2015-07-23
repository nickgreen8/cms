SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `cms` ;
CREATE SCHEMA IF NOT EXISTS `cms` DEFAULT CHARACTER SET utf8 ;
SHOW WARNINGS;
USE `cms` ;

-- -----------------------------------------------------
-- Table `cms`.`Page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Page` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `content` LONGTEXT NULL,
  `parent` VARCHAR(45) NULL DEFAULT NULL,
  `order` INT NULL,
  `removable` TINYINT(1) NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`User` ;

SHOW WARNINGS;
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

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`SecurityQuestion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`SecurityQuestion` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`SecurityQuestion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`UserSecurityQuestion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`UserSecurityQuestion` ;

SHOW WARNINGS;
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

SHOW WARNINGS;
CREATE INDEX `securityQuestionUserSecurityQuestionKey_idx` ON `cms`.`UserSecurityQuestion` (`securityQuestion` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`UserGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`UserGroup` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`UserGroup` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`AccessRight`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`AccessRight` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`AccessRight` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `mandatory` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`UserUserGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`UserUserGroup` ;

SHOW WARNINGS;
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

SHOW WARNINGS;
CREATE INDEX `userGroupUserUserGroupKey_idx` ON `cms`.`UserUserGroup` (`userGroup` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`UserGroupAccessRight`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`UserGroupAccessRight` ;

SHOW WARNINGS;
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

SHOW WARNINGS;
CREATE INDEX `accessRightUserGroupAccessRightKey_idx` ON `cms`.`UserGroupAccessRight` (`accessRight` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Salt`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Salt` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Salt` (
  `saltStr1` VARCHAR(10) NOT NULL,
  `saltStr2` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`saltStr1`, `saltStr2`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Config`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Config` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL COMMENT '	',
  `value` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Type` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`PageType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`PageType` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`PageType` (
  `page` INT NOT NULL,
  `type` INT NOT NULL,
  PRIMARY KEY (`page`, `type`),
  CONSTRAINT `PagePageTypeKey`
    FOREIGN KEY (`page`)
    REFERENCES `cms`.`Page` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `TypePageTypeKey`
    FOREIGN KEY (`type`)
    REFERENCES `cms`.`Type` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `TypePageTypeKey_idx` ON `cms`.`PageType` (`type` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Post`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Post` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NULL,
  `content` LONGTEXT NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` INT NOT NULL,
  `type` VARCHAR(7) NOT NULL DEFAULT 'POST',
  PRIMARY KEY (`id`),
  CONSTRAINT `authorPostsKey`
    FOREIGN KEY (`author`)
    REFERENCES `cms`.`User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `authorPostsKey_idx` ON `cms`.`Post` (`author` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Audit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Audit` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Audit` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `record` INT NOT NULL,
  `table` VARCHAR(20) NOT NULL,
  `author` INT NOT NULL,
  `action` VARCHAR(9) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `authorAuditKey`
    FOREIGN KEY (`author`)
    REFERENCES `cms`.`User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `authorAuditKey_idx` ON `cms`.`Audit` (`author` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Rating`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Rating` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Rating` (
  `post` INT NOT NULL,
  `rating` INT NOT NULL,
  CONSTRAINT `postRatingKey`
    FOREIGN KEY (`post`)
    REFERENCES `cms`.`Post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `postRatingKey_idx` ON `cms`.`Rating` (`post` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Poll`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Poll` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Poll` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  `description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Option`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Option` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Option` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(255) NULL,
  `count` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`PollOption`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`PollOption` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`PollOption` (
  `poll` INT NOT NULL,
  `option` INT NOT NULL,
  PRIMARY KEY (`poll`, `option`),
  CONSTRAINT `pollPollOptionKey`
    FOREIGN KEY (`poll`)
    REFERENCES `cms`.`Poll` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `optionPollOptionKey`
    FOREIGN KEY (`option`)
    REFERENCES `cms`.`Option` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `optionPollOptionKey_idx` ON `cms`.`PollOption` (`option` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`PollLink`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`PollLink` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`PollLink` (
  `poll` INT NOT NULL,
  `id` INT NOT NULL,
  `linkTo` VARCHAR(4) NOT NULL,
  CONSTRAINT `pollPollLinkKey`
    FOREIGN KEY (`poll`)
    REFERENCES `cms`.`Poll` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`PagePost`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`PagePost` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`PagePost` (
  `page` INT NOT NULL,
  `post` INT NOT NULL,
  PRIMARY KEY (`page`, `post`),
  CONSTRAINT `pagePagePostKey`
    FOREIGN KEY (`page`)
    REFERENCES `cms`.`Page` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `postPagePostKey`
    FOREIGN KEY (`post`)
    REFERENCES `cms`.`Post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `postPagePostKey_idx` ON `cms`.`PagePost` (`post` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`PostComment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`PostComment` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`PostComment` (
  `post` INT NOT NULL,
  `comment` INT NOT NULL,
  PRIMARY KEY (`post`, `comment`),
  CONSTRAINT `postPostCommentKey`
    FOREIGN KEY (`post`)
    REFERENCES `cms`.`Post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `commentPostCommentKey`
    FOREIGN KEY (`comment`)
    REFERENCES `cms`.`Post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `commentPostCommentKey_idx` ON `cms`.`PostComment` (`comment` ASC);

SHOW WARNINGS;
USE `cms` ;

-- -----------------------------------------------------
-- procedure GetPosts
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetPosts`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE `GetPosts`
	(IN page INT(11))
BEGIN
	#Get the data
	SELECT
		bp.id,
		bp.title,
		bp.content,
		bp.timestamp,
		bp.author,
		bp.rating,
		bp.edited,
		bp.editor,
		bp.editTime,
		bp.page,
		bp.useful,
		bp.notUseful,
		bp.commentCount
	FROM
		BlogPosts bp
	WHERE
		bp.page = page;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure GetMonthFilter
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetMonthFilter`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE `GetMonthFilter`
	(IN page CHAR(45))
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the ID of the page
	SET id = IF(page REGEXP '^-?[0-9]+$', page, calcPageId(page));

	#Get the data
	SELECT
		DATE_FORMAT(p.timestamp, '%b %Y') as month,
		DATE_FORMAT(p.timestamp, '%b %Y') as year,
		DATE_FORMAT(p.timestamp, '%b %Y') as date
	FROM
		Post p JOIN PagePost pp
			ON p.id = pp.post
		JOIN Page P2
			on pp.page = p2.id
	WHERE
		p2.id = id
	GROUP BY
		DATE_FORMAT(p.timestamp, '%b %Y')
	ORDER BY
		p.timestamp ASC;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure GetConfigurationData
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetConfigurationData`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE GetConfigurationData
	()
BEGIN
	#Get the data
	SELECT
		c.name,
		c.value
	FROM
		Config c;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- function validateUser
-- -----------------------------------------------------

USE `cms`;
DROP function IF EXISTS `cms`.`validateUser`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE FUNCTION validateUser(cookie CHAR(40)) RETURNS BOOLEAN
BEGIN
	#Create valid flag
	DECLARE valid BOOLEAN DEFAULT false;

	#Check the cookie
	SET valid = true;

	#Return the valid flag
	RETURN valid;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- function calcPageId
-- -----------------------------------------------------

USE `cms`;
DROP function IF EXISTS `cms`.`calcPageId`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE FUNCTION calcPageId(name CHAR(45)) RETURNS INT
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the page ID
	SELECT
		p.id INTO id
	FROM
		page p
	WHERE
		LCASE(REPLACE(p.name, ' ', '-')) = name;

	RETURN id;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure GetPageData
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetPageData`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE GetPageData
	(IN page CHAR(45))
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the ID of the page
	SET id = IF(page REGEXP '^-?[0-9]+$', page, calcPageId(page));

	#Get the page data
	SELECT
		p.id,
		p.name,
		p.content,
		p.parent,
		t.name as type
	FROM
		page p LEFT JOIN PageType pt
		ON p.id = pt.page
	LEFT JOIN Type t
		ON pt.type = t.id
	WHERE
		p.id = id;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure GetPageType
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetPageType`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE GetPageType
	(IN page CHAR(45))
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the ID of the page
	SET id = IF(page REGEXP '^-?[0-9]+$', page, calcPageId(page));

	#Get the page data
	SELECT
		t.name as type
	FROM
		page p LEFT JOIN PageType pt
		ON p.id = pt.page
	LEFT JOIN Type t
		ON pt.type = t.id
	WHERE
		p.id = id;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- View `cms`.`BlogPosts`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `cms`.`BlogPosts` ;
SHOW WARNINGS;
DROP TABLE IF EXISTS `cms`.`BlogPosts`;
SHOW WARNINGS;
USE `cms`;
CREATE  OR REPLACE VIEW `BlogPosts` AS
SELECT
	p.id,
	p.title,
	p.content,
	p.timestamp,
	CONCAT(u.firstName, ' ', u.lastName) as author,
    IFNULL((SELECT AVG(r.rating) FROM Rating r WHERE r.post = p.id GROUP BY r.post), 0) as rating,
    IF((SELECT a.id FROM Audit a WHERE a.table = 'Post' AND a.record = p.id AND a.action = 'EDIT' ORDER BY a.timestamp DESC LIMIT 1), TRUE, FALSE) as edited,
    (SELECT CONCAT(u2.firstName, ' ', u2.lastName) FROM Audit a JOIN User u2 ON a.author = u2.id WHERE a.table = 'Post' AND a.record = p.id AND a.action = 'EDIT' ORDER BY a.timestamp DESC LIMIT 1) as editor,
    (SELECT a.timestamp FROM Audit a WHERE a.table = 'Post' AND a.record = p.id AND a.action = 'EDIT' ORDER BY a.timestamp DESC LIMIT 1) as editTime,
	p2.id as page,
	0 as useful,
	0 as notUseful,
	IFNULL((SELECT COUNT(*) FROM PostComment pc WHERE pc.post = p.id GROUP BY pc.post), 0) as commentCount
FROM
	Post p JOIN User u
		ON p.author = u.id
    JOIN PagePost pp
    	ON p.id = pp.post
    JOIN Page p2
    	ON pp.page = p2.id
WHERE
	p.type = 'POST'
GROUP BY
	p.id
ORDER BY
	p.timestamp DESC;
SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `cms`.`Page`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`Page` (`id`, `name`, `content`, `parent`, `order`, `removable`) VALUES (1, 'Home', '# The Grass Framework\\r\\n\\r\\nWelcome to Grass, your bed of internet platform grown.\\r\\n\\r\\nThis has been developed by Nick Green as a learning exercise. As time progresses, there will be increased tools and functionality.\\r\\n\\r\\nIf you would like to see more on my and my work, [please click here](http://nickgreenweb.co.uk/) or to contact me, [click here](http://nickgreenweb.co.uk/pages/contact.php).', NULL, NULL, false);
INSERT INTO `cms`.`Page` (`id`, `name`, `content`, `parent`, `order`, `removable`) VALUES (NULL, 'Cookies', '# Cookie Policy', '-1', NULL, false);
INSERT INTO `cms`.`Page` (`id`, `name`, `content`, `parent`, `order`, `removable`) VALUES (NULL, 'Site Details', '# Site Details', '-1', 100, false);

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`SecurityQuestion`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'When is your anniversary');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'Who was your childhood hero');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your father\'s middle name');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your mother\'s maiden name');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your favourite colour');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your favourite film');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your favourite pet\'s name');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your favourite sports team');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What was your favourite teacher\'s name');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What is your first child\'s middle name');
INSERT INTO `cms`.`SecurityQuestion` (`id`, `question`) VALUES (NULL, 'What was the name of your high school');

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`UserGroup`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`UserGroup` (`id`, `name`, `description`) VALUES (1, 'Master', 'These users should have full access rights');

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`Config`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`Config` (`id`, `name`, `value`) VALUES (NULL, 'meta-autor', 'Nick Green');
INSERT INTO `cms`.`Config` (`id`, `name`, `value`) VALUES (NULL, 'theme', 'n8g');
INSERT INTO `cms`.`Config` (`id`, `name`, `value`) VALUES (NULL, 'copyright', 'Nick Green 2015');
INSERT INTO `cms`.`Config` (`id`, `name`, `value`) VALUES (NULL, 'url', 'http://cms.localhost/');
INSERT INTO `cms`.`Config` (`id`, `name`, `value`) VALUES (NULL, 'site-tag-line', NULL);
INSERT INTO `cms`.`Config` (`id`, `name`, `value`) VALUES (NULL, 'site-title', 'N8G Grass Framework');
INSERT INTO `cms`.`Config` (`id`, `name`, `value`) VALUES (NULL, 'favicon', NULL);
INSERT INTO `cms`.`Config` (`id`, `name`, `value`) VALUES (NULL, 'nav-separator', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`Type`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`Type` (`id`, `name`) VALUES (1, 'index');
INSERT INTO `cms`.`Type` (`id`, `name`) VALUES (NULL, 'page');
INSERT INTO `cms`.`Type` (`id`, `name`) VALUES (NULL, 'blog');

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`PageType`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`PageType` (`page`, `type`) VALUES (1, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`Option`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`Option` (`id`, `name`, `description`, `count`) VALUES (NULL, 'Useful', NULL, 0);
INSERT INTO `cms`.`Option` (`id`, `name`, `description`, `count`) VALUES (NULL, 'Not Useful', NULL, 0);

COMMIT;

