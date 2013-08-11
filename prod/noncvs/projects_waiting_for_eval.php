<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'Stopwatch.inc');

if (!(user_is_a_sitemanager() || user_is_an_access_request_reviewer())) die("permission denied");

$cache_usernames = FALSE;
// TRUE
// P2/P3: 511 projects
// 517.138472 for all queries; 0.021004 for the first query
// F1/F2: 184 projects
// 240.744743 for all queries; 0.007412 for the first query
// FALSE
// P2/P3:
// 81.928603 for all queries; 0.021038 for the first query
// F1/F2:
// 92.55318 for all queries; 0.007674 for the first query

error_reporting(E_ALL);

echo "<pre>";


echo "<h2>Find projects with work waiting for review</h2>";
echo "\n";

$round_ids_ = array("P1", "P2", "P3", "F1", "F2");
$states_ = array("proj_avail", "proj_waiting");
// get the work round and review round

// see what we have... 
$submit_button  = array_get( $_POST, 'submit_button', '' );
$work_round  = array_get( $_POST, 'work_round', '' );
$review_round  = array_get( $_POST, 'review_round', '' );
$get_waiting  = array_get( $_POST, 'get_waiting', '' );

switch ( $submit_button )
{
    case '':
        // we are not here as a result of submitting the form. Display it.
        display_form($work_round, $review_round, $get_waiting, $round_ids_);
        break;

    case 'Do it!':
        display_form($work_round, $review_round, $get_waiting, $round_ids_);
        // we are here as a result of submitting the form.
        // we want to go ahead and actually do the stuff.
        do_stuff($work_round, $review_round, $get_waiting, $states_);
       break;

    default:
        // we're not meant to get here. What on earth is going on?
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

function display_form($work_round, $review_round, $get_waiting, $round_ids_)
{

    echo "<form method='post'>";

    echo "\n\nWork round <select name='work_round'>";
    foreach ($round_ids_ as $rid)
    {
        $sel = "";
        if ($work_round == $rid) {
            $sel = " selected='selected'";
        }
        echo "\n                  <option value='$rid' $sel>$rid</option>";
    }
    echo "\n      </select>  ";

    echo "&nbsp;&nbsp;&nbsp;&nbsp;Review round <select name='review_round'>";
    foreach ($round_ids_ as $rid)
    {
        $sel = "";
        if ($review_round == $rid) {
            $sel = " selected='selected'";
        }
        echo "\n                  <option value='$rid' $sel>$rid</option>";
    }
    echo "\n      </select>  ";
    echo "\n\nE.g., for F2 access work round is F1 and review round is F2";
    $sel = "";
    $unsel = " checked='checked'";
    if ($get_waiting == 1) {
        $sel = " checked='checked'";
        $unsel = "";
    }
    echo "\n\nGet projects that are waiting <input type='radio' name='get_waiting' value='1' $sel>&nbsp;&nbsp;&nbsp;&nbsp;or ";
    
    echo "<input type='radio' name='get_waiting' value='0' $unsel> available in the review round";
    echo "\n\n<input type='submit' name='submit_button' value='Do it!'>";

    echo "\n</form>";
}

// find the list of relevant projects that any evaluatee has worked on
// then for each project, list the evaluatees and the number of pages they've done
function do_stuff($work_round, $review_round, $get_waiting, $states_)
{
    global $code_url;

    $state = $review_round . "." . $states_[$get_waiting];
    // query for all projects waiting for (or available in) the round. 
    // We do this rather than
    // just look for all relevant projects because of the hassles with
    // the projectid field in the page_events table when projects have
    // been merged
    $query = "
              SELECT projects.projectid, projects.nameofwork, projects.state 
              FROM projects
              WHERE projects.state = '$state' 
             ";
    $watch = new Stopwatch;
    $watch->start();
    $result = mysql_query($query) or die(mysql_error());
    $ttaken1 = $watch->read();

    echo "\n</pre>";
    // OK, we've got the projects
    echo "<p>Projects in $state that had work done in $work_round by people waiting to be evaluated for $review_round</p>";


    echo "\n<table border='1'>";
    echo "\n<tr><th width='50%'>Project</th><th>Usernames</th><th>Users</th><th>Pages</th></tr>";
    $round = get_Round_for_round_id($work_round);
    $col = $round->user_column_name;

    // now get the info we want for each of them
    $project_count = deal_with_projects($result, $col, $review_round);
    echo "\n</table>";
    echo "\n<p>$project_count projects</p>";
    $watch->stop();
    $ttaken2 = $watch->read();
    echo "\n<p>$ttaken2 for all queries; $ttaken1 for the first query</p>";
}

// go through the projects looking for the ones we want
function deal_with_projects($result, $col, $review_round)
{
    global $code_url, $cache_usernames;
    $project_count = 0;
    $unames = '';
    if ($cache_usernames)
    {
        // we only need to find the relevant usernames once
        $query = "
                   SELECT username
                   FROM usersettings
                   WHERE usersettings.setting = '$review_round.access' AND
                         usersettings.value = 'requested'
                 ";
        $user_result = mysql_query($query) or die(mysql_error());
        $first = TRUE;
        while ( list($username) = mysql_fetch_row($user_result) )
        {
            if (! $first) 
            {
                $unames .= ", ";
            }
            $first =  FALSE;
            $unames .= "'$username'";
        }
    }

	while ( list($projectid, $title, $state) = mysql_fetch_row($result) )
	{ 
        $url = "$code_url/project.php?id=$projectid&detail_level=3";
        if ($cache_usernames)
        {
            $query = "
                   SELECT $projectid.$col, COUNT(*)
                   FROM $projectid
                   WHERE $projectid.$col IN ($unames)
                   GROUP BY $projectid.$col
                 ";
        }
        else
        {
            // do the join for each project in turn
            $query = "
                   SELECT $projectid.$col, COUNT(*)
                   FROM $projectid, usersettings
                   WHERE usersettings.setting = '$review_round.access' AND
                         usersettings.value = 'requested' AND
                         $projectid.$col = usersettings.username
                   GROUP BY $projectid.$col
                 ";
        }
        $proj_result = mysql_query($query) or die(mysql_error());
        $user_count = mysql_num_rows($proj_result);
        if ($user_count > 0)
        {
            $project_count ++;
            echo "\n<tr><td><a href='$url'>$title</a></td>";
            $tpages = 0;
            echo "\n<td>";
            while ( list($username, $pagecount) = mysql_fetch_row($proj_result) )
            { 
                echo "\n$username, $pagecount pages<br />";
                $tpages += $pagecount;
            }
            echo "\n</td>";
            echo "\n<td>$user_count</td><td>$tpages</td>";
            echo "\n</tr>";
        }
    }
    return $project_count;
}
// vim: sw=4 ts=4 expandtab
?> 
