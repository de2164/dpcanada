<?PHP
$relPath = '../../pinc/';
require_once $relPath . 'dpinit.php';
require_once $relPath . 'DpPage.class.php';

$projectid      = Arg('projectid');
$pagename       = Arg('pagename');
$image_zoom     = Arg("image_zoom", "100");
$submit_replace = IsArg("submit_replace");

$project = new DpProject($projectid);
$page    = new DpPage($projectid, $pagename);

$image   = $page->Image();

/** @var DpPage $page */
if($project->UserMayManage() && $submit_replace && count($_FILES) > 0 ) {
    $upfilename  = $_FILES["upfile"]["name"];
    if(mb_strlen($upfilename) >= 5) {
        $uptempname  = $_FILES["upfile"]["tmp_name"];
        $upfiletype  = $_FILES["upfile"]["type"];
        $upfilesize  = $_FILES["upfile"]["size"];
        if(right($upfiletype, 3) != right($image, 3)) {
            die("Cannot replace $image with $upfilename (different types)");
        }
        if($upfilesize == 0) {
            die("Cannot replace $image with $upfilename (zero length file)");
        }
        $page->ReplaceImage($uptempname);
        divert("?projectid=$projectid&pagename=$pagename");
    }
}

setcookie("image_zoom", $image_zoom);

$rows = $dpdb->SqlObjects("
        SELECT image, fileid AS pgname FROM $projectid
        ORDER BY image");

if($pagename == "") {
    $row = $rows[0];
    $pagename = imagefile_to_pagename($row->pgname);
    $image    = $row->image;
}

$prev_image = "";
$next_image = "";
$prev_pgname = "";
$next_pgname = "";

$opts = "";
$isel = 0;
for($i = 0; $i < count($rows); $i++) {
    $row = $rows[$i];
    $pgname     = $row->pgname;
    if($pgname == $pagename) {
        $image      = $row->image;
        $isel       = $i;
    }
    $opts .= "<option value='$pgname'>$pgname</option>\n";
}

if($isel > 0) {
    $row = $rows[$isel-1];
    $prev_image = $row->image;
    $prev_pgname = $row->pgname;
}
if($isel < count($rows)-1) {
    $row = $rows[$isel+1];
    $next_image = $row->image;
    $next_pgname = $row->pgname;
}

$state = $project->State();
$title = $project->NameOfWork();
$returnto = _("Return to Project Page");

echo "
<html>
<head>
<script>
function ebody() {
    var c = document.cookie;
    if(! c)
        return;
    var m = c.match(/image_zoom=(.*?);/);
    if(! m || m.length < 2)
        return;
    var val = m[1];
    document.getElementById('image_zoom').value = val;
    document.getElementById('jumpto').selectedIndex = {$isel};
}

function eJumpTo() {
    // set hidden input
    document.getElementById('pagename').value 
                        = document.getElementById('jumpto').value ;
    // submit, with input set to desired pagename
    document.forms[0].submit();
}
function ePrevClick() {
    document.getElementById('pagename').value = '$prev_pgname';
    document.forms[0].submit();
}
function eNextClick() {
    document.getElementById('pagename').value = '$next_pgname';
    document.forms[0].submit();
}

</script>
</head>

<body onload='ebody()'>
<form name='imgform' id='imgform' 
    enctype='multipart/form-data' method='POST' action=''
    style='margin: 0;'>
  <input type='hidden' name='projectid' id='projectid' value='$projectid'>
  <input type='hidden' name='pagename' id='pagename' value='$pagename'>
  <div style='width: 100%; text-align: center'>
    <a style='float: left' href='$code_url/project.php?projectid=$projectid'>$returnto</a>
    <h3 style='margin: 0;'>$title</h3> 
  </div>
  <div style='float: left'>
    Width:
    <input type='text' maxlength='3' name='image_zoom' id='image_zoom' size='3'  
            value='$image_zoom'> %
    <input type='submit' value='Resize' name='submit_resize'>
  </div>
  <div style='float: right'>
    Jump to:
    <select name='jumpto' id='jumpto' onChange='eJumpTo()'>
      $opts
    </select>
    <input type='button' value='Previous' onClick='ePrevClick()'>
    <input type='button' value='Next' onClick='eNextClick()'>
  </div>\n";

if($project->UserMayManage()) {
    echo "
  <div style='clear: both; text-align: center'>
    <input type='file' name='upfile' id='upfile' style='border: 1px solid gray; width: 25em;'>
    <input type='submit' value='Replace Image File' name='submit_replace'>
  </div>\n";
}
echo "
</form>
<br>
<img src='{$projects_url}/{$projectid}/{$image}' width='{$image_zoom}%' border='1'>
</body></html>";

