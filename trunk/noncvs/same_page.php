<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dpsql.inc');

if (!(user_is_a_sitemanager() || user_is_proj_facilitator())) die("permission denied");

// $username_A = 'Vasa';
// $username_B = 'the Senior Gravedigger';
$username_A = 'proofer1';
$username_B = 'proofer2';

echo "<h2>Pages saved as done by both (A) $username_A and (B) $username_B</h2>\n";

dpsql_dump_query("
    SELECT
        e_A.projectid AS projectid,
        e_A.image AS image,

        FROM_UNIXTIME(e_A.timestamp) AS save_A,
        e_A.round_id                 AS round_A,
        FROM_UNIXTIME(e_B.timestamp) AS save_B,
        e_B.round_id                 AS round_B
    FROM
        page_events AS e_A JOIN page_events AS e_B
        ON
            e_A.projectid=e_B.projectid
            AND e_A.image=e_B.image
    WHERE
        e_A.username='$username_A' AND e_A.event_type='saveAsDone'
        and
        e_B.username='$username_B' and e_B.event_type='saveAsDone'
");

// vim: sw=4 ts=4 expandtab
?>
