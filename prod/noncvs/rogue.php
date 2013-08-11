<?

// This is a fairly inefficient script, and probably shouldn't be used.
// (Gets a list of projects, then visits each project's page table.)

$relPath='../c/pinc/';
include($relPath.'site_vars.php');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include_once($relPath.'user_is.inc');
include($relPath.'theme.inc');


if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) exit();


$rogue_user = isset($_GET['r_user'])?$_GET['r_user']:$GLOBALS['pguser'];

$daysback = isset($_GET['days'])?$_GET['days']:14;


echo "<h3><b>Usage</b></h3><br>Edit the url in the address bar of your browser to inspect a different proofer or for a different number of days back. Details follow.<br><br>";
echo "Add ?r_user=NAME to the url of this page to inspect NAME's projects. ";
echo "(if r_user is not specified, the page will check YOUR projects.) ";
echo "Add ?days=X to inspect projects that have changed state in last X days. ";
echo "NOTE this is not the same as all pages proofed in last X days! It's all pages proofed in those projects that have changed state (e.g. entered or left a round, or PPing) in the last X days. If X is not specified, it uses 14<br><br>";
echo "To specify both user and days, use the format ?r_user=NAME&days=X<br><br>";


echo "<h2>Starting candidate projects query for rogue user $rogue_user<br><br></h2>";
echo "<h3>Note: restricted to projects that changed state in last $daysback days</h3><br>\n\n";

$proj_qry = mysql_query("
    SELECT projectid, nameofwork, state, difficulty, authorsname, username
    FROM projects
    WHERE modifieddate > unix_timestamp() - ($daysback * 86400)
        AND state != 'project_new'
        AND state != 'waiting_1'
    ORDER BY state, username, authorsname, nameofwork
");
$numproj = mysql_num_rows($proj_qry);

echo "Found $numproj candidate projects <br><br>\n\n";

$last_state = 'x';

while ($row = mysql_fetch_array($proj_qry)) {
    $curr_state = $row['state'];

    if ($last_state != $curr_state) {
        $last_state = $curr_state;
        $st_changed = 1;
    }

    $curr_proj = $row['projectid'];

    $user_qry = mysql_query("
        SELECT
            SUM(round1_user='$rogue_user'),
            SUM(round2_user='$rogue_user'),
            SUM(round3_user='$rogue_user'),
            SUM(round4_user='$rogue_user')
        FROM $curr_proj
    ");

    //some projects may have been archived
    if ($user_qry) {
        list($r1,$r2,$r3,$r4) = mysql_fetch_row($user_qry);

        if ($r1 + $r2 + $r3 + $r4 > 0) {
            if ($st_changed) {
                $st_changed = 0;
                flush();
                echo "<br><br><b>";
                echo project_states_text($curr_state);
                echo " projects</b><hr><br>";
            }

            $name = $row['nameofwork'];
            $auth = $row['authorsname'];
            $diff = $row['difficulty'];

            echo "P1 = $r1 P2 = $r2 F1 = $r3 F2 = $r4<b><a href='http://www.pgdp.net/c/tools/project_manager/project_detail.php?project=$curr_proj&type=Full'>$name</a></b> ($diff)<br>\n";
        }
    }
}

echo "Done"

// vim: sw=4 ts=4 expandtab
?>
