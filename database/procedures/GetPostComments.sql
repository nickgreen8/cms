-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE `GetPostComments`
	(IN post INT(11))
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the ID of the post
	SET id = IF(post REGEXP '^-?[0-9]+$', post, calcPostId(post));	

	#Get the data
	SELECT
		pc.id,
		pc.content,
		pc.timestamp,
		pc.author,
    	pc.post
	FROM
		PostComments pc
	WHERE
		pc.post = id;
END