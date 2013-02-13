<?
$relPath="../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dpsql.inc');
include_once($relPath.'../stats/jpgraph_files/common.inc');
include_once($relPath.'connect.inc');

$graph = init_simple_bar_graph(640, 400, 60);

new dbConnect();

$result = mysql_query("
    SELECT
        FROM_UNIXTIME(t_last_activity, '%Y-%m') AS month,
        COUNT(*)
    FROM users
    GROUP BY month
    ORDER BY month
");

// If there was a month when nobody was last active,
// then the results will not include a row for that month.
// This may lead to a misleading graph,
// depending on its style.

list( $datax, $n_active_data ) = dpsql_fetch_columns($result);

draw_simple_bar_graph(
    $graph,
    $datax,
    $n_active_data,
    1,
    'DP users according to their month of last activity',
    '# users'
);

// vim: sw=4 ts=4 expandtab
?>
