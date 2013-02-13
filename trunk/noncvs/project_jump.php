<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'DPage.inc'); // unused??
include_once('../c/tools/project_manager/page_operations.inc');
include_once($relPath.'project_events.inc');

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}

echo "<pre>";

echo "<h2>Jump a project to a specific round</h2>";
echo "\n";

$submit_button = array_get( $_POST, 'submit_button', '' );

switch ( $submit_button )
{
    case '':
        echo "<form method='post'>";
        echo "Project: <input type='text' name='projectid'>\n";
        echo "Round:   <select name='new_state'>
                     <option value='P1.proj_unavail'>P1</option>
                     <option value='P2.proj_unavail'>P2</option>
                     <option value='P3.proj_unavail'>P3</option>
                     <option value='F1.proj_unavail'>F1</option>
                     <option value='F2.proj_unavail'>F2</option>
                     <option value='proj_post_first_available'>PP available</option>
                     <option value='proj_post_first_checked_out'>PP checked out</option>
                     </select>\n";
        echo "<input type='submit' name='submit_button' value='Check'>";
        echo "</form>";
        break;

    case 'Check':

        $projectid  = array_get( $_POST, 'projectid',  NULL );
        $new_state = array_get( $_POST, 'new_state', NULL );

        do_stuff( $projectid, $new_state, TRUE );

        echo "<form method='post'>";
        echo "<input type='hidden' name='new_state'   value='{$new_state}'>";
        echo "<input type='hidden' name='projectid'   value='{$projectid}'>";
        echo "<input type='submit' name='submit_button'      value='Do it!'>";
        echo "</form>";
        break;

    case 'Do it!':

        $projectid = array_get( $_POST, 'projectid',  NULL );
        $new_state = array_get( $_POST, 'new_state', NULL );

        do_stuff( $projectid, $new_state, FALSE );

        $url = "$code_url/project.php?id={$projectid}&amp;detail_level=3";
        echo "\n\n<a href='$url'>Project page</a>\n";

        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

function do_stuff( $projectid, $new_state, $just_checking )
{
    global $pguser;
    if ( is_null($projectid) )
    {
        die( "Error: no projectid supplied" );
    }

    echo "    projectid : $projectid\n";

    $res = mysql_query("
            SELECT nameofwork, state, checkedoutby
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());

    $n_projects = mysql_num_rows($res);
    if ( $n_projects == 0 )
    {
        die( "projects table has no match for projectid='$projectid'" );
    }
    else if ( $n_projects > 1 )
    {
        die( "projects table has $n_projects matches for projectid='$projectid'. (Can't happen)" );
    }

    list($title, $current_state, $checkedoutby) = mysql_fetch_row($res);

    echo "    title     : $title\n";

    // ----------------------

    echo "    state     : $current_state\n";
    if ( 'proj_post_first_checked_out' == $new_state &&
         '' == $checkedoutby )
    {
        die ("project must have a PPer assigned to jump to $new_state" );
    }
    else if ( 'proj_post_first_checked_out' == $new_state )
    {
        echo "    PPer      : $checkedoutby\n";
    }
    if ( 'proj_post_first_available' == $new_state &&
         '' != $checkedoutby )
    {
        die ("project has a PPer assigned ($checkedoutby) so can't jump to $new_state" );
    }

    if ( $just_checking )
    {
        echo "</pre>\n";
        echo "<p style='font-size:150%; font-weight:bold;'>\n";
        echo "The project will be jumped to state {$new_state}.<br />\n";
        echo "No page details will be changed.\n";
        echo "</p>\n<pre>";
        return;
    }

    // ----------------------------------------------------

    $res = mysql_query("
            UPDATE projects 
            SET state = '$new_state'
            WHERE projectid='$projectid'
        ") or die(mysql_error());
    echo "    jumped to : $new_state\n";
    log_project_event( $projectid, $pguser, 'transition', $current_state,
                       $new_state, 'Project jumped to correct state');

}

// vim: sw=4 ts=4 expandtab
?>
