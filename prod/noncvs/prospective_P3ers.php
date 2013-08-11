<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dpsql.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'Stage.inc');
include_once($relPath.'stages.inc');
include_once('page_events.inc');

if (1)
{
    include_once($relPath.'dp_main.inc');
    if (!user_is_a_sitemanager()) die("permission denied");
}
else
{
    include_once($relPath.'connect.inc');
    new dbConnect();
    include_once('cli.inc');
    $_GET['language'] = 'French';
}

if ( empty($_GET) )
{
    echo <<<_
    <form>
    Language: <input name='language' type='text'>
    <input type='submit'>
    </form>
_;
    exit;
}

$language = $_GET['language'];
$days_back = 30;
$work_round_id = 'P2';
$access_round_id = 'P3';

$res = dpsql_query("
    SELECT projectid
    FROM projects
    WHERE language LIKE '$language%'
");
list($projectids) = dpsql_fetch_columns($res);
$n = count($projectids);

echo "$n projects have primary language = '$language'<br>\n";
if ( $n == 0 ) exit;
if ( $n > 100 ) die("That may be too many for this script.");

$seconds_back = $days_back * 24 * 60 * 60;
$timestamp = time() - $seconds_back;
$event_id = get_least_event_id_with_timestamp( $timestamp );

$projectids_s = surround_and_join( $projectids, "'", "'", "," );

// Who has saved a page in P2 in a language-X project in the last n days?
$res = dpsql_query("
    SELECT DISTINCT username
    FROM page_events
    WHERE
        event_type IN ('saveAsInProgress','saveAsDone')
        AND
        round_id = '$work_round_id'
        AND
        projectid IN ($projectids_s)
        AND
        event_id >= $event_id
");
list($usernames) = dpsql_fetch_columns($res);
$n = count($usernames);
echo "$n users have saved a page in $work_round_id in a $language project in the last $days_back days<br>\n";
echo "Here is a chart of their access with respect to $access_round_id:<br>\n";

$access_round = get_Stage_for_id($access_round_id);

echo "<table border='1'>\n";
{
    echo "<tr>";
    echo "<th rowspan='2'>username</th>\n";
    foreach ( $access_round->access_minima as  $criterion_code => $minimum )
    {
        echo "<th>$criterion_code</th>\n";
    }
    echo "<th rowspan='2'>all minima satisfied?</th>\n";
    echo "<th rowspan='2'>request status</th>\n";
    echo "<th rowspan='2'>can access $access_round_id?</th>\n";
    echo "</tr>";

    echo "<tr>";
    foreach ( $access_round->access_minima as  $criterion_code => $minimum )
    {
        echo "<th>min = $minimum</th>\n";
    }
    echo "</tr>";

}
foreach ($usernames as $username)
{
    $zo = array('0','1');
    $uao = $access_round->user_access( $username );
    $collater = ''
        . $zo[$uao->can_access]
        . $zo[$uao->all_minima_satisfied]
        . $uao->request_status
    ;
    foreach ( $uao->minima_table as $row )
    {
        list($criterion_str, $minimum, $user_score, $satisfied) = $row;
        $collater .= $zo[$satisfied];
    }
    $x[$collater][$username] = $uao;
}

ksort($x);
foreach ( $x as $collater => $uaos )
{
    foreach ( $uaos as $username => $uao )
    {
        show_row( $username, $uao );
    }
}

function show_row( $username, $uao )
{
    echo "<tr>";
    echo "<td>$username</td>\n";
    foreach ( $uao->minima_table as $row )
    {
        list($criterion_str, $minimum, $user_score, $satisfied) = $row;
        $bgcolor = ( $satisfied ? '#ccffcc' : '#ffcccc' );
        echo "<td bgcolor='$bgcolor'>$user_score</td>\n";
    }

    $bgcolor = ( $uao->all_minima_satisfied ? '#ccffcc' : '#ffcccc' );
    $ams = ( $uao->all_minima_satisfied ? 'yes' : 'no' );
    echo "<td bgcolor='$bgcolor'>$ams</td>\n";

    echo "<td>{$uao->request_status}</td>\n";

    $bgcolor = ( $uao->can_access ? '#ccffcc' : '#ffcccc' );
    $ca = ( $uao->can_access ? 'yes' : 'no' );
    echo "<td bgcolor='$bgcolor'>$ca</td>\n";

    echo "</tr>";
    echo "\n";
}
echo "</table>\n";

// vim: sw=4 ts=4 expandtab
?>
