<?php
$relPath="./../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'theme.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'../tools/project_manager/word_freq_table.inc');
include_once("project_retread_skip_recommendations.inc");

set_time_limit(0); // no time limit

$projectid = $_REQUEST["projectid"];
$source_round_id = @$_REQUEST["source_round_id"];
$compare_round_id = @$_REQUEST["compare_round_id"];

// do some sanity checking on the projectid
// in case they haven't pulled in the initial 'projectID' part
if(!empty($projectid) && strpos($projectid,'projectID')!==0)
    $projectid="projectID$projectid";

$title = _("Project retread/skip recommendations");
# TODO - this will need fixing before commiting to CVS
$page_text = _("This page calculates how many wdiff alterations were detected for a specific project between specific rounds and recommends project retreads or skips based on this value and values obtained from the <a href='http://www.pgdp.net/wiki/Confidence_in_Page_analysis'>CiP analysis</a>.");

$no_stats=1;
$theme_args['css_data'] = _get_stylesheet();
theme($title, "header", $theme_args);

echo "<h1>$title</h1>";

echo "<p>$page_text</p>";

// get an array of round IDs
$rounds=array_keys($Round_for_round_id_);
array_unshift($rounds,'OCR');

// show the form
echo "<form method='GET' action='" . $_SERVER["SCRIPT_NAME"] . "'>";
echo "<table>";
echo  "<tr>";
echo   "<td>" . _("Source Round") . "</td>";
echo   "<td><select name='source_round_id'>";
_echo_round_select($rounds,$source_round_id);
echo    "</select>";
echo  "</tr>";
echo  "<tr>";
echo   "<td>" . _("Compare Round") . "</td>";
echo   "<td><select name='compare_round_id'>";
_echo_round_select(array_slice($rounds,1),$compare_round_id);
echo     "</select>";
echo  "</tr>";
echo  "<tr>"; 
echo   "<td>" . _("Project ID") . "</td>";
echo   "<td><input name='projectid' type='text' value='$projectid'></td>";
echo  "</tr>";
echo "</table>";
echo "<input type='submit' value='Calculate'>";
echo "</form>";

function _echo_round_select($rounds,$selected) {
    foreach($rounds as $round) {
        echo "<option value='$round'";
        if($round == $selected) echo " selected";
        echo ">$round</option>";
    }
}

// stop if no projectid was specified
if(empty($projectid))
{
    theme('', 'footer');
    exit;
}

echo "<hr>";

// confirm the user can edit the project
$ucep_result = user_can_edit_project($projectid);
if(!($ucep_result == USER_CAN_EDIT_PROJECT || $pguser == 'piggy'))
{
    echo "<p class='error'>" . _("You are not authorized to access this project.") . "</p>";
    theme('',"footer");
    exit;
}

// confirm the compare_round_id is later than source_round_id
if(array_search($compare_round_id,$rounds)<=array_search($source_round_id,$rounds)) {
    echo "<p class='error'>" . _("Compare Round should be a round later than Source Round.") . "</p>";
    theme('', 'footer');
    exit;
}


$project_name = get_project_name($projectid);
// if the project doesn't have a name, this isn't a valid project
// TODO: make this check more robust
if(empty($project_name))
{
    echo "<p class='error'>" . _("Project does not exist.") . "</p>";
    theme('', 'footer');
    exit;
}

$project_text = sprintf(_("Project: %s"),$project_name);
echo "<h2>$project_text</h2>\n";

$project_state = _get_project_state($projectid);
echo "<p><b>" . _("Current project state:") . "</b> $project_state</p>";

$project_round = get_Round_for_project_state($project_state);
$source_round = get_Round_for_round_id($source_round_id);
$compare_round = get_Round_for_round_id($compare_round_id);

// do some sanity checking
if($project_round->round_number <= $source_round->round_number)
{
    echo "<p class='error'>" . sprintf(_("Project is not yet through the source round (%s) yet!"), $source_round_id) . "</p>";
}

if($project_round->round_number == $compare_round->round_number)
{
    echo "<p class='warning'>" . sprintf(_("Project has not yet completed the compare round (%s) yet!"), $compare_round_id) . "</p>";
}


list($total_words, $wdiff_alterations) = get_wdiff_alterations($projectid, $source_round_id, $compare_round_id);

$wa_w = $wdiff_alterations / $total_words;

echo "<p>";
echo "<b>" . _("Source round:") . "</b> $source_round_id<br>";
echo "<b>" . _("Compare round:") . "</b> $compare_round_id<br>";
echo "</p>";

echo "<p>";
echo "<b>" . _("Words:") . "</b> $total_words<br>";
echo "<b>" . _("wdiff alterations:") . "</b> $wdiff_alterations<br>";
echo "<b>" . _("wdiff alterations per word (wa/w):") . "</b> $wa_w<br>";
echo "</p>";

// TODO: This next section is specific to pgdp.net and needs to be made
// generic before checking into CVS
$wa_w_p3_skip_recommendation = 0.00075;
$wa_w_p1_repeat_recommendation = 0.1;

if($wa_w == 0)
    echo "<p class='warning'>A wa/w of 0 means that there were <b>no</b> changes between the two rounds.</p>";

if($wa_w >= $wa_w_p1_repeat_recommendation)
    echo "<p class='warning'>wa/w is >= $wa_w_p1_repeat_recommendation, you should consider putting this project back through P1.</p>";

if($compare_round_id == 'P2' && $wa_w <= $wa_w_p3_skip_recommendation)
    echo "<p class='warning'>wa/w is <= $wa_w_p3_skip_recommendation, you should consider skipping P3.</p>";

echo "<hr width='75%'>";

show_waw_comparison($wa_w);

theme('','footer');


//---------------------------------------------------------------------------
// supporting page functions

function _get_project_state($projectid) {
    $res = mysql_query("SELECT state FROM projects WHERE projectid = '$projectid'");
    $ar = mysql_fetch_array($res);
    return $ar['state'];
}

function _get_stylesheet() {
    return "
        p.error { color: red; }
        p.warning { color: blue; }
        table th { background-color: black; color: white; }
        table td { padding-right: 0.5em; }
    ";
}

// vim: sw=4 ts=4 expandtab
?>
