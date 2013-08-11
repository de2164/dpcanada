<?PHP
$relPath = '../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

// Temporarily disable access restriction
// if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) exit();

echo "<html><head><title>Project Transitions</title>\n";
echo "<style type='text/css'> td { text-align: right; font-family: monospace; } </style>\n";
echo "</head><body>\n";

foreach ( array(7,30,90,180) as $days_back)
{
    $min_timestamp = time() - $days_back * 86400;

    echo "<h2>Project transitions  during the past $days_back days</h2>\n";

    echo "<table border=0 cellspacing=3 cellpadding=3>\n";
    echo "<tr><th>Round</th><th>Queued</th><th>Released</th><th>Completed</th><th>Round Growth</th><th>Queue Growth</th></tr>\n";

    $result = mysql_query("
        SELECT count(distinct projectid), details2
        FROM   project_events
        WHERE  event_type = 'transition'
        AND    timestamp > $min_timestamp
        GROUP BY details2
    ");

    while ( list($count,$state) = mysql_fetch_row($result) ) { $a[$state] = $count; };

    foreach ( array("P1","P2","P3","F1","F2") as $round)
    {
        $waiting=$a[$round.".proj_waiting"]+0;
        $entered=$a[$round.".proj_avail"]+0;
        $leaving=$a[$round.".proj_done"]+0;

    echo "<tr><td>";
    echo $round;
    echo "</td><td>";
    echo $waiting;
    echo "</td><td>";
    echo $entered;
    echo "</td><td>";
    echo $leaving;
    echo "</td><td>";
    echo $entered - $leaving;
    echo "</td><td>";
    echo $waiting - $entered;
    echo "</td></tr>\n";

    }

    echo "</table>\n";
}

echo "</body></html>";

// vim: sw=4 ts=4 expandtab
?>
