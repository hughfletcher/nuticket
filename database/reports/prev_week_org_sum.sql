SELECT 
	qo.id AS hide_id, 
	qo.lft AS hide_lft, 
	qo.rgt AS hide_rgt, 
	qo.name AS Name, 
	(
		SELECT COUNT(*)
		FROM tickets
		WHERE created_at 
			BETWEEN CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))+6 DAY 
			AND CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
		AND org_id = qo.id 
		AND deleted_at IS NULL
	) AS Created, 
	(
		SELECT COUNT(*)
		FROM orgs o
		LEFT JOIN tickets t ON o.id = t.org_id
		WHERE t.created_at 
			BETWEEN CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))+6 DAY 
			AND CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
		AND t.deleted_at IS NULL
	) AS hide_total_created, 
	(
		SELECT COUNT(*)
		FROM tickets
		WHERE closed_at 
			BETWEEN CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))+6 DAY 
			AND CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
		AND org_id = qo.id 
		AND deleted_at IS NULL
	) AS Closed, 
	(
		SELECT COUNT(*)
		FROM orgs o
		LEFT JOIN tickets t ON o.id = t.dept_id
		WHERE t.closed_at 
			BETWEEN CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))+6 DAY 
			AND CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
		AND t.deleted_at IS NULL
	) AS hide_total_closed, 
	(
		SELECT COUNT(*)
		FROM tickets
		WHERE 
		(
			closed_at > CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
			OR closed_at IS NULL
		) 
		AND org_id = qo.id 
		AND deleted_at IS NULL
	) AS 'Open/Closed', 
	(
		SELECT COUNT(*)
		FROM tickets t
		LEFT JOIN orgs o ON o.id = t.dept_id
		WHERE t.created_at < CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
		AND (
			t.closed_at > CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
			OR t.closed_at IS NULL
		) 
		AND t.deleted_at IS NULL
		) AS hide_total_open_new, 
	SUM(
		CASE 
			WHEN qtl.time_at 
				BETWEEN CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))+6 DAY 
				AND CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
				AND qta.deleted_at IS NULL 
			THEN qtl.hours 
			ELSE 0 
			END
	) AS 'Time Spent', 
	(
		SELECT SUM(tl.hours)
		FROM time_log tl
		WHERE tl.time_at 
			BETWEEN CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))+6 DAY 
			AND CONVERT_TZ(CURDATE(), '+0:00', '-6:00') - INTERVAL DAYOFWEEK(CONVERT_TZ(CURDATE(), '+0:00', '-6:00'))-1 DAY 
		AND tl.ticket_action_id IS NOT NULL 
		AND tl.deleted_at IS NULL
	) AS hide_total_worked_hrs
FROM orgs AS qo
LEFT OUTER JOIN tickets AS qt ON qo.id = qt.org_id
LEFT JOIN ticket_actions qta ON qta.ticket_id = qt.id
LEFT JOIN time_log qtl ON qtl.ticket_action_id = qta.id
GROUP BY qo.id
ORDER BY qo.lft ASC;