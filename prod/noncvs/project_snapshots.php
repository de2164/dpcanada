<?php
$relPath="./../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'theme.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'misc.inc'); // surround_and_join()
include_once($relPath.'project_edit.inc'); // user_can_edit_project()
include_once($relPath.'slim_header.inc'); // slimheader() & slimfooter()

// Created by: cpeel (pester him for questions about this script)

$projectid = array_get($_REQUEST, "projectid", "");
$username = array_get($_REQUEST, "username", "");
$snapshot = array_get($_REQUEST, "snapshot", "");
$image = array_get($_REQUEST, "image", "");
$roundnum = array_get($_REQUEST, "roundnum", "");

// If they've passed in a snapshot, an image, and a roundid, just show the
// image text for that page.
if($snapshot && $image && $roundnum)
{
    slim_header();
    $round = get_Round_for_round_number($roundnum);
    mysql_select_db("project_snapshots");
    $sql = sprintf("SELECT {$round->text_column_name} FROM $snapshot WHERE image = '%s'", mysql_real_escape_string($image));
    $res = mysql_query($sql);
    if($res)
    {
        list($text) = mysql_fetch_row($res);
        echo "<pre>$text</pre>";
        mysql_free_result($res);
    }
    else
    {
        echo "<p>" . _("Error fetching requested text from the database.") . "</p>";
    }
    slim_footer();

    // ------------------------------------------------
    // Set it back, otherwise you get:
    // Table 'project_snapshots.sessions' doesn't exist
    mysql_select_db($db_name);
    exit;
}

// do some sanity checking on the projectid
// in case they haven't pulled in the initial 'projectID' part
if(!empty($projectid) && strpos($projectid,'projectID')!==0)
    $projectid="projectID$projectid";

$title = _("Project Snapshots");
$page_text = _("This page allows access to user information for projects that have repeated one or more rounds. Unless you have edit privileges for this project, you can only see the pages you personally proofed/foofed.");

$no_stats=1;
$theme_args['css_data'] = _get_stylesheet();
theme($title, "header", $theme_args);

echo "<h1>$title</h1>";
echo "<p>$page_text</p>";

// show the form
echo "<form method='GET' action='" . $_SERVER["SCRIPT_NAME"] . "'>";
echo "<table>";
echo  "<tr>"; 
echo   "<td>" . _("Project ID") . "</td>";
echo   "<td><input name='projectid' type='text' value='$projectid' size='35'></td>";
echo  "</tr>";
echo "</table>";
echo "<input type='submit' value='Show'>";
echo "</form>";

// stop if no projectid was specified
if(empty($projectid))
{
    theme('', 'footer');
    exit;
}

echo "<hr>";

// confirm the user can edit the project
$limit_user_to_only_their_pages = TRUE;
$ucep_result = user_can_edit_project($projectid);
if($ucep_result == USER_CAN_EDIT_PROJECT)
{
    if(!$username)
        $limit_user_to_only_their_pages = FALSE;
}
else
{
    $username = $pguser;
}

echo "<h1>Snapshots for: $projectid</h1>";

if($limit_user_to_only_their_pages)
{
    echo "<p>" . _("You do not have edit privileges for this project and will only be shown pages you have worked on.") . "</p>";

    $latest_round_user_worked_on_page = array();

    // get the list of pages the user has worked on in the non-snapshot
    // version of the project table
    foreach($Round_for_round_number_ as $round_num => $round)
    {
        $sql = sprintf("SELECT image FROM $projectid WHERE {$round->user_column_name} = '%s'", mysql_real_escape_string($username));

        $image_res = mysql_query($sql);
        while(list($image) = mysql_fetch_row($image_res))
            $latest_round_user_worked_on_page[$image]=$round_num;
        mysql_free_result($image_res);
    }
}

mysql_select_db("project_snapshots");
$res = mysql_query(sprintf("SHOW TABLES LIKE '%s_%%'", mysql_real_escape_string($projectid)));
if(!$res)
{
    echo "<p>" . _("There are no snapshots for this project.") . "</p>";
    theme('','footer');

    mysql_select_db($db_name);

    exit;
}

$snapshots = array();
while ( list($table_name) = mysql_fetch_row($res) )
{
    list($projectid2, $timestamp) = explode('_', $table_name);
    assert( $projectid2 == $projectid );
    assert( is_numeric($timestamp) );
    $snapshots[$table_name] = strftime( '%Y-%m-%d %H:%M:%S', intval($timestamp) );
}

mysql_free_result($res);

// Sort the snapshots from most recent to oldest. This is important not only
// to have the most recent at top, but is used in the logic to determine
// which pages to show the user.
arsort($snapshots);


echo "<p>" . sprintf(_("There are %s snapshots."), count($snapshots)) . "</p>";
echo "<ul>";
foreach($snapshots as $table_name => $time_string)
{
    echo "<li><a href='#$table_name'>$time_string</li>";
}
echo "</ul>";

$round_columns = array();
$round_headers = array();
foreach($Round_for_round_number_ as $round_num => $round)
{
    $round_columns[$round_num] = $round->user_column_name;
    $round_headers[$round_num] = $round->id;
}

foreach($snapshots as $table_name => $time_string)
{
    echo "<a name='$table_name'></a>";
    echo "<h2>" . sprintf(_("Snapshot taken: %s"), $time_string ) . "</h2>\n";

    echo "<table>";
    echo "<tr>";
    echo "<th>" . _("Image") . "</th>";
    foreach($round_columns as $round_num => $column)
        echo "<th>" . $round_headers[$round_num] . "</th>";
    echo "</tr>";

    $sql = "SELECT image, " . surround_and_join($round_columns,"","",", ") . " FROM $table_name ORDER BY image";
    $user_res = mysql_query($sql);

    $number_rows_output = 0;
    while($row = mysql_fetch_assoc($user_res))
    {
        $image = $row["image"];
        $have_row_to_output = FALSE;

        // determine the rounds to show in the row
        $latest_round_worked_on = NULL;

        // If the user can see all pages, bypass bypass this check
        if($limit_user_to_only_their_pages === FALSE)
        {
        }
        // If we've already predetermined a latest page (either from the
        // non-snapshot version of the project table or from a later
        // timestamped snapshot version of the project table) use that one
        elseif(@$latest_round_user_worked_on_page[$image])
        {
            $latest_round_worked_on = $latest_round_user_worked_on_page[$image];
        }
        // Otherwise see what the latest page is that they've worked on in
        // this snapshot and if necessary update the $latest_round_user_worked_on_page
        // array to reflect this value for other more recent snapshots.
        else
        {
            foreach(array_reverse($round_columns) as $round_num => $column)
            {
                if($row[$column] == $username)
                {
                    $latest_round_worked_on = $round_num;
                    $latest_round_user_worked_on_page[$image] = $round_num;
                    break;
                }
            }
        }

        if(!$limit_user_to_only_their_pages || $latest_round_worked_on)
        {
            $number_rows_output++;

            echo "<tr>";
            echo "<td>" . $image . "</td>";
            foreach($round_columns as $round_num => $column)
            {
                if($row[$column])
                    $column_contents = "<a href='?snapshot=$table_name&amp;image=$image&amp;roundnum=$round_num'>text</a> | " . $row[$column];
                else
                    $column_contents = "&nbsp;";

                echo "<td>";
                if($limit_user_to_only_their_pages)
                {
                    if($last_round_worked_on <= $round_num)
                        echo $column_contents;
                    else
                        echo "&nbsp;";
                }
                else
                    echo $column_contents;
                echo "</td>";
            }
            echo "</tr>\n";
        }
    }
    mysql_free_result($user_res);
    echo "</table>";

    if($number_rows_output == 0)
        echo "<p>" . _("You did not proof any pages in this project.") . "</p>";
}


theme('','footer');

// ------------------------------------------------
// Set it back, otherwise you get:
// Table 'project_snapshots.sessions' doesn't exist
mysql_select_db($db_name);


//---------------------------------------------------------------------------
// supporting page functions

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
