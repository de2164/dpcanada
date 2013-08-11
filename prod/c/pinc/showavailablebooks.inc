<?
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');

function show_block_for_round( $round_number, $projects_filter )
{
	global $pguser, $userP;

	$round = get_Round_for_round_number($round_number);

	$setting = "{$round->id}_order";

	// read saved sort orders from user_settings table 
	$result = mysql_query("
		SELECT value
		FROM usersettings
		WHERE username = '$pguser' AND setting = '$setting'"
	);
	if (mysql_num_rows($result) >= 1)
	{
		$order_old = mysql_result($result, 0, "value");
	}
	else
	{
		$order_old = 'DaysD';
	}

	// read new sort order from url, if any
	$url_param = "order{$round->id}";
	$order_new = (isset($_GET[$url_param]) ? $_GET[$url_param] : $order_old );

	// if orders have changed, save them to database
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

	echo "\n<a name='{$round->id}'></a>";
	echo "\r\n<table border=1>";
	echo "\r\n<tr bgcolor='{$round->listing_bgcolors[1]}'>";

	$title = _('Projects Currently Available');
	echo "\n<td colspan=2><h3>$title</h3></td>";

	{
		echo "\n<td colspan=6>{$round->description}</td>";
		echo "</tr>";

		show_books_available_for_proofing( $round, $userP, $projects_filter, $order_new );
	}
	echo "</table>\n<br>";
}

// -----------------------------------------------------------------------------

function show_books_available_for_proofing( $round, $userP, $where_filter = " ", $order = "DaysD" )
{
	global $code_url;

	$flip_title = FALSE;
	$flip_author = FALSE;
	$flip_lang = FALSE;
	$flip_genre = FALSE;
	$flip_PM = FALSE;
	$flip_PgAv = FALSE;
	$flip_PgTot = FALSE;
	$flip_days = FALSE;
	
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
		$orderclause = 'username ASC, nameofwork ASC';
		$flip_PM = TRUE;
	}
	elseif ( $order == 'PMD' )
	{
		$orderclause = 'username DESC, nameofwork ASC';
	}
	elseif ( $order == 'PgAvA' )
	{
		$orderclause = 'n_available_pages ASC, nameofwork ASC';
		$flip_PgAv = TRUE;
	}
	elseif ( $order == 'PgAvD' )
	{
		$orderclause = 'n_available_pages DESC, nameofwork ASC';
	}
	elseif ( $order == 'PgTotA' )
	{
		$orderclause = 'n_pages ASC, nameofwork ASC';
		$flip_PgTot = TRUE;
	}
	elseif ( $order == 'PgTotD' )
	{
		$orderclause = 'n_pages DESC, nameofwork ASC';
	}

	// note that we SHOW "days since M", but *order* by M, so the logic is flipped

	elseif ( $order == 'DaysA' )
	{
		$orderclause = 'modifieddate DESC, nameofwork ASC';
		$flip_days = TRUE;
	}
	elseif ( $order == 'DaysD' )
	{
		$orderclause = 'modifieddate ASC, nameofwork ASC';
	}
	else
	{
		echo "showavailablebooks.inc: bad order value: '$order'";
		exit;
	}

	$order_param = "order{$round->id}";

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
	$linkend = "#{$round->id}'";

	// Always put the BEGIN projects at the top.
	$query = "
		SELECT *,
			(difficulty = 'beginner') AS is_beginner,
			round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) AS days_avail
		FROM projects
		WHERE
		       	(state = '{$round->project_available_state}')
			$where_filter
		ORDER BY
			is_beginner DESC,
			$orderclause
	";
	$result = mysql_query($query);

	echo "<tr align=center bgcolor='{$round->listing_bgcolors[1]}'>";

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

	$word = _("Available Pages");
 	$link = $linkbase.($flip_PgAv?"PgAvD":"PgAvA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Total Pages");
 	$link = $linkbase.($flip_PgTot?"PgTotD":"PgTotA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Days");
 	$link = $linkbase.($flip_days?"DaysD":"DaysA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	echo "</tr>";

	// Determine whether to use special colors or not
	// (this does not affect the alternating between two
	// background colors) in the project listing.
	// Regardless of the preference, don't display
	// special colors to newbies.
	global $pguser;
	$userSettings =& Settings::get_Settings($pguser);
	$show_special_colors = (get_pages_proofed_maybe_simulated() >= 10
                                && !$userSettings->get_boolean('hide_special_colors'));

	$numrows = mysql_num_rows($result);
	$rownum = 0;
	$rownum2 = 0;

	while ($rownum2 < $numrows) {
		$book=mysql_fetch_assoc($result);
		$bgcolor = $round->listing_bgcolors[$rownum % 2];

		// Special colours for special books of various types
		if ($show_special_colors)
		{
			$special_color = get_special_color_for_project($book);
			if (!is_null($special_color))
			{
				$bgcolor = $special_color;
			}
		}

		if (TRUE)
		{
			echo "<tr bgcolor='$bgcolor'>";
			$eURL = "$code_url/project.php?id={$book['projectid']}&amp;expected_state={$book['state']}";
			if ($userP['i_newwin']==0)
				{$temp="<a href=\"$eURL\">";}
			else
				{$temp="<a href=\"$eURL\" onclick=\"newProofWin('$eURL'); return false;\">";}
			$temp.="{$book['nameofwork']}</a>";
			echo "\n<td>$temp</td>";
			echo "\n<td>{$book['authorsname']}</td>";
			echo "\n<td>{$book['language']}</td>";
			if ($book['difficulty'] == "beginner")
			{
				if ( $round->is_a_mentee_round() )
					$genre = _("BEGINNERS ONLY")." ".$book['genre'];
				else if ( $round->is_a_mentor_round() )
					$genre = _("MENTORS ONLY")." ".$book['genre'];
				else
					$genre = $book['genre'];
			}
			elseif ($book['difficulty'] == "easy")
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
			echo "\n<td>$genre</td>";
			echo "\n<td>{$book['username']}</td>";

			echo "\n<td align=center>{$book['n_available_pages']}</td>";
			echo "\n<td align=center>{$book['n_pages']}</td>";

			echo "\n<td align=center>{$book['days_avail']}</td>";

		}
		else
		{
			$rownum--;
		}
		$rownum++;
		$rownum2++;
	}

}

?>