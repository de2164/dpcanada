<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'project_events.inc');

// send a project through the rounds again

// need to specify:
//  which project
//  round from which to take proofed text (it is moved into the OCR slot)
//  round in which the project is to end up (as waiting)

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}
echo "<pre>";

echo "<h2>Send a project back through the rounds again</h2>";
echo "\n";

$projectid      = array_get( $_POST, 'projectid',  NULL );
$text_round_id  = array_get( $_POST, 'text_round_id', NULL );
$new_round_id   = array_get( $_POST, 'new_round_id',  NULL );
$submit_button  = array_get( $_POST, 'submit_button', '' );

$projectid = trim($projectid);

switch ( $submit_button )
{
    case '':
        // we are not here as a result of submitting the form. Display it.
        display_form($projectid, $text_round_id, $new_round_id, FALSE);
        break;

    case 'Check':
        // we are here as a result of submitting the form. 
        // we want to check that everything's OK, and give the user the
        // option to bail
        do_check($projectid, $text_round_id, $new_round_id);
        display_form($projectid, $text_round_id, $new_round_id, TRUE);
        break;

    case 'Do it!':
        // we are here as a result of submitting the form.
        // we want to go ahead and actually do the stuff.
        // do the checks again, just in case
        do_check($projectid, $text_round_id, $new_round_id);
        do_stuff($projectid, $text_round_id, $new_round_id);
        $url = "$code_url/project.php?id={$projectid}&amp;detail_level=3";
        echo "\n\n<a href='$url'>Project page</a>\n";
       break;

    default:
        // we're not meant to get here. What on earth is going on?
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

function display_form($projectid, $text_round_id, $new_round_id, $checking)
{

    echo "<form method='post'>";
    if (!$checking) {
        // need to get user input
        echo "id of the project to send round again:   <input type='text' name='projectid'>\n";
        echo "Round from which to take proofed text:   <select name='text_round_id'>
                     <option value='P1'>P1</option>
                     <option value='P2'>P2</option>
                     <option value='P3'>P3</option>
                     <option value='F1'>F1</option>
                     <option value='F2'>F2</option>
                     </select>\n";
        echo "Round to put project into:               <select name='new_round_id'>
                     <option value='P1'>P1</option>
                     <option value='P2'>P2</option>
                     <option value='P3'>P3</option>
                     <option value='F1'>F1</option>
                     <option value='F2'>F2</option>
                     </select>\n";

        echo "<input type='submit' name='submit_button' value='Check'>";
    }
    else
    {

        echo "\nIf this information isn't correct, use your back button to correct it.\n";
        // just need to get the user's OK to go ahead
        echo "<input type='hidden' name='projectid' value='$projectid'>";
        echo "<input type='hidden' name='text_round_id' value='$text_round_id'>";
        echo "<input type='hidden' name='new_round_id' value='$new_round_id'>";
        echo "<input type='submit' name='submit_button'      value='Do it!'>";

    }
    echo "</form>";

}

// need to check that
//   valid project id
//   text_round_id, new_round_id both specified
//   project currently
//     either unavail or waiting, or in PP 
//   text exists in the text_round
//   show information to user for them to check
// die if anything's invalid
function do_check($projectid, $text_round_id, $new_round_id)
{
 
    if ( is_null($projectid) || 
         is_null($text_round_id) ||
         is_null($new_round_id) ||
         "" == $projectid ||
         "" == $text_round_id ||
         "" == $new_round_id )
    {
        die( "Error: must supply projectid, round to take text from and new round for project" );
    }

    echo "   projectid : $projectid\n";

    // die if there's no project table for the project
    $res= mysql_query("
            DESCRIBE $projectid
        ") or die(mysql_error());

    // die if there's anything other than a single row in the projects table
    $res = mysql_query("
            SELECT nameofwork, state, n_pages
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
    list($title, $current_state, $n_pages) = mysql_fetch_row($res);
    echo "   title     : $title\n";
    echo "   state     : $current_state\n";
    echo "   pages     : $n_pages\n";

    // now see if we're happy with the state it's in
    $state_ok = false;
    // first, it's OK if it's in PP unavail or avail
    // next, it's OK if it's unavail or waiting 
    $state_ok = "proj_post_first_unavailable" == $current_state ||
        "proj_post_first_available" == $current_state ||
        "proj_post_first_checked_out" == $current_state ||
        "proj_waiting" == substr($current_state, 3) ||
        "proj_unavail" == substr($current_state, 3);

    if (!$state_ok) {
        die("Error: project must be waiting or unavailable in a round, or must be in PP");
    }

    echo "
     The new project title will be:
        $title {". $text_round_id . "->" . $new_round_id ."}\n";

    echo "
       Text from $text_round_id will be moved into the OCR slot
       and the project will be moved into $new_round_id.
     ";

    // now see if it has been proofed in the relevant round, ie that the
    // text to be used actually exists
    $text_round = get_Round_for_round_id($text_round_id);
    $sums_str = "SUM( {$text_round->text_column_name} != '' )";
    $res = mysql_query("
            SELECT $sums_str
            FROM $projectid
        ") or die(mysql_error());
    list($sum_text) = mysql_fetch_row($res);
    $text_pages_count = intval($sum_text);
        echo "      
       There are $text_pages_count pages in $text_round_id with proofed text
       ";
    if ($text_pages_count != $n_pages) 
    {
        $empty_pages = $n_pages - $text_pages_count;
        // we're OK if all the pages have text. if not,
        // we'll tell the user and give them the option to bail
        echo "
       There are $empty_pages pages that have no proofed text in $text_round_id.
       You should check that these are genuinely empty pages before continuing.\n";
    }
}

// first we take a snapshot of the project table
// then put the correct text in as the OCR, 
//      clear the information from all other rounds
//      add the rubric to the project title
//      move the project to the correct round (unavailable)
//         (including setting the page states)
function do_stuff($projectid, $text_round_id, $new_round_id)
{
    global $db_name, $db_link, $pguser;

    // project_event needs this:
    $res = mysql_query("
            SELECT state
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());
    list($old_state) = mysql_fetch_row($res);

    echo "\n Creating project snapshot...\n";
    $table_name = $projectid . "_" . time();
    $res = mysql_select_db("project_snapshots", $db_link) 
        or die(mysql_error());
    $query = "
         CREATE TABLE $table_name 
                SELECT * FROM $db_name.$projectid";
    echo "   ... Executing query  $query\n";
    $res = mysql_query($query) or die(mysql_error());
    $res = mysql_select_db($db_name, $db_link) or die(mysql_error());
    echo "   ... new table created: project_snapshots.$table_name\n";

    echo "\n Updating projects table ...\n";
    $query = "
    UPDATE projects SET state = '$new_round_id.proj_unavail',
                        n_available_pages = n_pages, 
                        nameofwork = CONCAT(nameofwork, ' {" . $text_round_id . "->" . $new_round_id ."}') 
                   WHERE projectid = '$projectid' LIMIT 1;";
    echo "   ... Executing query  $query\n";
    $res = mysql_query($query) or die(mysql_error());
    $n = mysql_affected_rows();
    echo "   ... $n rows updated.\n";

    echo "\n Updating $projectid table...\n";
    $text_round = get_Round_for_round_id($text_round_id);

    $query = "
    UPDATE $projectid SET master_text = {$text_round->text_column_name},
                           round1_text = '',
                           round1_user = '',
                           round1_time =0,
                           round2_text = '',
                           round2_user = '',
                           round2_time =0,
                           round3_text = '',
                           round3_user = '',
                           round3_time =0,
                           round4_text = '',
                           round4_user = '',
                           round4_time =0,
                           round5_text = '',
                           round5_user = '',
                           round5_time =0,
                           state = '$new_round_id.page_avail'";
    echo "   ... Executing query  $query\n";
    $res = mysql_query($query) or die(mysql_error());
    $n = mysql_affected_rows();
    echo "   ... $n rows updated.\n";
    log_project_event( $projectid, $pguser, 'transition', $old_state,
                       "$new_round_id.proj_unavail", 'Send project back through round(s)' );
}

// vim: sw=4 ts=4 expandtab
?>
