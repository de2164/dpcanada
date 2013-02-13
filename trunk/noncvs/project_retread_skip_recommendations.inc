<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'../tools/project_manager/post_files.inc');

function show_waw_comparison($project_waw)
{
    // scale provided by piggy
    $waw_scale=array();
    $waw_scale['0.0007500']=_('P3 skip recommendation');
    $waw_scale['0.0011478']=_('P3 first quartile');
    $waw_scale['0.0024176']=_('P3 median');
    $waw_scale['0.0026143']=_('P2 first quartile');
    $waw_scale['0.0056472']=_('P3 mean');
    $waw_scale['0.0059510']=_('P2 median');
    $waw_scale['0.0061945']=_('P3 third quartile');
    $waw_scale['0.0109650']=_('P2 mean');
    $waw_scale['0.0123700']=_('P2 third quartile');
    $waw_scale['0.0181210']=_('P1 first quartile');
    $waw_scale['0.0384810']=_('P1 median');
    $waw_scale['0.0710050']=_('P1 mean');
    $waw_scale['0.0740030']=_('P1 third quartile');
    $waw_scale['0.1000000']=_('P1->P1 recommendation');

    echo "<h2>" . _("Comparison with other projects") . "</h2>";
    echo "<p> " . _("Here is how this project compares to other project wa/w values.") . "</p>";
    echo "<table>";
    echo "<tr><th>" . _("wa/w") . "</th><th>" . _("comparison") . "</th></tr>";
    $last_value=0;
    foreach($waw_scale as $value => $string)
    {
        if($project_waw >= $last_value && $project_waw < $value)
            echo "<tr><td><b>$project_waw</b></td><td><b>" . _("This project") . "</b></td></tr>";
        echo "<tr><td>$value</td><td>$string</td></tr>";
        $last_value = $value;
    }
    if($project_waw > $last_value)
        echo "<tr><td><b>$project_waw</b></td><td><b>" . _("This project") . "</b></td></tr>";
    echo "</table>";
}

// return wa/w value for a project given two rounds
// if the wa/w value is undefined (because $words are 0)
// the function returns NULL
function calculate_waw_for_project($projectid, $source_round_id, $compare_round_id)
{
    list($words, $alterations) = get_wdiff_alterations($projectid, $source_round_id, $compare_round_id);
    if($words > 0)
        return ($alterations / $words);
    else
        return NULL;
}

// calculate the number of words and wa/w alterations
// for a project given two rounds
function get_wdiff_alterations($projectid, $source_round_id, $compare_round_id)
{
    global $aspell_temp_dir;

    $source_filename = "$aspell_temp_dir/${projectid}_source.txt";
    $compare_filename = "$aspell_temp_dir/${projectid}_compare.txt";

    $messages = array();

    // OCR is a special round
    if($source_round_id == 'OCR')
        $source_round_id = '[OCR]';

    // get the source round text
    $pages_res = page_info_query($projectid,$source_round_id,'LE');
    $all_page_text = get_page_texts( $pages_res );
    // remove any formatting tags and add a final \r\n to each page-text
    // to ensure that there is whitespace between pages so they don't run together
    $all_page_text = preg_replace(array('#<[/]?\w+>#','#$#'),array('',"\r\n"),$all_page_text);
    file_put_contents($source_filename, $all_page_text);
    unset($all_page_text);

    // get the compare round text
    $pages_res = page_info_query($projectid,$compare_round_id,'LE');
    $all_page_text = get_page_texts( $pages_res );
    // remove any formatting tags and add a final \r\n to each page-text
    // to ensure that there is whitespace between pages so they don't run together
    $all_page_text = preg_replace(array('#<[/]?\w+>#','#$#'),array('',"\r\n"),$all_page_text);
    file_put_contents($compare_filename, $all_page_text);
    unset($all_page_text);

    // make external call to wdiff
    exec("wdiff -s $source_filename $compare_filename | tail -2", $wdiff_output, $return_code);

    // check to see if wdiff wasn't found to execute
    if($return_code == 127)
        die("Error invoking wdiff to do the diff analysis. Perhaps it is not installed.");
    if($return_code == 2)
        die("Error reported from wdiff while attempting to do the diff analysis.");

    // clean up the temporary files
    if(is_file($source_filename)) {
        unlink($source_filename);
    }
    if(is_file($compare_filename)) {
        unlink($compare_filename);
    }

    // calculate the number of alterations, output looks something like:
    // testing: 3 words  2 66% common  0 0% deleted  1 33% changed
    // testing1: 3 words  2 66% common  0 0% inserted  1 33% changed
    $num_alterations=0;

    // parse the incoming data one segment at a time
    // from the original datastream to conserve memory
    foreach($wdiff_output as $line)
    {
        if(preg_match("/\s+(\d+)\s+\d+% deleted\s+(\d+)\s+\d+%\s+changed/",$line,$matches))
        {
            $num_alterations += $matches[1];
            $num_alterations += $matches[2];
        }
        elseif(preg_match("/:\s+(\d+)\s+words.*\s+(\d+)\s+\d+% inserted/",$line,$matches))
        {
            $num_words = $matches[1];
            $num_alterations += $matches[2];
        }
    }
    // $wdiff_output can be very large
    // so unset it here to be nice for the rest of the function
    unset( $wdiff_output );

    return array($num_words, $num_alterations);
}

// vim: sw=4 ts=4 expandtab
?>
