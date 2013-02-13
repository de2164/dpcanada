<?PHP
$relPath='../c/pinc/';
include($relPath.'dp_main.inc');
include($relPath.'dpsql.inc');
include($relPath.'user_project_info.inc');

echo "<h2>Event subscriptions for projects PM'd by $pguser</h2>\n";

echo "<p>The numbers indicate the number of users that have subscribed to the given event for the given project.</p>\n";

$columns = "
    CONCAT('<a href=\"$code_url/project.php?id=', projectid, '\">', nameofwork, '</a>') AS 'Project'
";
foreach( $subscribable_project_events as $event_code => $blurb )
{
    $columns .= ",\nSUM(user_project_info.iste_$event_code) AS '$blurb'";
}

dpsql_dump_query("
    SELECT $columns
    FROM user_project_info user_project_info JOIN projects USING (projectid)
    WHERE projects.username='$pguser'
    GROUP BY projectid
    ORDER BY nameofwork
");

// vim: sw=4 ts=4 expandtab
?>
