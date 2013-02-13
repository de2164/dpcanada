<?PHP
// Just a start, nothing too useful yet.

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'dpsql.inc');
new dbConnect;

dpsql_dump_query("
    SELECT
        (UNIX_TIMESTAMP() - date_created)/(24*60*60) >= 21 AS d_ge_21,
        tally_value >= 500 AS p1_ge_500,
        COUNT(*)
    FROM
        current_tallies
        JOIN
        users
        ON (holder_id=u_id)
    WHERE
        tally_name='P1'
        AND holder_type='U'
    GROUP by 1, 2
");

// vim: sw=4 ts=4 expandtab
?>
