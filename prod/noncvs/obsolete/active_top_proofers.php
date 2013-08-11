<?
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() ) die("not allowed");

$n_proofers = 100;
$n_pages = 1;
$n_months = 6;

echo "<h3>Top $n_proofers proofers (as ranked by total page count) who have saved at least $n_pages page in the last $n_months months</h3>\n";

if ( TRUE )
{
	// don't hide any names
	$x = 'username';

}
else
{
	// hide names of users who don't want even logged on people to see their names
	$x = "IF(u_privacy = 1,'Anonymous', username)";

}

$n_months_ago = time() - $n_months * 30.5 * 24 * 60 * 60;

$limit = $n_proofers * 2;
$res1 = dpsql_query("
	SELECT u_id, $x, pagescompleted
	FROM users
	WHERE pagescompleted > 0
	ORDER BY pagescompleted DESC, 1 ASC
	LIMIT $limit
");

echo "<table border='1'>";
{
	echo "<tr>";
	echo "<th>rank</th>";
	echo "<th>username</th>";
	echo "<th>pagescompleted</th>";
	echo "<th># pages in last $n_months months</th>";
	echo "</tr>\n";
}
$rank = 0;
while ( list($u_id, $username,$pagescompleted) = mysql_fetch_row($res1) )
{
	$res2 = dpsql_query("
		SELECT SUM(daily_pagescompleted)
		FROM member_stats
		WHERE u_id = $u_id
		AND date_updated > $n_months_ago
	") or die("aborting");
	list($n_pages_in_last_n_months) = mysql_fetch_row($res2);
	if ($n_pages_in_last_n_months == 0 )
	{
		echo "skipping $username with $pagescompleted<br>\n";
		continue;
	}

	$rank++;
	echo "<tr>";
	echo "<td>$rank</td>";
	echo "<td>$username</td>";
	echo "<td>$pagescompleted</td>";
	echo "<td>$n_pages_in_last_n_months</td>";
	echo "</tr>\n";
	if ( $rank == $n_proofers ) break;
}
echo "</table>";

?>
