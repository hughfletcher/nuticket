SELECT u.display_name AS Name, 
(
	SELECT COALESCE(SUM(tl.hours),0)
	FROM time_log AS tl
	WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-1) DAY), "%Y-%m-%d 00:00:00") 
	AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-1) DAY), "%Y-%m-%d 23:59:59") 
	AND tl.user_id = u.id
) AS Sun, 
(
	SELECT COALESCE(SUM(tl.hours),0)
	FROM time_log AS tl
	WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-2) DAY), "%Y-%m-%d 00:00:00") 
	AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-2) DAY), "%Y-%m-%d 23:59:59") 
	AND tl.user_id = u.id
) AS Mon, 
(
	SELECT COALESCE(SUM(tl.hours),0)
	FROM time_log AS tl
	WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-3) DAY), "%Y-%m-%d 00:00:00") 
	AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-3) DAY), "%Y-%m-%d 23:59:59") 
	AND tl.user_id = u.id
) AS Tue, 
(
	SELECT COALESCE(SUM(tl.hours),0)
	FROM time_log AS tl
	WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-4) DAY), "%Y-%m-%d 00:00:00") 
	AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-4) DAY), "%Y-%m-%d 23:59:59") 
	AND tl.user_id = u.id
) AS Wed, 
(
	SELECT COALESCE(SUM(tl.hours),0)
	FROM time_log AS tl
	WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-5) DAY), "%Y-%m-%d 00:00:00") 
	AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-5) DAY), "%Y-%m-%d 23:59:59") 
	AND tl.user_id = u.id
) AS Thur, 
(
	SELECT COALESCE(SUM(tl.hours),0)
	FROM time_log AS tl
	WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-6) DAY), "%Y-%m-%d 00:00:00") 
	AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-6) DAY), "%Y-%m-%d 23:59:59") 
	AND tl.user_id = u.id
) AS Fri, 
(
	SELECT COALESCE(SUM(tl.hours),0)
	FROM time_log AS tl
	WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-7) DAY), "%Y-%m-%d 00:00:00") 
	AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-7) DAY), "%Y-%m-%d 23:59:59") 
	AND tl.user_id = u.id
) AS Sat, 
(
	SELECT COALESCE(SUM(tl.hours),0)
	FROM time_log AS tl
	WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-1) DAY), "%Y-%m-%d 00:00:00") 
	AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE())-7) DAY), "%Y-%m-%d 23:59:59") 
	AND tl.user_id = u.id
) AS Total
FROM users u
WHERE u.is_staff = 1