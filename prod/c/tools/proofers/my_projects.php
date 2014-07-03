<?PHP
$relPath='../../pinc/';
include_once($relPath.'dpinit.php');
include_once($relPath.'stages.inc');

$pcheckout      = ArgArray("checkout");     // checkout to PP
$puncheckout    = ArgArray("uncheckout");   // return without upload
$pupload        = ArgArray("upload");       // complete with upload
$psetcomplete   = ArgArray("setcomplete");  // complete without upload

$error_message = "";

if(count($pcheckout) > 0) {
    foreach($pcheckout as $k => $v) {
        $p = new DpProject($k);
        $p->PPCheckout();
        break;
    }
}

if(count($puncheckout) > 0) {
    foreach($puncheckout as $k => $v) {
        $p = new DpProject($k);
        $p->PPUnCheckout();
        break;
    }
}

if(count($pupload) > 0) {
    $keys = array_keys($pupload);
    $projectid = $keys[0];
    divert(url_for_upload_pp($projectid));
}

if(count($psetcomplete) > 0) {
    foreach($psetcomplete as $k => $v) {
        // dump("$k $v");
        // projectid 'PP Complete'
        $p = new DpProject($k);
        /** @var DpProject $p */
        if($p->Phase() == "PP") {
            $msgs = $p->PPSetComplete();
        }
        else if($p->Phase() == "PPV") {
            $msgs = $p->PPVSetComplete();
        }
        else {
            $msgs = array(
                "Attempting to set PP or PPV complete while phase = {$p->Phase()}.");
            break;
        }
        if(count($msgs) > 0) {
            $msgs[] = "PP Completion failed";
            $error_message = implode("<br>", $msgs);
            die($error_message);
        }
    }
}


$theme_args = array();
if($User->IsNewWindow()) {
    $newProofWin_js = include($relPath.'js_newwin.inc');
    $theme_args['js_data'] = $newProofWin_js;
    $link_js = "onclick='newProofWin(\"%s\"); return false;'";
}

if ( $User->IsSiteManager() || $User->IsProjectFacilitator() ) {
    $username = Arg("username", $User->Username() );
}
else {
    $username = $User->Username();
}

$no_stats = 1;
theme( _("My Projects"), 'header', $theme_args );

echo link_to_my_diffs("P1", "My diffs", true);


if ( $username == $User->Username() ) {
    $head_title = $heading_proof = _("My Projects");
    $open_title = _("I have pages checked out in the following projects");
    $heading_proof = _("Projects I've helped format and/or proof");
    $heading_reserved =  _("Projects reserved for me to post-process");
}
else {
    $head_title = _("Projects for User $username");
    $open_title = _("$username has pages checked out in the following projects");
    $heading_proof = _("Projects $username has helped format and/or proof");
    $heading_reserved =  _("Projects reserved for $username to post-process");
}

echo "<h2 class='center'>$head_title</h2>\n";

$rows = open_page_counts($username);
if(count($rows) > 0) {
    echo "
    <hr>
    <h4 class='center'>$open_title</h4>\n";
    show_open_page_counts($rows);
}

if ( $username == $User->Username() ) {
    $head_title = $heading_proof = _("My Projects");
}
else {
    $head_title = _("Projects for User $username");
}

echo_my_pp_projects($username);



// -------------------------------------------------
// My projects
// -------------------------------------------------

$tbl = new DpTable();

$tbl->SetClass("dptable sortable w75");

$tbl->SetId("tbl_my_projects");
$tbl->AddColumn("<Title", "nameofwork", "eTitle");
$tbl->AddColumn("<Current state", "phase", "eephase", "sortkey=roundseq");
$tbl->AddColumn("<Worked in", "round_id", "eRound");
$tbl->AddColumn("<Last activity", "max_time", "eLastTime", "sortkey=strtime");


$sql = "
SELECT  pe.projectid,
            GROUP_CONCAT(DISTINCT pe.round_id) round_id,
            pph.sequence roundseq,
            DATE_FORMAT(MAX(FROM_UNIXTIME(pe.timestamp)), '%M %d %Y') AS max_time,
            MAX(FROM_UNIXTIME(pe.timestamp)) AS strtime,
            p.nameofwork,
            p.username,
            p.phase,
            p.state,
            MIN(h.id) is_hold
    FROM page_events pe
    JOIN projects p ON pe.projectid = p.projectid
    JOIN phases ph ON pe.round_id = ph.phase
    JOIN phases pph ON p.phase = pph.phase
    LEFT JOIN project_holds h ON p.projectid = h.projectid AND p.phase = h.phase
    WHERE pe.username='$username'
        AND pe.event_type = 'saveAsDone'
        AND p.archived = 0
        AND p.phase IN ('P1', 'P2', 'P3', 'F1', 'F2', 'PP', 'PPV')
    GROUP BY pe.projectid
    ORDER BY strtime DESC ";

    /*
    SELECT  pe.projectid,
            GROUP_CONCAT(DISTINCT pe.round_id) round_id,
            DATE_FORMAT(MAX(FROM_UNIXTIME(pe.timestamp)), '%M %d %Y') AS max_time,
            MAX(FROM_UNIXTIME(pe.timestamp)) AS strtime,
            p.nameofwork,
            p.username,
            p.phase,
            p.state,
            (SELECT COUNT(1) FROM project_holds
             WHERE projectid = p.projectid AND phase = p.phase) hold_count
    FROM page_events pe
    JOIN projects p ON pe.projectid = p.projectid
    JOIN 
    WHERE pe.username='$username'
        AND pe.event_type = 'saveAsDone'
        AND p.archived = 0
        AND p.phase IN ('P1', 'P2', 'P3', 'F1', 'F2', 'PP', 'PPV')
    GROUP BY pe.projectid
    ORDER BY strtime DESC";
    */

echo "<!-- \n $sql \n -->\n";

$rows = $dpdb->SqlRows($sql);

$tbl->SetRows($rows);

echo "<hr>
    <h4 class='center'>$heading_proof</h4>\n";

$tbl->EchoTable();

function eephase($phase, $row) {
    switch($phase) {
        case "P1":
        case "P2":
        case "P3":
        case "F1":
        case "F2":
            return "$phase " . ($row["is_hold"] > 0 ? "On Hold" : "Available");
        default:
            return $phase;
    }
}

theme( '', 'footer' );

function open_page_counts($username) {
    global $dpdb;
    $sql = "
            SELECT p.nameofwork,
                   p.phase,
                   pe.projectid, 
                   COUNT(1) pagecount
            FROM page_events pe
            JOIN projects p ON pe.projectid = p.projectid
            LEFT JOIN page_events pe0
                ON pe.projectid = pe0.projectid
                    AND pe.image = pe0.image
                    AND pe.timestamp < pe0.timestamp
            WHERE pe.event_type IN ('checkout', 'reclaim', 'reopen', 'saveAsInProgress')
                AND p.phase IN ('P1', 'P2', 'P3', 'F1', 'F2')
                AND pe.username = '$username'
                AND pe0.event_id IS NULL
            GROUP BY pe.projectid
            ORDER BY p.phase, p.nameofwork";

    echo "<!-- \n $sql \n -->\n";

    $rows = $dpdb->SqlRows($sql);
    return $rows;
}

function show_open_page_counts($rows) {

    $tbl = new DpTable();
    $tbl->SetClass("dptable sortable w50");
    $tbl->AddColumn("<Title", "nameofwork", "etitle");
    $tbl->AddColumn("^Round", "phase", "ephase");
    $tbl->AddColumn("^Pages", "pagecount");
    $tbl->SetRows($rows);
    $tbl->EchoTable();
}

function etitle($nameofwork, $row) {
    $title = $nameofwork;
    return link_to_project($row['projectid'], $title, true);
}


function ephase($phase) {
    return $phase;
}

function eRound($roundid) {
    $a = preg_split("/,\s*/", $roundid);
    if(count($a) == 1) {
        return $a[0];
    }
    $b = array();
    foreach(array("P1", "P2", "P3", "F1", "F2") as $rid) {
        if(array_search($rid, $a)) {
            $b[] = $rid;
        }
    }
    // dump($b);
    return implode(", ", $b);
}

function ePM($pm) {
    return link_to_pm($pm, $pm, true);
}

function eLastTime($ts) {
    return $ts;
}

function echo_my_pp_projects($username) {
    global $dpdb;

    $rows = $dpdb->SqlRows("
        SELECT
            projectid,
            nameofwork,
            authorsname,
            language,
            genre,
            n_pages,
            username AS pm,
            DATEDIFF(FROM_UNIXTIME(smoothread_deadline), CURRENT_DATE()) AS smooth_days,
            DATEDIFF(CURRENT_DATE(), FROM_UNIXTIME(phase_change_date)) AS days_avail
        FROM projects
        WHERE phase = 'PP'
            AND postproofer = '$username'
        ORDER BY days_avail");

    if(count($rows) == 0) {
        return;
    }

    echo "
    <div class='center w75'>
    <h4 class='center'>
        I have checked out the following projects to Post-Process</h4>
    <p class='left'>Іn order to advance to PP Verification, you need to<br/>
    1. upload a zip file with the completed project, and<br/>
    2. click the 'PP Complete' button,<br/>
    3. wait until the smooth-reading period is over, if there is one open.
    <br/>
    The 'Uploaded file' column shows the status of that file, with a button to click if
    you want to Upload. (It still works after uploading if you want to resend.<p>
    <p class='left'>Once you have uploaded, and any smooth-reading periods are completed,
    and when you click the 'PP Completed' button, the project
    will advance to PP Verification.</p>
    </div>

    <form name='myform' action='' method='POST'>\n";


    $tbl = new DpTable();
    $tbl->SetClass("w75 dptable sortable");
    $tbl->AddColumn("<Title", "nameofwork", "etitle");
    $tbl->AddColumn("<Author", "authorsname");
    $tbl->AddColumn("<Language", "language");
    $tbl->AddColumn("<Genre", "genre");
    $tbl->AddColumn("^Pages", "n_pages");
    $tbl->AddColumn("<Proj mgr", "pm", "euser");
    $tbl->AddColumn("^Days", "days_avail", "edays");
    $tbl->AddColumn("^Smooth<br>days", "smooth_days", "esmooth");
    $tbl->AddColumn("^Uploaded<br>file", "projectid", "eupload");
    $tbl->AddColumn("^Manage", "projectid", "emanage");
    $tbl->SetRows($rows);

    $tbl->EchoTable();
    echo "</form>\n";
}

function emanage($projectid) {
    $color =  is_pp_upload_file($projectid) ? "lightGreen" : "inherit" ;
    $disabled = is_pp_upload_file($projectid) ? "" : " disabled";
    return "
        <input name='uncheckout[$projectid]' type='submit' value='Return to Avail'>
        <br/>
        <input name='setcomplete[$projectid]' type='submit'
            style='background-color: $color;' value='PP Complete' $disabled>\n";
}

function is_pp_upload_file($projectid) {
    return file_exists(ProjectPPUploadPath($projectid));
}

function esmooth($num) {
    return $num < 0 ? "" : edays($num);
}
function edays($num) {
    return number_format($num);
}
function euser($username) {
    return link_to_pm($username, $username, true);
}

function eupload($projectid) {
    $caption = is_pp_upload_file($projectid) ? "Replace" : "Upload";
    // $color =  is_pp_upload_file($projectid) ? "inherit" : "lightGreen" ;
    $color =  "lightGreen" ;
    return "<input type='submit' name='upload[$projectid]' 
        style='background-color: $color;' value='$caption'>\n";
}

