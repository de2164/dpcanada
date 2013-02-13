<?PHP


$relPath= ( 0 ? 'pinc/' : '../c/pinc/' );
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'dpsql.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
{
    die("permission denied");
}

$qual_round = "P2";
$days = 30;
$query = "
            SELECT
               page_events.username, 
               SUM(page_events.event_type='saveAsDone') - SUM(page_events.event_type='clear' OR page_events.event_type='reopen')
                    AS n_saved
            FROM page_events, projects
            WHERE
                page_events.timestamp > UNIX_TIMESTAMP() - ($days * 24 * 60 * 60)
                AND page_events.round_id='P1'
                AND page_events.projectid = projects.projectid
                AND projects.difficulty = 'easy'
            GROUP BY username
            ORDER BY n_saved DESC
            LIMIT 100
        ";
$query1 = "
            SELECT
               page_events.username, 
               SUM(page_events.event_type='saveAsDone') - SUM(page_events.event_type='clear' OR page_events.event_type='reopen')
                    AS n_saved
            FROM page_events, usersettings
            WHERE
                page_events.timestamp > UNIX_TIMESTAMP() - ($days * 24 * 60 * 60)
                AND page_events.round_id='P1'
                AND page_events.username = usersettings.username
                AND usersettings.setting = '$qual_round.access'
                AND usersettings.value = 'yes'
            GROUP BY username
            ORDER BY n_saved DESC
            LIMIT 100
        ";

echo "<pre>";
echo $query;
echo "</pre>";


echo "<table border='1'>";
dpsql_dump_query($query);
echo "</table>";


// vim: sw=4 ts=4 expandtab
?>
