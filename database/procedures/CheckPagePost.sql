-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE CheckPagePost
	(IN blog CHAR(45), IN post CHAR(45))
BEGIN
	#Create blog ID variable
	DECLARE blogId INT(11);
	#Create post ID variable
	DECLARE postId INT(11);

	#Get the ID of the blog
	SET blogId = IF(blog REGEXP '^-?[0-9]+$', blog, calcPageId(blog));
	#Get the ID of the post
	SET postId = IF(post REGEXP '^-?[0-9]+$', post, calcPostId(post));

	#Get the data
	SELECT
		po.id
	FROM
		Post po JOIN PagePost pp
			ON po.id = pp.post
		JOIN Page pa
			ON pp.page = pa.id
	WHERE
		po.id = postId
		AND
		pa.id = blogId;
END