<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

header('content-type: text/plain');

if (!user_is_a_sitemanager()) die("permission denied");

// check page_events table for evidence of p2alt-recycling

foreach ( array(
    'projectID4441a5063c462',
    'projectID43d51802bec83',
    'projectID444cff5842f25',
    'projectID42c228559b030',
    'projectID42e3fc6632b0d',
    'projectID4317d216479eb',
    'projectID44199ed09d228',
    'projectID442cd07eb867e',
    'projectID44831c8779794',

    'projectID445f45e96d04f',
    'projectID4462affc5244c',
    'projectID442d87a41be5f',
    'projectID4431f41bb2ec0',
    'projectID4443b28b5bbaf',
    'projectID4457b09ec9781',

    )
    as
    $projectid
)
{
    echo "\n";
    echo $projectid, "\n";
    $res = mysql_query("
        SELECT *, FROM_UNIXTIME(timestamp) AS ts
        FROM page_events
        WHERE projectid='$projectid' AND round_id='P1'
        ORDER BY event_id
    ") or die(mysql_error());
    // dpsql_dump_query_result($res);

    $something = array();
    while ( $row = mysql_fetch_assoc($res) )
    {
        $image = $row['image'];
        $something[$image] = 1;
        if (count($something) == 4) break;
    }
    mysql_data_seek($res,0);

    foreach ( $something as $image => $_ )
    {
        echo "    $image\n";
        while ( $row = mysql_fetch_assoc($res) )
        {
            if ( $row['image'] == $image )
            {
                echo "        {$row['ts']} {$row['event_type']} {$row['username']}\n";
            }
        }
        mysql_data_seek($res,0);
    }
}

// vim: sw=4 ts=4 expandtab
?>
