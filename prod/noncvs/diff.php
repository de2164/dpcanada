<?php
$relPath="../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'links.inc');
include_once("DifferenceEngineWrapper.inc");

$projectid   = $_GET['project'];
$image       = $_GET['image'];
$L_round_num = get_integer_param($_GET, 'L_round_num', null, 0, MAX_NUM_PAGE_EDITING_ROUNDS);
$R_round_num = get_integer_param($_GET, 'R_round_num', null, 0, MAX_NUM_PAGE_EDITING_ROUNDS);

$project = new Project( $projectid );
$state = $project->state;
$project_title = $project->nameofwork;

// --------------------------------------------------------------
// get information about this diff
if ( $L_round_num == 0 )
{
    $L_text_column_name = 'master_text';
    $L_user_column_name = "'none'";  // string literal, not column name
    $L_label = _('OCR');
}
else
{
    $L_round = get_Round_for_round_number($L_round_num);
    $L_text_column_name = $L_round->text_column_name;
    $L_user_column_name = $L_round->user_column_name;
    $L_label = $L_round->id;
}

if ( $R_round_num == 0 )
{
    $R_text_column_name = 'master_text';
    $R_user_column_name = "'none'";  // string literal, not column name
    $R_label = _('OCR');
}
else
{
    $R_round = get_Round_for_round_number($R_round_num);
    $R_text_column_name = $R_round->text_column_name;
    $R_user_column_name = $R_round->user_column_name;
    $R_label = $R_round->id;
}

$query = "
    SELECT $L_text_column_name, $R_text_column_name,
           $L_user_column_name, $R_user_column_name
    FROM $projectid
    WHERE image='$image'";

$res = mysql_query($query);
list($L_text, $R_text, $L_user, $R_user) = mysql_fetch_row($res);
$can_see_names_for_this_page = can_see_names_for_page($projectid, $image);
if ( $can_see_names_for_this_page) {
    $L_label .= " ($L_user)";
    $R_label .= " ($R_user)";
}
// now have the image, users, labels etc all set up
// -----------------------------------------------------------------------------

$title = sprintf( _('Difference for page %s'), $image );
$image_url = "$code_url/tools/project_manager/displayimage.php?project=$projectid&amp;imagefile=$image";
$image_link = sprintf( _('Difference for page %s'), new_window_link($image_url, $image));
$no_stats = 1;
$extra_args = array("css_data" => get_DifferenceEngine_stylesheet());
theme("$title: $project_title", "header", $extra_args);

echo "<h1>$project_title</h1>\n";
echo "<h2>$image_link</h2>\n";

do_navigation($projectid, $image, $L_round_num, $R_round_num, 
              $L_user_column_name, $L_user);

$url = "$code_url/project.php?id=$projectid&amp;expected_state=$state";
$label = _("Go to Project Page");

echo "<a href='$url'>$label</a>";
echo "<br>\n";

// ---------------------------------------------------------

$diffEngine = new DifferenceEngineWrapper();

$diffEngine->showDiff($L_text, $R_text, $L_label, $R_label);

theme("", "footer");

function do_navigation($projectid, $image, $L_round_num, $R_round_num, 
                       $L_user_column_name, $L_user) 
{
    $jump_to_js = "this.form.image.value=this.form.jumpto[this.form.jumpto.selectedIndex].value; this.form.submit();";

    echo "\n<form method='get' action='diff.php'>";
    echo "\n<input type='hidden' name='project' value='$projectid'>";
    echo "\n<input type='hidden' name='image' value='$image'>";
    echo "\n<input type='hidden' name='L_round_num' value='$L_round_num'>";
    echo "\n<input type='hidden' name='R_round_num' value='$R_round_num'>";
    echo "\nJump to: <select name='jumpto' onChange='$jump_to_js'>\n";

    $query = "SELECT image, $L_user_column_name  FROM $projectid ORDER BY image ASC";
    $res = mysql_query( $query) or die(mysql_error());
    $num_rows = mysql_num_rows($res);
    $prev_image = "";
    $next_image = "";
    $prev_from_proofer = "";
    $next_from_proofer = "";
    $got_there = FALSE;
    $got_to_next = FALSE;
    // construct the dropdown; work out where previous and next buttons should take us
    while ( list($this_val, $this_user) = mysql_fetch_row($res) )
    {
        echo "\n<option value='$this_val'";
        if ($this_val == $image)
        {
            echo " selected";  // make the correct element of the drop down selected
            $got_there = TRUE;
        }
        else if ($got_there && ! $got_to_next) {
            // we are at the one after the current one
            $got_to_next = TRUE;
            $next_image = $this_val;
        }
        if ($got_to_next && $next_from_proofer == "" && $this_user == $L_user)
        {
            $next_from_proofer = $this_val;
        }
        if ( !$got_there )
        {
            $prev_image = $this_val;  // keep track of what the previous image was
            if ($this_user == $L_user)
            {
                $prev_from_proofer = $this_val;
            }
        }
        echo ">$this_val</option>";
    }
    echo "\n</select>";
    $previous_js = "this.form.image.value='$prev_image'; this.form.submit();";
    $next_js = "this.form.image.value='$next_image'; this.form.submit();";
    $previous_from_proofer_js = "this.form.image.value='$prev_from_proofer'; this.form.submit();";
    $next_from_proofer_js = "this.form.image.value='$next_from_proofer'; this.form.submit();";

    echo "\n<input type='button' value='" . _("Previous") . "' onClick=\"$previous_js\"";
    if ( $prev_image == "" ) {
        echo " disabled";
    }
    echo ">";
    echo "\n<input type='button' value='" . _("Next") . "' onClick=\"$next_js\"";
    if ( $next_image == "" ) {
        echo " disabled";
    }
    echo ">";

    if (can_navigate_by_proofer($projectid, $L_user))
    {
        echo "\n<input type='button' value='" . _("Proofer previous") . "' onClick=\"$previous_from_proofer_js\"";
        if ( $prev_from_proofer == "" ) {
            echo " disabled";
        }
        echo ">";
        echo "\n<input type='button' value='" . _("Proofer next") . "' onClick=\"$next_from_proofer_js\"";
        if ( $next_from_proofer == "" ) {
            echo " disabled";
        }
        echo ">";

    }
    echo "\n</form>";
}

// discover whether the user is allowed to see proofer names for this page
function can_see_names_for_page($projectid, $image)
{
    global $pguser, $Round_for_round_id_;

    // If requestor isn't logged in, they can't see any names.
    if ( $pguser == '' ) return FALSE;

    $project = new Project( $projectid );
    $answer = $project->names_can_be_seen_by_current_user; // can see for all pages
    if (! $answer) 
    {
        $fields = "";
        foreach ( $Round_for_round_id_ as $round_id => $round )
        {
            if ($fields != "") {
                $fields .= ", ";
            }
            $fields .= $round->user_column_name;
        }
        $query = "SELECT $fields from $projectid WHERE image = '$image'";
        $res = mysql_query($query) or die(mysql_error());
        $page_res = mysql_fetch_array($res);
        foreach ($page_res as $page_user) {
            if ($page_user == $pguser) {
                $answer = TRUE;
                break;
            }
        }
    }
    return $answer;
}

// discover whether the user is allowed to navigate by proofer for this page
function can_navigate_by_proofer($projectid, $L_user) 
{
    global $pguser;
    $answer =  FALSE;
    // If user isn't logged in, they definitely can't 
    if ( $pguser == '' ) return FALSE;
    $project = new Project( $projectid );

    // if user can manage project, or is evaluator they can
    $answer = $project->can_be_managed_by_current_user ||
        user_is_an_access_request_reviewer();
    
    // otherwise, they can if this diff is one of theirs
    if (! $answer)
    {
        $answer = ($pguser == $L_user);
    }
    return $answer;
}

// vim: sw=4 ts=4 expandtab
?>
