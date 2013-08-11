<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

echo "<h2>Completed projects, by month</h2>";

echo "(Don't believe the 2002-01 count.)<br><br>";

dpsql_dump_query("
    SELECT 
        FROM_UNIXTIME(modifieddate,'%Y-%m') AS month,
        COUNT(*) AS '# projects posted'
    FROM projects
    WHERE state='".PROJ_SUBMIT_PG_POSTED."'
    GROUP BY month
    ORDER BY month
");

// vim: sw=4 ts=4 expandtab
?>
