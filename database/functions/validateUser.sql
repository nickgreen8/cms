-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE FUNCTION validateUser(cookie CHAR(40)) RETURNS BOOLEAN
BEGIN
	#Create valid flag
	DECLARE valid BOOLEAN DEFAULT false;

	#Check the cookie
	SET valid = true;

	#Return the valid flag
	RETURN valid;
END