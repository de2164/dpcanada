<?PHP
// Replacing text for a project for a round that's already been done
  // the project must be unavailable, and all pages have been saved in
  // the round whose text is being replaced
  // (ie, usually the project is unavailable in the next round, with no
  // pages proofed yet)

//-------------------------------------------------
// INSTRUCTIONS
// Change the parameters as appropriate.
// run from the command line: php -f replace_text.php

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

$projectid = 'projectID415c01c0e1eab';  // project id
$txt_files_dir = '/data/home/dpscans/acunning40/journalp1/';  // where the text files are. 1 file per page.
$image_suffix = '.png'; // might be .jpg
$round = get_Round_for_round_id('P1');      // the round we want the loaded text to appear as the output of
$current_round = get_Round_for_round_id('P2');  // the round the project is in
$select_condition = "1";    // how to select the pages to be dealt with. Use '1' to do all pages
$wd_frac_limit = 0.35; // limit for differences .2 is a good initial value
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

if ( $project->state != $current_round->project_waiting_state &&
     $project->state != $current_round->project_unavailable_state )
{
    echo "Warning: project's state is neither {$current_round->project_waiting_state} nor {$current_round->project_unavailable_state}\n";
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
        SELECT image, state, {$round->text_column_name}, {$round->user_column_name}
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
    assert( ($state == $round->page_save_state) || 
            ($state == $current_round->page_avail_state) );

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

$projectid = $project->projectid;

for ( $i = 0; $i < $n_pages; $i++ )
{
    $txt_file_name = $txt_files[$i];
    $image = basename($txt_file_name,'.txt') . $image_suffix;
    $text_file_content_expr = file_content_expr( $txt_file_name );
    _Page_UPDATE( $projectid, $image, 
                  "{$round->text_column_name}=$text_file_content_expr");
    _log_page_event( $projectid, $image, 'replaceText', $pguser, $round, FALSE );
    echo "Done $image\n";
}


// vim: sw=4 ts=4 expandtab
?>
