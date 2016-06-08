-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE GetFormFields
	(IN id INT(11))
BEGIN
	#Get the page data
	SELECT
		ff.label,
		ff.identifier,
		ff.type,
		ff.additional,
		ff.required
	FROM
		FormField ff LEFT JOIN FormFormField fff
			ON ff.id = fff.field
		LEFT JOIN Form f
			ON f.id = fff.form
	WHERE
		f.id = id;
END