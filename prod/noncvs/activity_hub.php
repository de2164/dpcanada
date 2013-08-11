<?PHP
// This page covers all project-related activities of the site.
// For each, it:
// -- describes the activity;
// -- briefly summarizes its current state; and
// -- gives a link to the particular page for that activity.
//
// (Leaves out non-project-related activities like:
// forums, documentation/faqs, development, admin.)

$relPath="../c/pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'mentorbanner.inc');
include_once($relPath.'filter_project_list.inc');

$_Activity_Hub = _("Activity Hub");

$css_data = "
/* ------------------------------------------------------------------------ */
/* Round Status table on Activity Hub */
table.roundtable { 
    margin: 0;
    padding: 0;
    border-collapse: collapse;
    empty-cells: hide;
    width: 100%;
}

table.roundtable td,
table.roundtable th { 
    border: 1px solid black;
    padding: 2px;
    margin: 0; 
    font-size: 0.9em!important;
    text-align: center;
}

table.roundtable img {
    border: none;
    padding: 0;
    margin: 0;
}

table.roundtable th {
    background-color: #e0e8dd;
    font-weight: normal;
}

/* ------------------------------------------------------------------------ */
/* Progress bar */

div.progressbar {
    border: 1px solid black;
    font-size: 0.5em;
    float: left;
}
";

theme($_Activity_Hub, "header", array("css_data" => $css_data));

echo "<center><img src='$code_url/graphics/Activity_Hub.png' width='350' height='60' border='0' title='$_Activity_Hub' alt='$_Activity_Hub'></center>\n";

echo "<p>\n";
echo sprintf(_('Welcome to the %1$s Activity Hub. From this page you can view the phases of %1$s production.'),$site_abbreviation);
echo "\n";
/*
echo _("The information below is customised for you, and details the phases of production in which you can particpate.");
echo "\n";
*/
echo _("Follow the links to the specific areas of the site.");
echo "</p>\n";


$pagesproofed = get_pages_proofed_maybe_simulated();


// Unread messages
if ($pagesproofed <= 300)
{
    $numofPMs = get_number_of_unread_messages($pguser);
    if ($numofPMs > 0)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("You have received a private message in your Inbox.");
        echo "</div>";

        echo "<p>";
        echo _("This could be from somebody sending you feedback on some of the pages you had proofread earlier. We strongly recommend you <b>read</b> your messages. In the links at the top of this page, there is one that says Inbox. Just click on that to open your Inbox.");
        echo "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }

}


welcome_see_beginner_forum( $pagesproofed );

thoughts_re_mentor_feedback( $pagesproofed );


// Site News
if ($pagesproofed >= 20)
{

    if ($pagesproofed < 40)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("You can now see Site News.");
        echo "</div>";

        echo "<p>";
        echo _("Now that you have proofread 20 pages you can see the Site News. This is updated regularly with announcements from the administrators.");
        echo "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }

    show_news_for_page("HUB");
}

// Show any mentor banners.
foreach ( $Round_for_round_id_ as $round )
{
    if ( $round->is_a_mentor_round() &&
        user_can_work_on_beginner_pages_in_round($round) )
    {
        mentor_banner($round);
    }
}

// =============================================================================

echo "\n<hr>\n";

// ----------------------------------

// Get the project transitions for the number of projects completed today
// set the timestamp representing the start of today
$t_start_of_today = mktime(0,0,0,date('m'),date('d'),date('y'));

$res = mysql_query("
    SELECT details2, count(distinct projectid)
    FROM project_events
    WHERE event_type = 'transition' AND timestamp >= $t_start_of_today
    GROUP BY details2
") or die(mysql_error());

$n_project_transitions_in_state_ = array();
while ( list($project_state,$count) = mysql_fetch_row($res) )
{
    $n_project_transitions_in_state_[$project_state] = $count;
}

// Get the current count for the number of projects in their current state
$res = mysql_query("
    SELECT state, COUNT(*)
    FROM projects
    GROUP BY state
") or die(mysql_error());

$n_projects_in_state_ = array();
while ( list($project_state,$count) = mysql_fetch_row($res) )
{
    $n_projects_in_state_[$project_state] = $count;
}

$show_filtered_projects = (array_get( $_GET, "show_filtered", 0) == 1);
$show_filtered_option = TRUE;

// Proofers with fewer than 21 pages can't see the filter box on the round
// pages so prevent those users from selecting the filtered option
if ($pagesproofed <= 20)
{
    $show_filtered_projects = FALSE;
    $show_filtered_option = FALSE;
}


activity_summary_table($show_filtered_projects, $show_filtered_option);

activity_descriptions();

// ----------------------------------

function activity_summary_table($show_filtered_projects=FALSE, $show_filtered_option=TRUE)
// Prints out a table containing a row for each stage and project/page
// information.
// Arguments:
//   $show_filtered_projects = if TRUE, the the table will show numbers based
//                             on the user's project filter for that stage
//   $show_filtered_options  = if TRUE, the the table will allow the user to
//                             toggle between viewing numbers for ALL projects
//                             and just those for filters.
{
    global $Stage_for_id_, $code_url;

    // start the table
    echo "<a name='activities'></a>";
    echo "<h2>" . _("Activities") . "</h2>";
    echo "<div style='width: 100%;'>";
    echo "<table class='roundtable'>";

    // Loop through the stages three times, once each for Round, Pool, and
    // everything else.

    // Round headers
    echo "<tr>";
    echo "<td rowspan='2' colspan='3' style='border: none;'><img src='$code_url/graphics/icon_proofer.png'></td>";
#    echo "<th rowspan='2'>" . _("Your<br>Access") . "</th>";
    echo "<th colspan='4'>" .  _("Projects") . " - ";
    if($show_filtered_projects)
    {
        if($show_filtered_option)
            echo "<a href='?show_filtered=0#activities'>" . _("All") . "</a> | ";
        echo "<b>" . _("Filtered") . "</b>";
    }
    else
    {
        echo "<b>" . _("All") . "</b>";
        if($show_filtered_option)
            echo " | <a href='?show_filtered=1#activities'>" . _("Filtered") . "</a>";
    }
    echo "</th>";
    echo "<th colspan='3'>" . _("Pages Today") . "</th>";
    echo "</tr>\n";

    echo "<tr>";
    echo "<th>" . _("Total") . "</th>";
    echo "<th>" . _("Waiting") . "</th>";
    echo "<th>" . _("Available") . "</th>";
    echo "<th>" . _("Completed Today") . "</th>";
    echo "<th>" . _("Target") . "</th>";
    echo "<th>" . _("Completed") . "</th>";
    echo "<th>" . _("Status") . "</th>";
    echo "</tr>\n";

    // Round rows
    foreach ( $Stage_for_id_ as $stage )
    {
        if( !is_a( $stage, 'Round' ) )
            continue;
    
        $desired_states = array($stage->project_waiting_state, $stage->project_available_state, $stage->project_complete_state);

        summarize_stage($stage, $desired_states, $show_filtered_projects, $stage->id);
    }

    // Pool and Stage headers
    echo "<tr>";
    echo "<td rowspan='2' colspan='3' style='border: none;'><img src='$code_url/graphics/icon_pp.png'></td>";
#    echo "<th rowspan='2'>" . _("Your<br>Access") . "</th>";
    echo "<th colspan='3'>" . _("Projects") . " - ";
    if($show_filtered_projects)
    {
        if($show_filtered_option)
            echo "<a href='?show_filtered=0#activities'>" . _("All") . "</a> | ";
        echo "<b>" . _("Filtered") . "</b>";
    }
    else
    {
        echo "<b>" . _("All") . "</b>";
        if($show_filtered_option)
            echo " | <a href='?show_filtered=1#activities'>" . _("Filtered") . "</a>";
    }
    echo "</th>";
    echo "<td colspan='4' style='border: none;'></td>";
    echo "</tr>\n";

    echo "<tr>";
    echo "<th>" . _("Total") . "</th>";
    echo "<th>" . _("Available") . "</th>";
    echo "<th>" . _("In Progress") . "</th>";
    echo "<td colspan='4' style='border: none;'></td>";
    echo "</tr>\n";

    // Pool rows
    foreach ( $Stage_for_id_ as $stage )
    {
        if( !is_a( $stage, 'Pool' ) )
            continue;

        $desired_states = array($stage->project_available_state, $stage->project_checkedout_state);

        summarize_stage($stage, $desired_states, $show_filtered_projects, "{$stage->id}_av");
    }

    // Stage rows
    foreach ( $Stage_for_id_ as $stage )
    {
        if( is_a( $stage, 'Pool' ) || is_a( $stage, 'Round') )
            continue;

        $desired_states = array();

        summarize_stage($stage, $desired_states);
    }

    echo "</table>";
    echo "</div>";
}


function summarize_stage($stage, $desired_states, $load_filtered_projects=FALSE, $filter_type="")
// Prints out an activity summary table row for a specific stage (be it a
// Round, Pool, or Stage).
{
    global $pguser, $n_projects_in_state_, $n_project_transitions_in_state_, $code_url;

    // Get the stage identifier.
    $stage_icon_url = get_dyn_image_url_for_file("stage_icons/{$stage->id}");
    if ( !is_null($stage_icon_url) )
        $stage_id_bit = "<img src='$stage_icon_url' alt='($stage->id)' title='$stage->id'>";
    else
        $stage_id_bit = "$stage->id";

    // Get the stage description for displaying in the title of the link.
    $description = strip_tags($stage->description);

    // Determine access eligibility for this stage.
    $uao = $stage->user_access( $pguser );
    if($uao->can_access)
    {
        $access_icon = "activity_hub_graphics/access_yes.png";
        $access_text = _("You can work in this activity");
    }
    elseif($uao->all_minima_satisfied)
    {
        $access_icon = "activity_hub_graphics/access_eligible.png";
        $access_text = _("You are eligible to work in this activity");
    }
    else
    {
        $access_icon = "activity_hub_graphics/access_no.png";
        $access_text = _("You are not yet eligible to work in this activity");
    }

    // If we're a round, get page information and calcluate status.
    if ( is_a( $stage, 'Round' ) )
    {
        $round_stats = get_site_page_tally_summary($stage->id);
        list($progress_bar_width, $progress_bar_color, $percent_complete) =
            calculate_progress_bar_properties($round_stats->curr_day_actual, $round_stats->curr_day_goal);
    }

    // Calculate the total number of projects.
    $total_projects = 0;
    $stage_totals = array();
    foreach($desired_states as $stage_state)
    {
        // Pull the number of completed projects from the project
        // transitions array and the others from the current state array.
        if($stage_state == $stage->project_complete_state)
            $count = array_get( $n_project_transitions_in_state_, $stage_state, 0 );
        else
            $count = array_get( $n_projects_in_state_, $stage_state, 0 );

        $stage_totals[$stage_state] = $count;
        $total_projects += $count;
    }

    // Pull the project filter
    $n_projects_in_state_by_filter_ = array();
    if($load_filtered_projects)
        $project_filter = get_project_filter_sql($pguser, $filter_type);

    // We can't show filtered numbers without a filter and without
    // a list of desired states.
    if($load_filtered_projects &&
        ($project_filter=="" || count($desired_states)==0))
        $load_filtered_projects = FALSE;

    // Load any projects based on filters
    if($load_filtered_projects)
    {
        $states_list = '';
        foreach ( $desired_states as $desired_state )
        {
            if ($states_list) $states_list .= ',';
            $states_list .= "'$desired_state'";
            $n_projects_in_state_by_filter_[$desired_state] = 0;
            if($desired_state == $stage->project_complete_state)
                $n_projects_in_state_by_filter_[$desired_state] = _("N/A");
        }

        $res = mysql_query("
            SELECT state, COUNT(*)
            FROM projects
            WHERE state IN ($states_list) $project_filter
            GROUP BY state
        ") or die(mysql_error());

        $total_projects = 0;
        while ( list($project_state,$count) = mysql_fetch_row($res) )
        {
            $n_projects_in_state_by_filter_[$project_state] = $count;
            $total_projects += $count;
        }
    }

    // Output the table row.
    // Every row gets a label, name, and access information.
    echo "<tr>";

    if($load_filtered_projects)
        $span_rows = "rowspan='2'";
    else
        $span_rows = "";

    echo "<td style='border-right: 0;' $span_rows>$stage_id_bit</td>";
    echo "<td style='text-align: left; border-left: 0; border-right: 0;' $span_rows><a href='$code_url/{$stage->relative_url}' title='$description'>{$stage->name}</a></td>";
    echo "<td style='border-left: 0;' $span_rows><a href='$code_url/{$stage->relative_url}#Entrance_Requirements'><img src='$access_icon' alt='$access_text' title='$access_text'></a></td>";

    // Rounds and Pools also get project totals.
    if ( is_a( $stage, 'Round' ) || is_a( $stage, 'Pool' ) )
    {
        echo "<td>$total_projects</td>";
        foreach ( $desired_states as $desired_state )
        {
            echo "<td>";
            if($load_filtered_projects)
                echo $n_projects_in_state_by_filter_[$desired_state];
            else
                echo $stage_totals[$desired_state];
            echo "</td>";
        }
    }
    else
    {
        echo "<td colspan='3' style='border: none;'></td>";
    }

    // Rounds also get page totals.
    if ( is_a( $stage, 'Round' ) )
    {
        echo "<td>{$round_stats->curr_day_goal}</td>";
        echo "<td>{$round_stats->curr_day_actual}</td>";
        echo "<td><div class='progressbar' style='background-color: $progress_bar_color; width: $progress_bar_width%;'>&nbsp;</div><br>$percent_complete%</td>";
    }
    else
    {
        echo "<td colspan='4' style='border: none;'></td>";
    }

    echo "</tr>\n";

    if($load_filtered_projects)
    {
        $display_filter = get_project_filter_display($pguser, $filter_type);
        $display_filter = preg_replace(array("/^<br>/","/<br>/"),array(""," | "),$display_filter);
        echo "<tr>";
        echo "<td colspan='7' style='text-align: left;'>";
        echo "<small><a href='$code_url/{$stage->relative_url}#filter_form'>" . _("Filter") . "</a>: $display_filter</small>";
        echo "</td>";
        echo "</tr>";
    }
}


function activity_descriptions()
// Prints out a list of activities (Stages, Rounds, and Pools) and their
// description.
{
    global $Stage_for_id_, $code_url;

    echo "<h2>" . _("Activity descriptions") . "</h2>";
    echo "<div id='stagedescriptions'>";
    echo "<dl>\n";

    // Providing Content
    {
        echo "<dt>";
        echo _("Providing Content");
        echo "</dt>";
        echo "<dd>";
        echo sprintf(_("Want to help out the site by providing material for us to proofread? <a href='%s'>Find out how!</a>"), "$code_url/faq/cp.php");
        echo "</dd>\n";
    }

    foreach ( $Stage_for_id_ as $stage )
    {
        $stage_icon_url = get_dyn_image_url_for_file("stage_icons/{$stage->id}");
        if ( !is_null($stage_icon_url) )
            $stage_id_bit = "<img style='vertical-align: middle;' src='$stage_icon_url' alt='($stage->id)' title='$stage->id'>";
        else
            $stage_id_bit = "($stage->id)";

        echo "<dt>$stage_id_bit <a href='$code_url/{$stage->relative_url}'>{$stage->name}</a></dt>";
        echo "<dd>{$stage->description}</dd>\n";
    }

    echo "</dl>";
    echo "</div>";
}

theme("", "footer");

function calculate_progress_bar_properties($actual, $goal, $time_scale=TRUE, $color_thresholds=NULL)
// A simple progress bar can be created by using the following HTML:
//   <div class='progressbar' style='background-color: $color; width: $width%;'>&nbsp;</div>
// The <div> can be placed inside another container used to control
// the total width. This function assists in calculating the $color and $width
// values used above in addition to returning the actual percentage of the
// goal achieved.
// Arguments:
//   actual           - actual value obtained
//   goal             - goal
//   time_scale       - if TRUE, the returned color will be calculated based
//                      on how much of the current day has passed. This is
//                      useful so that at the beginning of a new day the bar
//                      isn't at the low color.
//   color_thresholds - associative array containing the colors to use for
//                      each percentage completed range
//                      eg: array(100 => "lightgreen", 75 => "orange", 0 => "red");
//
// Returns three values via a non-associative array():
//   progress_bar_width - percentage width of the progress bar in range [0-100]
//   progress_bar_color - color of progress bar using step-wise colors
//   percent_complete   - actual percentage complete in whole numbers
{
    // Define the colors used for the status bar graph
    if(!is_array($color_thresholds))
    {
        // Thresholds defined here are assumed to be highest to
        // lowest. Thresholds passed into this function are sorted.
        $color_thresholds = array(100 => "lightgreen", 75 => "orange", 0 => "red");
    }
    else
    {
        krsort($color_thresholds);
    }

    // calculate the width and percentage complete
    if($goal > 0) { 
        $percent_complete = ceil(($actual / $goal) * 100);
        $progress_bar_width = min($percent_complete, 100);
    } else {
        $percent_complete = 100;
        $progress_bar_width = 100;
    }

    // Calculate the progress bar color scaling based on the time of day.
    if($time_scale)
    {
        $t_start_of_today = mktime(0,0,0,date('m'),date('d'),date('y'));
        $percentage_of_day_passed = (time() - $t_start_of_today) / (60*60*24);
    }
    else
    {
        $percentage_of_day_passed = 1;
    }

    foreach($color_thresholds as $threshold => $color)
    {
        if($percent_complete >= ($threshold * $percentage_of_day_passed))
        {
            $progress_bar_color = $color;
            break;
        }
    }

    return array($progress_bar_width, $progress_bar_color, $percent_complete);
}

// vim: sw=4 ts=4 expandtab
?>
