<?PHP

// What happened to User X's pages after s/he worked on them?

$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager()) die("permission denied");

$subject_username = 'jmdyck';
$subject_round_id = 'P1';

echo "<h2>What happened to $subject_username's $subject_round_id pages after s/he worked on them?</h2>\n";

/*
dpsql_dump_query("
    SELECT STRAIGHT_JOIN
        e1.projectid,
        e1.image,
        
        e1.event_id, from_unixtime(e1.timestamp) as e1_t,
        e2.event_id, from_unixtime(e2.timestamp) as e2_t,
        e2.event_type,
        e2.round_id,
        e2.username
    FROM page_events AS e1 JOIN page_events AS e2 USING (projectid,image)
    WHERE e1.username='$subject_username'
        AND e1.round_id='$subject_round_id'
        AND e1.event_type='saveAsDone'
        AND e2.event_id > e1.event_id
    ORDER BY e1.projectid, e1.image, e1.event_id, e2.event_id;
") or die(mysql_error());
*/

mysql_query("
    CREATE TEMPORARY TABLE t1
    SELECT projectid, image, MAX(event_id) AS event_id
    FROM page_events
    WHERE username='$subject_username' AND round_id='$subject_round_id' AND event_type='saveAsDone'
    GROUP BY projectid, image
    ORDER BY projectid, image
") or die(mysql_error());

dpsql_dump_query("
    SELECT
        t1.projectid, t1.image, t1.event_id,
        e2.event_id, from_unixtime(e2.timestamp) as e2_t,
        e2.event_type,
        e2.round_id,
        e2.username
    FROM t1 JOIN page_events AS e2
        ON (t1.projectid=e2.projectid AND t1.image=e2.image AND t1.event_id < e2.event_id)
    -- ORDER BY t1.projectid, t1.image, t1.event_id, e2.event_id
    ORDER BY e2.event_id
") or die(mysql_error());

// vim: sw=4 ts=4 expandtab
?>
