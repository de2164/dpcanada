<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'DPage.inc'); // unused??
include_once('../c/tools/project_manager/page_operations.inc');

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}

echo "<pre>";

echo "<h2>Delete pages from a project</h2>";
echo "\n";

$submit_button = array_get( $_POST, 'submit_button', '' );

switch ( $submit_button )
{
    case '':
        echo "<form method='post'>";
        echo "Delete page(s) <input type='text' name='from_image_[lo]' size='12'>";
        echo           " - <input type='text' name='from_image_[hi]' size='12'>";
        echo "\n";
        echo "from project: <input type='text' name='projectid'>\n";
        echo "<input type='submit' name='submit_button' value='Check'>";
        echo "</form>";
        break;

    case 'Check':

        $projectid  = array_get( $_POST, 'projectid',  NULL );
        $from_image_ = array_get( $_POST, 'from_image_', NULL );


        do_stuff( $projectid, $from_image_, TRUE );

        echo "<form method='post'>";
        echo "<input type='hidden' name='from_image_[lo]'    value='{$from_image_['lo']}'>";
        echo "<input type='hidden' name='from_image_[hi]'    value='{$from_image_['hi']}'>";
        echo "<input type='hidden' name='projectid'   value='{$projectid}'>";
        echo "<input type='submit' name='submit_button'      value='Do it!'>";
        echo "</form>";
        break;

    case 'Do it!':

        $projectid = array_get( $_POST, 'projectid',  NULL );
        $from_image_ = array_get( $_POST, 'from_image_', NULL );

        do_stuff( $projectid, $from_image_, FALSE );

        $url = "$code_url/tools/project_manager/page_detail.php?project={$projectid}&amp;show_image_size=0";
        echo "<a href='$url'>Project's detail page</a>\n";

        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

function do_stuff( $projectid, $from_image_, $just_checking )
{
    if ( is_null($projectid) )
    {
        die( "Error: no projectid supplied" );
    }

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

    if ( $n_pages == 0 )
    {
        die( "project has no pages to delete" );
    }

    $all_image_values = array();
    while ( list($image,$fileid) = mysql_fetch_row($res) )
    {
        $all_image_values[] = $image;
    }

    // ----------------------

    $lo = trim($from_image_['lo']);
    $hi = trim($from_image_['hi']);

    if ( $lo == '' && $hi == '' )
    {
        die( "no pages specified for deletion" );
    }
    elseif ( $hi == '' )
    {
        $hi = $lo;
    }

    echo "    pages to delete: $lo - $hi\n";

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

    $n_pages_to_delete = 1 + $hi_i - $lo_i;
    echo "    ($n_pages_to_delete pages)\n";

    if ( $just_checking )
    {
        return;
    }

    // ----------------------------------------------------


    echo "-------------------------------------\n";
    echo "\n";

   for ( $i = $lo_i; $i <= $hi_i; $i++ )
   {
       $image = $all_image_values[$i];
       echo "image=$image:<br>\n";
       $err = page_del( $projectid, $image );
       echo ( $err ? $err : "success" );
       echo "<br>\n";
       echo "<br>\n";
   }

}

// vim: sw=4 ts=4 expandtab
?>
