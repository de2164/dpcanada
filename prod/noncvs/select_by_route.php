<?PHP
// Select projects according to the route they take through the rounds.
// Currently hardcoded for garwyne's request at:
// http://www.pgdp.net/phpBB2/viewtopic.php?p=425159#425159

$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() ) die("permission denied"); //x

$desired_route = 'P1->P1->P2->P3';

dpsql_dump_query("
    SELECT
        projectid,
        GROUP_CONCAT(
            REPLACE(details2,'.proj_done','')
            ORDER BY event_id
            SEPARATOR '->'
        ) AS route
    FROM project_events
    WHERE event_type = 'transition'
        AND details2 LIKE '%.proj_done'
    GROUP BY projectid
    HAVING route LIKE '$desired_route%'
    ORDER BY projectid
");

// vim: sw=4 ts=4 expandtab
?>
