<?
$relPath="../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_trans.inc');

// Verify that it's the pp-er trying to perform this action.

$project = new Project($projectid);
if (! $project->PPer_is_current_user || $project->state != PROJ_POST_FIRST_CHECKED_OUT ) {
  echo _("The project is not checked out to you.");
  exit;
}

$qry =  mysql_query("
    UPDATE projects SET postcomments = '$postcomments'
    WHERE projectid = '$projectid'
");

$msg = _("Comments added.");
metarefresh(1, "$code_url/project.php?id=$projectid", $msg, $msg);

?>
