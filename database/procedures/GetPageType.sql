-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

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
END