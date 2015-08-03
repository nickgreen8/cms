-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

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
END