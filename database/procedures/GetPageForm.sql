-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

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
END