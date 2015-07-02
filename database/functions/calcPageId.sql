-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

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
END