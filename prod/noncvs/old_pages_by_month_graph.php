<?
$relPath="./../c/pinc/";
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
include_once('../c/stats/jpgraph_files/common.inc');

$graph = init_pages_graph(1440);

new dbConnect();

///////////////////////////////////////////////////
//Total pages by month since beginning of stats
//query db and put results into arrays
$result = mysql_query("
    SELECT CONCAT(year, '-', month), SUM(pages), SUM(dailygoal)
    FROM pagestats
    GROUP BY year, month
    ORDER BY year ASC, month ASC
");

list($datax,$datay1,$datay2) = dpsql_fetch_columns($result);

draw_pages_graph(
    $graph,
    $datax,
    $datay1,
    $datay2,
    'monthly',
    'increments',
    'Pages Done Each Month Since the Beginning of Statistics Collection'
);

// vim: sw=4 ts=4 expandtab
?>

