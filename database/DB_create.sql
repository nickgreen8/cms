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
INSERT INTO `cms`.`Page` (`id`, `name`, `content`, `removable`, `parent`) VALUES (2, 'Cookies', '## Privacy\nThis privacy policy is for this website and powered by [N8G](http://www.nick8green.co.uk/ \"Nick Green\") and governs the privacy of its users who choose to use it.\n\nThe policy sets out the different areas where user privacy is concerned and outlines the obligations & requirements of the users, the website and website owners. Furthermore the way this website processes, stores and protects user data and information will also be detailed within this policy.\n\n### The Website\nThis website and its owners take a proactive approach to user privacy and ensure the necessary steps are taken to protect the privacy of its users throughout their visiting experience. This website complies to all UK national laws and requirements for user privacy.\n\n** This site will never pass on your personal details to any third party without consent. **\n\n## Cookies\nThis website uses cookies to better the users experience while visiting the website. Where applicable this website uses a cookie control system allowing the user on their first visit to the website to allow or disallow the use of cookies on their computer / device. This complies with recent legislation requirements for websites to obtain explicit consent from users before leaving behind or reading files such as cookies on a user\'s computer / device.\nCookies are small files saved to the user\'s computers hard drive that track, save and store information about the user\'s interactions and usage of the website. This allows the website, through its server to provide the users with a tailored experience within this website.\nUsers are advised that if they wish to deny the use and saving of cookies from this website on to their computers hard drive they should take necessary steps within their web browsers security settings to block all cookies from this website and its external serving vendors.\nThis website uses tracking software to monitor its visitors to better understand how they use it. This software is provided by Google Analytics which uses cookies to track visitor usage. The software will save a cookie to your computers hard drive in order to track and monitor your engagement and usage of the website, but will not store, save or collect personal information. You can read Google\'s privacy policy here for further information [ http://www.google.com/privacy.html ].\nOther cookies may be stored to your computers hard drive by external vendors when this website uses referral programs, sponsored links or adverts. Such cookies are used for conversion and referral tracking and typically expire after 30 days, though some may take longer. No personal information is stored, saved or collected.\n\nA cookie is a small text file which is stored in your browser. They are used by some websites for a variety of reasons. The use of cookies can be disabled in your browser but this is advised against. Disabling cookies may render certain features unusable and may even cause whole sites to be inoperable.\n\nEach time that, as a user you load a website, the site will retrieve any cookies that it created in a previous visit. The site will then use the data held within these cookies to tailor the experience for the user.\n\nIt must be stressed that cookies are not virus\' and are not executable. They are purely plain text. Even so, cookies can be malicious and can be used as a form of spyware. Many anti-spyware products will routinely flag potential threats and will remove them.\n\nThe most common use of a cookie is to store a user\'s session when they are logged in to a website or to store your \'shopping basket\' when placing an online order. They are used to identify your computer for various analytics terms as well as to customise your experience. They are widely used but social media sites, such as Facebook and Twitter, to allow you to share pages and links with great ease.\n\n## Contact & Communication\nUsers contacting this website and/or its owners do so at their own discretion and provide any such personal details requested at their own risk. Your personal information is kept private and stored securely until a time it is no longer required or has no use, as detailed in the Data Protection Act 1998. Every effort has been made to ensure a safe and secure form to email submission process but advise users using such form to email processes that they do so at their own risk.\nThis website and its owners use any information submitted to provide you with further information about the products / services they offer or to assist you in answering any questions or queries you may have submitted. This includes using your details to subscribe you to any email newsletter program the website operates but only if this was made clear to you and your express permission was granted when submitting any form to email process. Or whereby you the consumer have previously purchased from or enquired about purchasing from the company a product or service that the email newsletter relates to. This is by no means an entire list of your user rights in regard to receiving email marketing material. Your details are not passed on to any third parties.\nEmail Newsletter\nThis website operates an email newsletter program, used to inform subscribers about products and services supplied by this website. Users can subscribe through an online automated process should they wish to do so but do so at their own discretion. Some subscriptions may be manually processed through prior written agreement with the user.\nSubscriptions are taken in compliance with UK Spam Laws detailed in the Privacy and Electronic Communications Regulations 2003. All personal details relating to subscriptions are held securely and in accordance with the Data Protection Act 1998. No personal details are passed on to third parties nor shared with companies / people outside of the company that operates this website. Under the Data Protection Act 1998 you may request a copy of personal information held about you. If you would like a copy of the information held on you please contact us.\n\nIn compliance with UK Spam Laws and the Privacy and Electronic Communications Regulations 2003 subscribers are given the opportunity to un-subscribe at any time through an automated system. This process is detailed at the footer of each email. If an automated un-subscription system is unavailable clear instructions on how to un-subscribe will by detailed instead.\n\n## External Links\nAlthough this website only looks to include quality, safe and relevant external links, users are advised adopt a policy of caution before clicking any external web links mentioned throughout this website. (External links are clickable text / banner / image links to other websites.)\n\nThe owners of this website cannot guarantee or verify the contents of any externally linked website despite their best efforts. Users should therefore note they click on external links at their own risk and this website and its owners cannot be held liable for any damages or implications caused by visiting any external links mentioned.\nAdverts and Sponsored Links\nThis website may contain sponsored links and adverts. These will typically be served through our advertising partners, to whom may have detailed privacy policies relating directly to the adverts they serve.\nClicking on any such adverts will send you to the advertisers website through a referral program which may use cookies and will track the number of referrals sent from this website. This may include the use of cookies which may in turn be saved on your computers hard drive. Users should therefore note they click on sponsored external links at their own risk and this website and its owners cannot be held liable for any damages or implications caused by visiting any external links mentioned.\nSocial Media Platforms\nCommunication, engagement and actions taken through external social media platforms that this website and its owners participate on are custom to the terms and conditions as well as the privacy policies held with each social media platform respectively.\nUsers are advised to use social media platforms wisely and communicate / engage upon them with due care and caution in regard to their own privacy and personal details. This website nor its owners will ever ask for personal or sensitive information through social media platforms and encourage users wishing to discuss sensitive details to contact them through primary communication channels such as by telephone or email.\n\nThis website may use social sharing buttons which help share web content directly from web pages to the social media platform in question. Users are advised before using such social sharing buttons that they do so at their own discretion and note that the social media platform may track and save your request to share a web page respectively through your social media platform account.\n\n## Shortened Links in Social Media\nThis website and its owners through their social media platform accounts may share web links to relevant web pages. By default some social media platforms shorten lengthy urls or web addresses (this is an example: http://bit.ly/zyVUBo). \n\nUsers are advised to take caution and good judgement before clicking any shortened urls published on social media platforms by this website and its owners. Despite the best efforts to ensure only genuine urls are published many social media platforms are prone to spam and hacking and therefore this website and its owners cannot be held liable for any damages or implications caused by visiting any shortened links.\n\n## Resources & Further Information\n* [Data Protection Act 1998](http://www.legislation.gov.uk/ukpga/1998/29/contents \"Data Protection Act 1998\")\n* [Privacy and Electronic Communications Regulations 2003](http://www.legislation.gov.uk/uksi/2003/2426/contents/made \"Privacy and Electronic Communications Regulations 2003\")\n* [Twitter Privacy Policy](https://twitter.com/privacy?lang=en \"Twitter Privacy Policy\")\n* [Facebook Privacy Policy](https://www.facebook.com/about/privacy/ \"Facebook Privacy Policy\")\n* [Google Privacy Policy](http://www.google.com/policies/privacy/ \"Google Privacy Policy\")\n* [Linkedin Privacy Policy](https://www.linkedin.com/legal/privacy-policy \"Linkedin Privacy Policy\")\n* [Website Privacy Policy Template](http://jamieking.co.uk/resources/free_sample_privacy_policy.html \"Website Privacy Policy Template\")\n\nv.3.0 March 2016 Edited & customised by: [Nick Green](http://www.nick8green.co.uk)', false, NULL);
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

