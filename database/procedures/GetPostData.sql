-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE GetPostData
	(IN post CHAR(45))
BEGIN
	#Create ID variable
	DECLARE id INT(11);

	#Get the ID of the post
	SET id = IF(post REGEXP '^-?[0-9]+$', post, calcPostId(post));

	#Get the data
	SELECT
		bp.id,
		bp.title,
		bp.content,
		bp.timestamp,
		bp.author,
		bp.rating,
		bp.ratingCount,
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
		bp.id = id;
END