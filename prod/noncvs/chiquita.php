<?PHP
// Adding text to a project for a round that's been done offline.

//-------------------------------------------------
// INSTRUCTIONS
// Change the parameters as appropriate.
// run from the command line: php -f add_text.php

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

//--------------------------------------------
// Change these parameters as appropriate

$projectid = 'projectID45121111d6742';  // project that will continue through the rounds
$txt_files_dir = '/data/home/dpscans/ortonmc/chiquita/';  // where the text files are. 1 file per page.
$username_for_page_events = '[merge]';     // use something containing [] if you want a fake user
$username_for_project_events = 'donovan';    // who is running this script

$image_suffix = '.png'; // might be .jpg
$round = get_Round_for_round_id('P2');      // the round we want the loaded text to appear as the output of
$change_project_round = FALSE;              // set it to true if you want the project to move rounds
$next_round = get_Round_for_round_id('P3');  // the round we want project to end up in, unavailable. Only has any effect if $change_project_round is TRUE.
$prev_text_column_name = 'round1_text'; // $round->prevtext_column_name
$increment_page_count = FALSE;          // does the merger get their page count incremented?
$select_condition = "'1'";    // how to select the pages to be dealt with. Use '1' to do all pages

$wd_frac_limit = 0.70; // limit for differences .2 is a good initial value
$small_page_size = 500; // pages smaller than this can have large diffs

// end of parameters
//-------------------------------------------


$project = new Project($projectid);

echo "\n";
echo "project :\n";
echo "    {$project->projectid}\n";
echo "    {$project->nameofwork}\n";
echo "    by {$project->authorsname}\n";
echo "    state: {$project->state}\n";

if ( $project->state != $round->project_waiting_state &&
     $project->state != $round->project_unavailable_state )
{
    echo "Warning: project's state is neither {$round->project_waiting_state} nor {$round->project_unavailable_state}\n";
}

echo "\n";
$r = get_response('Is this the correct project? [yn] ', array('y', 'n') );
if ( $r == 'n' )
{
    echo "Okay, aborting\n";
    exit;
}

$txt_files = glob("$txt_files_dir/*.txt");
$n_pages = count($txt_files);

$n_overlimit_wd_fracs = 0;
echo "\n";
echo "looking at pages in project...\n";

assert( $project->n_pages >= $n_pages );

$res = mysql_query("
        SELECT image, state, $prev_text_column_name, {$round->user_column_name}
        FROM {$project->projectid}
        WHERE 1 AND $select_condition
        ORDER BY image
    ") or die(mysql_error());

// check we've got the right number of pages
$row_count = mysql_num_rows($res);
echo "  Replacing $row_count pages from $n_pages text files...\n";
assert( mysql_num_rows($res) == $n_pages );


for ( $i = 0; $i < $n_pages; $i++ )
{
    $txt_file_name = $txt_files[$i];
    list($image,$state,$prev_text,$curr_user) = mysql_fetch_row($res);

    assert( basename($txt_file_name,'.txt') == basename($image,$image_suffix) );
    if (  $state != $round->page_avail_state )
    {
        echo "    $image: will be reclaimed from $curr_user\n";
        $reclaims[] = $image;
    }

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

if ( $n_overlimit_wd_fracs > 0 )
{
    echo "\n";
    echo "Exiting due to $n_overlimit_wd_fracs questionable wd_frac values for pages large than {$small_page_size}b\n";
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


$projectid = $project->projectid;

foreach ($reclaims as $image)
{
    Page_reclaim( $projectid, $image, $round, $username_for_project_events );
}

for ( $i = 0; $i < $n_pages; $i++ )
{
    $txt_file_name = $txt_files[$i];
    $image = basename($txt_file_name,'.txt') . $image_suffix;

    Page_checkout(   $projectid, $image, $round, $username_for_page_events );
    Page_saveAsDone( $projectid, $image, $round, $username_for_page_events, addslashes(file_get_contents($txt_file_name)) );
    if ( $increment_page_count )
    {
        page_tallies_add( $round->id, $username_for_page_events, +1 );
    }
}

// Do project...

$projectid = $project->projectid;
$state = $project->state;

if ( $state == $round->project_unavailable_state )
{
    project_transition( $projectid, $round->project_waiting_state, $username_for_project_events );
}

// "release" the project
project_transition( $projectid, $round->project_available_state, $username_for_project_events, array('details'=>'[loading page-texts from off-line proofing]') );

if ($change_project_round)
{
    project_transition( $projectid, $round->project_complete_state, PT_AUTO );
    project_transition( $projectid, $next_round->project_waiting_state, PT_AUTO );
    project_transition( $projectid, $next_round->project_unavailable_state, $username_for_project_events );
}

// vim: sw=4 ts=4 expandtab
?>
