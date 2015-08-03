CREATE VIEW `PostComments` AS
SELECT
	p.id,
	p.content,
	p.timestamp,
	CONCAT(u.firstName, ' ', u.lastName) as author,
    pc.post
FROM
	Post p JOIN User u
		ON p.author = u.id
    JOIN PostComment pc
    	ON p.id = pc.comment
WHERE
	p.type = 'COMMENT'
ORDER BY
	p.timestamp ASC