<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}

global $projects_dir;

echo "<pre>";

echo "<h2>Rotate a single image in a project</h2>";
echo "\n";

$submit_button = array_get( $_POST, 'submit_button', '' );

switch ( $submit_button )
{
    case '':
        echo "<form method='post'>";
        echo "projectID     : <input type='text' name='projectid'  size='18'>\n";
        echo "image filename: <input type='text' name='image_name' size='12'>\n";
        echo "<input type='submit' name='submit_button' value='Load'>";
        echo "</form>";
        break;

    case 'Load':

        $projectid  = array_get( $_POST, 'projectid',  NULL );
        $image_name = array_get( $_POST, 'image_name', NULL );

	// Check to see that image exists
	$full_path_of_image = $projects_dir ."/" . $projectid . "/" . $image_name;
	// get its info
	$image_info = getimagesize($full_path_of_image)
	    or die("Image file $image_name does not exist in project $projectid");

	$image_width  = $image_info[0];
	$image_height = $image_info[1];
	$image_type   = $image_info[2];

//        print_r($image_info);

	// Load it and create/display a thumbnail at 100px width
	switch($image_type)
	{
	case '3':
	// png format
	    $image = imagecreatefrompng($full_path_of_image);
	    break;
	
	case '2';
            // jpg format
    	    $image = imagecreatefromjpeg($full_path_of_image);
	    break;
	
	default:
    	    die("I don't recognize image type $image_type.");
	    break;
	}

	// Get values to scale to 150xwhatever thumbnail
	// find slope 
        $slope = $image_height/$image_width;
	// set x side to max
        $thumb_x = 150;
	// set y side to a proportional size
        $thumb_y = $slope * $thumb_x;
	// Make the thumbnail
	$thumb_image = imagecreatetruecolor($thumb_x,$thumb_y);
	// Resample into it
	imagecopyresampled($thumb_image, $image, 0, 0, 0, 0, $thumb_x, $thumb_y, $image_width, $image_height);
	// Write the thumbnail to d/download_tmp as a png
	$thumb_file = "/d/download_tmp/".$projectid."_thumb_".$image_name.".png";
	imagepng($thumb_image,"/data/htdocs/".$thumb_file,9);
	echo "<br>";
	echo "<img style='border: 1px solid gray;' src='$thumb_file'>\n";

//        do_stuff_new( $projectid, $image_list, $img_w, $img_h, $img_t, $p_txt, TRUE );

	echo "ProjectID: $projectid\n";
	echo "Image    : $image_name\n";
	echo "\n";
        echo "<form method='post'>";
        echo "<input type='hidden' name='image_name' value='{$image_name}'>";
        echo "<input type='hidden' name='projectid'  value='{$projectid}'>";
	echo "Rotate: ";
	echo "<input name='rotate_direction' type='radio' value='Right' checked>Right&nbsp;";
	echo "<input name='rotate_direction' type='radio' value='Left'>Left&nbsp;";
	echo "<input name='rotate_direction' type='radio' value='180'>Upside Down";
	echo "<input type='hidden' name='image_type' value='{$img_type}'>";
	echo "<input type='hidden' name='thumb_file' value='{$thumb_file}'>";
	echo "<br>\n";
        echo "<input type='submit' name='submit_button'      value='Preview' disabled>";
	echo "<br>\n";
	echo "Preview will create a rotated copy of the image but will NOT overwrite the original.\n";
	echo "The rotated image will be displayed on the next page and you will choose to replace\n";
	echo "the original image or not from that screen.\n";

        echo "</form>";

	// Immediately destroy the disk file ... we don't need it once it's been displayed in the browser
//	unlink("/data/htdocs/".$thumb_file);

        break;

    case 'Preview':

	break;

    case 'Do it!':

        $projectid = array_get( $_POST, 'projectid',  NULL );
        $from_image_ = array_get( $_POST, 'image_list', NULL );

        do_stuff_new( $projectid, $image_list, $img_w, $img_h, $img_t, $p_txt, FALSE );

        $url = "$code_url/tools/project_manager/page_detail.php?project={$projectid}&amp;show_image_size=0";
        echo "<a href='$url'>Project's detail page</a>\n";

        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";



function do_stuff_new( $projectid, $image_list, $img_w, $img_h, $img_t, $p_txt, $just_checking )
{
    global $projects_dir;

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
            SELECT fileid, image
            FROM $projectid
            ORDER BY image
        ") or die(mysql_error());

    $n_pages = mysql_num_rows($res);

    echo "    # pages  : $n_pages\n";

    // Useless test perhaps? otoh, can't tell page state without a preexisting page
    if ( $n_pages == 0 )
    {
        die( "project has no pages" );
    }

    // We will use this to check for collisions
    $all_image_values = array();
    while ( list($image,$fileid) = mysql_fetch_row($res) )
    {
        $all_image_values[$fileid] = $image;
    }

    // Do the same for images we want to insert
    $images_to_insert = array();
    $images_to_insert = split("[ ]+", $image_list);
    $num_blanks = count($images_to_insert);


    // Check for collisions
    $collisions = array();
    foreach( $images_to_insert as $blank)
    {
	$test = array_search( $blank, $all_image_values);
	if ($test != '')
	{
	    $collisions[] = $test;
	}
    }
    if (!empty($collisions))
    {
	echo "\n\nThe following blank pages cannot be inserted:\n";
	foreach ( $collisions as $bad )
	    {
	    echo "    $bad collides with existing page.\n";
	    }
	die("Fatal error.");
    }
    else {
	echo "    no page collisions detected ...\n";
    }

    // ----------------------------------------------------

    // Where image files will go
    $path_to_project_dir = $projects_dir ."/" . $projectid . "/";
    // Temporary table name for templating
    $tmp_table = $projectid . "_blanks";

    // Select the page state of the lowest numbered image in the target project
    // Assume that this state is the desired state for the inserted blanks
    // In practice, this should probably always be the saved state of the highest
    // completed round.

    $res = mysql_query("
	SELECT state, image
	FROM $projectid
	ORDER BY image ASC
	LIMIT 1
	");
    list($desired_state,$unused) = mysql_fetch_row($res);


    // Tell it like it is

    echo "\n\n";

    echo "Requested image size       : $img_w x $img_h pixels.\n";
    echo "Requested image type       : .$img_t\n";
    echo "Target directory           : $path_to_project_dir\n";
    echo "Desired page state         : $desired_state\n";
    echo "Using temporary table      : $tmp_table\n";
    echo "Number of new blank page(s): $num_blanks\n";
    echo "Page text for blank page(s): $p_txt\n";
    echo "List of new blank page(s)  : \n";
    foreach ($images_to_insert as $pgno)
    {
        echo "                           : $pgno\n";
    }
    echo "\n";

    if ( $just_checking )
    {
        return;
    }
    else
    {
	echo "\n\n** Proceeding with changes **\n\n";
    }

    echo "-------------------------------------\n";
    echo "\n";

    // ----------------------------------------------------
    // Using built-in GD functions - Create a new GD image resource
    $blank_image = imagecreate( $img_w, $img_h);
    // Set the palette properly
    $background_color = imagecolorallocate($blank_image, 255, 255, 255);
    imagefilltoborder ( $blank_image , 0, 0, 1, $background_color );

    // ----------------------------------------------------
    // Make a "temporary" table to copy pages from the parent project into as a template

    $res = mysql_query("
	CREATE TABLE $tmp_table
	LIKE $projectid
	");

    // Copy rows from the parent to the temporary table to get enough to template from.
    // As-is, this will fail if you are trying to insert more pages than the project currently has
    $res = mysql_query("
	INSERT INTO $tmp_table
	SELECT *
	FROM $projectid
	LIMIT $num_blanks
	");

    // Globally set the master_text (OCR) and page state in the temporary table
    $res = mysql_query("UPDATE $tmp_table set master_text = '$p_txt', state = '$desired_state'");

    // Hardcoding for the current 5 rounds, sorry
    // By only setting if the user is empty, we should automagically get
    // the text/user info into the right rounds, and we don't care about
    // the page times.

    $res = mysql_query("UPDATE $tmp_table SET round1_user = '[none]', round1_text = '$p_txt' WHERE round1_user != '';");
    $res = mysql_query("UPDATE $tmp_table SET round2_user = '[none]', round2_text = '$p_txt' WHERE round2_user != '';");
    $res = mysql_query("UPDATE $tmp_table SET round3_user = '[none]', round3_text = '$p_txt' WHERE round3_user != '';");
    $res = mysql_query("UPDATE $tmp_table SET round4_user = '[none]', round4_text = '$p_txt' WHERE round4_user != '';");
    $res = mysql_query("UPDATE $tmp_table SET round5_user = '[none]', round5_text = '$p_txt' WHERE round5_user != '';");

    // Get the list of image and fileid from temporary table so we can do the renumbering
    $res = mysql_query("
            SELECT fileid, image
            FROM $tmp_table
            ORDER BY image
        ") or die(mysql_error());

    $n_pages = mysql_num_rows($res);

    if ( $n_pages == 0 )
    {
        die( "temporary project $tmp_table has no pages" );
    }

    // We will use this to do the updates
    $tmp_image_values = array();
    while ( list($image,$fileid) = mysql_fetch_row($res) )
    {
        $tmp_image_values[$fileid] = $image;
    }
//    print_r($tmp_image_values);
//    echo "\n\n";

    // PROCESS EACH PAGE IN THE TEMPORARY TABLE

    // ----------------------------------------------------
    // For each file in the list ...

    foreach( $images_to_insert as $row )
    {
	$imagename = $row.".".$img_t;
	$full_image_name = $path_to_project_dir."/".$imagename;
	echo "Adding $imagename for $row\n";
	// Write the generated image to the target project directory with full compression
	switch($img_t)
	{
	case 'png':
	    // Sensible compression for png
	    imagepng($blank_image, $full_image_name, 9);
	    break;
	case 'jpg':
	    // We could make arg3 higher than quality '0' but these are just blanks
	    imagejpeg($blank_image, $full_image_name, 0);
	    break;
	default:
	    echo "Unrecognized image type, you may have a problem.\n";
	    break;
	}

        // Update a row in the temporary table to have the new fileid, image, and text fields
	$target_row = array_pop($tmp_image_values);
	echo "Updating row with fileid $target_row in $tmp_table ...\n";
//	echo "considering update $tmp_table set fileid = '$row', image = '$imagename' where fileid = '$target_row';\n";
	$res = mysql_query("
	    UPDATE $tmp_table
	    SET fileid = '$row',
	        image  = '$imagename'
	    WHERE fileid = '$target_row'
	    ") or die(mysql_error());
    }

    // ------------------------------------------------------
    // HERE WE GO!

    // Copy the updated rows from the temporary project into the target project
    echo "Copying updated rows from $tmp_table to $projectid ...\n";
    $res = mysql_query("
	INSERT INTO $projectid
	SELECT * FROM $tmp_table
	") or die(mysql_error());

    // Clean up after ourselves
    // Destroy the GD image resource
    imagedestroy($blank_image);    

    // Destroy the temporary project table
    $res = mysql_query("DROP TABLE $tmp_table");

    // Update the projects table with the new total page count
    $res = mysql_query("
            SELECT fileid, image
            FROM $projectid
            ORDER BY image
        ") or die(mysql_error());

    $newtotal = mysql_num_rows($res);

    $res = mysql_query("
	UPDATE projects
	SET n_pages = '$newtotal'
	WHERE projectid = '$projectid'
	") or die(mysql_error());

    return;
}

// vim: sw=4 ts=4 expandtab
?>
