CREATE VIEW `BlogPosts` AS
SELECT
	p.id,
	p.title,
	p.content,
	p.timestamp,
	CONCAT(u.firstName, ' ', u.lastName) as author,
    IFNULL((SELECT AVG(r.rating) FROM Rating r WHERE r.post = p.id GROUP BY r.post), 0) as rating,
    IF((SELECT a.id FROM Audit a WHERE a.table = 'Post' AND a.record = p.id AND a.action = 'EDIT' ORDER BY a.timestamp DESC LIMIT 1), TRUE, FALSE) as edited,
    (SELECT CONCAT(u2.firstName, ' ', u2.lastName) FROM Audit a JOIN User u2 ON a.author = u2.id WHERE a.table = 'Post' AND a.record = p.id AND a.action = 'EDIT' ORDER BY a.timestamp DESC LIMIT 1) as editor,
    (SELECT a.timestamp FROM Audit a WHERE a.table = 'Post' AND a.record = p.id AND a.action = 'EDIT' ORDER BY a.timestamp DESC LIMIT 1) as editTime,
	p2.id as page,
	0 as useful,
	0 as notUseful,
	IFNULL((SELECT COUNT(*) FROM PostComment pc WHERE pc.post = p.id GROUP BY pc.post), 0) as commentCount
FROM
	Post p JOIN User u
		ON p.author = u.id
    JOIN PagePost pp
    	ON p.id = pp.post
    JOIN Page p2
    	ON pp.page = p2.id
WHERE
	p.type = 'POST'
GROUP BY
	p.id
ORDER BY
	p.timestamp DESC