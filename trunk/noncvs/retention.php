<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

echo "
<p>
For each calendar month,
the number of users who registered in that month,
and the percentage of those who were active at least 1 day (7 days, 28 days) after reg.
</p>
";

$res = dpsql_dump_query("
    SELECT
        FROM_UNIXTIME(date_created,'%Y-%m') AS month_registered,
        COUNT(*) AS n_registered,
        100 * SUM( (t_last_activity >= date_created +  1*24*60*60) ) / COUNT(*) AS f_active_after_1_day,
        100 * SUM( (t_last_activity >= date_created +  7*24*60*60) ) / COUNT(*) AS f_active_after_7_days,
        100 * SUM( (t_last_activity >= date_created + 28*24*60*60) ) / COUNT(*) AS f_active_after_28_days
    FROM users
    GROUP BY month_registered
    ORDER BY month_registered
");

// Note that ((t_last_activity - date_created) >= 1*24*60*60) doesn't work, because
// t_last_activity can be 0,
// so t_last_activity - date_created would be a big negative,
// except that t_last_activity and date_created are unsigned,
// so their difference is also unsigned,
// so instead of a big negative, we get a huge positive,
// which makes the user count as if retained into the far future.

// vim: sw=4 ts=4 expandtab
?>
