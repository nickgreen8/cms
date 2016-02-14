-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

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
END