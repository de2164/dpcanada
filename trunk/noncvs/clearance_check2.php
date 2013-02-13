<?
$relPath = '../c/pinc/';

include_once($relPath . 'dp_main.inc');
include_once($relPath . 'site_vars.php');
include_once($relPath . 'dpsql.inc');
include_once($relPath . 'connect.inc');
include_once($relPath . 'user_is.inc');
include_once($relPath . 'theme.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
{
    die("permission denied");
}

theme('','header');
echo "<br /><br />";

if (isset($_GET['makeunavail']) && user_is_a_sitemanager() )
{
	$project = mysql_fetch_object(dpsql_query("SELECT * FROM projects 
WHERE projectid = '$_GET[projectid]'"));

       echo $project->state{2};
       if (!   (  $project->state{0} != 'P' ||
		$project->state{0} != 'F' ) &&
		$project->state{2} != '.')

	die("Can't operate on projects not in rounds"); 

        $round_id = substr($project->state,0,2);

	$result = dpsql_query("UPDATE projects SET state = 
'$round_id.proj_unavail' WHERE projectid = '$project->projectid'");

        if ($result)
                  echo "Project made unavailable! <a 
href='../c/tools/project_manager/editproject.php?project=$project->projectid&action=edit'>Edit 
project</a>.";

	die;
}


$sql_query = "SELECT REPLACE(REPLACE('<a href=\"$code_url/project.php?id=PROJECTID\">TITLE</a>','TITLE',nameofwork),'PROJECTID',projectid) AS 'Title',
                authorsname AS 'Author',
                clearance AS 'Clearance',
                username AS 'Project Manager',
                state AS 'State'
                FROM projects
		
		WHERE clearance NOT LIKE 'gbn%' 
		AND clearance NOT LIKE '200%' 
		AND clearance NOT LIKE '% ok'

		AND state NOT IN 
('proj_submit_pgposted','project_new','project_delete','P1.proj_unavail') 
";


$sql_query .= "ORDER BY 'Project Manager'";

dpsql_dump_themed_query($sql_query);

echo "<br /><br />";


theme('','footer');

?>
