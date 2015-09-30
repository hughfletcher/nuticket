SELECT qtd.id AS hide_id, qtd.lft AS hide_lft, qtd.rgt AS hide_rgt, qtd.name AS Name, 
(
	SELECT COUNT(*)
	FROM tickets
	WHERE created_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY 
	AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
	AND dept_id = qtd.id 
	AND deleted_at IS NULL
) AS Created, 
(
	SELECT COUNT(*)
	FROM depts td
	LEFT JOIN tickets t ON td.id = t.dept_id
	WHERE t.created_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY 
	AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
	AND t.deleted_at IS NULL
) AS hide_total_created, 
(
	SELECT COUNT(*)
	FROM tickets
	WHERE closed_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY 
	AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
	AND dept_id = qtd.id 
	AND deleted_at IS NULL
) AS Closed, 
(
	SELECT COUNT(*)
	FROM depts td
	LEFT JOIN tickets t ON td.id = t.dept_id
	WHERE t.closed_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY 
	AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
	AND t.deleted_at IS NULL
) AS hide_total_closed, 
(
	SELECT COUNT(*)
	FROM tickets
	WHERE (
		closed_at > CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
		OR closed_at IS NULL
	) 
	AND dept_id = qtd.id 
	AND deleted_at IS NULL
) AS 'Open/Closed', 
(
	SELECT COUNT(*)
	FROM tickets t
	LEFT JOIN depts td ON td.id = t.dept_id
	WHERE t.created_at < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
	AND (
		t.closed_at > CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
		OR t.closed_at IS NULL
	) 
	AND t.deleted_at IS NULL
) AS hide_total_open_new, 
SUM(
	CASE WHEN 
		qtl.time_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY 
		AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
		AND qta.deleted_at IS NULL 
	THEN qtl.hours ELSE 0 
	END
) AS 'Time Spent', 
 (
	SELECT SUM(tl.hours)
	FROM time_log tl
	WHERE tl.time_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY 
	AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY
	AND tl.ticket_action_id IS NOT NULL 
	AND tl.deleted_at IS NULL
) AS hide_total_worked_hrs
FROM depts AS qtd
LEFT OUTER JOIN tickets AS qt ON qtd.id = qt.dept_id
LEFT JOIN ticket_actions qta ON qta.ticket_id = qt.id
LEFT JOIN time_log qtl ON qtl.ticket_action_id = qta.id
GROUP BY qtd.id
ORDER BY qtd.lft ASC;