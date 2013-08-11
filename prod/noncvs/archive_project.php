<?
// This modified version of the crontab script 
// operates on a single project only.

$relPath="../c/pinc/";
include($relPath.'misc.inc');
include($relPath.'connect.inc');
include($relPath.'project_states.inc');
include_once($relPath.'archiving.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

// Find projects that were posted to PG a while ago
// (that haven't been archived yet), and:
// -- move the project's page-table to the archive database,
// -- move the project's directory out of $projects_dir
//    (for later off-site migration),
// -- mark the project as having been archived.

$dry_run = array_get( $_GET, 'dry_run', '' );
if ($dry_run)
{
    echo "This is a dry run.\n";
}

$project = array_get( $_GET, 'project', '' );
if (!$project)
    die("Specify projctid");


$result = mysql_query("
    SELECT *
    FROM projects
    WHERE
        projectid = '$project'
        AND archived = '0'
        AND state = '".PROJ_SUBMIT_PG_POSTED."'
") or die(mysql_error());

echo "Archiving page-tables for ", mysql_num_rows($result), " projects...\n";

while ( $project = mysql_fetch_object($result) )
{
    archive_project($project, $dry_run);
}

echo "archive_projects.php executed.";

// vim: sw=4 ts=4 expandtab
?>
