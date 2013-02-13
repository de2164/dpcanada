<?
$relPath="../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dpsql.inc');
include_once($relPath.'../stats/jpgraph_files/common.inc');
include_once($relPath.'connect.inc');

$graph = init_simple_bar_graph(640, 400, 60);

new dbConnect();

$expr = "FLOOR( LOG10( (t_last_activity - date_created) / (24 * 60 * 60) ) )";
$result = mysql_query("
    SELECT
        $expr,
        COUNT(*)
    FROM users
    WHERE t_last_activity > date_created
    GROUP BY 1
    ORDER BY 1
");

list( $datax, $datay ) = dpsql_fetch_columns($result);

draw_simple_bar_graph(
    $graph,
    $datax,
    $datay,
    1,
    "DP users according to $expr",
    '# users'
);

// vim: sw=4 ts=4 expandtab
?>
