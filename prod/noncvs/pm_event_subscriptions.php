<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_project_info.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager()) die("permission denied");

echo "<pre>\n";
if ( empty($_POST) )
{
    echo "<form method='post'>\n";
    echo "PM username: ";
    echo "<input type='text' name='username'>\n";
    echo "\n";
    echo "Subscribe that user to the following events for each of their projects:\n";
    echo "<table>\n";
    foreach ( $subscribable_project_events as $id => $label )
    {
        echo "<tr>";
        echo "<td>$label</td>";
        echo "<td><input type='checkbox' name='events_to_subscribe_to[$id]'></td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "(Unlike the project page, leaving an event unselected will not cause unsubscription.)\n";
    echo "<input type='submit' name='action' value='Check'>\n";
    echo "</form>\n";
}
elseif ( $_POST['action'] == 'Check' )
{
    // var_dump($_POST);
    $username = @$_POST['username'];
    $res = mysql_query("
        SELECT projectid
        FROM projects
        WHERE username='$username'
            AND state != 'proj_submit_pgposted'
            AND state != 'project_delete'
    ") or die(mysql_error());
    $n_projects = mysql_num_rows($res);
    echo "$username is PM of $n_projects active projects\n";
    if ( $n_projects == 0 ) exit;

    $events_to_subscribe_to = array_keys( @$_POST['events_to_subscribe_to'] );
    if ( count($events_to_subscribe_to) == 0 )
    {
        echo "You did not select any events to subscribe to.\n";
        exit;
    }

    $s = implode( ', ', $events_to_subscribe_to );
    echo "Subscribing $username to ($s) for each of those projects...\n";
    echo "\n";

    if (1)
    {
        // using the API
        while ( list($projectid) = mysql_fetch_row($res) )
        {
            foreach ( $events_to_subscribe_to as $event )
            {
                echo "subscribe_user_to_project_event( $username, $projectid, $event );\n";
                subscribe_user_to_project_event( $username, $projectid, $event );
            }
        }
    }
    else
    {
        // all at once
        $sql = "
            INSERT IGNORE INTO user_project_info
            SELECT
                username,
                projectid,
                0 AS iste_round_available,
                etc
            FROM projects
            WHERE username='$username'
                AND state != 'proj_submit_pgposted'
                AND state != 'project_delete'
        ";
        echo "$sql\n";
        $sets = surround_and_join( $events_to_subscribe_to, 'iste_', '=1', ',');
        list($projectids) = dpsql_fetch_columns($res);
        $projectids = surround_and_join( $projectids, "'", "'", "," );
        $sql = "
            UPDATE user_project_info
            SET $sets
            WHERE username='$username'
                AND projectid IN ($projectids)
        ";
        echo $sql;

    }
}
else
{
    echo "bad action: '{$_POST['action']}'\n";
}
echo "</pre>";

// vim: sw=4 ts=4 expandtab
?>
