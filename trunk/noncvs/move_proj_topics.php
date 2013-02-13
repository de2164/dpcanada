<?
$relPath='../c/pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'f_move_post.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() ) die("not allowed");

$result = mysql_query("
SELECT state, projectid FROM projects where topic_id is not null and state != 'proj_submit_pgposted'
");

while ($row = mysql_fetch_array($result)) {

	$state = $row["state"];
	$project_to_move = $row["projectid"];

	$foo = get_forum_id_for_project_state($state);
	echo "Attempt to move $project_to_move to $foo<br>";

	move_project_thread( $project_to_move, get_forum_id_for_project_state($state) );

}

echo "All done!";


?>
