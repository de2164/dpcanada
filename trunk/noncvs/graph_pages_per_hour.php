<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'../stats/jpgraph_files/common.inc');
include_once('page_events.inc');

// die("This graph is resource-intensive (page_events) and not terribly useful.");

$graph = init_simple_bar_graph(640, 400, 60);

new dbConnect();

// $cutoff = strtotime('Jan 1, 2008');
$cutoff = time() - 7 * 24 * 60 * 60;
$event_id = get_least_event_id_with_timestamp( $cutoff );

$res = mysql_query("
    SELECT
        FROM_UNIXTIME(timestamp,'%Y-%b-%d %H'),
        SUM(event_type='saveAsDone') - SUM(event_type='reopen')
    FROM page_events
    WHERE event_id >= $event_id
    -- WHERE timestamp >= $cutoff
    -- WHERE FROM_UNIXTIME(timestamp,'%Y-%b-%d') between '2005-Jun-06' and '2005-Jun-09'
    GROUP BY 1
    ORDER BY timestamp
") or die(mysql_error());

list($datax,$datay) = dpsql_fetch_columns($res);

$tick = 24;
$title = "Number of pages saved per hour";

draw_simple_bar_graph(
    $graph,
    $datax,
    $datay,
    $tick,
    $title,
    _('# pages')
);

// vim: sw=4 ts=4 expandtab
?>
