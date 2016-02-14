-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

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
END