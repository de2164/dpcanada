<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

$limit = isset($_REQUEST['n']) ? mysql_real_escape_string($_REQUEST['n'])
    : 100;

dpsql_dump_query("SELECT 
FROM_UNIXTIME(timestamp) as 'time', projects.nameofwork,who,event_type,details1,details2,details3
 FROM project_events,projects WHERE projects.projectid = project_events.projectid ORDER BY event_id DESC LIMIT $limit");

// vim: sw=4 ts=4 expandtab
?>
