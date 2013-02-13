<?
error_reporting(E_ALL);

$relPath='../c/pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) die('not allowed');

$cutoff = 100;

$res = mysqL_query("
    SELECT MAX(timestamp), FROM_UNIXTIME(MAX(timestamp))
    FROM past_tallies
") or die(mysql_error());
list($timestamp, $readable) = mysql_fetch_row($res);

echo "Users who proofed more than $cutoff pages yesterday (day ending $readable):<br>";

dpsql_dump_query( "
    SELECT users.username, tally_name, tally_delta, tally_value
    FROM past_tallies,users
    WHERE
        past_tallies.holder_type = 'U'
        AND past_tallies.holder_id = users.u_id
        AND timestamp=$timestamp
        AND tally_delta > $cutoff
    ORDER BY tally_delta DESC
");

// vim: sw=4 ts=4 expandtab
?>

