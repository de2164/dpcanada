<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager()) die("permission denied");

$res = dpsql_dump_query("
    SELECT DISTINCT username, FROM_UNIXTIME(users.t_last_activity) AS 'Last Seen'
    FROM projects LEFT OUTER JOIN users USING (username)
    WHERE state LIKE '%.proj_%'
    ORDER BY username
") or die(mysql_error());

// vim: sw=4 ts=4 expandtab
?>
