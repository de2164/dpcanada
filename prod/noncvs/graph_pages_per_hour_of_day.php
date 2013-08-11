<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'../stats/jpgraph_files/common.inc');
include_once('page_events.inc');

$graph = init_simple_bar_graph(640, 400, 60);

new dbConnect();

if (1)
{
    $period = 'hour of the day';
    $format = '%H';
    $tick = 1;
}
else
{
    $period = 'hour of the week (0=Sunday)';
    $format = '%w %H';
    $tick = 6;
}

$timestamp = time() - 7 * 24 * 60 * 60;
$event_id = get_least_event_id_with_timestamp( $timestamp );

$res = mysql_query("
    SELECT
        FROM_UNIXTIME(timestamp,'$format'),
        SUM(event_type='saveAsDone') - SUM(event_type='reopen')
    FROM page_events
    WHERE event_id >= $event_id
    GROUP BY 1
    ORDER BY 1
") or die(mysql_error());

list($datax,$datay) = dpsql_fetch_columns($res);

$title = "Number of pages saved per $period";

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
