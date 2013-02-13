<?PHP

$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'dpsql.inc');
include_once('page_events.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
{
    die("permission denied");
}

$username = @$_GET['username'];
$round_id = @$_GET['round_id'];

if ( empty($username) || empty($round_id) )
{
    echo "<form method='get'>\n";
    echo "<pre>";
    echo "username: <input name='username' type='text' size='26' value='$username'>\n";
    echo "round_id: <input name='round_id' type='text' size='2' value='$round_id'>\n";
    echo "<input type='submit'>\n";
    echo "</pre>";
    echo "</form>";
    exit;
}

echo "<h2>$username in $round_id</h2>";

/*
List all the projects proofread in the given round by the given user
in the last 1 - 3 - 7 - 14 days, with number of pages proofread.
*/

foreach ( array(1,3,7,14) as $num_days )
{
    echo "<h3>All projects proofed in $round_id in the last $num_days days</h3>";

    $timestamp = time() - ($num_days * 24 * 60 * 60);
    $event_id = get_least_event_id_with_timestamp( $timestamp );

    dpsql_dump_query("
        SELECT
            SUM(event_type='saveAsDone') - SUM(event_type='clear' OR event_type='reopen')
                AS n_saved,
            CONCAT(
                REPLACE(
                    '<a href=\"$code_url/tools/project_manager/page_detail.php?project=PROJECTID&amp;select_by_user=$username\">',
                    'PROJECTID',
                    page_events.projectid
                ),
                nameofwork,
                '</a>'
            ) AS project
        FROM page_events JOIN projects USING (projectid)
        WHERE
            event_id >= $event_id
            AND round_id='$round_id'
            AND page_events.username='$username'
        GROUP BY page_events.projectid
        ORDER BY n_saved DESC
    ");
}

/*
A kind of project page, listing all the pages done in any project by an
user in a given round in the last n days could be very useful too - and
might be very popular if made available for everybody for his own pages,
as a kind of auto-feedback. But might require too many resources.
*/


// vim: sw=4 ts=4 expandtab
?>
