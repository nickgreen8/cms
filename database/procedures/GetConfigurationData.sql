-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE GetConfigurationData
	()
BEGIN
	#Get the data
	SELECT
		c.name,
		c.value
	FROM
		Config c;
END