<?PHP
ini_set("display_errors", true);
error_reporting(E_ALL);
// Give information on smooth reading
// including (most importantly) the list of projects currently available

$relPath = '../../pinc/';
include_once($relPath.'dpinit.php');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'page_header.inc');
include_once($relPath.'links.php');

// ---------------------------------------
//Page construction varies with whether the user is logged in or out

$header_text = $User->IsLoggedIn() 
        ? _("Smooth Reading Projects")
        : _("Smooth Reading Projects Preview");
theme( $header_text, 'header');
dp_page_header( 'SR', $header_text );
show_news_for_page( $User->IsLoggedIn() ? "SR" : "SR_PREV");

if ($User->IsLoggedIn()) {
    echo "
    <p>
        The goal of 'Smooth Reading' is to read the text attentively, as for
        pleasure, with just a little more attention than usual to punctuation,
        etc.  This is NOT full scale proof-reading, and comparison with the
        scans is not needed.  Just read it as your normal,
        sensitized-to-proofing-errors self, and report any problem that
        disrupts the sense or the flow of the book.  Note that some of these
        will be due to the author and/or publisher.
    </p>

    <p>
    The way to report errors is by adding a comment of the form
        <blockquote>
        <font color='red'>[**correction or query]</font><br>
        </blockquote>
    immediately after the problem spot.
    Do not correct or change the problem, just note it in the above format.
    </p>

    <p>
    Examples:
    <ul>
    <li>that was the end,[**.] However, the next day</li>
    <li>that was the end[**.] However, the next day</li>
    <li>that was the emd.[**end] However, the next day</li>
    </ul>
    </p>

    <p>
    For more information on the origin of smoothreading,
    see <a href='{$forums_url}/viewtopic.php?t=3429'>this thread</a>.
    </p>
    ";
}
else {
    echo "
    <p>
    This Preview page shows which books are currently available for Smooth Reading.
    </p>

    <p>
    To be able to upload corrections, join DP.  There is a register link near
    the upper right corner of this page.
    </p>
    ";
}


$sql = "
        SELECT p.projectid,
               p.nameofwork,
               p.authorsname,
               p.language,
               p.difficulty,
               p.genre,
               p.n_pages,
               p.postproofer,
               DATEDIFF(DATE(FROM_UNIXTIME(p.smoothread_deadline)), 
                    CURRENT_DATE()) days_left,
               p.username as PM
        FROM projects p
        WHERE smoothread_deadline > UNIX_TIMESTAMP()
        ORDER BY p.smoothread_deadline";

$rows = $dpdb->SqlRows($sql);

$tbl = new DpTable();
$tbl->AddColumn("<Title", "nameofwork", "etitle");
$tbl->AddColumn("<Author", "authorsname");
$tbl->AddColumn("<Language", "language");
$tbl->AddColumn("<Genre", "genre");
if($User->IsLoggedIn()) {
    $tbl->AddColumn("<PM", "PM", "epm");
    $tbl->AddColumn("<PPer", "postproofer", "epm");
    $tbl->AddColumn("<Download", "projectid", "edownload");
    $tbl->AddColumn("^Upload", "projectid", "eupload");
}
$tbl->AddColumn(">Pages", "n_pages");
$tbl->AddColumn("^Days<br/>left", "days_left");
$tbl->SetRows($rows);
$tbl->EchoTable();

theme('', 'footer');
exit;

function eupload($projectid) {
    global $projects_dir;
    $nup = count(glob("$projects_dir/$projectid/*smooth*"));
    $nup -= 1;
    $url = url_for_upload_text($projectid, "smooth_done");
    return "<a href='$url' title='$nup so far'>upload</a>\n";
}
function edownload($projectid) {
    return link_to_smooth_download($projectid, "download");
}

function epm($str) {
    return link_to_pm($str);
}

function etitle($title, $row) {
    $projectid = $row["projectid"];
    return link_to_project($projectid, $title);
}

// vim: sw=4 ts=4 expandtab
?>

