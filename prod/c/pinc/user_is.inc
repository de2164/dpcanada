<?
// $Id: user_is.inc,v 1.44 2006/03/29 05:19:05 jmdyck Exp $

// This file provides settings (mostly permissions) for the logged-on user.

include_once($relPath.'page_tally.inc');
include_once($relPath.'SettingsClass.inc');

// This function reuturns the Settings object for $pguser.
// It should work properly (by returning appropriate null-type values) if
// the user is not logged on.

function & get_pguser_settings()
{
    global $pguser;
    return Settings::get_settings($pguser);
}

// -----------------------------------------------------------------------------

function that_user_is_a_sitemanager($username)
{
    $userSettings =& Settings::get_settings($username);
    return $userSettings->get_boolean("sitemanager");
}

function user_is_a_sitemanager()
{
    global $pguser;
    return that_user_is_a_sitemanager($pguser);
}

// ---------

function that_user_is_proj_facilitator($username)
{
    $userSettings =& Settings::get_settings($username);
    return
        $userSettings->get_boolean("proj_facilitator")
        ||
        $userSettings->get_boolean("sitemanager");
}

function user_is_proj_facilitator()
{
    global $pguser;
    return that_user_is_proj_facilitator($pguser);
}

// ---------

function user_is_an_access_request_reviewer()
{
    $userSettings =& get_pguser_settings();
    return $userSettings->get_boolean("access_request_reviewer");
}

function user_is_taskcenter_mgr()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("task_center_mgr");
}

function user_can_modify_access()
{
    if ( user_is_a_sitemanager() || user_is_an_access_request_reviewer() )
    {
        $can_grant = TRUE; $can_revoke = TRUE;
    }
    elseif ( user_is_proj_facilitator() )
    {
        $can_grant = TRUE; $can_revoke = FALSE;
    }
    else
    {
        $can_grant = FALSE; $can_revoke = FALSE;
    }
    return array( $can_grant, $can_revoke );
}

function user_is_PM()
{
    $userSettings =& get_pguser_settings();

    return user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $userSettings->get_boolean("manager");
}

function user_is_site_news_editor()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_news_editor");
}

function user_is_site_translator()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_translator");
}

function user_can_work_on_beginner_pages_in_round($round)
{
    $round_number = $round->round_number;
    $userSettings =& get_pguser_settings();
    if (user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $userSettings->get_boolean("see_BEGIN_R".$round_number)
            || $userSettings->get_boolean("{$round->id}_mentor.access")
        )
        return TRUE;

    global $pguser;
    $n_pages = user_get_ELR_page_tally( $pguser );
    if ( TRUE )
    {
        // Augment user's ELR count with their R* count,
        // so that old-timers don't do beginner projects.
        global $userP;
        $old_tallyboard = new TallyBoard( 'R*', 'U' );
        $old_count = $old_tallyboard->get_current_tally( $userP['u_id'] );
        $n_pages += $old_count;
    }
    if ($round_number == 1)
        return $n_pages <= 40;
    else if ($round_number == 2)
        // Can't qualify by page-count. Must qualify by mentor.access above.
        return FALSE;
    else
        return TRUE;
        // The round-restriction is sufficient.
}

function user_is_authors_db_manager()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean('authors_db_manager');
}

function user_is_authors_db_editor() {
  return user_is_PM();
}

function user_is_image_sources_manager()
{
    $userSettings =& get_pguser_settings();
    return user_is_a_sitemanager() || $userSettings->get_boolean("image_sources_manager");
}


// vim: sw=4 ts=4 expandtab
?>