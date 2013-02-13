<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_can_work_in_stage($pguser, 'PP'))
    die ("permission denied");

if (isset($_GET['projectid']))
{
    dpsql_dump_query("SELECT nameofwork,clearance FROM projects WHERE projectid = '$_GET[projectid]'");
    die;
}

?>
  
<form method='get'>
<input type='text' name='projectid' value='projectID' /><br />
<input type='submit' value='Get clearance' />
</form>  

<?
// vim: sw=4 ts=4 expandtab
?>
