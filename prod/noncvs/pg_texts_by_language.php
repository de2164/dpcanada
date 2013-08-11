<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager()) die("permission denied");

dpsql_dump_query("
    SELECT languages, COUNT(*)
    FROM (
        SELECT
            postednum,
            GROUP_CONCAT( DISTINCT language ORDER BY language SEPARATOR ' & ' ) AS languages
        FROM projects
        WHERE state='proj_submit_pgposted'
        GROUP BY postednum
    ) AS T
    GROUP BY languages
    ORDER BY languages
");

// vim: sw=4 ts=4 expandtab
?>
