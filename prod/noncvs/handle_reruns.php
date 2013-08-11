<?PHP
// A "rerun" is a project that is put through P1 twice,
// both times starting with the OCR text.
// Then garweyne merges the two P1 results,
// and we put the merged page-texts in as the P2 result.

//-------------------------------------------------
// INSTRUCTIONS
// Change the parameters as appropriate.
// run from the command line: php -f handle_reruns.php

$relPath='../c/pinc/';
if ( php_sapi_name() == 'cli' )
{
    include_once('./cli.inc');
}
else
{
    include_once($relPath.'dp_main.inc');
    if (!user_is_a_sitemanager()) die("permission denied");
    die("you must run this script from the command-line");
}
include_once($relPath.'Project.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'project_trans.inc');
include_once('./showdiff.inc');

error_reporting(E_ALL);
assert_options(ASSERT_BAIL,1);

define('A', 1);
define('B', 2);
define('C', 3);
//--------------------------------------------
// Change these parameters as appropriate

$AB = array( A, B );  // either A, B or A, B, C

$projectid_[A] = 'projectID45c7f3c7d1ee2';  // project that will continue through the rounds
$projectid_[B] = 'projectID45c6b64621a21';  // project that will eventually be deleted
// $projectid_[C] = 'projectID45abcf807a7a6';  // project that will eventually be deleted
$txt_files_dir = '/home/dpscans/ortonmc/crown';  // where the text files are. 1 file per page.
$username_for_page_events = '[merge]';     // contains [] so that it isn't a possible username
$username_for_project_events = 'lpryor';    // who is running this script

$image_suffix = '.png'; // might be .jpg
$round = get_Round_for_round_id('P2');      // the round we want the merged text to appear as the output of
$next_round = get_Round_for_round_id('P3');  // the round we want project A to end up in, unavailable.
$prev_text_column_name = 'round1_text'; // $round->prevtext_column_name
$increment_page_count = FALSE;          // does the merger get their page count incremented?

$wd_frac_limit = 0.3; // limit for differences
$small_page_size = 800; // pages smaller than this can have large diffs

// end of parameters
//-------------------------------------------

foreach ( $AB as $s )
{
    $project_[$s] = new Project($projectid_[$s]);

    $blurb = (
        $s == A
        ? "the one that will advance"
        : "the one that will not advance"
    );

    echo "\n";
    echo "project #$s ($blurb):\n";
    echo "    {$project_[$s]->projectid}\n";
    echo "    {$project_[$s]->nameofwork}\n";
    echo "    by {$project_[$s]->authorsname}\n";
    echo "    state: {$project_[$s]->state}\n";

    if ( $project_[$s]->state != $round->project_waiting_state &&
         $project_[$s]->state != $round->project_unavailable_state )
    {
        echo "Warning: project $s's state is neither {$round->project_waiting_state} nor {$round->project_unavailable_state}\n";
    }
}

foreach ( $AB as $s )
{
    $contains = str_contains($project_[$s]->nameofwork, '{RERUN');
    // omit the closing brace for eg {RERUN2} if there are 3 projects
    if ($s == A) {
        if ($contains) 
        {
            echo "Warning: project #1's title contains '{RERUN'\n";
        }
    }
    else
    {
        if (! $contains) {
            echo "Warning: project #$s's title does not contain '{RERUN'\n";
        }
        if ( $project_[A]->authorsname != $project_[$s]->authorsname )
        {
            echo "Warning: project authors differ\n";
        }
    }
}

echo "\n";
$r = get_response('Are these the correct projects? [yn] ', array('y', 'n') );
if ( $r == 'n' )
{
    echo "Okay, aborting\n";
    exit;
}

$txt_files = glob("$txt_files_dir/*.txt");
$n_pages = count($txt_files);

$n_overlimit_wd_fracs = 0;

foreach ( $AB as $s )
{
    echo "\n";
    echo "looking at pages in project #$s...\n";

    assert( $project_[$s]->n_pages == $n_pages );

    $res = mysql_query("
        SELECT image, state, $prev_text_column_name
        FROM {$project_[$s]->projectid}
        ORDER BY image
    ") or die(mysql_error());
    assert( mysql_num_rows($res) == $n_pages );

    for ( $i = 0; $i < $n_pages; $i++ )
    {
        $txt_file_name = $txt_files[$i];
        list($image,$state,$prev_text) = mysql_fetch_row($res);

        assert( basename($txt_file_name,'.txt') == basename($image,$image_suffix) );
        assert( $state == $round->page_avail_state );

        $new_text = file_get_contents($txt_file_name);
        $wd = wdiff($prev_text,$new_text);
        $page_len = strlen($new_text);
        $wd_frac = strlen($wd) / (strlen($prev_text)+ $page_len);
        if ( $wd_frac > $wd_frac_limit)
        {
            echo "    $image: wd_frac=$wd_frac   {$page_len}b\n";
            if ( $page_len > $small_page_size) 
            {
                $n_overlimit_wd_fracs += 1;
            }
        }
    }
}

if ( $n_overlimit_wd_fracs > 0 )
{
    echo "\n";
    echo "Exiting due to $n_overlimit_wd_fracs questionable wd_frac values for pages larger than {$small_page_size}b\n";
    echo "(wd_frac_limit is $wd_frac_limit; perhaps this is too low.)\n";
    exit;
}

echo "\n";
echo "Okay, the .txt files in $txt_files_dir look reasonable.\n";
$r = get_response('Proceed with actual changes? [yn] ', array('y','n') );
if ( $r == 'n' )
{
    echo "Okay, aborting.\n";
    exit;
}

// Do pages...

echo "
  Doing pages
             ";

foreach ( $AB as $s )
{
    $projectid = $project_[$s]->projectid;
    for ( $i = 0; $i < $n_pages; $i++ )
    {
        $txt_file_name = $txt_files[$i];
        $image = basename($txt_file_name,'.txt') . $image_suffix;
        Page_checkout(   $projectid, $image, $round, $username_for_page_events );
        Page_saveAsDone( $projectid, $image, $round, $username_for_page_events, addslashes(file_get_contents($txt_file_name)) );
        if ( $increment_page_count && $s == A ) // so we only count once per page
        {
            page_tallies_add( $round->id, $username_for_page_events, +1 );
        }
    }
}

// Do projects...

echo "
  Doing projects
             ";

foreach ( $AB as $s )
{
    $projectid = $project_[$s]->projectid;
    $state = $project_[$s]->state;

    if ( $state == $round->project_unavailable_state )
    {
        project_transition( $projectid, $round->project_waiting_state, $username_for_project_events );
    }

    // "release" the project
    project_transition( $projectid, $round->project_available_state, $username_for_project_events, array('details'=>'[loading merged page-texts]') );

    if ( $s == A )
    {
        project_transition( $projectid, $round->project_complete_state, PT_AUTO );
        project_transition( $projectid, $next_round->project_waiting_state, PT_AUTO );
        project_transition( $projectid, $next_round->project_unavailable_state, $username_for_project_events );
    }
    else
    {
        project_transition( $projectid, $round->project_unavailable_state, $username_for_project_events );
    }

    
}

// Do event notifications

echo "
  Transferring event notifications
             ";
$count = 0;

foreach ( $AB as $s )
{

    $projectid = $project_[$s]->projectid;
    if ( $s == A )
    {
        // don't transfer these notifications, but the others
        // will come here
        $toprojectid = $projectid;
    }
    else
    {

        // for each subscribable event
        //   for each user subscribed to "from" project
        //      subscribe user to "to" project
        global $subscribable_project_events;
        foreach ( $subscribable_project_events as $event => $label )
        {
            $query = "
                      SELECT username FROM user_project_info
                      WHERE projectid = '$projectid' AND
                            iste_$event = 1";
            $res1 = mysql_query($query) or die(mysql_error());
            while ( list($username) = mysql_fetch_row($res1) )
            {
                set_user_project_event_subscription( $username, 
                                                     $toprojectid, 
                                                     $event, 1 );
                $count++;
            }
        }

    }
    
}

echo "
                  $count notifications transferred
             ";


// vim: sw=4 ts=4 expandtab
?>
