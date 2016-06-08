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
  `removable` TINYINT(1) NOT NULL DEFAULT TRUE,
  `parent` INT NULL DEFAULT NULL,
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

-- -----------------------------------------------------
-- Table `cms`.`Navigation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Navigation` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Navigation` (
  `page` INT NOT NULL,
  `parent` INT NULL,
  `priority` INT NULL,
  PRIMARY KEY (`page`, `parent`),
  CONSTRAINT `PageNaviationKey`
    FOREIGN KEY (`page`)
    REFERENCES `cms`.`Page` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`Form`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`Form` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`Form` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `method` VARCHAR(6) NOT NULL,
  `enctype` VARCHAR(33) NULL DEFAULT NULL,
  PRIMARY KEY (`id`));

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`FormField`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`FormField` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`FormField` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(100) NOT NULL,
  `identifier` VARCHAR(100) NULL DEFAULT NULL,
  `type` VARCHAR(255) NOT NULL,
  `additional` LONGTEXT NULL DEFAULT NULL,
  `required` TINYINT(1) NULL DEFAULT FALSE,
  PRIMARY KEY (`id`));

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`FormFormField`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`FormFormField` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`FormFormField` (
  `form` INT NOT NULL,
  `field` INT NOT NULL,
  PRIMARY KEY (`form`, `field`),
  CONSTRAINT `formFormFormFieldKey`
    FOREIGN KEY (`form`)
    REFERENCES `cms`.`Form` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fieldFormFormFieldKey`
    FOREIGN KEY (`field`)
    REFERENCES `cms`.`FormField` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fieldFormFormFieldKey` ON `cms`.`FormFormField` (`field` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `cms`.`PageForm`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cms`.`PageForm` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `cms`.`PageForm` (
  `page` INT NOT NULL,
  `form` INT NOT NULL,
  PRIMARY KEY (`page`, `form`),
  CONSTRAINT `pagePageFormFieldKey`
    FOREIGN KEY (`page`)
    REFERENCES `cms`.`Page` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `formPageFormFieldKey`
    FOREIGN KEY (`form`)
    REFERENCES `cms`.`Form` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `formPageFormFieldKey` ON `cms`.`PageForm` (`form` ASC);

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
	(IN page INT(11), IN filter CHAR(7))
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
		bp.page = page
		AND
        (
        	filter = 'NULL'
        	OR
            DATE_FORMAT(bp.timestamp, '%m-%Y') = filter
		);
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure CheckPagePost
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`CheckPagePost`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE CheckPagePost
	(IN blog CHAR(45), IN post CHAR(45))
BEGIN
	#Create blog ID variable
	DECLARE blogId INT(11);
	#Create post ID variable
	DECLARE postId INT(11);

	#Get the ID of the blog
	SET blogId = IF(blog REGEXP '^-?[0-9]+$', blog, calcPageId(blog));
	#Get the ID of the post
	SET postId = IF(post REGEXP '^-?[0-9]+$', post, calcPostId(post));

	#Get the data
	SELECT
		po.id
	FROM
		Post po JOIN PagePost pp
			ON po.id = pp.post
		JOIN Page pa
			ON pp.page = pa.id
	WHERE
		po.id = postId
		AND
		pa.id = blogId;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure GetPostComments
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetPostComments`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE `GetPostComments`
	(IN post CHAR(45))
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the ID of the post
	SET id = IF(post REGEXP '^-?[0-9]+$', post, calcPostId(post));	

	#Get the data
	SELECT
		pc.id,
		pc.content,
		pc.timestamp,
		pc.author,
    	pc.post
	FROM
		PostComments pc
	WHERE
		pc.post = id;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure GetPostData
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetPostData`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE GetPostData
	(IN post CHAR(45))
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the ID of the post
	SET id = IF(post REGEXP '^-?[0-9]+$', post, calcPostId(post));

	#Get the data
	SELECT
		bp.id,
		bp.title,
		bp.content,
		bp.timestamp,
		bp.author,
		bp.rating,
		bp.ratingCount,
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
		bp.id = id;
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
		DATE_FORMAT(p.timestamp, '%m') as month,
		DATE_FORMAT(p.timestamp, '%Y') as year,
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
-- function calcPostId
-- -----------------------------------------------------

USE `cms`;
DROP function IF EXISTS `cms`.`calcPostId`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE FUNCTION calcPostId(name CHAR(45)) RETURNS INT
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the page ID
	SELECT
		p.id INTO id
	FROM
		Post p
	WHERE
		LCASE(REPLACE(p.title, ' ', '-')) = name;

	RETURN id;
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
-- function calcChildPageCount
-- -----------------------------------------------------

USE `cms`;
DROP function IF EXISTS `cms`.`calcChildPageCount`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE FUNCTION calcChildPageCount(page INT) RETURNS INT
BEGIN
	#Create count variable
	DECLARE count INT(11);

	#Get the page ID
	SELECT
		COUNT(*) INTO count
	FROM
		Navigation n
	WHERE
		n.parent = page
	GROUP BY
		n.parent;

	RETURN count;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure GetNavigation
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetNavigation`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE GetNavigation
	(IN page INT(45))
BEGIN
	SELECT
		p.id,
		p.name,
		t.name as type,
		calcChildPageCount(p.id) as children,
		LCASE(REPLACE(p.name, ' ', '-')) as identifier
	FROM
		Page p JOIN Navigation n
	    	ON p.id = n.page
	    JOIN PageType pt
			ON p.id = pt.page
		JOIN Type t
			ON pt.type = t.id
	WHERE
		n.parent = page
	ORDER BY
		n.priority ASC,
		p.id ASC;
END$$

DELIMITER ;
SHOW WARNINGS;

-- -----------------------------------------------------
-- procedure GetPageForm
-- -----------------------------------------------------

USE `cms`;
DROP procedure IF EXISTS `cms`.`GetPageForm`;
SHOW WARNINGS;

DELIMITER $$
USE `cms`$$
CREATE PROCEDURE GetPageForm
	(IN page CHAR(45))
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the ID of the page
	SET id = IF(page REGEXP '^-?[0-9]+$', page, calcPageId(page));

	#Get the page data
	SELECT
		f.id,
		f.name,
		f.title,
		f.action,
		f.method,
		f.enctype
	FROM
		Form f LEFT JOIN PageForm pf
		ON f.id = pf.form
	WHERE
		pf.page = id;
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
    IFNULL((SELECT COUNT(r.rating) FROM Rating r WHERE r.post = p.id GROUP BY r.post), 0) as ratingCount,
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

-- -----------------------------------------------------
-- View `cms`.`PostComments`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `cms`.`PostComments` ;
SHOW WARNINGS;
DROP TABLE IF EXISTS `cms`.`PostComments`;
SHOW WARNINGS;
USE `cms`;
CREATE  OR REPLACE VIEW `PostComments` AS
SELECT
	p.id,
	p.content,
	p.timestamp,
	CONCAT(u.firstName, ' ', u.lastName) as author,
    pc.post
FROM
	Post p JOIN User u
		ON p.author = u.id
    JOIN PostComment pc
    	ON p.id = pc.comment
WHERE
	p.type = 'COMMENT'
ORDER BY
	p.timestamp ASC;
SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `cms`.`Page`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`Page` (`id`, `name`, `content`, `removable`, `parent`) VALUES (1, 'Home', '# The Grass Framework\\r\\n\\r\\nWelcome to Grass, your bed of internet platform grown.\\r\\n\\r\\nThis has been developed by Nick Green as a learning exercise. As time progresses, there will be increased tools and functionality.\\r\\n\\r\\nIf you would like to see more on my and my work, [please click here](http://nickgreenweb.co.uk/) or to contact me, [click here](http://nickgreenweb.co.uk/pages/contact.php).', false, NULL);
INSERT INTO `cms`.`Page` (`id`, `name`, `content`, `removable`, `parent`) VALUES (2, 'Cookies', '## Cookies\nTo provide the best possible experience, this website utilises cookies. Without the ability to use cookies, the management of the site would be impossible. **No** personal data is stored in any cookies generated/utilised by this website.\n\n**This site will never pass on your personal details to any third party without consent.**\n### Information\n#### What is a Cookie?\nA cookie is a small text file which is stored in your browser. They are used by some websites for a variety of reasons. The use of cookies can be disabled in your browser but this is advised against. Disabling cookies may render certain features unusable and may even cause whole sites to be inoperable.\n\nEach site will create it\'\'s own very different cookies with different goals. Each time that, as a user you load a website, the site will retrieve any cookies that it created in a previous visit. The site will then use the data held within these cookies to taylor the experience for the user.\n\nIt must be stressed that cookies are not virus\'\' and are not executable. They are purely plain text. Even so, cookies can be malicious and can be used as a form of spyware. Many anti-spyware products will routinely flag potential threats and will remove them.\n#### What are they used for?\nThe most common use of a cookie is to store a user\'\'s session when they are logged in to a website or to store your \'\'shopping basket\'\' when placing an online order. They are used to identify your computer for various analytics terms as well as to customise your experience. They are widely used but social media sites, such as Facebook and Twitter, to allow you to share pages and links with great ease.\n### Cookie Register\n| Cookie Name | Description                                      |\n|-------------|--------------------------------------------------|\n| ng_login    | Holds the user\'\'s session ID.                     |\n| ng_cookies  | Indicates whether to display the cookie message. |', false, NULL);
INSERT INTO `cms`.`Page` (`id`, `name`, `content`, `removable`, `parent`) VALUES (3, 'Site Details', '# Site Details', false, NULL);
INSERT INTO `cms`.`Page` (`id`, `name`, `content`, `removable`, `parent`) VALUES (4, 'Login', '# Login', false, NULL);

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
INSERT INTO `cms`.`Type` (`id`, `name`) VALUES (2, 'page');
INSERT INTO `cms`.`Type` (`id`, `name`) VALUES (3, 'blog');
INSERT INTO `cms`.`Type` (`id`, `name`) VALUES (4, 'login');

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`PageType`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`PageType` (`page`, `type`) VALUES (1, 1);
INSERT INTO `cms`.`PageType` (`page`, `type`) VALUES (2, 2);
INSERT INTO `cms`.`PageType` (`page`, `type`) VALUES (3, 2);
INSERT INTO `cms`.`PageType` (`page`, `type`) VALUES (4, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`Option`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`Option` (`id`, `name`, `description`, `count`) VALUES (NULL, 'Useful', NULL, 0);
INSERT INTO `cms`.`Option` (`id`, `name`, `description`, `count`) VALUES (NULL, 'Not Useful', NULL, 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `cms`.`Navigation`
-- -----------------------------------------------------
START TRANSACTION;
USE `cms`;
INSERT INTO `cms`.`Navigation` (`page`, `parent`, `priority`) VALUES (1, NULL, 0);
INSERT INTO `cms`.`Navigation` (`page`, `parent`, `priority`) VALUES (2, -1, 1);
INSERT INTO `cms`.`Navigation` (`page`, `parent`, `priority`) VALUES (3, -1, 2);
INSERT INTO `cms`.`Navigation` (`page`, `parent`, `priority`) VALUES (4, -1, 0);
INSERT INTO `cms`.`Navigation` (`page`, `parent`, `priority`) VALUES (4, NULL, 10);

COMMIT;

