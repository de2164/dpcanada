<?PHP

// Use this when someone invokes send_again.php
// and specifies the wrong round-ids.
// (If they specified the wrong projectid, that's a bigger problem.)

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'connect.inc');
new dbConnect;

// Change the settings of variables in this section,
// then invoke from the command-line:
// php -f fix_send_again.php

$projectid = 'projectID4163149e12b9a';
$update_comments = FALSE; // This script doesn't handle comments yet.
$who = 'JulietS'; // Who invoked send_again.php with the wrong round-ids?
$incorrect_text_round_id = 'F2';
$incorrect_new_round_id = 'P1';
$correct_text_round_id = 'P3';
$correct_new_round_id = 'P1';

$dry_run = TRUE;

// ------------------------------------

assert_options(ASSERT_BAIL,1);
error_reporting(E_ALL);

$incorrect_text_round = get_Round_for_round_id($incorrect_text_round_id);
assert(!is_null($incorrect_text_round));

$correct_text_round = get_Round_for_round_id($correct_text_round_id);
assert(!is_null($correct_text_round));

$res = mysql_query("
    SHOW TABLES FROM project_snapshots LIKE '{$projectid}%'
") or die(mysql_error());

$n = mysql_num_rows($res);
echo "$n snapshots found.\n";
if ( $n == 0 ) exit;

$latest_snapshot = NULL;
while( list($table_name) = mysql_fetch_row($res) )
{
    echo "    $table_name\n";
    if ( is_null($latest_snapshot) or $table_name > $latest_snapshot )
    {
        // Using > works because all timestamps occur within the 10-digit era.
        $latest_snapshot = $table_name;
    }
}
assert( !is_null($latest_snapshot) );
list($p,$snapshot_timestamp) = explode('_', $latest_snapshot);
assert( $p == $projectid );

echo "\n";
echo "The latest snapshot is $latest_snapshot.\n";

$res = mysql_query("
    SELECT nameofwork, state, comments
    FROM projects
    WHERE projectid = '$projectid'
") or die(mysql_error());
list($nameofwork, $state, $comments) = mysql_fetch_row($res);

$expected_appended_tag = " \{{$incorrect_text_round_id}->{$incorrect_new_round_id}}";

$res = mysql_query("
    SELECT *
    FROM project_events
    WHERE projectid='$projectid'
    ORDER BY event_id DESC
    LIMIT 1
") or die(mysql_error());
$latest_event = mysql_fetch_object($res);

{
    echo "\n";
    echo "Checking that things are as we would expect them to be after the incorrect send_again.php...\n";

    // projects.nameofwork:
    echo "\n";
    echo "nameofwork: $nameofwork\n";
    echo "expected_appended_tag: '$expected_appended_tag'\n";
    assert( endswith($nameofwork,$expected_appended_tag) );

    // projects.state:
    echo "\n";
    echo "state: $state\n";
    assert( $state == "$incorrect_new_round_id.proj_unavail" );

    // projects.comments:
    if ( $update_comments )
    {
        echo "comments:\n";
        echo $comments;
        die("This script doesn't handle comments yet.\n");
    }

    // project_events:
    // var_dump($latest_event);
    assert( abs($latest_event->timestamp - $snapshot_timestamp) <= 1 );
    assert( $latest_event->projectid == $projectid );
    assert( $latest_event->who == $who );
    assert( $latest_event->event_type == 'transition' );
    // assert( $latest_event->details1 == 
    assert( $latest_event->details2 == "{$incorrect_new_round_id}.proj_unavail" );
    assert( $latest_event->details3 == 'Send project back through round(s)' ); 

    // pages-table:

    // texts:
    // Expect that "OCR" text in table '$projectid' matches
    // $incorrect_text_round_id text in latest_snapshot.
    $remaining_texts = get_page_texts( $projectid, 'master_text' );
    $snapshot_texts  = get_page_texts( "project_snapshots.$latest_snapshot",
        $incorrect_text_round->text_column_name );
    assert( $remaining_texts === $snapshot_texts );
    echo "\n";
    echo "page-texts as expected.\n";

    // states:
    $res = mysql_query("
        SELECT image
        FROM $projectid
        WHERE NOT(
            state = '$incorrect_new_round_id.page_avail'
            AND round1_text = ''
            AND round1_user = ''
            AND round1_time = 0
            AND round2_text = ''
            AND round2_user = ''
            AND round2_time = 0
            AND round3_text = ''
            AND round3_user = ''
            AND round3_time = 0
            AND round4_text = ''
            AND round4_user = ''
            AND round4_time = 0
            AND round5_text = ''
            AND round5_user = ''
            AND round5_time = 0
        )
    ") or die(mysql_error());
    $n = mysql_num_rows($res);
    if ( $n != 0 )
    {
        echo "$n pages with something unexpected:\n";
        while( list($image) = mysql_fetch_row($res) )
        {
            echo "    $image\n";
        }
        assert(FALSE);
    }
    echo "\n";
    echo "page-states as expected.\n";
}

// -----------------------------------------------------------------------------

{
    // Everything is as expected, so do the fix.
    echo "--------------\n";
    echo "Things are as expected, so doing the fix...\n";

    echo "\n";
    echo "pages-table:\n";
    $correct_texts = get_page_texts( "project_snapshots.$latest_snapshot",
        $correct_text_round->text_column_name );
    foreach( $correct_texts as $image => $text )
    {
        $escaped_text = mysql_escape_string($text);
        $query = "
            UPDATE $projectid
            SET
                state = '$correct_new_round_id.page_avail',
                master_text = '$escaped_text'
            WHERE image = '$image'
        ";
        // We could change the state on all pages in one query,
        // but doing it a page at a time means that
        // if there's an abort in the middle, it's easier to see how far we got.
        // (assuming that $incorrect_new_round_id != $correct_new_round_id)
        maybe_do_query($query);
    }

    echo "\n";
    echo "project_events:\n";
    $query = "
        UPDATE project_events
        SET details2 = '{$correct_new_round_id}.proj_unavail'
        WHERE event_id = {$latest_event->event_id}
    ";
    maybe_do_query($query);

    echo "\n";
    echo "projects:\n";
    $correct_nameofwork = 
        substr($nameofwork, 0, -strlen($expected_appended_tag) )
        . " \{{$correct_text_round_id}->{$correct_new_round_id}}";
    $correct_nameofwork_escaped = mysql_escape_string($correct_nameofwork);
    $correct_state = "$correct_new_round_id.proj_unavail";
    if ( $update_comments )
    {
        die("This script doesn't handle comments yet.\n");
    }
    $correct_comments_escaped = '';
    $query = "
        UPDATE projects
        SET
            nameofwork = '$correct_nameofwork_escaped',
            state      = '$correct_state'
            -- , comments = '$correct_comments_escaped'
        WHERE projectid = '$projectid'
    ";
    maybe_do_query($query);

    echo "\nDone!\n";
}

// -----------------------------------

function get_page_texts( $table_name, $column_name )
{
    $query = "
        SELECT image, $column_name
        FROM $table_name
        ORDER BY image
    ";
    $res = mysql_query($query) or die($query . "\n" . mysql_error() . "\n");
    $result = array();
    while( list($image, $text) = mysql_fetch_row($res) )
    {
        $result[$image] = $text;
    }
    return $result;
}

function maybe_do_query( $query )
{
    global $dry_run;
    if ( $dry_run )
    {
        echo "\n$query\n";
    }
    else
    {
        $res = mysql_query($query);
        if ( $res === FALSE )
        {
            echo "Query:\n";
            echo $query, "\n";
            echo "raised error:\n";
            echo mysql_error(), "\n";
            exit;
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
