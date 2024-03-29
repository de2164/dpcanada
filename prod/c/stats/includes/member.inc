<?
//A file full of member statistic functions

include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'page_tally.inc');
include_once($relPath.'ThemedTable.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'access_log.inc');
include_once($relPath.'../stats/includes/common.inc');

function showMbrInformation( $curMbr, $tally_name )
{
	showXmlButton($curMbr['username']);
	showMbrProfile($curMbr);

	showMbrTallySelector( $curMbr['u_id'], $tally_name );

	if ( is_null($tally_name) )
	{
		showMbrAccess($curMbr);
	}
	else
	{
		showMbrPageStats($curMbr, $tally_name);
		showMbrTeams($curMbr, $tally_name);
		showMbrNeighbors($curMbr, $tally_name);
		$now = time();
		if (($now - $curMbr['date_created']) > 86400)
		{
			showMbrHistory($curMbr, $tally_name);
		}
	}
}

function showXmlButton( $username )
{
	global $code_url;

	$xml_doc_url = "$code_url/stats/members/mbr_xml.php?username=$username";
	$xml_img_url = "$code_url/graphics/xml.gif";
	$xml_img_tag = "<img src='$xml_img_url' border='0' width='36' height='14' style='vertical-align:middle'>";
	$xml_button = "<a href='$xml_doc_url'>$xml_img_tag</a>";
	echo "\n";
	echo $xml_button;
}

function showMbrProfile($curMbr) {

	echo "\n";
	echo "<table width='99%'>";
	echo "\n";
	echo "<tr>";
	echo "<td align='center' valign='top'>";

	showForumProfile($curMbr['username']);

	echo "</td>";

	echo "<td align='center' valign='top'>";

	showMbrDpProfile($curMbr);

	echo "</td></tr></table><p>";
}

function showMbrDpProfile( $curMbr )
{
	$now = time();
	$daysInExistenceInteger = floor(($now - $curMbr['date_created'])/86400);
	$daysInExistenceString = number_format($daysInExistenceInteger)._(" days"); // '1,234 days'
	$daysSinceLastSeen = number_format(floor(($now - $curMbr['t_last_activity'])/86400));
	if (empty($daysSinceLastSeen)) { $daysSinceLastSeen = _("Today!"); } else { $daysSinceLastSeen = $daysSinceLastSeen._(" days"); }

//MS
	if ($daysSinceLastSeen <= 0) { $daysSinceLastSeen = _("Today!"); }
	
	$t = new ThemedTable(
		2,
		_("DP Profile"),
		array( 'width'=>100 )
	);
	$t->set_column_widths( 30, 70 );

	$t->row(
		_("Date Joined"),
		date("m/d/Y", $curMbr['date_created']) . "&nbsp;($daysInExistenceString)"
	);
	$t->row(
		_("Last Seen"),
		date("m/d/Y", $curMbr['t_last_activity']) . "&nbsp;($daysSinceLastSeen)"
	);

	global $users_ELR_page_tallyboard;
	$current_P_page_tally = $users_ELR_page_tallyboard->get_current_tally( $curMbr['u_id'] );
	$facilitator = @mysql_result(mysql_query("SELECT * FROM `usersettings`
		WHERE `setting` ='proj_facilitator'
			AND `username` = '".$curMbr['username']."'"),0,"value");
	$ppver = @mysql_result(mysql_query("SELECT * FROM `usersettings`
		WHERE `setting` ='PPV.access'
			AND `username` = '".$curMbr['username']."'"),0,"value");
	$t->row(
		_('Roles'),
		showMbrRoles($curMbr['sitemanager'], $curMbr['manager'], $facilitator, $curMbr['postprocessor'], $ppver, $current_P_page_tally)
	);

	$t->end();
}

function showMbrRoles($siteadmin, $manager, $proj_facilitator, $postprocessor, $pp_verifier, $pagescompleted) {
	global $code_url;
	$mbrStatus = "";
	if ($pagescompleted < 50) { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_newbie.png' border='0' alt='"._("Newbie")."' title='"._("Newbie")."' width='25' height='18'>&nbsp;"; }
	if ($pagescompleted >= 50) { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_proofer.png' border='0' alt='"._("Proofer")."' title='"._("Proofer")."' width='11' height='25'>&nbsp;"; }
	// Formatter
	if ($manager == "yes") { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_pm.png' border='0' alt='"._("Project Manager")."' title='"._("Project Manager")."' width='50' height='25'>&nbsp;"; }
	if ($postprocessor == "yes") { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_pp.png' border='0' alt='"._("Post-Processor")."' title='"._("Post-Processor")."' width='25' height='22'>&nbsp;"; }
	if ($pp_verifier == "yes") { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_ppv.png' border='0' alt='"._("Post-Processing Verifier")."' title='"._("Post-Processing Verifier")."' width='24' height='25'>&nbsp;"; }
	if ($proj_facilitator == "yes") { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_pf.png' border='0' alt='"._("Project Facilitator")."' title='"._("Project Facilitator")."' width='46' height='25'>&nbsp;"; }
	if ($siteadmin == "yes") { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_sa.png' border='0' alt='"._("Site Administrator")."' title='"._("Site Administrator")."' width='25' height='21'>&nbsp;"; }

	$mbrStatus .= "&nbsp;";

	return $mbrStatus;
}

function showForumProfile($username) {
	global $forums_url, $theme;

	$result = mysql_query("
		SELECT *
		FROM phpbb_users
		WHERE username = '$username'
	");
	$bb_user = mysql_fetch_object($result);

        if (isset($GLOBALS['pguser']) && $bb_user->username == $GLOBALS['pguser'])
	{
		$edit_url = "$forums_url/profile.php?mode=editprofile&u=$bb_user->user_id";
		$text = _('Edit');
		$colored_text = "<font color='".$theme['color_headerbar_font']."' size='2'>$text</font>";
		$editlink = "&nbsp;[<a href='$edit_url'>$colored_text</a>]";
        }
	else
	{
		$editlink = '';
	}

	$t = new ThemedTable(
		2,
		_("Forum Profile") . $editlink,
		array( 'width'=>100 )
	);

	if (empty($bb_user->user_avatar))
	{
		$rhs = '(none)';
	}
	else
	{
		$avatar = $GLOBALS['forums_url']."/images/avatars/$bb_user->user_avatar";
		$rhs = "<img border='0' src='$avatar' alt='avatar'>";
	}
	$t->row(
		_("Avatar"),
		$rhs
	);

	if (!empty($bb_user->user_from) || !empty($bb_user->user_occ) || !empty($bb_user->user_interests) || !empty($bb_user->user_website)) { $t->hr( 90 ); }
        if (!empty($bb_user->user_from))      { $t->row( _("Location"),   $bb_user->user_from ); }
        if (!empty($bb_user->user_occ))       { $t->row( _("Occupation"), $bb_user->user_occ ); }
	if (!empty($bb_user->user_interests)) { $t->row( _("Interests"),  $bb_user->user_interests ); }
        if (!empty($bb_user->user_website))   { $t->row( _("Website"),    "<a href='$bb_user->user_website'>".$bb_user->user_website."</a>" ); }

	$t->hr( 90 );

	$contact_data = array(
		array(
			!empty($bb_user->user_viewemail),
			_("E-mail"),
			"icon_email.gif",
			"mailto:$bb_user->user_email",
			''
		),
		array(
			TRUE,
			_("Private Message"),
			"icon_pm.gif",
			"$forums_url/privmsg.php?mode=post&u=$bb_user->user_id",
			''
		),
		array(
			!empty($bb_user->user_aim),
			"AIM",
			"icon_aim.gif",
			"aim:goim?screenname={$bb_user->user_aim}&message=Hello!+Are+you+there?++I+am+IMing+you+from+DP",
			''
		),
		array(
			!empty($bb_user->user_msnm),
			"MSN",
			"icon_msnm.gif",
			"javascript:alert(\"$bb_user->user_msnm\")",
			''
		),
		array(
			!empty($bb_user->user_yim),
			"Yahoo Message",
			"icon_yim.gif",
			"http://edit.yahoo.com/config/send_webmesg?.target={$bb_user->user_yim}&.src=pg",
			"_new"
		),
		array(
			!empty($bb_user->user_icq),
			"ICQ",
			"icon_icq.gif",
			"http://wwp.icq.com/scripts/search.dll?to=$bb_user->user_icq",
		       	"_new"
		),
	);

	foreach ( $contact_data as $contact )
	{
		list( $condition, $label, $icon_filename, $url, $target ) = $contact;

		if (!$condition) continue;
		
		$icon_path = "$forums_url/templates/subSilver/images/lang_english/$icon_filename";
		$img_tag = "<img src='$icon_path' alt='$label $bb_user->username' border='0'>";
		$target_str = ( empty($target) ? '' : " target='$target'" );

		$t->row(
			$label,
			"<a href='$url'$target_str>$img_tag</a>"
		);
	}

	$t->end();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showMbrAccess( $curMbr )
{
	list($can_grant,$can_revoke) = user_can_modify_access();
	if ( !$can_grant ) return;

	echo "<i>(";
	echo _('This chart is visible only to those who can grant access.');
	echo ")</i><br>\n";

	show_user_access_chart( $curMbr['username'] );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showMbrTallySelector( $u_id, $tally_name )
{
	global $page_tally_names;

	$choices = '';
	foreach ( $page_tally_names as $t_name => $t_title )
	{
		if (!empty($choices))
		{
			$choices .= " | ";
		}

		$text = $t_name;
		if ($t_name != $tally_name)
		{
			$text = "<a href='mdetail.php?id=$u_id&amp;tally_name=$t_name'>$text</a>";
		}
		$choices .= $text;
	}
	echo "\n";
	echo "<center>";
	echo "<p>";
	echo _("Display user stats for different rounds:") . ' ' . $choices;
	echo "</p>";
	echo "</center>";

	echo "\n";
	echo "<hr width='95%'>";

	if ( is_null($tally_name) ) return;

	$tally_title = $page_tally_names[$tally_name];
	echo "\n";
	echo "<center>";
	echo "<p>";
	echo $tally_title;
	echo "</p>";
	echo "</center>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showMbrPageStats( $curMbr, $tally_name )
{
	$users_tallyboard = new TallyBoard( $tally_name, 'U' );

	$vitals = $users_tallyboard->get_vitals( $curMbr['u_id'] );

	$now = time();
  
  // There are two special cases needed here:
  // Firstly, the R* tally should span the time period from date_created to the site R* apocalypse
  // Secondly, the P1 tally should start from the site P genesis, or date_created, whichever is later.

  $site_R_apocalypse = 1117576800;
  $site_P_genesis = 1117720800;

	if ($tally_name == "R*")
	{
	  $firstWorkInRound = $curMbr['date_created'];
	  $daysInRoundInteger = floor(($site_R_apocalypse - $firstWorkInRound)/86400);
  }
  elseif ($tally_name == "P1")
  {
   $firstWorkInRound = ($curMbr['date_created'] > $site_P_genesis) ? $curMbr['date_created'] : $site_P_genesis;
  	$daysInRoundInteger = floor(($now - $firstWorkInRound)/86400);
  }
  else
  {
    $firstWorkInRound = get_first_granted_date($curMbr['username'],$tally_name);
  	$daysInRoundInteger = floor(($now - $firstWorkInRound)/86400);
  }

	$daysInRoundString = number_format($daysInRoundInteger)._(" days"); // '1,234 days'

	$bestDayTime = date("M. jS, Y", ($vitals->best_delta_timestamp-1));

	if ($daysInRoundInteger > 0) {
	        $daily_Average = $vitals->current_tally/$daysInRoundInteger;
	} else {
		$daily_Average = 0;
	}

	$t = new ThemedTable(
		2,
		_("Page Statistics"),
		array( 'width'=>100 )
	);

		if ($firstWorkInRound != 0)
		{
		  $round_row = date("m/d/Y", $firstWorkInRound) . "&nbsp;($daysInRoundString)";
    }
    else
    {
      $round_row = '--';
    }

  $t->row(
		_("This Round"),
    $round_row
	);
	$t->row(
		_("Total Pages"),
		number_format($vitals->current_tally) . " " . _("pages")
	);
	$t->row(
		_("Overall Rank"),
		number_format($vitals->current_rank)
	);
	$t->row(
		_("Highest Rank"),
		number_format($vitals->best_rank)
		. "&nbsp;<i>(" . date("M. jS, Y", $vitals->best_rank_timestamp-1) . ")</i>"
		. "&nbsp;" . showChangeInRank($vitals->best_rank, $vitals->current_rank)
	);
	$t->row(
		_("Best Day Ever"),
		number_format($vitals->best_delta) . " " . _("pages")
		. " <i>(" . $bestDayTime . ")</i>"
	);
	$t->row(
		_("Daily Average"),
		number_format($daily_Average) . " " . _("pages")
	);

	$t->end();
}

function showMbrNeighbors($curMbr, $tally_name) {
	$now = time();

	$t = new ThemedTable(
		4,
		_("Neighbors"),
		array( 'width'=>99, 'rollover_highlight'=>TRUE )
	);
	$t->set_column_widths( 15, 28, 28, 29 );
	$t->column_headers(
		_("Rank"),
		_("Username"),
		_("Date Joined DP"),
		_("Overall Pages Completed")
	);

	$neighbors =
		user_get_page_tally_neighborhood(
			$tally_name, $curMbr['username'], 4 );
	foreach ( $neighbors as $rel_posn => $neighbor )
	{
		$rank = $neighbor->get_current_page_tally_rank();
		$pagescompleted = number_format($neighbor->get_current_page_tally());

		if (!$neighbor->is_anonymized()) {

			$u_id = $neighbor->get_u_id();
			$username = $neighbor->get_username();
			$date_created = $neighbor->get_date_joined();

			$username_html = "<a href='".$GLOBALS['code_url']."/stats/members/mdetail.php?id=$u_id&tally_name=$tally_name'>$username</a>";
			$day_html = date("m/d/Y", $date_created)." <i>(".number_format(floor(($now - $date_created)/86400))." "._("days").")</i>";

		} else {
			$username_html = "Anonymous";
			$day_html = "--/--/-- <i>(--- "._("days").")</i>";
		}
		$t->row(
			$rank,
			$username_html,
			$day_html,
			$pagescompleted
		);
	}

	$t->end();
}

function showMbrTeams($curMbr, $tally_name) {

	if (empty($curMbr['team_1']) && empty($curMbr['team_2']) && empty($curMbr['team_3'])) { return; }

	$t = new ThemedTable(
		3,
		_("Teams"),
		array( 'width'=>99, 'rollover_highlight'=>TRUE )
	);
	$t->set_column_widths( 40, 30, 30 );
	$t->column_headers(
		_("Team Name"),
		_("Pages Completed"),
		_("Active Members")
	);

	$teams_tallyboard = new TallyBoard( $tally_name, 'T' );

	list($joined_with_team_tallies,$team_tally_column) =
		$teams_tallyboard->get_sql_joinery_for_current_tallies('id');

	$result = mysql_query("
		SELECT user_teams.*, $team_tally_column AS page_count
		FROM user_teams $joined_with_team_tallies
		WHERE id IN ({$curMbr['team_1']}, {$curMbr['team_2']}, {$curMbr['team_3']})
	") or die(mysql_error());

	while ($row = mysql_fetch_assoc($result)) {
		$url = $GLOBALS['code_url']."/stats/teams/tdetail.php?tid=".$row['id'];
		$t->row(
			"<a href='$url'>".$row['teamname']."</a>",
			number_format($row['page_count'])." "._("pages"),
			number_format($row['active_members'])." "._("members")
		);
	}

	$t->end();
}

function showMbrHistory($curMbr, $tally_name) {

	if (empty($_GET['range'])) { $range = 30; } else { $range = $_GET['range']; }

	$choices = array();
	$u_id = $curMbr['u_id'];
	foreach( array( 7, 14, 30, 60, 365, 'all' ) as $d )
	{
		$text =
			($d == 'all')
			? _('Lifetime')
			: sprintf( _('Last %d Days'), $d );
		$choices[] = "<a href='mdetail.php?id=$u_id&tally_name=$tally_name&range=$d'>$text</a>";
	}
	$choices_str = join( $choices, ' | ' );

	$image_url = $GLOBALS['code_url']."/stats/jpgraph_files/tallyboard_deltas.php?tally_name=$tally_name&holder_type=U&holder_id=$u_id&days_back=$range";

	$t = new ThemedTable(
		1,
		_("History"),
		array( 'width'=>99 )
	);
	$t->row( "<center><br><p><font size='2'>$choices_str</font><p><img src='$image_url' width='600' height='300'></center><br>" );
	$t->end();
}

?>
