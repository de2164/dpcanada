<?php
/*
    buttons are: next, prev, proofer next, proofer prev
    which needs current projectid, pagename, roundid, proofer
    to make query SELECT pagename FROM pages
                  WHERE projectid ... AND pagename < or > pagename
                  (AND maybe roundn_user = joe)
*/

$relPath="./../../pinc/";
require_once $relPath . "dpinit.php";
require_once "DifferenceEngineWrapper.php";

/** @var $User DpThisUser */
$User->IsLoggedIn()
    or RedirectToLogin();

$projectid       = ArgProjectId();
$imagefile       = Arg("imagefile");
$pagename        = Arg("pagename", imagefile_to_pagename($imagefile));
$roundid         = Arg("roundid");

$btnnext         = IsArg("btnnext");
$btnprev         = IsArg("btnprev");
$btnprfnext      = IsArg("btnprfnext");
$btnprfprev      = IsArg("btnprfprev");

if(! $projectid)
    die(_("Project id not provided."));
if(! $pagename)
    die(_("Page Name not provided."));
if(! $roundid)
    die(_("Round id not provided."));

$project         = new DpProject($projectid);
$page            = new DpPage($projectid, $pagename);
$proofername     = $page->RoundUser($roundid);
$prevroundid     = RoundIdBefore($roundid);
$prevproofername = $page->RoundUser($prevroundid);


// determine what page is wanted
if($btnnext) {
    $pgname = $project->PageNameAfter($pagename);
    if($pgname) {
        $pagename = $pgname;
    }
}
else if($btnprev) {
    $pgname = $project->PageNameBefore($pagename);
    if($pgname) {
        $pagename = $pgname;
    }
}
else if($btnprfnext) {
    $pgname = $project->ProoferRoundPageNameAfter($pagename, $prevroundid);
    if($pgname) {
        $pagename = $pgname;
    }
}
else if($btnprfprev) {
    $pgname = $project->ProoferRoundPageNameBefore($pagename, $prevroundid);
    if($pgname) {
        $pagename = $pgname;
    }
}

// if(empty($pagename)) {
    // $pagename = $imagefile;
// }

// get the page
$page        = new DpPage($projectid, $pagename);

$proofername     = $page->RoundUser($roundid);
$prevproofername = $page->RoundUser($prevroundid);
$pagename        = $page->PageName();
$project_title   = $page->Title();
$text            = $page->RoundText($roundid);
$prevtext        = $page->RoundText($prevroundid);

$label           = $project->UserMaySeeNames()
                     ? $roundid . " " . $page->RoundUser($roundid)
                     : $roundid;
$prevlabel       = $project->UserMaySeeNames()
                     ? $prevroundid." ".$page->RoundUser($prevroundid)
                     : $prevroundid;

// now have the image, users, labels etc all set up
// -----------------------------------------------------------------

$pagename   = $page->PageName();
$title      = "Page Diff — {$page->Title()} ("._("page name ")."{$pagename})";
// $imgurl     = $page->ImageUrl();
$projlink   = link_to_project($projectid, "Go to project page");

$diffEngine = new DifferenceEngineWrapper();
$view_image = _("view image");
$imgurl = url_for_view_image($projectid, $pagename, true);

echo "<!DOCTYPE HTML>
<html>
<head>
<meta charset='utf-8'>
<title>$title</title>
<link type='text/css' rel='stylesheet' href='{$css_url}/dp.css'>
<script type='text/javascript' src='dpdiff.js'></script>
</head>

<body onload='init()'>
<div class='container left'>

<h1>$title</h1>

<div id='diffbox' class='center w80'>
    <a href='{$imgurl}'>$view_image</a>
    <form id='navform' name='navform' method='GET' 
            accept-charset='UTF-8' class='right' >\n";

    echo "
        <div class='lfloat'>
        <input type='submit' name='btnprev' value='"
            ._("Previous")."'>

        ". _("Jump to:") . " 
        <select id='pagelist' onChange='eListChange()'>\n";

    foreach($project->PageRows() as $row) {
        $sel = ($row['fileid'] === $pagename
            ? " selected='selected' "
            : "");
        $name = $row['fileid'];
        echo "<option value='$name' {$sel}>$name</option>\n";
    } 

    echo "
        </select>
    <input type='submit' name='btnnext' value='"._("Next")."'>
    </div>\n";

    if($prevroundid == "P1" || $prevroundid == "P2" 
    || $prevroundid == "P3" || $prevroundid == "F1" ) {
        echo "
        <div class='rfloat'>
        <input type='submit' name='btnprfprev' 
                            value='"._("$prevroundid $prevproofername Prev")."'>
        <input type='submit' name='btnprfnext' 
                                value='"._("$prevroundid $prevproofername Next")."'>
        </div>\n";
    }

    echo "
        <input type='hidden' 
            id='projectid' name='projectid' value='$projectid'>
        <input type='hidden' 
            id='pagename' name='pagename' value='$pagename'>
        <input type='hidden' id='roundid' name='roundid' 
            value='$roundid'>
        <input type='hidden' id='imagepath' value='{$imgurl}'>
    </form>
    <p>{$projlink}</p>
    </div>\n";

    if($prevtext == $text) {
        echo "
        <div class='w50 center'>
            <h1>
                <span class='pct75 nobold'>$prevroundid($prevproofername)</span>"
                ._("&nbsp;&nbsp;No differences.&nbsp;&nbsp;")."
                <span class='pct75 nobold'>$roundid($proofername)</span>"
            ."</h1>
        </div>\n";
    }
    else {
        $a = maybe_convert($prevtext);
        $b = maybe_convert($text);
        $diffEngine->showDiff($a, $b, $prevlabel, $label);
    }
echo "
</div>
</body></html>\n";

// ---------------------------------------------------------


// theme_footer();
exit;


// vim: sw=4 ts=4 expandtab
?>
projectID50c6514b5aff5
