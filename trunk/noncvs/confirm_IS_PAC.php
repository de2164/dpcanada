<?PHP
// Temporary page listing those projects already posted at the time
// of the image source harvesting from scannercredit, that had no
// scannercredit value, possibly because they were created before that
// field had been added. These were all marked during the changeover
// with scannercredit = 'TBC' (To Be Confirmed)

$relPath='../c/pinc/';
include_once($relPath.'dpsession.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'maintenance_mode.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'special_colors.inc');

//Check to see if we are in a maintenance mode
abort_if_in_maintenance_mode();

$locuserSettings = Settings::get_Settings($pguser);


$no_stats = 1;
$tcolspan = 7;
$header_text = _("Image Sources Harvesting - Books Already Posted at Changeover");
theme( $header_text, 'header');
echo "<h1>{$header_text}</h1>\n\n\n";



echo "
<font size=+1>
<p>
We recently harvested data stored in the old freeform \"scannercredit\" field and
resolved it, where we could, into the new fields of image_source, text_preparer,
image_preparer and extra_credits. An image_source must be one of the \"known\" 
image sources as listed at the Image Source Info Page (or set to \"DP Internal\"); 
text_preparer and image_preparer must be DP usernames, or blank; extra_credits 
is a free form type in field, for recording names of to-be-credited people who have
no DP account, etc.
</p>
<br>
<p>
At the time of this conversion, there were several thousand projects which had no 
value for scannercredit. While some of these were no doubt legitimately blank, because
we did not always have a scannercredit field, some projects will have been created
before there was a dedicated place to record information on the source of the images.
As these projects were created before scannercredit existed, information on the source
of the images for these projects, if any, may have been recorded in the project comments,
or the project forum thread, or maybe even only in the credit line of the posted etext.
</p>
<br>
<p>
The projects listed on this page were those that had no scannercredit value and had
already been posted to PG at the time of the image source harvesting. To track them,
they were marked with scannercredit = 'TBC' (To Be Confirmed). When their scannercredit
field is cleared, they will drop off this list. Before clearing the scannercredit, the
project comments, project forum thread (if any - not all will have one), and the credit
line of the posted etext should all be checked to determine what values should be
set for image_source (they will all by default be DP Internal), image_preparer, 
text_preparer and extra_credits, and those values set in those fields.
</p>
<br>
<p>
If, by inspecting the project comments, etc, it is determined that the images used came
from a \"known\" image source, such as Gallica, then that should be selected from the 
Image Sources drop down on the project edit page. In these cases, the DP username of 
the harvester goes into the image_preparer field. If no one else is mentioned, this will
be the PM. If someone else did OCR, their DP username goes into the text_preparer field. 
If someone or some organisation should have a credit and they do not have a DP username,
or, in the case of organisations, are not a \"known\" image source, then they should be
recorded in the extra_credits field. When all of these changes that apply to a particular
project are made, scannercredit should be cleared for that project and the whole 
project record saved.
</p>
<br>
<p>
This manual harvesting of image source information can be done by the PMs of the
projects listed. Projects not done by PMs, or managed by PMs no longer active on-site,
will be manually harvested by a group of volunteers. The project title in the table below
is a link to the project page for that project.
</p>
<br>
<p>
N.B. This page only lists those projects that were already posted at the time of the
changeover. Another page will appear later that lists those that had not been posted
at the time of the changeover.
</p>
<br>

</font>

";

// filter block
echo "<hr width='75%'>\n";

$state_sql = " (scannercredit = 'TBC' ) ";
$label = "Manual IS Harvesting";
$filtertype_stem = "MISH_1";
include_once($relPath.'filter_project_list.inc');

if (!isset($RFilter)) { $RFilter = ""; }

// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No";
if (!$locuserSettings->get_boolean('hide_special_colors'))
{
    echo "<hr width='75%'>\n";
    echo "<p><font face='{$theme['font_mainbody']}'>\n";
    echo_special_legend($state_sql);
    echo "</font></p><br>\n";
}

// read saved sort orders from user_settings table;

$setting = 'MISH_1order';

$result = mysql_query("
    SELECT value
    FROM usersettings
    WHERE username = '$pguser' AND setting = '$setting'
");
if (mysql_num_rows($result) >= 1)  {
    $order_old = mysql_result($result, 0, "value");
} else {
    $order_old = 'TitleA';
}


// read new sort order from url, if any
$url_param = "orderMISH_1";
$order_new = (isset($_GET[$url_param]) ? $_GET[$url_param] : $order_old );

// if orders have changed for a logged in user, save them to database
if ($order_new != $order_old)
{
        $result = mysql_query("
                DELETE FROM usersettings
                WHERE username = '$pguser' AND setting = '$setting'
        ");
        $result = mysql_query("
                INSERT INTO usersettings
                VALUES ('$pguser', '$setting', '$order_new')
        ");
}


$listing_bgcolors =  array(0 => "#DCFFC0", 1 => "#DCFF90");

echo "\r\n<table border=1>";
echo "\r\n<tr bgcolor='$listing_bgcolors[1]'>";

$title = _('Projects to be Checked that were Posted at Changeover');
echo "\n<td colspan='$tcolspan'><h3>$title</h3></td>";

$order = $order_new;

$flip_title = FALSE;
$flip_author = FALSE;
$flip_lang = FALSE;
$flip_genre = FALSE;
$flip_PM = FALSE;
$flip_POSTED = FALSE;

if ( $order == 'TitleA' )
{
        $orderclause = 'nameofwork ASC';
        $flip_title = TRUE;
}
elseif ( $order == 'TitleD' )
{
        $orderclause = 'nameofwork DESC';
}
elseif ( $order == 'AuthorA' )
{
        $orderclause = 'authorsname ASC, nameofwork ASC';
        $flip_author = TRUE;
}
elseif ( $order == 'AuthorD' )
{
        $orderclause = 'authorsname DESC, nameofwork ASC';
}
elseif ( $order == 'LangA' )
{
        $orderclause = 'language ASC, nameofwork ASC';
        $flip_lang = TRUE;
}
elseif ( $order == 'LangD' )
{
        $orderclause = 'language DESC, nameofwork ASC';
}
elseif ( $order == 'GenreA' )
{
        $orderclause = 'genre ASC, nameofwork ASC';
        $flip_genre = TRUE;
}
elseif ( $order == 'GenreD' )
{
        $orderclause = 'genre DESC, nameofwork ASC';
}
elseif ( $order == 'PMA' )
{
        $orderclause = 'projects.username ASC, nameofwork ASC';
        $flip_PM = TRUE;
}
elseif ( $order == 'PMD' )
{
        $orderclause = 'projects.username DESC, nameofwork ASC';
}
elseif ( $order == 'PostedA' )
{
        $orderclause = 'projects.posted_num ASC';
        $flip_PM = TRUE;
}
elseif ( $order == 'PostedD' )
{
        $orderclause = 'projects.posted_num DESC';
}
else
{
        echo "confirm_IS_PAC.php: bad order value: '$order'";
        exit;
}

$order_param = "orderMISH_1";

// The originating request may have query-string settings (other than
// for $order_param). We should preserve those, and just append the
// setting for $order_param.
$other_settings = '';
foreach ( $_GET as $name => $value )
{
        if ( $name != $order_param )
        {
                $other_settings .= "$name=$value&amp;";
        }
}

$linkbase = "<a href='?{$other_settings}{$order_param}=";
$linkend = "'";

$query = "
        SELECT projectid, nameofwork, authorsname, username as PM, special_code,
			language, genre, topic_id, postednum
        FROM projects
        WHERE
			  scannercredit = 'TBC' ".
                $RFilter.
   "     ORDER BY  " .
                $orderclause
;

$result = mysql_query($query);

$cntq = "SELECT count(*) as numleft from projects WHERE scannercredit = 'TBC'";
$cntres = mysql_query($cntq);
$cntassoc = mysql_fetch_assoc($cntres);


echo "<br><br>Total number of 'TBC' projects remaining to be checked is ".$cntassoc['numleft']."<br><br>";

$cntqF = "SELECT count(*) as numleft from projects WHERE scannercredit = 'TBC' ". $RFilter;
$cntresF = mysql_query($cntqF);
$cntassocF = mysql_fetch_assoc($cntresF);


echo "Total number of 'TBC' projects under current filter remaining to be checked is ".$cntassocF['numleft']."<br><br><br>";



echo "<tr align=center bgcolor='{$listing_bgcolors[1]}'>";

$word = _("Title");
$link = $linkbase.($flip_title?"TitleD":"TitleA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

$word = _("Author");
$link = $linkbase.($flip_author?"AuthorD":"AuthorA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

$word = _("Language");
$link = $linkbase.($flip_lang?"LangD":"LangA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

$word = _("Genre");
$link = $linkbase.($flip_genre?"GenreD":"GenreA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

$word = _("Project Manager");
$link = $linkbase.($flip_PM?"PMD":"PMA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";


// no point sorting by this link
$word = _("Project Thread");
echo "\n<td><b>$word</b></td>";

$word = _("PG Posted Number");
$link = $linkbase.($flip_POSTED?"PostedD":"PostedA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";



echo "</tr>";

// Determine whether to use special colors or not
// (this does not affect the alternating between two
// background colors) in the project listing.
global $pguser;
$userSettings = Settings::get_Settings($pguser);
$show_special_colors = !$userSettings->get_boolean('hide_special_colors');

$numrows = mysql_num_rows($result);
$rownum = 0;
$rownum2 = 0;

while ($rownum2 < $numrows) {
        $book=mysql_fetch_assoc($result);
        $bgcolor = $listing_bgcolors[$rownum % 2];

        // Special colours for special books of various types
        if ($show_special_colors) {
            $special_color = get_special_color_for_project($book);
            if (!is_null($special_color)) {
                $bgcolor = $special_color;
            }
        }

        if (TRUE) {

            $pm = $book['PM'];

            echo "<tr bgcolor='$bgcolor'>";
            $prid = $book['projectid'];
            echo "\n<td><a href='$code_url/project.php?id=$prid'>{$book['nameofwork']}</a></td>";
            echo "\n<td>{$book['authorsname']}</td>";
            echo "\n<td>{$book['language']}</td>";
            if ($book['difficulty'] == "beginner")  {
                $genre = _("BEGINNERS")." ".$book['genre'];
            } elseif ($book['difficulty'] == "easy") {
                $genre = _("EASY")." ".$book['genre'];
            } elseif ($book['difficulty'] == "hard") {
                $genre = _("HARD")." ".$book['genre'];
            } else {
                $genre = $book['genre'];
            }
            echo "\n<td>$genre</td>";

            echo "\n<td>$pm</td>";
            if (strlen($book['topic_id']) > 1 )
            {
                echo "\n<td><a href='$forums_url/viewtopic.php?".$book['topic_id']."'>Forum Thread</a></td>";
            } else echo "<td>No Forum Thread</td>";
            echo "\n<td><a href='http://www.gutenberg.net/etext/".$book['posted_num']."'>{$book['postednum']}</a></td>";

        } else {
            $rownum--;
        }
        $rownum++;
        $rownum2++;
}

echo "</table>\n<br>";

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>
