<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'project_trans.inc');


$testing = true;

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}

// for now, change: 
// $where_condition
// $why
// $new_state
// $user

$where_condition = "projectid = 'projectID455039d0c6eca' "; 
$why = "click-through cleanup (garweyne request)";
$new_state = "P1.proj_unavail";
$user = 'mikeyc21';

// this should be more general.
// ideal would be: equivalent of project search page
// plus dropdown for state to move to.
// more achievable would be: input WHERE clause for projects
// plus dropdown for state to move to

// then "check" would list the projects found (at least title and current state)
// and ask you to confirm

// "do it" would move all projects to the new state


switch ( $submit_button )
{
    case '':
        echo "<form method='post'>";
        echo "WHERE $where_condition";
        echo "\n<br />new state: $new_state ";
        echo "\n<br />user: $user ";
        echo "\n<br />reason: $why ";
        echo "\n<br /><input type='submit' name='submit_button' value='OK'>";
        echo "</form>";
        break;

    case 'OK':
        $res = mysql_query("SELECT nameofwork, projectid 
            FROM projects
            WHERE $where_condition
        ") or die(mysql_error());
        
        $n_projects = mysql_num_rows($res);
        if ( $n_projects == 0 )
        {
            die( "projects table has no match for '$where_condition'" );
        }

        while (list($projectname, $projectid)  = mysql_fetch_row($res))
        {
            echo project_transition( $projectid, $new_state, $user, array('details' => $why) );
            echo "$projectname moved to $new_state";
        }
}

// vim: sw=4 ts=4 expandtab
?>
