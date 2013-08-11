<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'site_vars.php');

if (!user_is_a_sitemanager() && 
    $pguser != 'PM QC' &&
    $pguser != 'stygiana') die("You are not allowed to run this script");

error_reporting(E_ALL);

// first, get a list of projects we are interested in. 
// - finished the specified round between the specified dates
// - titles contain (or don't contain) specified tags
// always ignore projects with 'missing pages' in the title
// always ignore projects with 'Quality control' in the title

echo "<pre>";

echo "<h2>Select pages for a quality control project</h2>";
echo "\n";

// these are the potential tags to appear (or not) in the 
// project titles. Add more by adding them to the array.
$title_tags_ = array ( "retread_in_title" => '{R}',
                       "rerun_in_title" => '{RERUN}',
                       "p1p1_in_title" => '{P1->P1}',
                       "p2alt_in_title" => '{P2alt-r}',
                       "nop2_in_title" => '{No P2}',
                       "p2skipped_in_title" => '{P2 skipped}',
                       "p3skipped_in_title" => '{P3 skipped}',
                       "p3qual_in_title" => '{P3 Qual}',
                       "f1skipped_in_title" => '{F1 skipped}',
                       "f2skipped_in_title" => '{F2 skipped}',
                       "latex_in_title" => '{LaTeX}',
                       "fraktur_in_title" => '{fraktur}'
                       );
// potential language components. Add more by adding them to the array.
$language_parts_ = array("english_in_lang" => 'English',
                         "with_in_lang" => ' with ',
                         "french_in_lang" => 'French',
                         "german_in_lang" => 'German',
                         "italian_in_lang" => 'Italian',
                         "portuguese_in_lang" => 'Portuguese',
                         "spanish_in_lang" => 'Spanish',
                         "latin_in_lang" => 'Latin',
                         "greek_in_lang" => 'Greek');

$round_ids_ = array("P1", "P2", "P3", "F1", "F2");
$difficulties = array("any" => "any difficulty",
                      "easy_or_ave" => "easy or average",
                      "easy" => "easy",
                      "average" => "average",
                      "hard" => "hard");

// see what we have...
$submit_button  = array_get( $_POST, 'submit_button', '' );
$days_lo = array_get( $_POST, 'days_lo',  '0' );
$days_hi = array_get( $_POST, 'days_hi',  '30' );
$round_done = array_get( $_POST, 'round_done',  'P2' );
$round_text = array_get( $_POST, 'round_text',  'P2' );
$total_pages = array_get( $_POST, 'total_pages',  NULL );
$pages_per = array_get( $_POST, 'pages_per', '150'  );
$identifier = array_get( $_POST, 'identifier',  'QC' );
$difficulty = array_get( $_POST, 'difficulty',  'average' );

foreach ($title_tags_ as $key => $value)
{
    $tag_requests_[$key] =  array_get( $_POST, $key,  "maybe" );
}
foreach ($language_parts_ as $key => $value)
{
    $lang_requests_[$key] =  array_get( $_POST, $key,  "maybe" );
}

switch ( $submit_button )
{
    case '':
        // we are not here as a result of submitting the form. Display it.
        display_form($days_lo, $days_hi,  $round_done, $round_text,
                     $total_pages, $pages_per, $identifier, $difficulty,
                     $tag_requests_, $lang_requests_);
        break;

    case 'Get projects!':
        // get the projects
        get_projects($days_lo, $days_hi, 
                     $round_done, $round_text, 
                     $total_pages, $pages_per,  $identifier,  $difficulty,
                     $tag_requests_, $lang_requests_, TRUE);
       break;

    case 'Get pages!':
        // get the pages
        get_pages($days_lo, $days_hi, 
                  $round_done, $round_text, 
                  $total_pages, $pages_per,  $identifier,  $difficulty,
                  $tag_requests_, $lang_requests_);
       break;

    default:
        // we're not meant to get here. What on earth is going on?
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

function display_form($days_lo, $days_hi, $round_done, $round_text,
                      $total_pages, $pages_per,  $identifier, $difficulty, 
                      $tag_requests_, $lang_requests_)
{
    global $title_tags_, $language_parts_, $round_ids_, $difficulties; 
    echo "<form method='post'>";
    echo "\n\n   Get projects that finished    <select name='round_done'>";
    foreach ($round_ids_ as $rid)
    {
        $sel = "";
        if ($round_done == $rid) {
            $sel = " selected='selected'";
        }
        echo "\n                  <option value='$rid' $sel>$rid</option>";
    }
    echo "\n      </select>  ";
    echo "between <input type='text' name='days_lo' value='$days_lo'> and  <input type='text' name='days_hi' value='$days_hi'> days ago";
    echo "\n\n   Include or exclude those with the following tags in their titles:";
    echo "\n   <table>";
    echo "\n   <tr><td>&nbsp;</td><td>include&nbsp;&nbsp;</td><td>exclude&nbsp;&nbsp;</td><td>don't care&nbsp;&nbsp;</td></tr>";
    foreach ($title_tags_ as $key => $value)
    {
        $seliem = array("include" => "",
                        "exclude" => "",
                        "maybe" => "");
        $seliem[$tag_requests_[$key]] = " checked='checked'";
        echo "\n   <tr><td>&nbsp;&nbsp;&nbsp;   $value&nbsp;&nbsp; </td> ";
        echo "\n      <td>&nbsp;<input type='radio' name='$key' value='include' {$seliem['include']} >   </td>";
        echo "\n      <td>&nbsp;<input type='radio' name='$key' value='exclude'  {$seliem['exclude']}>   </td>";
        echo "\n      <td>&nbsp;<input type='radio' name='$key' value='maybe'  {$seliem['maybe']} >   </td>\n</tr>";
    }
    echo "\n   </table>";

    echo "\n\n   Include or exclude those with the following in their language designation:";
    echo "\n   <table>";
    echo "\n   <tr><td>&nbsp;</td><td>include&nbsp;&nbsp;</td><td>exclude&nbsp;&nbsp;</td><td>don't care&nbsp;&nbsp;</td></tr>";
    foreach ($language_parts_ as $key => $value)
    {
        echo "\n<tr><td>&nbsp;&nbsp;&nbsp;   $value&nbsp;&nbsp; </td> ";
        $seliem = array("include" => "",
                        "exclude" => "",
                        "maybe" => "");
        $seliem[$lang_requests_[$key]] = " checked='checked'";
        echo "\n<td>&nbsp;<input type='radio' name='$key' value='include' {$seliem['include']} >   </td>";
        echo "\n<td>&nbsp;<input type='radio' name='$key' value='exclude'  {$seliem['exclude']}>   </td>";
        echo "\n<td>&nbsp;<input type='radio' name='$key' value='maybe'  {$seliem['maybe']} >   </td>\n</tr>";
    }
    echo "\n</table>";
    echo "\n    Hint: including eg 'English' and excluding ' with ' will select projects whose language is English only";

    echo "\n\n   Get projects that are   <select name='difficulty'>";
    foreach ($difficulties as $key => $value)
    {
        $sel = "";
        if ($difficulty == $value) {
            $sel = " selected='selected'";
        }
        echo "\n               <option value='$key' $sel>$value</option>";

    }
    echo "\n   </select>  ";
    echo "\n\n   <input type='submit' name='submit_button' value='Get projects!'>";
    echo "</form>";
}

function get_projects($days_lo, $days_hi, $round_done, $round_text,
                      $total_pages, $pages_per, $identifier,  $difficulty,
                      $tag_requests_, $lang_requests_, $first_time)
{
    global $title_tags_, $language_parts_, $round_ids_, $difficulties; 

    $query = "SELECT projects.projectid, projects.nameofwork, projects.language, projects.difficulty, projects.n_pages, project_events.timestamp
              FROM projects, project_events
              WHERE projects.projectid = project_events.projectid AND 
                    NOT (projects.nameofwork LIKE '%missing pages%') AND
                    NOT (projects.nameofwork LIKE '%Quality control%') AND
                    projects.n_pages > 0 AND
                    NOT (projects.state = 'project_delete') AND";
    
    $sel_tags = get_selector_string($title_tags_, $tag_requests_, 'nameofwork');
    $query .= $sel_tags;

    $sel_tags = get_selector_string($language_parts_, $lang_requests_, 'language');
    $query .= $sel_tags;
    if ($difficulty == 'easy_or_ave')
    {
        $query .= "
                    (projects.difficulty = 'easy' OR projects.difficulty = 'average')  AND";
    }
    else if ($difficulty != 'any')
    {
        $query .= "
                    projects.difficulty = '$difficulty' AND";
    }
    $time_early = time() - ($days_hi * 60 * 60 * 24); 
    $time_late = time() - ($days_lo * 60 * 60 * 24); 
    $query .= " 
                    project_events.event_type = 'transition' AND
                    project_events.details2 = '$round_done.proj_done' AND
                    project_events.timestamp > $time_early AND
                    project_events.timestamp < $time_late
              "; 

    echo "\n\n running query: \n $query"; 
    $res= mysql_query($query) or die(mysql_error());

    if ($first_time) 
    {
        $rows = mysql_num_rows($res);
        echo "\n\n{$rows} projects were found:\n</pre><table>"; 
        $total_pages = 0;
        while ( list($projectid, $pname, $plang, $pdiff, $pages, $days) = mysql_fetch_row($res) )
        {
            $days = round((time() -$days)/(60 * 60 * 24));
            $total_pages += $pages;
            echo "\n<tr><td>$projectid</td><td width='30%'>$pname</td><td>$plang</td><td>$pdiff</td>
              <td style='text-align: right;'>$pages pages</td>
              <td style='text-align: right;'>$days days</td></tr>";
        }

        echo "\n</table>\n<pre>";
        echo "\nThere are a total of <b>$total_pages</b> pages in the selected projects.";

        echo "\n\nChange your criteria ...";
        display_form($days_lo, $days_hi,  $round_done, $round_text,
                     $total_pages, $pages_per, $identifier, $difficulty,
                     $tag_requests_, $lang_requests_);
        echo "\n\n... or continue";
        echo "<form method='post'>";
        echo "<input type='hidden' name='total_pages' value='$total_pages'>";
        echo "\n   Pages in the quality control project:  <input type='text' name='pages_per' value='150'> pages";
        echo "\n   Use text from round   <select name='round_text'>";
        foreach ($round_ids_ as $rid)
        {
            $sel = "";
            if ($round_text == $rid) {
                $sel = " selected='selected'";
            }
            echo "\n               <option value='$rid' $sel>$rid</option>";
        }
        echo "\n   </select>  ";

        echo "\n   Identifier for this project: <input type='text' name='identifier' value='$identifier'>";
        echo "<input type='hidden' name='round_done' value='$round_done'>";
        echo "<input type='hidden' name='days_lo' value='$days_lo'>";
        echo "<input type='hidden' name='days_hi' value='$days_hi'>";
        echo "<input type='hidden' name='difficulty' value='$difficulty'>";
        foreach ($tag_requests_ as $key => $value)
        {
            echo "<input type='hidden' name='$key' value='$value'>";
        }
        foreach ($lang_requests_ as $key => $value)
        {
            echo "<input type='hidden' name='$key' value='$value'>";
        }

        echo "\n\n   <input type='submit' name='submit_button' value='Get pages!'>";
        echo "</form>";
    }
    else     // want to return the list of projectids
    {
        while ( list($projectid, $pname, $plang, $pdiff, $pages, $days) = mysql_fetch_row($res) )
        {
            $projectids[] = $projectid;
        }
        return $projectids;
    }
} 

function get_selector_string($tags, $requests, $col_name)
{
    $sel_tags = "";
    foreach ($tags as $key => $value)
    {
        if ("include" == $requests[$key])
        {
            $sel_tags .= "
                    projects.{$col_name} LIKE '%{$value}%' AND ";
        }
        else if  ("exclude" == $requests[$key])
        {
            $sel_tags .= "
                    NOT (projects.{$col_name} LIKE '%{$value}%') AND ";
        }
    }
    return $sel_tags;
}

// actually get the pages and put them somewhere that we can load 
// them into the new projects
function get_pages($days_lo, $days_hi,  $round_done, $round_text,
                   $total_pages, $pages_per, $identifier, $difficulty, 
                   $tag_requests_, $lang_requests_)
{
    global $uploads_dir, $projects_dir, $pguser;
    global $title_tags_, $language_parts_, $round_ids_, $difficulties; 

    $projectids = get_projects($days_lo, $days_hi, 
                               $round_done, $round_text,
                               $total_pages, $pages_per, $identifier,  $difficulty,
                               $tag_requests_, $lang_requests_, FALSE);

    $frequency = (int) round($total_pages/$pages_per);
    echo "\n\nUsing text from round $round_text";
    echo "\n$pages_per pages in the project from $total_pages total pages";
    echo "\n    (sampling 1 in $frequency pages)";
    echo "\nDirectories will use identifier '$identifier'";
    // for each projectid,
    //   for each page in the project,
    //      random sample: in or out?
    //      if in,
    //         if 1st page of qc project,
    //            create directory on dpscans
    //            start numbering at one
    //            close off last info file if nec
    //            create info file
    //         copy png to dpscans, renaming it
    //         write round text to dpscans as text file
    //         add info to info file
    //   next page
    // next project

    $round = get_Round_for_round_id($round_text);
    $col = $round->text_column_name;
    $running_count = 0;
    $dir_prefix =  date("Ymd") . "_{$identifier}";
    $info_handle = NULL;
    foreach ($projectids as $projectid) 
    {
        // find the pages
        $query = "SELECT fileid, image, $col FROM $projectid"; 
        echo "\n\n running query: \n $query";
        $res = mysql_query($query) or die(mysql_error());
        $rows = mysql_num_rows($res);
        echo "\n$rows rows found";
        while ( list($fileid, $image, $text) = mysql_fetch_row($res) )  
        {
            if ( rand(1, $frequency) == 1)
            {
                // 1 in $frequency chance of getting here
                if (0 == $running_count) 
                {
                    // start a new qc project
                    $dir_name = "$uploads_dir/QCprojects/{$pguser}_{$dir_prefix}";
                    // if the directory already exists, complain
                    echo "\n\nCreating directory $dir_name";
                    if (file_exists($dir_name))
                    {
                        die("\n$dir_name already exists");
                    }
                    if (! mkdir($dir_name, 0777))
                    {
                        die("\nCouldn't create $dir_name");
                    }
                    chmod($dir_name, 0777);
                    $info_handle = fopen("$dir_name/project.info", "wb");
                    fwrite($info_handle, "Directory: {$dir_prefix}\n"); // this gives user and date information
                    fwrite($info_handle, "days_lo: $days_lo\n");  
                    fwrite($info_handle, "days_hi: $days_hi\n");  
                    fwrite($info_handle, "round_done: $round_done\n");
                    fwrite($info_handle, "difficulty: $difficulty\n");
                    foreach($tag_requests_ as $key => $value)
                    {
                        fwrite($info_handle, "$key: $value\n");  
                    } 
                    foreach($lang_requests_ as $key => $value)
                    {
                        fwrite($info_handle, "$key: $value\n");  
                    } 
                    fwrite($info_handle, "round_text: $round_text\n");  
                    fwrite($info_handle, "total_pages: $total_pages\n");  
                    fwrite($info_handle, "pages_per: $pages_per\n");  
                }
                $running_count ++;
                // deal with the image file
                // want it to keep the same extension as it currently has
                $pos = strrpos($image, "."); // last . in image file name
                $ext = substr($image, $pos); // extension
                $from = "{$projects_dir}/{$projectid}/{$image}";
                $new_file = sprintf("%03d", $running_count);
                $to = "{$dir_name}/{$new_file}{$ext}";
                echo "\n      Copying {$projectid}/{$image} to {$new_file}{$ext}";
                if (!copy($from, $to))
                {
                    die("\nCouldn't copy $from to $to");
                }
                chmod($to, 0777);
                // deal with the text file
                $to = "{$dir_name}/{$new_file}.txt";
                if (file_put_contents($to, $text) === FALSE)
                {
                    die("\nCouldn't write $to");
                }
                chmod($to, 0777);
                fwrite($info_handle, "{$new_file}, {$projectid}, {$image}\n");
            }
        } 
    }
    // close the old file
    fclose($info_handle);
    $total_selected =  $running_count;
    echo "\n\n{$total_selected} pages selected out of {$total_pages} total pages.";
    $pct = $total_selected / $total_pages;
    echo "\n   (Target number of pages was $pages_per)";
    echo sprintf("\n   (%01.2f%% compared to the target rate of %01.2f%%)", 100*$pct, 100/$frequency);
}

// vim: sw=4 ts=4 expandtab
?> 
