<?PHP

// Graph the number of user registered per day.

$relPath='../c/pinc/';
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'../stats/jpgraph_files/common.inc');

$graph = init_simple_bar_graph(640, 400, 60);

new dbConnect();

$min_date_str = 'Dec 1, 2007';
$min_timestamp =  strtotime($min_date_str);

$graph_specs = array(
    'day' => '%Y-%b-%d',
    'week' => '%Y-%U',
    // dang, it looks like jgraph will only draw one graph per http request
);

foreach ( $graph_specs as $time_interval_name => $date_format )
{
    $res = mysql_query("
        SELECT FROM_UNIXTIME(date_created,'$date_format'), COUNT(*)
        FROM users
        WHERE date_created >= $min_timestamp
        GROUP BY 1
        ORDER BY date_created
    ") or die(mysql_error());

    list($datax,$datay) = dpsql_fetch_columns($res);

    $tick = 1;
    $title = "Number of users registered per $time_interval_name since $min_date_str";

    draw_simple_bar_graph(
        $graph,
        $datax,
        $datay,
        $tick,
        $title,
        _('# users')
    );
}

// vim: sw=4 ts=4 expandtab
?>
