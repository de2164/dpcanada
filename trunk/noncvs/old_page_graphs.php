<?
$relPath='./../c/pinc/';
include($relPath.'site_vars.php');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

theme('Pages Proofed Graphs','header');
echo "<center><h1><i>Pages Proofed Graphs</i></h1></center>";

echo "<br><br>";
echo "<h3>These graphs use data collected under the old two round system between 1st Dec 2000 and 30th May 2005</h3><br><br>";
echo "They are presented here for historical interest<br><br>";
echo "<center><img src=\"old_pages_daily.php?cori=increments&timeframe=all_time\"></center><br>";
echo "<center><img src=\"old_pages_daily.php?cori=cumulative&timeframe=all_time\"></center><br>";
echo "<center><img src=\"old_pages_by_month_graph.php\"></center><br>";

theme('','footer');
?>

