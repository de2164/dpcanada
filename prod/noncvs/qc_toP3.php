<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'project_events.inc');
include_once($relPath.'DPage.inc');

if (!user_is_a_sitemanager() && 
    $pguser != 'PM QC' &&
    $pguser != 'stygiana') die("You are not allowed to run this script");

error_reporting(E_ALL);

echo "<pre>";

// get the projectid.
// check that it's a QC project, 
//   and that it's in New Project or P1 unavailable
//   and has some pages
// move it into P3 available: 
//   update projects table
//   update <projectid> table
//   assume there is no project thread

echo "<h2>Move a quality control project into P3</h2>";
echo "\n";

$round_ids_ = array("P1", "P2", "P3", "F1", "F2");

// see what we have...
$projectid      = array_get( $_POST, 'projectid',  NULL );
$submit_button  = array_get( $_POST, 'submit_button', '' );
switch ( $submit_button )
{
    case '':
        // we are not here as a result of submitting the form. Display it.
        display_form($projectid, FALSE);
        break;

   case 'Check':
        // we are here as a result of submitting the form. 
        // we want to check that everything's OK, and give the user the
        // option to bail
        do_check($projectid);
        display_form($projectid, TRUE);
        break;

    case 'Do it!':
        // we are here as a result of submitting the form.
        // we want to go ahead and actually do the stuff.
        // do the checks again, just in case
        do_check($projectid);
        do_stuff($projectid);
        $url = "$code_url/project.php?id={$projectid}&amp;detail_level=3";
        echo "\n\n<a href='$url'>Project page</a>\n";
       break;

    default:
        // we're not meant to get here. What on earth is going on?
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

function display_form($projectid, $checking)
{

    echo "<form method='post'>";
    if (!$checking) {
        // need to get user input
        echo "\nid of the QC project that's to go into P3: <input type='text' name='projectid' size='35' ></input>\n";
        echo "<input type='submit' name='submit_button' value='Check'>";
    }
    else
    {
        echo "\nIf this information isn't correct, use your back button to correct it.\n";

        // just need to get the user's OK to go ahead
        echo "<input type='hidden' name='projectid' value='$projectid'>";
        echo "<input type='submit' name='submit_button'      value='Do it!'>";
    }
    echo "</form>";
}

// project exists, is a QC project, is a new project or in P1 unavail,
// and has some pages

function do_check($projectid)
{
    if ( is_null($projectid) || 
         "" == $projectid)
    {
        die( "Error: must supply projectid" );
    }

    // die if there's no project table for the project
    $res= mysql_query("
            DESCRIBE $projectid
        ") or die(mysql_error());

    // die if there's anything other than a single row in the projects table
    $res = mysql_query("
            SELECT nameofwork, username, state, n_pages
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
 
    // OK, the project exists
    list($title, $pm, $current_state, $n_pages) = mysql_fetch_row($res);
    echo "   projectid : $projectid\n";
    echo "   title     : $title\n";
    echo "   PM        : $pm\n";
    echo "   state     : $current_state\n";
    echo "   pages     : $n_pages\n";

    // is it a QC project?
    $pos = strpos($title, 'Quality control');
    $qc_ok = str_contains($title, '{Quality control}') && ($pm == 'PM QC');
    if (!$qc_ok) {
        die("The project must be a quality control project");
    }
    // now see if we're happy with the state it's in
    $state_ok =  "P1.proj_unavail" == $current_state || 
        "project_new" == $current_state; 

    if (!$state_ok) {
        die("Error: project must be a New Project or in P1 unavailable"); 
    }

    // does it have pages?
    if ($n_pages == 0)
    {
        die("The project must have some pages");
    }

    // we're OK to go ahead
    echo ("\n    The project will be moved into P3 available.");
}

// put the project into P3 available
//   update projects table
//   update <projectid> table
function do_stuff($projectid)
{
    global $pguser;
    $res = mysql_query("
            SELECT state
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());
    list($old_state) = mysql_fetch_row($res);
    $new_state = 'P3.proj_avail';
    $mdate = time();
    $query = "
    UPDATE projects SET state = '$new_state',
                        modifieddate = '$mdate'
                    WHERE projectid = '$projectid';";
    $res = mysql_query($query) or die(mysql_error());
    echo "\n   Updated projects table";
    // WARNING: round number hard coded
    Pages_prepForRound( $projectid, 3 );
    echo "\n   Updated page information";
    log_project_event( $projectid, $pguser, 'transition', $old_state, 
                       $new_state, 'QC project moving to P3');
}
// vim: sw=4 ts=4 expandtab
?> 
