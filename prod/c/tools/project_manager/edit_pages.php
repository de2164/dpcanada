<?PHP
ini_set("display_errors", true);
error_reporting(E_ALL);

$relPath = "../../pinc/";
require_once $relPath . "dpinit.php";

$projectid      = Arg("projectid");
$confirmed      = Arg("confirmed");
$selected_pages = ArgArray("chk");
$submit_clear   = ArgArray("submit_clear");
$operation      = Arg("operation");

// -----------------------------------------------------------------------------
// Check for required parameters.

if($projectid == "") {
    die("Argument 'projectid' is required.");
}

$project = new DpProject( $projectid );

if(! $project->UserMayManage()) {
    die("Security violation.");
}

if(count($submit_clear) > 0) {
    $aclear = array_keys($submit_clear);
    foreach($aclear as $pagename) {
        $project->ClearPage($pagename);
    }
    divert(url_for_project_level($projectid, 4));
    exit;
}

// -----------------------------------------------------------------------------
// Check the set of selected pages.

if ( count($selected_pages)== 0 ) {
    echo _("You did not select any pages.") ;
    theme("","footer");
    exit;
}

// -----------------------------------------------------------------------------
// Check the requested operation.

foreach ( $selected_pages as $fileid => $setting ) {
    $page = new DpPage($projectid, $fileid);

    if($operation == "clear") {
        $page->ClearRound();
        echo "Page $fileid cleared.<br>\n";
    }
    else if($operation == "delete") {
        $page->Delete();
        echo "Page $fileid deleted.<br>\n";
    }
}
echo link_to_project($projectid, "Return to project");

$no_stats = 1;
theme( _("Edit Pages Confirmation"), "header");
theme("","footer");

// vim: sw=4 ts=4 expandtab
?>
