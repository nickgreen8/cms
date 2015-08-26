-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE `GetPosts`
	(IN page INT(11), IN filter CHAR(7))
BEGIN
	#Get the data
	SELECT
		bp.id,
		bp.title,
		bp.content,
		bp.timestamp,
		bp.author,
		bp.rating,
		bp.edited,
		bp.editor,
		bp.editTime,
		bp.page,
		bp.useful,
		bp.notUseful,
		bp.commentCount
	FROM
		BlogPosts bp
	WHERE
		bp.page = page
		AND
        (
        	filter = 'NULL'
        	OR
            DATE_FORMAT(bp.timestamp, '%m-%Y') = filter
		);
END