<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'DPage.inc'); // project_recalculate_page_counts
include_once($relPath.'user_project_info.inc');
include_once($relPath.'wordcheck_engine.inc');

// This script should maybe be merged with insert_project.php

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}

echo "<pre>";

echo "<h2>Copy pages from one project to another</h2>";
echo "\n";

$submit_button = array_get( $_POST, 'submit_button', '' );
$projectid_  = array_get( $_POST, 'projectid_',  NULL );
$from_image_ = array_get( $_POST, 'from_image_', NULL );
$page_name_handling = array_get( $_POST, 'page_name_handling', NULL );
$transfer_notifications = array_get( $_POST, 'transfer_notifications', false );
$add_deletion_reason = array_get( $_POST, 'add_deletion_reason', false );
$merge_wordcheck_files = array_get( $_POST, 'merge_wordcheck_files', false );
$repeat_project = array_get( $_POST, 'repeat_project', NULL );

switch ( $submit_button )
{
    case '':
        display_form($projectid_, $from_image, $page_name_handling, 
                     $transfer_notifications, $add_deletion_reason,
                     $merge_wordcheck_files, $repeat_project, FALSE);
        break;

    case 'Again!':
        display_form($projectid_, $from_image, $page_name_handling, 
                     $transfer_notifications, $add_deletion_reason,
                     $merge_wordcheck_files, $repeat_project, TRUE);
        break;

    case 'Check':
        do_stuff( $projectid_, $from_image_, $page_name_handling, 
                  $transfer_notifications, $add_deletion_reason, 
                  $merge_wordcheck_files, TRUE );

        echo "<form method='post'>";
        display_hiddens($projectid_, $from_image_, $page_name_handling, 
                        $transfer_notifications, $add_deletion_reason, 
                        $merge_wordcheck_files);
        echo "\n<input type='submit' name='submit_button'      value='Do it!'>";
        echo "\n</form>";
        break;

    case 'Do it!':
        do_stuff( $projectid_, $from_image_, $page_name_handling, 
                  $transfer_notifications, $add_deletion_reason, 
                  $merge_wordcheck_files, FALSE );

        $url = "$code_url/tools/project_manager/page_detail.php?project={$projectid_['to']}&amp;show_image_size=0";
        echo "<a href='$url'>'To' project's detail page</a>\n";
        echo "<form method='post'>";
        echo "\n\nCopy more pages ";
        echo " <input type='radio' name='repeat_project' value='FROM' > from &nbsp;&nbsp;&nbsp;&nbsp;";
        echo " <input type='radio' name='repeat_project' value='TO' > to the same project\n ";
        display_hiddens($projectid_, $from_image_, $page_name_handling, 
                        $transfer_notifications, $add_deletion_reason, 
                        $merge_wordcheck_files);
        echo "\n<input type='submit' name='submit_button'      value='Again!'>";
        echo "\n</form>";
        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "\n</pre>";

function display_form($projectid_, $from_image_, $page_name_handling, 
                      $transfer_notifications, $add_deletion_reason, 
                      $merge_wordcheck_files, $repeat_project, $repeating)
{
    echo "<form method='post'>";
    // always leave the page numbers blank
    echo "Copy page(s) <input type='text' name='from_image_[lo]' size='12'>";
    echo           " - <input type='text' name='from_image_[hi]' size='12'>";
    echo " (leave blank to copy all pages)\n";

    // if we are repeating, will want to fill one of these in
    $val = '';
    if ($repeating && $repeat_project == 'FROM')
    {
        $val = $projectid_['from'];
        $val = "value='$val'";
    }
    echo "from project: <input type='text' name='projectid_[from]' $val>\n";
    $val = '';
    if ($repeating && $repeat_project == 'TO')
    {
        $val = $projectid_['to'];
        $val = "value='$val'";
    }
    echo "  to project: <input type='text' name='projectid_[to]' $val>\n";

    // if we are repeating, want the right ones to be checked
    $checked = '';
    if (!$repeating ||
        ($repeating && $page_name_handling == 'PRESERVE_PAGE_NAMES') )
    {
        $checked = 'CHECKED';
    }
    echo "<input type='radio' name='page_name_handling' value='PRESERVE_PAGE_NAMES' $checked>preserve page numbers  ";
    if ($checked == '') {
        $checked = 'CHECKED';
    }
    else
    {
        $checked = '';
    }
    echo "<input type='radio' name='page_name_handling' value='RENUMBER_PAGES' $checked>renumber pages\n";

    do_radio_button_pair("Transfer event notifications:           ", "transfer_notifications", $repeating, 
                         $transfer_notifications );
    do_radio_button_pair("Add deletion reason to 'from' project:  ", "add_deletion_reason", $repeating, 
                         $add_deletion_reason  );
    do_radio_button_pair("Merge wordcheck files into 'to' project:", "merge_wordcheck_files", $repeating, 
                         $merge_wordcheck_files );

    echo "<input type='submit' name='submit_button' value='Check'>";
    echo "</form>";
}

function do_radio_button_pair($prompt, $input_name, $repeating, $first_is_checked )
{
    $checked = '';
    if (!$repeating ||
        ($repeating && $first_is_checked) )
    {
        $checked = 'CHECKED';
    }
    echo "\n$prompt <input type='radio' name='$input_name' value='1' $checked> Yes &nbsp;&nbsp;&nbsp;&nbsp; ";
    if ($checked == '') {
        $checked = 'CHECKED';
    }
    else
    {
        $checked = '';
    }
    echo "<input type='radio' name='$input_name' value='0' $checked > No  \n";
}

function display_hiddens($projectid_, $from_image_, $page_name_handling, 
                         $transfer_notifications, $add_deletion_reason,
                         $merge_wordcheck_files)
{
    echo "</pre>";
    echo "\n<input type='hidden' name='from_image_[lo]'    value='{$from_image_['lo']}'>";
    echo "\n<input type='hidden' name='from_image_[hi]'    value='{$from_image_['hi']}'>";
    echo "\n<input type='hidden' name='projectid_[from]'   value='{$projectid_['from']}'>";
    echo "\n<input type='hidden' name='projectid_[to]'     value='{$projectid_['to']}'>";
    echo "\n<input type='hidden' name='page_name_handling' value='$page_name_handling'>";
    echo "\n<input type='hidden' name='transfer_notifications' value='$transfer_notifications'>";
    echo "\n<input type='hidden' name='add_deletion_reason' value='$add_deletion_reason'>";
    echo "\n<input type='hidden' name='merge_wordcheck_files' value='$merge_wordcheck_files'>";
    echo "<pre>";

}

function do_stuff( $projectid_, $from_image_, $page_name_handling, 
                   $transfer_notifications, $add_deletion_reason,
                   $merge_wordcheck_files, 
                   $just_checking )
{
    if ( is_null($projectid_) )
    {
        die( "Error: no projectid data supplied" );
    }

    $page_names_ = array();

    foreach ( array( 'from', 'to' ) as $which )
    {
        $res= mysql_query("
            DESCRIBE {$projectid_[$which]}
        ") or die(mysql_error());

        $column_names = array();
        while ( $row = mysql_fetch_assoc($res) )
        {
            $column_names[] = $row['Field'];
        }
        $column_names_[$which] = $column_names;
    }
    $clashing_columns = array_intersect( $column_names_['from'], $column_names_['to'] );

    foreach ( array( 'from', 'to' ) as $which )
    {
        echo "$which:\n";

        $projectid = $projectid_[$which];

        echo "    projectid: $projectid\n";

        $res = mysql_query("
            SELECT nameofwork
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());

        $n_projects = mysql_num_rows($res);
        if ( $n_projects == 0 )
        {
            die( "projects table has no match for projectid='$projectid'" );
        }
        else if ( $n_projects > 1 )
        {
            die( "projects table has $n_projects matches for projectid='$projectid'. (Can't happen)" );
        }

        list($title) = mysql_fetch_row($res);

        echo "    title    : $title\n";

        // ------------

        $res = mysql_query("
            SELECT image, fileid
            FROM $projectid
            ORDER BY image
        ") or die(mysql_error());

        $n_pages = mysql_num_rows($res);

        echo "    # pages  : $n_pages\n";

        if ( $which == 'from' && $n_pages == 0 )
        {
            die( "project has no page data to extract" );
        }

        $all_image_values = array();
        $all_fileid_values = array();
        while ( list($image,$fileid) = mysql_fetch_row($res) )
        {
            $all_image_values[] = $image;
            $all_fileid_values[] = $fileid;
        }

        $all_image_values_[$which] = $all_image_values;
        $all_fileid_values_[$which] = $all_fileid_values;

        // ----------------------

        $n_columns = count($column_names_[$which]);
        echo "    # columns: $n_columns\n";

        $extra_columns_[$which] = array_diff( $column_names_[$which], $clashing_columns );
        if ( count($extra_columns_[$which]) > 0 )
        {
            echo "    extra columns: ", implode( " ", $extra_columns_[$which] ), "\n";
            if ( $which == 'from' )
            {
                echo "    (These columns will simply be ignored.)\n";
            }
            else
            {
                echo "    (These columns will be given their default value.)\n";
            }
        }

        // ----------------------

        if ( $which == 'from' )
        {
            $lo = trim($from_image_['lo']);
            $hi = trim($from_image_['hi']);

            if ( $lo == '' && $hi == '' )
            {
                $lo = $all_image_values[0];
                $hi = $all_image_values[ count($all_image_values) - 1 ];
            }
            elseif ( $hi == '' )
            {
                $hi = $lo;
            }

            echo "    pages to copy: $lo - $hi\n";

            $lo_i = array_search( $lo, $all_image_values );
            $hi_i = array_search( $hi, $all_image_values );

            if ( $lo_i === FALSE )
            {
                die( "project does not have a page with image='$lo'" );
            }

            if ( $hi_i === FALSE )
            {
                die( "project does not have a page with image='$hi'" );
            }

            if ( $lo_i > $hi_i )
            {
                die( "low end of range ($lo) is greater than high end ($hi)" );
            }

            $n_pages_to_copy = 1 + $hi_i - $lo_i;

            echo "    ($n_pages_to_copy pages)\n";
        }

        // ----------------------

        echo "\n";
    }

    if ( $projectid_['from'] == $projectid_['to'] )
    {
        die( "You can't insert a project into itself." );
    }

    // ----------------------------------------------------

    echo "page_name_handling:\n";
    echo "    $page_name_handling\n";
    echo "\n";

    if ( $page_name_handling == 'PRESERVE_PAGE_NAMES' )
    {
        // fine
    }
    elseif ( $page_name_handling == 'RENUMBER_PAGES' )
    {
        if ( count($all_fileid_values_['to']) == 0 )
        {
            $c_dst_format = '%03d';
            $c_dst_start_b = 1;
        }
        else
        {
            $max_dst_fileid = str_max( $all_fileid_values_['to'] );
            $max_dst_image  = str_max( $all_image_values_['to'] );
            $max_dst_image_base = preg_replace( '/\.[^.]+$/', '', $max_dst_image );
            $max_dst_base = (
                strcmp( $max_dst_fileid, $max_dst_image_base ) > 0
                ? $max_dst_fileid
                : $max_dst_image_base );
            $c_dst_format = '%0' . strlen($max_dst_base) . 'd';
            $c_dst_start_b = 1 + intval($max_dst_base);
        }
    }
    else
    {
        die( "bad page_name_handling" );
    }

    // The c_ prefix means that it only pertains to *copied* pages.

    $c_src_image_  = array();
    $c_src_fileid_ = array();
    $c_dst_image_  = array();
    $c_dst_fileid_ = array();

    for ( $i = $lo_i; $i <= $hi_i; $i++ )
    {
        $c_src_image = $all_image_values_['from'][$i];
        $c_src_fileid = $all_fileid_values_['from'][$i];

        if ( $page_name_handling == 'PRESERVE_PAGE_NAMES' )
        {
            $c_dst_fileid = $c_src_fileid;
            $c_dst_image  = $c_src_image;
        }
        elseif ( $page_name_handling == 'RENUMBER_PAGES' )
        {
            $c_src_image_ext = preg_replace( '/.*\./', '', $c_src_image );
            $c_dst_b = ( $i - $lo_i + $c_dst_start_b );
            $c_dst_fileid = sprintf( $c_dst_format, $c_dst_b );
            $c_dst_image  = "$c_dst_fileid.$c_src_image_ext";
        }
        else
        {
            assert( FALSE );
        }

        $c_src_image_[]  = $c_src_image;
        $c_src_fileid_[] = $c_src_fileid;
        $c_dst_image_[]  = $c_dst_image;
        $c_dst_fileid_[] = $c_dst_fileid;
    }

    $clashing_image_values = array_intersect( $c_dst_image_, $all_image_values_['to'] );
    if ( count($clashing_image_values) > 0 )
    {
        echo "Name clash! The 'to' project already has pages with these 'image' values:\n";
        foreach ( $clashing_image_values as $clashing_image_value )
        {
            echo "    $clashing_image_value\n";
        }
        die("");
    }

    $clashing_fileid_values = array_intersect( $c_dst_fileid_, $all_fileid_values_['to'] );
    if ( count($clashing_fileid_values) > 0 )
    {
        echo "Name clash! The 'to' project already has pages with these 'fileid' values:\n";
        foreach ( $clashing_fileid_values as $clashing_fileid_value )
        {
            echo "    $clashing_fileid_value\n";
        }
        die("");
    }

    if ( $page_name_handling == 'PRESERVE_PAGE_NAMES' )
    {
        echo "There don't appear to be any page-name clashes.\n\n";
    }
    elseif ( $page_name_handling == 'RENUMBER_PAGES' )
    {
        echo "As expected, there aren't any page-name clashes.\n\n";
    }

    if ($transfer_notifications) 
    {
        echo "Event notifications will be transferred\n\n" ;
    }
    else
    {
       echo "Event notifications will not be transferred\n\n" ;
    }

    if ($add_deletion_reason) 
    {
        echo "The following deletion reason will be added to the 'from' project:\n " ;
        echo "    merged into {$projectid_['to']}\n\n";
    }
    else
    {
       echo "No deletion reason will be added to the 'from' project\n\n" ;
    }

    if ($merge_wordcheck_files) 
    {
        echo "The wordcheck files from the 'from' project will be merged into the 'to' project\n\n" ;
    }
    else
    {
       echo "No wordcheck files will be changed\n\n" ;
    }

    if ( $just_checking )
    {
        return;
    }

    // ----------------------------------------------------

    echo "-------------------------------------\n";
    echo "\n";

    $for_real = 1;

    // cd to projects dir to simplify filesystem moves
    global $projects_dir;
    echo "cd $projects_dir\n";
    if ( ! chdir( $projects_dir ) )
    {
        die( "Unable to 'cd $projects_dir'" );
    }

    $items_array = array();
    foreach ( $column_names_['to'] as $col )
    {
        $items_array[] = (
            in_array( $col, $extra_columns_['to'] )
            ? '""' // (assuming that always works as a default value)
            : $col
        );
    }

    $items_list_template = join( $items_array, ',' );

    for ( $j = 0; $j < $n_pages_to_copy; $j++ )
    {
        $c_src_image  = $c_src_image_[$j];
        $c_src_fileid = $c_src_fileid_[$j];
        $c_dst_image  = $c_dst_image_[$j];
        $c_dst_fileid = $c_dst_fileid_[$j];

        echo "\n";
        echo "    $c_src_image ...\n";

        $items_list = str_replace(
            array(      'image',       'fileid' ),
            array("'$c_dst_image'","'$c_dst_fileid'"),
            $items_list_template );

        // This ignores $writeBIGtable
        $query = "
            INSERT INTO {$projectid_['to']}
            SELECT $items_list
            FROM {$projectid_['from']}
            WHERE image = '$c_src_image'
        ";
        echo $query;
        if ($for_real)
        {
            mysql_query($query) or die(mysql_error());
            $n = mysql_affected_rows();
            echo "
                $n rows inserted.
            ";
            if ( $n != 1 )
            {
                die( "unexpected number of rows inserted" );
            }
        }

        $c_src_path = "{$projectid_['from']}/$c_src_image";
        $c_dst_path = "{$projectid_['to']}/$c_dst_image";

        echo "
            copying $c_src_path to $c_dst_path...
        ";

        if ($for_real)
        {
            $success = copy( $c_src_path, $c_dst_path );
            $s = ( $success ? 'succeeded' : 'failed' ); 
            echo "
                copy $s
            ";
        }
    }

    project_recalculate_page_counts( $projectid_['to'] );
    echo "
  Page counts recalculated
         ";

    if ($transfer_notifications && $for_real) {
        echo "
  Transferring event notifications
             ";

        // for each subscribable event
        //   for each user subscribed to "from" project
        //      subscribe user to "to" project
        global $subscribable_project_events;
        $count = 0;
        foreach ( $subscribable_project_events as $event => $label )
        {
            $query = "
                      SELECT username FROM user_project_info
                      WHERE projectid = '{$projectid_['from']}' AND
                            iste_$event = 1";
            $res1 = mysql_query($query) or die(mysql_error());
            while ( list($username) = mysql_fetch_row($res1) )
            {
                set_user_project_event_subscription( $username, 
                                                     $projectid_['to'], 
                                                     $event, 1 );
                $count++;
            }
        }
                echo "
                  $count notifications transferred
             ";

    }

    if ($add_deletion_reason) {
        echo "
  Adding deletion reason to 'from' project
             ";
        $query = "
              UPDATE projects
              SET deletion_reason = 'merged into {$projectid_['to']}'
              WHERE projectid = '{$projectid_['from']}'
             ";
        echo $query;
       if ($for_real)
        {
            mysql_query($query) or die(mysql_error());
            $n = mysql_affected_rows();
            echo "
                  $n rows updated.
            ";
        }
    }

    if ($merge_wordcheck_files) {
        echo "
  Merging wordcheck files
             ";
        if ($for_real)
        {
            merge_wordcheck_files($projectid_['from'], $projectid_['to']);
        }
    }
    echo "\n";
}

function merge_wordcheck_files($from_id, $to_id)
{
    global $projects_dir;

    // good words
    $from_words = load_project_good_words( $from_id );
    $to_words = load_project_good_words( $to_id );
    $to_words = array_merge($to_words, $from_words);
    save_project_good_words( $to_id, $to_words );

    // crying out for some abstraction here?

    // bad words
    $from_words = load_project_bad_words( $from_id );
    $to_words = load_project_bad_words( $to_id );
    $to_words = array_merge($to_words, $from_words);
    save_project_bad_words( $to_id, $to_words );

    // suggestions
    // the file format is complicated and may change
    // so we take the sledgehammer approach, as suggested by cpeel...
    $from_path = "$projects_dir/$from_id/good_word_suggestions.txt";
    if ( !is_file($from_path) )
    {
        // The file does not exist.
        // Treat that the same as if it existed and was empty.
        $from_suggs = "";
    }
    else 
    {
        $from_suggs = file_get_contents($from_path);
    }
    $to_path = "$projects_dir/$to_id/good_word_suggestions.txt";
    if ( !is_file($to_path) )
    {
        // The file does not exist.
        // Treat that the same as if it existed and was empty.
        $to_suggs =  "";
    }
    else 
    {
        $to_suggs = file_get_contents($to_path);
    }
    file_put_contents($to_path, $to_suggs . $from_suggs);
    // we're assuming the projects are in unavailable or waiting, so there
    // is going to be no need to put locks on the files or anything fancy

}

function str_max( & $arr )
{
    $max_so_far = NULL;
    foreach ( $arr as $s )
    {
        if ( is_null($max_so_far) || strcmp( $s, $max_so_far ) > 0 )
        {
            $max_so_far = $s;
        }
    }
    return $s;
}

// vim: sw=4 ts=4 expandtab
?>
