<?PHP
// Give information about a single round,
// including (most importantly) the list of projects available for work.

$relPath='../../pinc/';
include_once $relPath.'dpinit.php';
include_once $relPath.'site_news.inc';
include_once $relPath.'mentorbanner.inc';

$roundid = Arg('round_id', Arg('roundid'));
if (!$roundid) {
    die("round.php invoked without round_id parameter.");
}
$round = get_Round_for_round_id($roundid);
if(! $round) {
    die("round.php invoked with invalid round_id='$roundid'.");
}

$username = $User->Username();
$pagesproofed = $User->PageCount();

if($User->IsNewWindow()) {
    $newProofWin_js = include($relPath.'js_newwin.inc');
    $theme_extras = array( 'js_data' => $newProofWin_js );
}
else {
    $theme_extras = array();
}

theme( "$round->id: $round->name", 'header', $theme_extras );

$title = "$round->id: $round->name";
echo "<h1 class='center'>$title</h1>\n";

if(! $User->MayWorkInRound($roundid)) {
    echo "<p align='center'>
    " . sprintf( _("Welcome to %s!"), $roundid ) . "
    ". _("Feel free to explore this stage.
    You can find out what happens here, and follow the progress of projects
    from earlier rounds. If you're interested in working in this stage, see
    below to find out how you can qualify.") . "</p>\n";
}

echo "<p>"._('What happens in this stage'). ":<br>$round->description</p>\n";

show_news_for_page($roundid);
$round_doc_url = "$code_url/faq/$round->document";

if ($pagesproofed >= 15 && $pagesproofed < 200) {
    echo "
        <hr class='w75'>
        <p>". _("New Proofreaders:")."
        <a href='$forums_url/viewtopic.php?t=388'>
        ". _("What did you think of the Mentor feedback you received?")."
        </a></p>\n";
}

if ($pagesproofed <= 20 && $User->MayWorkInRound($roundid)) {
    echo "
    <hr class='w75'>
    <p class='mainfont'>
    ". _("Click on the name of a book in the list below to start proofreading.")."
    </p>\n";
}

$phase = $round->id;
$sql = "
    SELECT  p.projectid,
            p.nameofwork,
            p.authorsname,
            p.language,
            p.genre,
            p.difficulty,
            p.username,
            LOWER(p.username) pmsort,
            p.n_pages,
            p.n_available_pages,
            DATEDIFF(CURRENT_DATE(),
                FROM_UNIXTIME(COALESCE(MAX(pe.timestamp), p.phase_change_date)))
                AS days_avail
    FROM projects p
    LEFT JOIN project_events pe
    ON p.projectid = pe.projectid
            AND pe.phase = p.phase
            AND event_type = 'hold'
            AND details1 LIKE 'release%'
    WHERE p.phase = '$phase'
        AND p.projectid NOT IN (
            SELECT projectid FROM project_holds
            WHERE phase = '$roundid'
        )
    GROUP BY p.projectid
    ORDER BY days_avail";
echo html_comment($sql);
$rows = $dpdb->SqlRows($sql);

$tbl = new DpTable();
$tbl->AddColumn("<Title", "nameofwork", "etitle");
$tbl->AddColumn("<Author", "authorsname");
$tbl->AddColumn("<Language", "language");
$tbl->AddColumn("<Genre", "genre");
$tbl->AddColumn("<Project<br>Mgr", "username", "epm", "sortkey=pmsort");
$tbl->AddColumn("^Available<br>Pages", "n_available_pages", "enumber");
$tbl->AddColumn("^Total<br>Pages", "n_pages", "enumber");
$tbl->AddColumn(">Days", "days_avail", "enumber");

$tbl->SetRows($rows);
$tbl->EchoTable();


theme('', 'footer');
exit;

// -----------------------------------------------------------------------------

function etitle($title, $row) {
    $projectid = $row["projectid"];
    return link_to_project($projectid, $title);
}

function epm($username) {
    return link_to_pm($username);
}

function enumber($val) {
    return $val;
}

// vim: sw=4 ts=4 expandtab
?>
