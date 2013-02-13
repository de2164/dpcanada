<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

// if (!user_is_a_sitemanager()) die("permission denied");

echo "<pre>";

echo "Hm.\n";

$result = dpsql_query("SELECT * 
    FROM project_events 
    WHERE (details1 = 'P3.proj_waiting' AND details2 = 'F1.proj_waiting')
    OR (details1 = 'F2.proj_waiting' AND details2 = 'proj_post_first_checked_out')");

echo "There were ".mysql_num_rows($result)." skips done since (whenever).\n";

echo "Who's been naughty, and who's been nice?\n";

while ($skip = mysql_fetch_object($result))
{
//    echo <<<TEXT
// For project "$skip->projectid", skipped $skip->timestamp by $skip->who\n
// TEXT;
    $result2 = dpsql_query("SELECT CONCAT('<a href=\"http://www.pgdp.net/c/project.php?id=',project_events.projectid,'&detail_level=3\">',projects.nameofwork,'</a>') as 'what',
project_events.who, FROM_UNIXTIME(project_events.timestamp) AS 'when',
projects.postproofer, projects.checkedoutby, projects.username as 'PM'
 FROM project_events,projects WHERE project_events.projectid = '$skip->projectid' AND details1 = 'proj_post_first_checked_out' AND details2 = 'proj_post_first_available'
AND projects.projectid = project_events.projectid");

    if (mysql_num_rows($result2))
    {
        dpsql_dump_query_result($result2);
    }

}



// vim: sw=4 ts=4 expandtab
?>
