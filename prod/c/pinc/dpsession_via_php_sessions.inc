<?
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');

$db_Connection=new dbConnect();

ini_set( 'session.name',            'DP_Session' );
ini_set( 'session.use_cookies',     1 );
ini_set( 'session.cookie_domain',   '' ); // A sensible value will be used.
ini_set( 'session.cookie_lifetime', 86400 );

// -----------------------------------------------------------------------------

function dpsession_begin_( $userID )
{
	global $pguser;

	session_set_handlers_and_start();

	$pguser = $userID;
	$_SESSION['pguser'] = $pguser;
	dpsession_set_preferences_from_db();
}

function dpsession_resume_()
{
	global $pguser, $userP;

	session_set_handlers_and_start();

	if ( isset($_SESSION['userP']) && !empty($_SESSION['userP']) )
	{
		// Refresh the cookie
		// (session_start() used to do this for us,
		// but they changed that in PHP 4.3.9)
		setcookie(
			ini_get('session.name'),
		 	session_id(),
			time() + ini_get('session.cookie_lifetime'),
			'/'
		);

		// Extract all $_SESSION items to globals.
		extract($_SESSION);
		return TRUE;
	}
	else
	{
		session_unset();
		session_destroy();
		return FALSE;
	}
}

function dpsession_set_preferences_from_db()
{
	global $pguser;

	$users_res = mysql_query("
		SELECT
			u_id, u_profile, u_lang, email_updates,
			u_plist, u_top10, u_neigh, u_align,
			i_prefs, i_theme, i_pmdefault,
			id, manager, postprocessor, sitemanager,
			team_1, team_2, team_3,
			u_intlang, u_privacy, last_login
		FROM users
		WHERE username='$pguser'
		");
	$users_data = mysql_fetch_assoc($users_res);

	$user_profiles_res = mysql_query("
		SELECT
			profilename,
			i_res, i_type, i_layout, i_toolbar, i_statusbar, i_newwin,
			v_fnts, v_fntf, v_zoom, v_tframe, v_tlines, v_tchars, v_tscroll, v_twrap,
			h_fnts, h_fntf, h_zoom, h_tframe, h_tlines, h_tchars, h_tscroll, h_twrap
		FROM user_profiles
		WHERE u_ref='{$users_data['u_id']}' AND id='{$users_data['u_profile']}'
		");
	$user_profiles_data = mysql_fetch_assoc($user_profiles_res);

	$prefsChanged_data = array("prefschanged" => 0);

	$_SESSION['userP'] = array_merge($users_data, $user_profiles_data, $prefsChanged_data);
}

function dpsession_set_preferences_temp( $user_prefs )
{
	$_SESSION['userP'] = $user_prefs;
}

function dpsession_end()
{
	session_unset();
	session_destroy();
}

// -----------------------------------------------------------------------------

// The 'debouncer' variable

function dpsession_page_set($info)
{
	$_SESSION['debouncer'] = $info;
}

function dpsession_page_end()
{
	$_SESSION['debouncer'] = NULL;
}

function dpsession_page_is_set()
{
	return isset($_SESSION['debouncer']);
}

function dpsession_page_get()
{
	return $_SESSION['debouncer'];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Helper functions that aren't part of the dpsession API.

function session_set_handlers_and_start()
{
	if ( session_id() )
	{
		// session_start() has already been called
	}
	else
	{
		session_set_save_handler(
			"mysql_session_open",
			"mysql_session_close",
			"mysql_session_select",
			"mysql_session_write",
			"mysql_session_destroy",
			"mysql_session_garbage_collect");

		session_start();
	}
}

function mysql_session_open($session_path, $session_name)
{
	// We've already opened the database so let's not open it again
	return true;
}

function mysql_session_close()
{
	// We use a persistent connection so we don't need to worry about closing it
	return true;
}

function mysql_session_select($sid)
{
	$result = do_session_query("SELECT value FROM sessions WHERE sid = '$sid' AND expiration > ".time()."");
	if (mysql_num_rows($result) == 1)
	{
		return mysql_result($result, 0);
	}
	else
	{
		return false;
	}
}

function mysql_session_write($sid, $value)
{
	$expiration = time() + ini_get("session.gc_maxlifetime");
	$result = do_session_query("SELECT COUNT(*) FROM sessions WHERE sid = '$sid'");
	$SessionExists = mysql_result($result, 0);
	// The table has a uniqueness constraint on 'sid',
	// so $SessionExists must be 0 or 1.

	// $value is a string (constructed by PHP's built-in session
	// code) that might contain apostrophes (or backslashes?),
	// which will screw up the SQL queries unless we escape them.
	$value = addslashes($value);
	// addslashes will also escape double-quotes, but that's okay.

	if ( $SessionExists == 0 )
	{
		$query = "
			INSERT INTO sessions
				(sid, expiration, value)
			VALUES
				('$sid', '$expiration', '$value')
			";
	}
	else
	{
		$query = "
			UPDATE sessions
			SET
				expiration = '$expiration',
				value = '$value'
			WHERE sid = '$sid' AND expiration > ".time()
			;
	}
	$result = do_session_query($query);
	return $result;
}

function mysql_session_destroy($sid)
{
	$result = do_session_query("DELETE FROM sessions WHERE sid = '$sid'");
	return $result;
}

function mysql_session_garbage_collect($lifetime)
{
	$result = do_session_query("DELETE FROM sessions WHERE expiration < ". (time()-$lifetime) ."");
	return $result;
}

function do_session_query( $query )
{
	// write_to_session_log( $query );
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}

function write_to_session_log( $msg )
{
	$time = strftime('%Y-%m-%d %H:%M:%S');
	$f = fopen('/tmp/session_query_log','a');
	fwrite( $f, "------\n$time\n$msg\n" );
	fclose($f);
}

?>
