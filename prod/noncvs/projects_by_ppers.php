<?PHP

$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'project_states.inc');

$no_stats = 1;
theme( 'Available projects, sorted by PPer', 'header' );

$order_by_clause = "ORDER BY 'PPer',nameofwork DESC";

for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
	$round = get_Round_for_round_number($rn);
  echo "<a href='http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?rn=$rn'>$round->name</a> ";
}
echo "<br />";

$rn = isset($_GET['rn']) ? $_GET['rn'] : 4;

$round = get_Round_for_round_number($rn);

echo "<h1>$round->name</h1>";

dpsql_dump_query("SELECT
	 		REPLACE(REPLACE(
			'<a href=\"$code_url/project.php?id=PROJECTID\">TITLE</a>',
			'PROJECTID',projectid),
			'TITLE',nameofwork) as title,
	 authorsname as 'Author',n_available_pages as 'Avail pages',n_pages as 'Pages',checkedoutby as 'PPer',username as 'Project Manager'
	 FROM projects
	 WHERE state = '$round->project_available_state' AND checkedoutby != ''
	 $order_by_clause");


echo "<br />";


?>
