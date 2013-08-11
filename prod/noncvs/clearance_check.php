<?
$relPath = '../c/pinc/';

include_once($relPath . 'dp_main.inc');
include_once($relPath . 'site_vars.php');
include_once($relPath . 'dpsql.inc');
include_once($relPath . 'connect.inc');
include_once($relPath . 'user_is.inc');
include_once($relPath . 'theme.inc');

theme('','header');
echo "<br /><br />";

$can_see_others = user_is_a_sitemanager() || user_is_proj_facilitator();

$username = isset($_GET['uname']) && $can_see_others ? $_GET['uname'] : $pguser;

$sql_query = "SELECT REPLACE(REPLACE('<a href=\"$code_url/project.php?id=PROJECTID\">TITLE</a>','TITLE',nameofwork),'PROJECTID',projectid) AS 'Title',
                authorsname AS 'Author',
                clearance AS 'Clearance',
                username AS 'Project Manager',
                state AS 'State'
                
                FROM projects
				WHERE username = '$username'";

if (!isset($_GET['all']))
{
    $sql_query .= "AND clearance NOT LIKE 'gbn%' AND clearance NOT LIKE '200%' AND clearance NOT LIKE '% ok'";
}

$sql_query .= "ORDER BY 'State'";

echo isset($_GET['all']) ?
    "Showing all your projects. <a href='?q'>Show only those with suspect clearances.</a><br /><br />"
   :"Showing your projects with suspect clearances. <a href='?all'>Show all your projects.</a><br /><br />";
   
dpsql_dump_themed_query($sql_query);

echo "<br /><br />";

if( $can_see_others )
{
    echo "Site Admin/Facilitator: select a user: <form method='get'>
	<input type='text' name='uname' /><input type='submit' /></form>";
}

theme('','footer');

?>
