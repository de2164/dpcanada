<?PHP
$relPath = '../../pinc/';
require_once $relPath . 'dpinit.php';
require_once $relPath . 'DpPage.class.php';

$projectid      = Arg('projectid');
$pagename       = Arg('pagename');
$submit_replace = IsArg("submit_replace");

$project = new DpProject($projectid);
$page    = new DpPage($projectid, $pagename);

$imagefile   = $page->Image();

/** @var DpPage $page */
if($project->UserMayManage() && $submit_replace && count($_FILES) > 0 ) {
    $upfilename  = $_FILES["upfile"]["name"];
    if(mb_strlen($upfilename) >= 5) {
        $uptempname  = $_FILES["upfile"]["tmp_name"];
        $upfiletype  = $_FILES["upfile"]["type"];
        $upfilesize  = $_FILES["upfile"]["size"];
        if(right($upfiletype, 3) != right($imagefile, 3)) {
            die("Cannot replace $imagefile with $upfilename (different types)");
        }
        if($upfilesize == 0) {
            die("Cannot replace $imagefile with $upfilename (zero length file)");
        }
        $page->ReplaceImage($uptempname);
        divert("?projectid=$projectid&pagename=$pagename");
    }
}

$rows = $dpdb->SqlObjects("
        SELECT image,
               fileid AS pgname
        FROM $projectid
        ORDER BY image");

if(! $pagename) {
    $pagename = $rows[0]->pgname;
}

$jsrows = json_encode($rows);

$opts = "";
$i = 0;
foreach($rows as $row) {
    $pgname     = $row->pgname;
    if($row->pgname == $pagename) {
        $opts .= "<option value='$i' selected='selected'>$pgname</option>\n";
    }
    else {
        $opts .= "<option value='$i'>$pgname</option>\n";
    }
    $i++;
}

$state = $project->State();
$title = $project->NameOfWork();
$returnto = _("Return to Project Page");
$imgpath = "/projects/$projectid/";

echo "
<!DOCTYPE HTML>
<html>
<head>
<title>DPC Page Image $pgname</title>
<meta charset='utf-8'/>
<script>
    var jsrows = " . $jsrows . ";

function $(ref) {
    return document.getElementById(ref);
}

function save_zoom(pct) {
    var c = document.cookie;
    if(! c) {
        c = 'image_zoom=' + pct.toString();
    }
    var m = c.match(/(.*)(image_zoom=\d*)(.*)/);
    if(m.length != 4) {
        return;
    }
    document.cookie = m[1] + 'image_zoom=' + pct.toString() + m[3];
}

function init_zoom() {
    var pct = parseInt($('image_zoom').value);

    if(isNaN(pct)) {
        var c = document.cookie;
        var m = c.match(/image_zoom=(.*?);/);
        if(m && m.length >= 2) {
            pct = parseInt(m[1]);
        }
    }
    pct = Math.max(pct, Math.min(pct, 200), 25);

    $('image_zoom').value = pct.toString();
    $('pageimage').style.width = pct.toString() + '%';
}

function eZoom() {
    init_zoom();
    return false;
}

function ebody() {
    init_zoom();
}

function eJumpTo() {
    // set hidden input
    var i = $('jumpto').selectedIndex;
    var r = jsrows[i];
    var imgfile = r.image;
    var src = $('imgpath').value + imgfile;
    $('pageimage').src = src;
    // $('pageimage').src = $('imgpath').value + r.image;
}

function ePrevClick() {
    var i = $('jumpto').selectedIndex;
    if(i <= 0) {
        return;
    }
    $('jumpto').selectedIndex--;
    $('pageimage').src = $('imgpath').value + jsrows[$('jumpto').selectedIndex].image;
}

function eNextClick() {
    var i = $('jumpto').selectedIndex;
    if(i >= $('jumpto').length) {
        return;
    }
    $('jumpto').selectedIndex++;
    $('pageimage').src = $('imgpath').value + jsrows[$('jumpto').selectedIndex].image;
}

</script>
</head>

<body onload='ebody()'>
<form name='imgform' id='imgform' 
    enctype='multipart/form-data' method='POST'
    style='margin: 0;'>
  <input type='hidden' name='projectid' id='projectid' value='$projectid'>
  <input type='hidden' name='pagename' id='pagename' value='$pagename'>
  <input type='hidden' name='imgpath' id='imgpath' value='{$imgpath}'>
  <div style='width: 100%; text-align: center'>
    <a style='float: left' href='$code_url/project.php?projectid=$projectid'>$returnto</a>
    <h3 style='margin: 0;'>$title</h3> 
  </div>
  <div style='float: left'>
    Width:
    <input type='text' maxlength='3' name='image_zoom' id='image_zoom' size='3' value=''> %
    <input type='button' value='Resize' name='submit_resize' onclick='eZoom()'>
  </div>
  <div style='float: right'>
    Page:
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
<img id='pageimage' src='{$imgpath}{$imagefile}' style='border: 1px solid gray;' alt=''>
</body></html>";

