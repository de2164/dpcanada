<?php
$relPath="./../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');

set_time_limit(0); // no time limit

$username = @$_GET["username"];

if(!(user_is_a_sitemanager() || user_is_proj_facilitator() || $pguser == 'piggy'))
    $username=$pguser;

# TODO - this entire script uses the 'P2', 'P3', and 'F1' hard-coded values
# and can not be checked into CVS as-is

// we're only interested in showing projects in:
// P2.waiting, P3.waiting, and F1.waiting for P1->P1 recommendations and
// P2.waiting for P3 skip recommendations
$interested_P1repeat_rounds = array("P2");
$interested_P3skip_rounds = array("P3");
$interested_rounds = array_merge($interested_P1repeat_rounds, $interested_P3skip_rounds);
$interested_states = array();
foreach($interested_rounds as $round_id)
{
    $round = get_Round_for_round_id($round_id);
    $interested_states[$round_id] = $round->project_waiting_state;
}

$title = _("Site-wide retread/skip recommendations");
$page_text = _("This page shows all projects in the system that have a recommendation based on their wa/w value. The data is pulled from a pre-computed data file (upside: very little overhead, downside: data might become stale). Data is sorted where we will get the most bang for our buck: recommendation and then number of pages.");

$page_text2 = _("Note: wa/w values of zero (0) are suspect, particularly for projects of more than a 100 pages, and point to some anomaly with the project. It is unlikely these warrant P3 skips and have been excluded from the output below.");

$no_stats=1;
$theme_args['css_data'] = _get_stylesheet();
theme($title, "header", $theme_args);

echo "<h1>$title</h1>";

echo "<p>$page_text</p>";
echo "<p>$page_text2</p>";

// show the form if they can see other's projects
if(user_is_a_sitemanager() || user_is_proj_facilitator() || $pguser == 'piggy')
{   
    echo "<form method='GET' action='" . $_SERVER["SCRIPT_NAME"] . "'>";
    echo "<table>";
    echo  "<tr>"; 
    echo   "<td>" . _("PM") . "</td>";
    echo   "<td><input type='text' name='username' value='$username'></td>";
    echo  "</tr>";
    echo "</table>";
    echo "<input type='submit' value='Show'>";
    echo "</form>";
}


$data_directory='/home/cpeel/data';
$data_last_updated=0;

$wa_w_p3_skip_recommendation = 0.00075;
$wa_w_p1_repeat_recommendation = 0.1;

$project_info = array();

// loop through all the interested states
foreach($interested_states as $round_id => $state)
{
    $data_file="$data_directory/$state.dat";
    $waw_data = array();

    // read in the data
    if(is_readable($data_file))
    {
        $fh=fopen($data_file,"r");
        while($line=fgets($fh))
        {
            $line=rtrim($line);
            list($projectid,$waw)=explode(":",$line);
            $data_waw[$projectid]=$waw;
        }
        fclose($fh);
        
        // get the file modification timestamp
        $file_stats = stat($data_file);
        $data_last_updated = max($data_last_updated, $file_stats["mtime"]);
    }

    // loop through the data pulling out only
    // the projects that have recommendations
    foreach($data_waw as $projectid => $wa_w)
    {
        if(in_array($round_id, $interested_P1repeat_rounds) && $wa_w >= $wa_w_p1_repeat_recommendation)
        {
            $project_data = _get_project_info($projectid, $state);
            if(empty($project_data["nameofwork"])) continue;
            $project_info[$projectid] = $project_data;
            $project_info[$projectid]["rec"] = _("repeat P1");
        }
        elseif(in_array($round_id, $interested_P3skip_rounds) && $wa_w <= $wa_w_p3_skip_recommendation)
        {
            $project_data = _get_project_info($projectid, $state);
            if(empty($project_data["nameofwork"])) continue;
            $project_info[$projectid] = $project_data;
            $project_info[$projectid]["rec"] = _("P3 skip");
        }
    }
}

// do sorting
foreach ($project_info as $key => $row) {
    $sort_recs[$key]  = $row['rec'];
    $sort_pages[$key] = $row['n_pages'];
    $sort_state[$key] = $row['state'];
}
array_multisort($sort_recs, SORT_ASC, $sort_state, SORT_DESC, $sort_pages, SORT_DESC, $project_info);

echo "<p>" . sprintf(_("The pre-computed data was last updated on: %s"), strftime("%B %d %T", $data_last_updated)) . "</p>";

echo "<table>";
echo "<tr>";
echo "<th>" . _("PM") . "</th>";
echo "<th>" . _("PP") . "</th>";
echo "<th>" . _("Name") . "</th>";
echo "<th>" . _("# Pages") . "</th>";
echo "<th>" . _("wa/w") . "</th>";
echo "<th>" . _("Time in state") . "</th>";
echo "<th>" . _("State") . "</th>";
echo "<th>" . _("Rec") . "</th>";
echo "</tr>";

$total_pages = array();

foreach($project_info as $projectid => $project_data)
{
    // skip this project if the user should only see their data
    // PM *or* PP
    if(!empty($username) && $project_data["username"] != $username && $project_data["checkedoutby"] != $username)
        continue;

    // also skip this if the wa/w is 0 and the number if pages is > 100
    if($data_waw[$projectid] == 0) // && $project_data["n_pages"] > 75)
        continue;

    $trunc_name = substr($project_data["nameofwork"],0,75);
    if($trunc_name != $project_data["nameofwork"])
        $trunc_name .= "...";

    list($source_round_id, $compare_round_id) = _get_source_compare_round_ids($project_data["state"], $project_data["nameofwork"]);
    echo "<tr>";
    echo "<td>" . $project_data["username"] . "</td>";
    echo "<td>" . $project_data["checkedoutby"] . "</td>";
    echo "<td><a href='$code_url/project.php?id=$projectid'>$trunc_name</a></td>";
    echo "<td style='text-align: right;'>" . $project_data["n_pages"] . "</td>";
    echo "<td><a href='project_retread_skip_recommendations.php?projectid=$projectid&amp;source_round_id=$source_round_id&amp;compare_round_id=$compare_round_id'>" . $data_waw[$projectid] . "</a></td>";
    echo "<td style='text-align: right;'>" . _convert_to_duration($project_data["timestamp"]) . "</td>";
    echo "<td>" . $project_data["state"] . "</td>";
    echo "<td style='white-space: nowrap'>" . $project_data["rec"] . "</td>";
    echo "<tr>";

    @$total_pages[$project_data["rec"]]+=$project_data["n_pages"];
    @$total_projects[$project_data["rec"]]++;
    @$total_pm_pages[$project_data["username"]][$project_data["rec"]]+=$project_data["n_pages"];
    @$total_pm_projects[$project_data["username"]][$project_data["rec"]]++;
}

echo "</table>";

foreach($total_pages as $rec => $pages)
{
    echo "<p>" . sprintf(_("Total projects with recommendation <b>%s</b>: %d"), $rec, $total_projects[$rec]) . "</p>";
    echo "<p>" . sprintf(_("Total pages with recommendation <b>%s</b>: %d"), $rec, $pages) . "</p>";
}

// if no username was specified show a PM report
if(empty($username))
{
    ksort($total_pm_pages);
    ksort($total_pm_projects);

    foreach($total_pages as $rec => $pages)
    {
        echo "<h2>" . sprintf(_("Summary for %s"), $rec) . "</h2>";
        echo "<table>";
        echo "<tr>";
        echo "<th>" . _("PM") . "</th>";
        echo "<th>" . _("# Projects") . "</th>";
        echo "<th>" . _("% Project Recs") . "</th>";
        echo "<th>" . _("# Pages") . "</th>";
        echo "<th>" . _("% Page Recs") . "</th>";
        echo "</tr>";
        foreach($total_pm_pages as $username => $recs)
        {
            // skip if there are no recommendations for this person
            if(@$total_pm_pages[$username][$rec]==0) continue;

            echo "<tr>";
            echo "<td>$username</td>";
            echo "<td style='text-align: right;'>" . $total_pm_projects[$username][$rec] . "</td>";
            echo "<td style='text-align: right;'>" . sprintf("%0.2f%%",$total_pm_projects[$username][$rec] / $total_projects[$rec] * 100) . "</td>";
            echo "<td style='text-align: right;'>" . $total_pm_pages[$username][$rec] . "</td>";
            echo "<td style='text-align: right;'>" . sprintf("%0.2f%%",$total_pm_pages[$username][$rec] / $total_pages[$rec] * 100) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

if(sizeof($total_pages)==0)
{
    echo "<p>" . _("There are no recommendations at this time") . "</p>";
}

theme('','footer');


//---------------------------------------------------------------------------
// supporting page functions

function _convert_to_duration($timestamp) {
    $elapsed_time_seconds = time() - $timestamp;

    $duration = "";

    if($elapsed_time_seconds < (60 * 60 * 24))
    // less than one day
    {
        $duration = _("< 1 day");
    }
    else
    {
        $duration = sprintf("%d days", ($elapsed_time_seconds / (60 * 60 * 24)));
    }


    return $duration;
}

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

function _get_project_info($projectid, $state)
{
    // pull all projects that are in the specified state
    $res = mysql_query("
        SELECT projects.projectid as projectid, username, nameofwork, state, n_pages, checkedoutby, timestamp
        FROM projects, project_events
        WHERE projects.projectid='$projectid' AND
            projects.projectid=project_events.projectid AND
            details2 = '$state'
        ORDER BY timestamp DESC
        LIMIT 1
    ");

    $data = mysql_fetch_assoc($res);
    mysql_free_result($res);
    return $data;
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
