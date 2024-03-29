<?
$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'pg.inc');
include_once($relPath.'dpsession.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.inc');

$user_is_logged_in = dpsession_resume();

include_once($relPath.'gettext_setup.inc');
include_once($relPath.'languages.inc');

if(isset($userP['i_theme']) && $userP['i_theme'] != '' ) {
		$theme_name = $userP['i_theme'];
	} else {
		$theme_name = "project_gutenberg";
}
include_once($relPath."templates/$theme_name/theme.tpl");
$theme = ${$theme_name};

function theme ($nameofpage, $location, $extra_args = array()) {

	global $code_url, $theme, $theme_name;

	if (isset($GLOBALS['pguser'])) {
		$userP = $GLOBALS['userP'];
		$statsbar_align = $userP['u_align'];
		} else {
		$statsbar_align = 0;
		$userP = "";
	}

	if (!isset($GLOBALS['no_stats'])) {
		if ($statsbar_align == 1) {
			if ($location == "header") {
				html_header($nameofpage, $extra_args);
				html_logobar($nameofpage);
				echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
				echo "<tr>\n";
				echo "<td align='left' valign='top' width='25%' bgcolor='".$theme['color_navbar_bg']."'>\n";
				html_statsbar($nameofpage);
				echo "</td>\n";
				echo "<td align='right' valign='top' bgcolor='".$theme['color_mainbody_bg']."'><img border='0' src='$code_url/pinc/templates/".$theme_name."".$theme['image_curve_left']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>\n";
				echo "<td align='left' valign='top' width='75%' bgcolor='".$theme['color_mainbody_bg']."'>\n";
			} elseif ($location == "footer") {
				echo "</td></tr></table>\n";
				html_footer();
			}
		} else {
			if ($location == "header") {
				html_header($nameofpage, $extra_args);
				html_logobar($nameofpage);
				echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
				echo "<tr>\n";
				echo "<td align='left' valign='top' width='5' bgcolor='".$theme['color_mainbody_bg']."'>&nbsp;</td>\n";
				echo "<td align='left' valign='top' width='75%' bgcolor='".$theme['color_mainbody_bg']."'>\n";
			} elseif ($location == "footer") {
				echo "</td>\n";
				echo "<td align='right' valign='top' bgcolor='" . $theme['color_mainbody_bg'] . "'><img border='0' src='$code_url/pinc/templates/".$theme_name."".$theme['image_curve_right']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>\n";
				echo "<td align='left' valign='top' width='25%' bgcolor='" . $theme['color_navbar_bg'] . "'>\n";
				html_statsbar($nameofpage);
				echo "</td></tr></table>\n";
				html_footer();
			}
		}
	} else {
		if ($location == "header") {
			html_header($nameofpage, $extra_args);
			html_logobar($nameofpage);
			echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
			echo "<tr>\n";
			echo "<td align='left' valign='top' width='5' bgcolor='".$theme['color_mainbody_bg']."'>&nbsp;</td>\n";
			echo "<td align='left' valign='top' width='100%' bgcolor='".$theme['color_mainbody_bg']."'>\n";
		} elseif ($location == "footer") {
			echo "</td></tr></table>\n";
			html_footer();
		}
	}
}

function html_header($nameofpage, $extra_args = array()) {
	global $code_url, $theme, $userP;
	global $relPath;
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
	echo "<html".lang_html_header().">\n<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset={$GLOBALS['charset']}\">\n";
	echo "<link rel='shortcut icon' href='$code_url/favicon.ico'>\n";
	echo "<title>DPC";
      if (isset($nameofpage)) { echo ": $nameofpage"; }
        echo "</title>\n";
	// Global theme CSS
	echo "\n<link type='text/css' rel='Stylesheet' href='";
	echo $relPath . 'templates/' . $theme['template_unix_name'] . "/main.css'>\n";
	// Per-page CSS
	if (isset($extra_args['css_data']))
      {
	    echo "<style type='text/css'>\n" .
	          $extra_args['css_data'] .
	          "</style>\n";
      }
 
 	// Per-page Javascript
	if (isset($extra_args['js_data']))
      {
	    echo "<script type='text/javascript'>\n" .
	          $extra_args['js_data'] .
	          "</script>\n";
      }

	echo "</head>\n\n";
	echo "<body bgcolor='" . $theme['body_bgcolor'] . "' text='" . $theme['body_text'];
	echo "' link='" . $theme['body_link'] . "' vlink='" . $theme['body_vlink'];
	echo "' alink='" . $theme['body_alink'];
	echo "' topmargin='0' leftmargin='0' marginheight='0' marginwidth='0'>\n\n";
}

function html_logobar($nameofpage) {
	global $code_url, $theme, $theme_name;
	global $dynstats_dir, $maintenance;
	echo "\n<table width='100%' cellSpacing=0 cellPadding=0 border=0>\n";
	echo "<tr>\n";
	echo "<td width='50%' bgcolor='".$theme['color_logobar_bg']."'>\n";
	echo "<a href='$code_url'><img src='$code_url/pinc/templates/".$theme_name."".$theme['image_logo']."' width='336' height='68' alt='Distributed Proofreaders' border='0'></a>\n";
	echo "</td>\n";

	echo "<td width='50%' bgcolor='" . $theme['color_logobar_bg'] .  "' align='center' valign='middle'>\n";

	if ($maintenance) {
		echo _("MAINTENANCE MODE");
		echo "<br>\n";
	}

	$psd = get_project_status_descriptor('posted');

	if (0) {
		// show number of projects posted to PG from DP
		$result = mysql_query( "
			SELECT COUNT(*)
			FROM projects
			WHERE $psd->state_selector
		");
		$numproj = mysql_result($result,0);
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='3'><b>";
		echo sprintf(
			_('%s projects completed!'),
			number_format($numproj));
		echo "</b></font>\n";
		echo "<br>";
	}

	if (1) {
		// show number of books posted to PG from DP
		// (takes into account that some books are processed as several projects)

		$result = mysql_query( "
			SELECT COUNT(distinct postednum)
			FROM projects
			WHERE $psd->state_selector
		");
		$numproj = mysql_result($result,0);
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='3'><b>";
		echo sprintf(
			_('%s titles preserved for the world!'),
			number_format($numproj));
		echo "</b></font>\n";
	}

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_headerbar_bg']."'>\n";
	echo "<tr>\n";
	echo "<td width='10' align='left' bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td>\n";
	echo "<td align='left' style='padding-bottom:1px;' valign='middle' bgcolor='".$theme['color_headerbar_bg']."'>\n";

	//Create the name of the page

	echo headerbar_text("DP","$code_url/default.php");

	// This probably isn't useful enough to justify its presence.
	// if (!empty($nameofpage)) { echo headerbar_text(" � ".$nameofpage,'')."\n"; }

	//Code to display login form if not logged in
	if (!isset($GLOBALS['pguser'])) {
		echo "</td>\n";
		// If you put the form element inside the td element (which would
		// seem to make sense), Netscape and IE both render the cell
		// (and thus the row and the table) twice as tall as it needs to be.
		// (Mozilla gets it right, but that's small comfort.)
		// So instead, put the td element within the form.
		// (Yeah, it doesn't make sense, but the browsers don't complain.)
		echo "<form action='$code_url/accounts/login.php' method='post'>\n";
		echo "<td align='right' bgcolor='".$theme['color_headerbar_bg']."'>\n";
		echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>";
		echo _("ID:") . "<input type='text' name='userNM' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;";
		echo _("Password:") . "<input type='password' name='userPW' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;";
		echo "<input type='submit' value='" . _("Sign In") ."' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>";
		echo headerbar_text(_("Register"), "$code_url/accounts/addproofer.php", true);
		echo headerbar_text(_("Help"), "http://www.pgdpcanada.net/wiki/index.php/FAQ_Central", true);
		echo "&nbsp;";
		echo "</font>\n";
		echo "</td>\n";
		echo "</form>\n";
	} else {
		//Code to display if the user is logged in
		$result = mysql_query("SELECT user_id FROM phpbb_users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
		$pguser_id = mysql_result($result, 0, "user_id");
		$result = mysql_query("SELECT COUNT(*) FROM phpbb_privmsgs WHERE privmsgs_to_userid = $pguser_id && privmsgs_type = 1 || privmsgs_to_userid = $pguser_id && privmsgs_type = 5");
		$numofPMs = mysql_fetch_row($result);
		$result = mysql_query("SELECT date_created FROM users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
		$datecreated = mysql_result($result, 0, "date_created");
		echo headerbar_text("|&nbsp;");
		if ((time() - $datecreated) < 604800)
			echo headerbar_text(" Start Here -->&nbsp;",'',true);
		echo headerbar_text(_("Help"),"http://www.pgdpcanada.net/wiki/index.php/FAQ_Central",false);
		echo headerbar_text(_("Activity Hub"),"$code_url/activity_hub.php",true);
		echo headerbar_text(_("Project Search"),"$code_url/tools/project_manager/projectmgr.php?show=search_form",true);

		echo headerbar_text(_("My Projects"),"$code_url/tools/proofers/my_projects.php",true);

		echo headerbar_text(_("My Preferences"),"$code_url/userprefs.php",true);

		$inbox_text = _("My Inbox");
		if($numofPMs[0] > 0) $inbox_text .= sprintf(_(" (%s unread)"),$numofPMs[0]);
		echo headerbar_text($inbox_text,$GLOBALS['forums_url']."/privmsg.php?folder=inbox",true);

		echo headerbar_text(_("Forums"),$GLOBALS['forums_url'],true);
		if(lang_forum())
		{
			echo headerbar_text(_("Your language forums"),$GLOBALS['forums_url']."/".lang_forum(),true);
		}

		if ( !empty($GLOBALS['wiki_url']) )
		{
			echo headerbar_text(_("Wiki"),$GLOBALS['wiki_url'],true);
		}
		echo headerbar_text(_("Log Out ($GLOBALS[pguser])"),"$code_url/tools/logout.php",true);
		echo "</font></td>\n";
		echo "<td align='right' style='vertical-align: middle;' bgcolor='".$theme['color_headerbar_bg']."'>\n";
		show_quick_links();
		echo "&nbsp;</td>";
	}

	echo "</tr>\n";
	echo "</table>\n";
}

function headerbar_text($text, $link = '', $with_divider = false)
{
global $theme, $code_url;
$out = "";

if ($with_divider)
    $out .= "&nbsp;&#183;&nbsp;\n";

if(!empty($link))
    $out .= "<a href='$link'>\n";

$out .= "<font face='$theme[font_headerbar]' size='1' color='$theme[color_headerbar_font]'>"
        . $text .
		    "</font>\n";

if(!empty($link))
    $out .= "</a>\n";

return $out;
}

function html_statsbar($nameofpage) {

	maybe_show_language_selector();

	// Show statistics that are pertinent to the particular request.
	// (i.e., to the main content of the page).

	$tally_name = @$_REQUEST['tally_name'];
	if (empty($tally_name)) $tally_name = @$_REQUEST['round_id'];

	if (empty($tally_name))
	{
	    if (strpos($_SERVER['PHP_SELF'],'activity_hub.php') !== false)
	    {
            echo "<br />\n";
            show_dp_birthdays();

        }
		echo "<br>\n";
		show_completed_projects();
	}
	else
	{
		show_tally_specific_stats( $tally_name );
	}

	global $code_url;
	echo "<br>";
	echo "<center>";
	echo "<font size='2'>";
	echo "<a href='$code_url/stats/stats_central.php'>";
	echo _("More Statistics...");
	echo "</a>";
	echo "</font>";
	echo "</center>\n";

	if (isset($GLOBALS['pguser']))
	{
		// requestor is a logged-in user.

		echo "<br><hr width='75%' align='center'><br>\n";
		show_user_teams();

		echo "<br><hr width='75%' align='center'><br>\n";
		show_key_help_links();
	}


    //Display the donation icon and link to donate.
    //Should be the last thing displayed in the right hand column
	echo "<br>";
	show_donation_stuff();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function maybe_show_language_selector()
{
	global $code_url, $userP, $charset;
	$instl=installed_langs();
	$uninstl=uninstalled_langs();
	if(!(count($instl)>1||count($uninstl)>0))
		return;

	if (empty($userP) || (!(empty($userP)) && !($userP['u_intlang'])))
	{
		?>
		<script language="JavaScript" type="text/javascript"><!--
			function submitLang(i) {
				top.document.langform.submit();
			}
		// --></script>
		<?

		echo "<form name=langform action='$code_url/tools/setlangcookie.php' method=POST>\n<input type=submit value='"._("Set language:")."'>\n<select name=lang onChange='submitLang(this)'>\n";
		foreach($instl as $v)
			echo "<option value='".$v."'".(($v==$GLOBALS['intlang'])?" selected":"").">".bilingual_name($v)."</option>\n";
		if(count($uninstl)>0) {
			echo "<option value='en_EN' disabled>-------------------</option>\n";
			echo "<option value='en_EN' disabled>(To be translated:)</option>\n";
			foreach($uninstl as $v)
				echo "<option value='"."en_EN"."' disabled>".bilingual_name($v)."</option>\n";
		}
		echo "</select><input type=hidden name=returnto value='".$_SERVER['REQUEST_URI']."'>\n</form>\n";
		echo "Languages below the line are planned to be added to the site, but currently are not; visit <a href='$code_url/faq/translate.php'>this page</a> if you can help us with translating the site into one of them.<hr>\n";
	}
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_quick_links()
// This parallels the Activity Hub.
{
	global $site_supports_metadata, $code_url, $theme, $Stage_for_id_, $pguser;

	if (is_null($pguser)) return;
	// Should show links to stages that are accessible to people
	// that aren't logged in? (i.e., SR)?

	$links = array();

	if (user_is_PM())
	{
		$links[] = array('tools/project_manager/projectmgr.php','PM',_('Manage Projects'));
	}

	foreach ( $Stage_for_id_ as $stage )
	{
		$uao = $stage->user_access($pguser);
		if ($uao->can_access)
		{
			$links[] = array($stage->relative_url, $stage->id, $stage->name);
		}
	}

	if ( count($links) > 0 )
	{

		while ( list($i, list($rel_url, $text, $title)) = each($links) )
		{

			$divider = ($i > 0) ? true : false;

			$rel_url_pattern = '@/' . preg_quote($rel_url, '@') . '\b@';
			if ( preg_match( $rel_url_pattern, $_SERVER['REQUEST_URI'] ) )
			{
				// We're at that page; no point to link to it.
				echo headerbar_text("<span title='$title'>$text</span>",'',$divider);
			}
			else
			{
				echo headerbar_text("<span title='$title'>$text</span>","$code_url/$rel_url",$divider);
			}
			echo "\n";
		}
	}
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_tally_specific_stats( $tally_name )
{
	global $code_url, $theme, $userP;

	// Put the whole thing in a table, just so we can put a box around it.
	echo "<br>";
	echo "<center>";
	echo "<table width='96%' border='1' bordercolor='#111111' style='border-collapse: collapse'>";
	echo "<tr>";
	echo "<td>";

	// Show the site statistics

	$site_stats = get_site_page_tally_summary($tally_name);

	global $page_tally_names;
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";
	echo "<b>" . $page_tally_names[$tally_name] . "</b><br><br>\n";
	echo "<b>" . _("Site Statistics:") . "</b></font></center>\n";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";


	// Today's Numbers
	echo "&nbsp;"._("Today's Goal:")." "
		. number_format($site_stats->curr_day_goal)
		. "<br>\n";
	echo "&nbsp;"._("Today's Total:")." "
		. number_format($site_stats->curr_day_actual)
		. " (" . strftime("%R") . ")<br><br>\n";

	// Yesterday's Numbers
	echo "&nbsp;"._("Yesterday's Goal:")." "
		. number_format($site_stats->prev_day_goal)
		. "<br>\n";
	echo "&nbsp;"._("Yesterday's Total:")." "
		. number_format($site_stats->prev_day_actual)
		. "<br><br>\n";

	// Month's Numbers
	echo "&nbsp;".strftime(_("%B's Goal:"))." "
		. number_format($site_stats->curr_month_goal)
		. ($site_stats->curr_month_goal
			? (" (" . number_format(($site_stats->curr_month_actual/$site_stats->curr_month_goal)*100, 2) . "%)")
			: ""
		)
		. "<br>\n";
	echo "&nbsp;".strftime(_("%B's Total:"))." "
		. number_format($site_stats->curr_month_actual)
		. "<br>\n";
	echo "<br>\n";

	// Number of users
	$res = mysql_query("SELECT COUNT(*) FROM users" );
	list($num_users) = mysql_fetch_row($res);
	echo '&nbsp;', sprintf( _('%s users'), number_format($num_users) ), "<br>\n";

	$tallyboard = new TallyBoard( $tally_name, 'U' );
	$num_positive_users = $tallyboard->get_num_holders_with_positive_tally();
	echo '&nbsp;',
		sprintf(
			_('%s users with at least one %s page'),
			number_format($num_positive_users), $tally_name ),
		"<br>\n";

	echo "</font><br><hr width='75%' align='center'><br>\n";
	// ---------------------------------------------------------------------

	if (isset($GLOBALS['pguser']))
	{
		// The requestor is a logged-in user.
		// Show the user's personal statistics

		//Get the personal statistics array
		$neighbors =
			user_get_page_tally_neighborhood(
				$tally_name, $GLOBALS['pguser'], $userP['u_neigh']);
		$usern = $neighbors[0];

		//get rank
		global $testing;
		if ($testing)
		{
			// Don't let people discover the honorifics by
			// playing with inflated pagecounts at test-site.
			$rankname = 'Valued Tester';
		}
		else
		{
			$round = get_Round_for_round_id( $tally_name );
			if ($round)
			{
				$rankname = $round->get_honorific_for_page_tally(
					$usern->get_current_page_tally() );
			}
			else
			{
				$rankname = '[unknown]';
			}
		}

		// Get userid for personal stats link
		$result = mysql_query("SELECT u_id FROM users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
		$u_id = mysql_result($result, 0, "u_id");

		echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><b>" . _("Personal Statistics:") . "</b></font></center>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;" . _("Total Pages: ");
		echo number_format($usern->get_current_page_tally())."<br>\n";
		echo "&nbsp;" . _("Current Position: ");
		echo number_format($usern->get_current_page_tally_rank())."<br>\n";
		echo "&nbsp;" . _("Current Rank: ");
		echo "$rankname<br></font>\n";
		echo "<center><font size='2'><a href='$code_url/stats/members/mdetail.php?id=".$u_id."&tally_name=".$tally_name."'>" . $tally_name . " " .  _("Details...") . "</a></font></center>\n";
		echo "<" . "!-- " . $userP['u_id'] . " -->";

		// -------------------------------------------------------------
		//Show the Neighbor table if the user has requested so
		if ($userP['u_neigh']) {
			echo "<br><hr width='75%' align='center'><br>\n";
			echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><b>Your Neighborhood:</b></font></center>\n";
			echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";

			foreach ( $neighbors as $rel_posn => $neighbor )
			{
				echo "&nbsp;";
				if ($rel_posn==0) echo "<b><i>";
				echo $neighbor->get_current_page_tally_rank(), ".) ";
				echo ( $neighbor->is_anonymized() ? _('Anonymous') : $neighbor->get_username() ), " - ";
				echo number_format($neighbor->get_current_page_tally());
				if ($rel_posn==0) echo "</i></b>";
				echo "<br>\n";
			}
		}
	}

	echo "<br>";
	echo "</td></tr></table>";
	echo "</center>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_user_teams()
{
	global $theme, $code_url, $userP;

	echo "<center>";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
	echo "<b>" .  _("Your Teams:") . "</b>";
	echo "</font>";
	echo "</center>";
	echo "\n";
	$teamRes=mysql_query("
		SELECT teamname, id
		FROM user_teams
		WHERE id IN ({$userP['team_1']}, {$userP['team_2']}, {$userP['team_3']})
	");
	while($row = mysql_fetch_assoc($teamRes))
	{
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
		echo "&nbsp;";
		echo "<a href='$code_url/stats/teams/tdetail.php?tid=".$row['id']."'>".$row['teamname']."</a>";
		echo "</font>";
		echo "<br>";
		echo "\n";
	}
        echo "<center>";
	echo "<font size='2'>";
	echo "<a href='$code_url/stats/teams/tlist.php'>"._("View all teams...")."</a>";
	echo "</font>";
	echo "</center>";
	echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_dp_birthdays()
{
    global $theme;

    $result = mysql_query("
        SELECT username,value
        FROM usersettings
        WHERE setting ='birthday_today'
        ORDER BY value DESC");
        
    if (mysql_num_rows($result) == 0)
       return;

    echo "<center><b>" . _("Today's DP Birthdays:") . "</b></center>\n";

    echo "<div style='text-align: center; font-family: $theme[font_navbar];
          color: $theme[color_navbar_font];'>";

    while ( list($username,$years) = mysql_fetch_row($result) )
    {
        echo "<font size='2'>$username ($years)</font><br />";
    }

    echo "</div><br><hr width='75%' align='center'>\n";
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_completed_projects()
{
	global $theme;

	echo "<center><b>" . _("Completed Projects:") . "</b></center>\n";
	echo "<table border=0 cellspacing=0 cellpadding=0 width='100%'>\n";
	$thismonth = date("n");
	$thisyear = date("Y");
	for ( $months_ago = 11; $months_ago >= 0; $months_ago-- )
	{
		// midnight that begins the 1st day of this month and the next:
		$begindate = mktime(0,0,0,$thismonth-$months_ago,  1,$thisyear);
		$enddate   = mktime(0,0,0,$thismonth-$months_ago+1,1,$thisyear);

		$displaydate = strftime(_("%b %Y"), $begindate );

		$result = mysql_query("
			SELECT COUNT(projectid)
			FROM projects
			WHERE modifieddate >= $begindate
				&& modifieddate <= $enddate
				&& state = '".PROJ_SUBMIT_PG_POSTED."'
		");
		$totalprojects = mysql_result($result,0);
		//echo "&nbsp;$displaydate - $totalprojects<br>\n";
		echo "<tr>";
		echo   "<td width='60%' align='right'>";
		echo     "<font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>";
		echo       "&nbsp;$displaydate&nbsp;-";
		echo     "</font>";
		echo   "</td>";
		echo   "<td width='40%' align='left'>";
		echo     "<font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>";
		echo       "&nbsp;$totalprojects&nbsp;";
		echo     "</font>";
		echo   "</td>";
		echo "</tr>";
		echo "\n";
	}
	echo "</table>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_key_help_links()
{
	global $theme, $code_url;

	echo "<center>";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
	echo "<b>"._("Key Help Documents:")."</b>";
	echo "</font>";
	echo "</center>\n";

	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
	echo "<a href='http://www.pgdpcanada.net/wiki/index.php/FAQ_Central'>"._("Frequently Asked Questions")."</a>";
	echo "</font>";
	echo "<br>\n";

	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
	echo "<a href='http://www.pgdpcanada.net/wiki/index.php/FAQ_Proofreading_Guidelines'>"._("Proofreading Guidelines")."</a>";
	echo " (<a href='$code_url/faq/".lang_dir()."proofing_summary.pdf'>"._("PDF Summary")."</a>)";
   echo "</font>";
	echo "<br>\n";

	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
	echo "<a href='http://www.pgdpcanada.net/wiki/index.php/FAQ_Formatting_Guidelines'>"._("Formatting Guidelines")."</a>";
	echo " (<a href='$code_url/faq/".lang_dir()."formatting_summary.pdf'>"._("PDF Summary")."</a>)";
	echo "</font>";
	echo "<br>\n";

	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
	echo "<a href='http://www.pgdpcanada.net/phpBB2/'>"._("Tips and How-To's")."</a>";
	echo "</font>";
	echo "<br>\n";

	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
	echo "<a href='$code_url/tasks.php'>"._("Support Request")."</a>";
	echo "</font>";
	echo "<br>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_donation_stuff()
{
	global $theme, $theme_name, $code_url;
	global $forums_url;

	$paypal_business_id = 'donate@pgdpcanada.net';
	$info_url = $code_url.'/faq/dpf.php';

	echo "<center>";
	echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>\n";
	echo "<input type='hidden' name='cmd' value='_xclick'>\n";
	echo "<input type='hidden' name='business' value='$paypal_business_id'>\n";
	echo "<input type='hidden' name='lc' value='CA'>\n";
	echo "<input type='hidden' name='item_name' value='Distributed Proofreaders Foundation Canada'>\n";
	echo "<input type='hidden' name='item_number' value='pgdpcanada.net site'>\n";
	echo "<input type='hidden' name='currency_code' value='CAD'>\n";
	echo "<input type='image' src='$code_url/pinc/templates/".$theme_name.$theme['image_donate']."' border='0' name='submit' alt='Donate to Distributed Proofreading'>\n";
	echo "</form>";
	echo "</center>\n";

	echo "<center>";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
	echo sprintf(
		_("Donate to the <a href='%s'>Distributed Proofreaders Foundation Canada</a>"),
		$info_url
	);
	echo "</font>";
	echo "</center>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function html_footer() {
	global $code_url, $theme;

	$mtime = explode(" ",microtime());
	$endtime = $mtime[1] + $mtime[0];
	$totaltime = ($endtime - $GLOBALS['starttime']);

    //Bottom Copyright Text
	echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_copyright_bg']."'>\n";
	echo "<tr>";
	echo "<td width='10'>&nbsp;</td>";
	echo "<td width='100%'>";
	echo "<center>";

	echo "<font color='".$theme['color_copyright_font']."' face='".$theme['font_copyright']."' size='1'>";
	echo _("Copyright")." "._("Distributed Proofreaders");
	echo " ("._("Page Build Time").": ".substr($totaltime, 0, 5).") ";
	echo "</font>";

	echo "<a href='$code_url/tasks.php'>";
	echo "<font color='".$theme['color_copyright_font']."' face='".$theme['font_copyright']."' size='1'>";
	echo _("Report a Bug");
	echo "</font>";
	echo "</a>";

	echo "</center>";
	echo "</td>";
	echo "</tr>\n";
	echo "</table>\n";

    //The End
	echo "\n</body>\n";
	echo "</html>\n";
}
?>
