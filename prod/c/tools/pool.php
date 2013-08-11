<?
$relPath="./../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'site_news.inc');

$pool_id = @$_GET['pool_id'];

$pool = get_Pool_for_id($pool_id);
if ( is_null($pool) )
{
    die("bad 'pool_id' parameter: '$pool_id'");
}

$available_filtertype_stem = "{$pool->id}_av";

// -----------------------------------------------------------------------------

theme("$pool->id: $pool->name", "header");

global $pguser;
$userSettings = Settings::get_Settings($pguser);

$uao = $pool->user_access($pguser);

$pool->page_top( $uao );



show_news_for_page($pool->id);


echo "<br>\n";
echo implode( "\n", $pool->blather );

echo "
<br>
<p>
If there's a project you're interested in,
  you can get to a page about that project
  by clicking on the title of the work.
(We strongly recommend you right-click
  and open this project-specific page in a new window or tab.)
The page will let you see the project comments
  and check the project in or out
  as well as download the associated text and image files.
</p>
";

if ( !$uao->can_access )
{
    echo "<hr width='75%'>\n";
    show_user_access_object( $uao );
}

// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No".
if (!$userSettings->get_boolean('hide_special_colors'))
{
    echo "<hr width='75%'>\n";
    echo "<p><font face='{$theme['font_mainbody']}'>\n";
    echo_special_legend(" 1 = 1");
    echo "</font></p><br>\n";
}

// --------------------------------------------------------------
echo "<hr>\n";

$header = _('Books I Have Checked Out');

echo "<h2 align='center'>$header</h2>";

echo "<a name='checkedout'></a>\n";
show_projects_in_state_plus( $pool, 'checkedout', " " );
echo "<br>";
echo "<br>";

// --------------------------------------------------------------
echo "<hr>\n";

$header = _('Books Available for Checkout');

echo "<h2 align='center'>$header</h2>";

// -------
$label = $pool->name;
$state_sql = " (state = '{$pool->project_available_state}') ";
$filtertype_stem = $available_filtertype_stem;
include($relPath.'filter_project_list.inc');
if (!isset($RFilter)) { $RFilter = ""; }
// -------

echo "<a name='available'></a>\n";
echo "<center><b>$header</b></center>";
show_projects_in_state_plus( $pool, 'available', $RFilter );
echo "<br>";
echo "<br>";

theme("", "footer");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_projects_in_state_plus(
    $pool,
    $checkedout_or_available,
    $RFilter
)
// A wrapped version of show_projects_in_state
// that handles getting and saving the table's sort order.
{
    global $pguser;

    $ch_or_av = substr( $checkedout_or_available, 0, 2 );
    $order_setting_name = "{$pool->id}_{$ch_or_av}_order";
    $order_param_name = "order_{$checkedout_or_available}";

    // Get saved sort order
    $res = mysql_query("
        SELECT value
        FROM usersettings
        WHERE username = '$pguser' AND setting = '$order_setting_name'
    ");
    if (mysql_num_rows($res) >= 1)
    {
        $saved_order = mysql_result($res, 0);
    }
    else
    {
        $saved_order = 'DaysD';
    }

    // Get new sort order, if any
    $new_order = array_get( $_GET, $order_param_name, $saved_order );

    // If order has changed, save it to database
    if ($new_order != $saved_order)
    {
        mysql_query("
            DELETE FROM usersettings
            WHERE username='$pguser' AND setting='$order_setting_name'
        ") or die(mysql_error());

        mysql_query("
            INSERT INTO usersettings
            SET username='$pguser', setting='$order_setting_name', value='$new_order'
        ") or die(mysql_error());
    }

    // -------------------------------------------------------------------

    $table = 1;

    global $code_url;

    $flip_title = FALSE;
    $flip_author = FALSE;
    $flip_lang = FALSE;
    $flip_genre = FALSE;
    $flip_PgTot = FALSE;
    $flip_Person = FALSE;
    $flip_days = FALSE;

    $theme = $GLOBALS['theme'];

    if ( $new_order == 'TitleA' )
    {
        $orderclause = 'nameofwork ASC';
        $flip_title = TRUE;
    }
    elseif ( $new_order == 'TitleD' )
    {
        $orderclause = 'nameofwork DESC';
    }
    elseif ( $new_order == 'AuthorA' )
    {
        $orderclause = 'authorsname ASC, nameofwork ASC';
        $flip_author = TRUE;
    }
    elseif ( $new_order == 'AuthorD' )
    {
        $orderclause = 'authorsname DESC, nameofwork ASC';
    }
    elseif ( $new_order == 'LangA' )
    {
        $orderclause = 'language ASC, nameofwork ASC';
        $flip_lang = TRUE;
    }
    elseif ( $new_order == 'LangD' )
    {
        $orderclause = 'language DESC, nameofwork ASC';
    }
    elseif ( $new_order == 'GenreA' )
    {
        $orderclause = 'genre ASC, nameofwork ASC';
        $flip_genre = TRUE;
    }
    elseif ( $new_order == 'GenreD' )
    {
        $orderclause = 'genre DESC, nameofwork ASC';
    }
    elseif ( $new_order == 'PgTotA' )
    {
        $orderclause = 'n_pages ASC, nameofwork ASC';
        $flip_PgTot = TRUE;
    }
    elseif ( $new_order == 'PgTotD' )
    {
        $orderclause = 'n_pages DESC, nameofwork ASC';
    }
    elseif ( $new_order == 'PersonA' )
    {
        $orderclause = "{$pool->foo_field_name} ASC, nameofwork ASC";
        $flip_Person = TRUE;
    }
    elseif ( $new_order == 'PersonD' )
    {
        $orderclause = "{$pool->foo_field_name} DESC, nameofwork ASC";
    }
    
    // note that we SHOW "days since M", but *order* by M, so the logic is flipped
    elseif ( $new_order == 'DaysA' )
    {
        $orderclause = 'modifieddate DESC, nameofwork ASC';
        $flip_days = TRUE;
    }
    elseif ( $new_order == 'DaysD' )
    {
        $orderclause = 'modifieddate ASC, nameofwork ASC';
    }
    else
    {
        echo "show_projects_in_state.inc: bad order value: '$new_order'";
        exit;
    }


    if ( $checkedout_or_available == 'checkedout' )
    {
        $proj_state = $pool->project_checkedout_state;
    }
    elseif ( $checkedout_or_available == 'available' )
    {
        $proj_state = $pool->project_available_state;
    }
    else
    {
        assert( FALSE );
    }

    $query = "
        SELECT
            projectid,
            nameofwork,
            authorsname,
            language,
            genre,
            username,
            postproofer,
            checkedoutby,
            correctedby,
            modifieddate,
            special_code,
            difficulty, 
            round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) as days_avail,
            n_pages,
            comments
        FROM projects
        WHERE state='$proj_state'
            $RFilter 
    ";
    if ( $checkedout_or_available == 'checkedout' )
    {
        // The project must be checked-out to somebody.
        // We're only interested if it's checked out to the current user.
        $query .= " AND checkedoutby = '$pguser'";
    }

    $query .= " ORDER BY $orderclause";

    $result = mysql_query($query);


    if ($table)
    {
        echo "
            <table
                align='center'
                border=1
                width=630
                cellpadding=0
                cellspacing=0
                style='border-collapse: collapse;'
                bordercolor='#111111'
            >
        ";
    }

    $tds="<td bgcolor='".$theme['color_headerbar_bg']."' align=\"center\"";
    $tdm="<font color='".$theme['color_headerbar_font']."'>";
    $tdc="</b></font></a></td>";

    echo "<tr>";

    $linkbase = "<a href=pool.php?pool_id={$pool->id}&{$order_param_name}=";
    $linkend  = "#{$checkedout_or_available}>";

    $word = _("Title");
    $link = $linkbase.($flip_title?"TitleD":"TitleA").$linkend;
    echo "$tds width=\"185\">$link$tdm<b>$word$tdc\n";

    $word = _("Author");
    $link = $linkbase.($flip_author?"AuthorD":"AuthorA").$linkend;
    echo "$tds width=\"95\">$link$tdm<b>$word$tdc\n";

    $word = _("Language");
    $link = $linkbase.($flip_lang?"LangD":"LangA").$linkend;
    echo "$tds width=\"85\">$link$tdm<b>$word$tdc\n";

    $word = _("Genre");
    $link = $linkbase.($flip_genre?"GenreD":"GenreA").$linkend;
    echo "$tds width=\"85\">$link$tdm<b>$word$tdc\n";

    $word = _("Pages");
    $link = $linkbase.($flip_PgTot?"PgTotD":"PgTotA").$linkend;
    echo "$tds width=\"45\">$link$tdm<b>$word$tdc\n";

    $word = $pool->foo_Header;
    $link = $linkbase.($flip_Person?"PersonD":"PersonA").$linkend;
    echo "$tds width=\"70\">$link$tdm<b>$word$tdc\n";

    $word = _("Days");
    $link = $linkbase.($flip_days?"DaysD":"DaysA").$linkend;
    echo "$tds width=\"35\">$link$tdm<b>$word$tdc\n";

    echo "</tr>";


    // the following only need to be done once, not every iteration of the loop below!
    $userid_q = mysql_query("SELECT u_id FROM users WHERE username = '".$GLOBALS['pguser']."'");
    $u_id = mysql_result($userid_q, 0, "u_id");
    $show_email = user_is_a_sitemanager() || $u_id == 1342 || $u_id == 12417;

    // Determine whether to use special colors or not
    // (this does not affect the alternating between two
    // background colors) in the project listing.
    $userSettings = Settings::get_Settings($pguser);
    $show_special_colors = !$userSettings->get_boolean('hide_special_colors');

    $numrow = mysql_numrows($result);
    for ( $rownum = 0; $rownum < $numrow; $rownum++ )
    {
        echo "<tr>\n";

        $book = mysql_fetch_assoc($result);

        $bgcolor = $pool->listing_bgcolors[$rownum % 2];
        $bgcolor_attr = " bgcolor='$bgcolor'";

        // Special colours for special books of various types
        if ($show_special_colors)
        {
            $special_color = get_special_color_for_project($book);
            if (!is_null($special_color))
            {
                $bgcolor_attr = " bgcolor='$special_color'";
            }
        }

        $foo_username = $book[$pool->foo_field_name];
        $users = mysql_query("SELECT user_id, user_email FROM phpbb_users WHERE username = '$foo_username'");
        
        if ( mysql_num_rows($users) > 0 )
        {
            if ($show_email)
            {
                $foo_email = mysql_result($users, 0, "user_email");
                $foo_cell = "<A HREF=\"mailto:$foo_email\">".$book[$pool->foo_field_name]."</A>";
            }
            else
            {
                $foo_userid = mysql_result($users, 0, "user_id");
                $foo_cell = "<A HREF=\"".$GLOBALS['forums_url']."/privmsg.php?mode=post&u=$foo_userid\">".$book[$pool->foo_field_name]."</A>";
            }
        }
        else
        {
            $foo_cell = $book[$pool->foo_field_name];
        }

        $url = "$code_url/project.php?id={$book['projectid']}&amp;expected_state=$proj_state";
        echo "\n<td $bgcolor_attr><a href='$url'>". $book['nameofwork']. "</a></td>";
        echo "\n<td $bgcolor_attr> ". $book['authorsname']. " </td>";
        echo "\n<td $bgcolor_attr align=center> ". $book['language']. " </td>";

        if ($book['difficulty'] == "easy")
        {
            $genre = _("EASY")." ".$book['genre'];
        }
        elseif ($book['difficulty'] == "hard")
        {
            $genre = _("HARD")." ".$book['genre'];
        }
        else
        {
            $genre = $book['genre'];
        }

        echo "\n<td $bgcolor_attr align=center>$genre</td>";
        echo "\n<td $bgcolor_attr align=center> ". $book['n_pages']. " </td>";
        echo "\n<td $bgcolor_attr align=center>$foo_cell</td>";
        echo "\n<td $bgcolor_attr align=center> ". $book['days_avail']. " </td>";

        echo "</tr>\n";
    }

    if ($table)
    {
        echo "</table>\n";
    }
}

// vim: sw=4 ts=4 expandtab
?>
