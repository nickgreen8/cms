-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

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
<<<<<<< HEAD
		LCASE(REPLACE(p.title, ' ', '-')) = name;
=======
		LCASE(REPLACE(p.name, ' ', '-')) = title;
>>>>>>> 758e343b9cf11387076c663590e68710e4e39f6a

	RETURN id;
END