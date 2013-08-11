<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

if (!user_is_a_sitemanager()) die("permission denied");

$username = @$_REQUEST['username'];
echo "username=$username";

if ( empty($username) )
{
    echo "<form>";
    echo "Project Manager's username: <input type='text' name='username'>";
    echo " ";
    echo "<input type='submit' value='Find Projects'>";
    echo "</form>";
    return;
}

dpsql_dump_query("
    SELECT nameofwork, state
    FROM projects
    WHERE username='$username'
        AND state in (
        '".PROJ_NEW."',
        '".PROJ_P1_UNAVAILABLE."',
        '".PROJ_P1_WAITING_FOR_RELEASE."'
        )
        AND n_pages > 0
        AND n_pages = n_available_pages
    ORDER BY ".sql_collater_for_project_state('state').", nameofwork
");


// vim: sw=4 ts=4 expandtab
?>
