<?php
$relPath="./../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'theme.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'../tools/project_manager/word_freq_table.inc');
include_once("project_retread_skip_recommendations.inc");

set_time_limit(0); // no time limit

$username = @$_REQUEST["username"];
$round_state = @$_REQUEST["round_state"];
$action = get_enumerated_param($_REQUEST,"action","select",array("view","select"));

// restrict everyone except SAs and PFs to only their projects
if(!(user_is_a_sitemanager() || user_is_proj_facilitator() || $pguser == 'piggy'))
    $username=$pguser;


# TODO - this entire script uses the 'P2', 'P3', and 'F1' hard-coded values
# and can not be checked into CVS as-is

$title = _("Show all project retread/skip recommendations");
$page_text = _("This page shows a list of all projects in a specific state and calculates the wa/w value for them and makes recommendations on P1->P1 and P3 skips.");

$no_stats=1;
$theme_args['css_data'] = _get_stylesheet();
theme($title, "header", $theme_args);

echo "<h1>$title</h1>";

echo "<p>$page_text</p>";

// we're only interested in showing projects in:
// P2.waiting, P3.waiting, and F1.waiting for P1->P1 recommendations and
// P2.waiting for P3 skip recommendations
$interested_rounds = array("P2","P3","F1");
$interested_states = array();
foreach($interested_rounds as $round_id)
{
    $round = get_Round_for_round_id($round_id);
    $interested_states[] = $round->project_waiting_state;
}

// show the form
echo "<form method='GET' action='" . $_SERVER["SCRIPT_NAME"] . "'>";
echo "<table>";
echo  "<tr>";
echo   "<td>" . _("Round state") . "</td>";
echo   "<td><select name='round_state'>";
_echo_round_select($interested_states,$round_state);
echo    "</select>";
echo  "</tr>";
if(user_is_a_sitemanager() || user_is_proj_facilitator() || $pguser == 'piggy')
{
    echo  "<tr>";
    echo   "<td>" . _("PM") . "</td>";
    echo   "<td><input type='text' name='username' value='$username'></td>";
    echo  "</tr>";
}
echo "</table>";
echo "<input type='hidden' name='action' value='view'>";
echo "<input type='submit' value='Show'>";
echo "</form>";

function _echo_round_select($rounds,$selected) {
    foreach($rounds as $round) {
        echo "<option value='$round'";
        if($round == $selected) echo " selected";
        echo ">$round</option>";
    }
}

// stop if we're on select mode only
if($action == "select")
{
    theme('', 'footer');
    exit;
}

echo "<hr>";

$where = "state='$round_state'";
if(!empty($username))
    $where .= " AND (username='$username' or checkedoutby='$username')";

// pull all projects that are in the specified state
$res = mysql_query("
    SELECT projectid, username, checkedoutby, nameofwork, n_pages
    FROM projects
    WHERE $where
    ORDER BY username, projectid
");

if(!empty($username))
    echo "<h2>" . sprintf(_("Projects for %s"), $username) . "</h2>";

echo "<table>";
echo "<tr>";
echo "<th>" . _("ProjectID") . "</th>";
echo "<th>" . _("PM") . "</th>";
echo "<th>" . _("PP") . "</th>";
echo "<th>" . _("Name") . "</th>";
echo "<th>" . _("# Pages") . "</th>";
echo "<th>" . _("wa/w") . "</th>";
echo "<th>" . _("Rec") . "</th>";
echo "</tr>";

$wa_w_p3_skip_recommendation = 0.00075;
$wa_w_p1_repeat_recommendation = 0.1;

$p3_skip_pages = 0;
$p3_skip_projects = 0;
$p1_repeat_pages = 0;
$p1_repeat_projects = 0;
$total_projects = 0;

// loop through them building our table and calculating the wa/w value
while( list($projectid,$pm,$pp,$name,$n_pages) = mysql_fetch_row($res) )
{
    $total_projects++;

    list($source_round_id, $compare_round_id) = _get_source_compare_round_ids($round_state, $name);
    $wa_w = calculate_waw_for_project($projectid, $source_round_id, $compare_round_id);
    $rec = "";
    if($wa_w >= $wa_w_p1_repeat_recommendation)
    {
        $rec = _("repeat P1");
        $p1_repeat_pages += $n_pages;
        $p1_repeat_projects++;
    }
    elseif($compare_round_id == 'P2' && $wa_w <= $wa_w_p3_skip_recommendation)
    {
        $rec = _("P3 skip");
        $p3_skip_pages += $n_pages;
        $p3_skip_projects++;
    }

    echo "<tr>";
    echo "<td><a href='$code_url/project.php?id=$projectid'>$projectid</a></td>";
    echo "<td>$pm</td>";
    echo "<td>$pp</td>";
    echo "<td>$name</td>";
    echo "<td>$n_pages</td>";
    echo "<td><a href='project_retread_skip_recommendations.php?projectid=$projectid&amp;source_round_id=$source_round_id&amp;compare_round_id=$compare_round_id'>$wa_w</a></td>";
    echo "<td>$rec</td>";
    echo "<tr>";
}
mysql_free_result($res);

echo "</table>";

echo "<p><b>" . _("Total projects:") . "</b> $total_projects</p>";
echo "<p><b>" . _("Total projects for P3 skip recommendations:") . "</b> $p3_skip_projects</p>";
echo "<p><b>" . _("Total pages for P3 skip recommendations:") . "</b> $p3_skip_pages</p>";
echo "<p><b>" . _("Total projects for P1 retread recommendations:") . "</b> $p1_repeat_projects</p>";
echo "<p><b>" . _("Total pages for P1 retread recommendations:") . "</b> $p1_repeat_pages</p>";

theme('','footer');


//---------------------------------------------------------------------------
// supporting page functions

function _get_source_compare_round_ids($round_state, $name)
{
    $current_round = get_Round_for_project_state($round_state);
    $current_round_number = $current_round->round_number;

    // we need to do some special cases for {P3 skipped} and {No P2} projects
    if(strpos($name,'{No P2}'))
    {
        if($current_round->id == 'P3')
        {
            $source_round = NULL;
            $compare_round = get_Round_for_round_id('P1');
        }
        elseif($current_round->id == 'F1')
        {
            $source_round = get_Round_for_round_id('P1');
            $compare_round = get_Round_for_round_id('P3');
        }
    }
    elseif(strpos($name,'{P3 skipped}'))
    {
        if($current_round->id == 'F1')
        {
            $source_round = get_Round_for_round_id('P1');
            $compare_round = get_Round_for_round_id('P2');
        }
    }
    else
    {
        $source_round = get_Round_for_round_number($current_round_number-2);
        $compare_round = get_Round_for_round_number($current_round_number-1);
    }

    // OCR isn't a 'round' but is a special case for the function which
    // will use $source_round_id
    if($source_round == NULL)
        $source_round_id = 'OCR';
    else
        $source_round_id = $source_round->id;

    $compare_round_id = $compare_round->id;

    return array($source_round_id, $compare_round_id);
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
