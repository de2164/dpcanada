<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'user_is.inc');

include_once('inc/Sajax.php');

/*
if ($pguser != 'donovan')
die("Temporarily closed--Donovan)");
*/
  
sajax_init();
$sajax_debug_mode = 1;
// sajax_export("update_tasks_list","fetch_task_list");
sajax_handle_client_request();

init();

$page = 'main';
$no_stats = 1;
$colour = $theme['color_headerbar_bg'];

$theme_args['css_data'] = <<<EOCSS

h1 {color: $colour; text-align: center; margin-left: auto;}

.link {color: blue; cursor: pointer; cursor: hand;}

fieldset p, fieldset table tr td {font-family: Tahoma, Arial, sans-serif;}

fieldset {margin-top: 1em; width: 95%; margin-left: auto; margin-right:auto;} 
fieldset legend {font-family: Tahoma, Arial, sans-serif; 
    color: $colour; 
    cursor: pointer; cursor:  hand;}

.ccon {border-radius: 1em; -moz-border-radius: 1em; padding: 1em;}

EOCSS;

$fade = file_get_contents('inc/fat.js');

$sajax_js = sajax_get_javascript();

$theme_args['js_data'] = <<<EOJS

var editing = false;

function toggle_module(module)
{
    var moduleDiv = document.getElementById(module + '_div');
    if (moduleDiv.style.display == 'none')
    {
        moduleDiv.style.display = 'block';
    }
    else
    {
        moduleDiv.style.display = 'none';
    }
}

function createElementById(parentname,myid,elementType,innerHtml,className)
{
    var parentElement = document.getElementById(parentname);
    var newElement = parentElement.ownerDocument.createElement(elementType);
    newElement.innerHTML = innerHtml;
    newElement.id = myid;
    newElement.className = className;
    parentElement.appendChild(newElement);
    return newElement;
}

function killElementById(id)
{
    dgid = document.getElementById(id);
    dgid.parentNode.removeChild(dgid);
}


$sajax_js
$fade
EOJS;

theme('Control Panel','header', $theme_args);


echo '<h1>Control Panel</h1>'; 

if ($page=='main')
{
    show('welcome','open');
    show('information','open');
    show('maintenance','closed');
}    

theme('','footer');
// -------------------------------------------------------------


function show($module,$state)
{
    global $modules,$code_url;
    $display = ($state=='open') ? 'block' : 'none';

    echo "<fieldset id='{$module}_fset'><legend onclick='toggle_module(\"$module\")'>{$modules[$module]['title']}</legend>";

    echo "<div style='display: $display;' id='{$module}_div'>
        <p>{$modules[$module]['blurb']}</p>";

    if (!empty($modules[$module]['guts']))
        eval($modules[$module]['guts']);

    echo "</div></fieldset>";

}

function open_table() {
    echo "<table width='100%'>\n";
    echo "<tr><th>Script</th><th width='8em'>Who</th><th width='33%'>What</th><th width='25%'>Usage</th></tr>\n";
}

function close_table() {
    echo "</table>";
}

function display_script($where, $url, $who, $what, $usage) {
    global $pguser;
    $show_this = FALSE;
    switch ($who) {
        case "SA":
            $show_this = user_is_a_sitemanager();
            break;
        case "PF":
           $show_this = (user_is_a_sitemanager() || user_is_proj_facilitator() );
            break;
        case "PP":
            $show_this = user_can_work_in_stage($pguser, 'PP');
            break;
        case "AR":
            $show_this = (user_is_a_sitemanager() || user_is_an_access_request_reviewer());
            break;
        case "QC":
            $show_this = (user_is_a_sitemanager() || $pguser == 'PM QC'  || $pguser == 'stygiana');
            break;

        case "any":
            $show_this = TRUE;
            break;
    }
    if ($show_this) {
        $script_name = preg_replace('/\?.*/', '', $url);
        echo "<tr><td><a href='$where/$url'>$script_name</a></td><td>$who</td><td>$what</td><td>$usage</td></tr>\n";
    }
}

function init() {
    global $modules;

    $modules['welcome']['title'] = 'Control Panel';
    $modules['welcome']['blurb'] = "<p>Open or close any of the bordered sections (\"modules\") below by clicking on its title.<br>
Scripts which you do not have access to are not shown.</p>";
    $modules['welcome']['guts'] = <<<EO_WELCOME

echo "<div style='float: left; clear:none;'>";
include_once('testcal.php');
echo "</div>";

EO_WELCOME;

    $modules['information']['title'] = 'Information';
    $modules['information']['blurb'] = '';
    $modules['information']['guts']  = "show('project_info','open'); show('user_info','closed');";

    $modules['maintenance']['title'] = 'Maintenance';
    $modules['maintenance']['blurb'] = '';
    $modules['maintenance']['guts']  = "show('wrangle_projects','closed'); show('wrangle_users','closed'); show('wrangle_queues','closed');";


// Following are the 'sub-modules'


    $modules['user_info']['title'] = 'User Information';
    $modules['user_info']['blurb'] = '';
    $modules['user_info']['guts'] = <<< U_GUTS

open_table();

// This is too easily clicked on, and kills the server with page_events insert blocking. Don't enable. -donovan
//display_script(".", "active_users.php", "PF", 
//               "For each round, rank its most active proofreaders in the last 1 - 7 - 14 days.", 
//               "Resource intensive.");

display_script(".", "absent_volunteers.php", "PF", 
               "Show volunteers who have specified access and who have been away for specified period", 
               "");
display_script(".", "current_projects.php", "PF", 
               "For each round, show the projects that have had pages saved in the last 15 minutes, max 10 projects per round.", 
               "No longer resource-intensive!");
display_script(".", "graph_new_users.php", "any", 
               "Graph the number of user registered per day.", 
               "");
display_script(".", "graph_users_by_month_joined.php?which_graph=abs_both", "any", 
               "For each month in which someone joined, get the number who joined, and the number of those who have been active in the last 28 days", 
               "");
display_script(".", "graph_users_by_t_latest_activity.php", "any", 
               "DP users according to their month of last activity", 
               "");
display_script(".", "many_diffs.php", "SA", 
               "show page-diffs for the work done by a particular proofer", 
               "Edit source to change proofer");
display_script(".", "minima_sat_vs_access.php", "SA", 
               "who has access without satisfying requirements, or satisfies requirements but doesn't have access", 
               "");
display_script(".", "pm_away.php", "PF", 
               "show PMs who have been away for more than n days", 
               "");
display_script(".", "PMs_with_projects_in_rounds.php", "SA", 
               "show PMs with projects in the rounds, and when they were last seen", 
               "");
display_script(".", "pp_automatic.php", "PF", 
               "show all PPers with projects that have arrived in their queues since they last visited the site, which was more than n days ago", 
               "");
display_script(".", "pp_diff.php", "PF", 
               "show info about PPers, counts of projects completed, reserved, checked out", 
               "");
display_script(".", "pp_reserved.php", "PF", 
               "show all PPers that have projects reserved, and who haven't been seen for more than m days", 
               "");
display_script(".", "ppv_old.php", "PF", 
               "show all PPVers that have projects older than n days, and who haven't been seen for more than m days", 
               "");
display_script(".", "retention.php", "PF", 
               "For each calendar month, the number of users who registered in that month, the percentage of those who were active at least 1 day (7 days, 28 days) after reg.", 
               "");
display_script(".", "rogue2.php", "PF", 
               "Projects worked on by a user within the last n days", 
               "No longer resource intensive!");
display_script(".", "same_page.php", "PF", 
               "Projects with pages worked on by two users", 
               "Edit source to specify users");
display_script(".", "show_newbies.php", "PF", 
               "show old newbies", 
               "");
display_script(".", "subsequent_page_history.php", "SA", 
               "What happened to User X's pages after s/he worked on them?", 
               "Edit source to specify user and round");
display_script(".", "user_in_round.php", "PF", 
               "List all the projects proofread in the given round by the given user in the last 1 - 3 - 7 - 14 days, with number of pages proofread.", 
               "");
display_script(".", "user_pp.php", "PF", 
               "Show how many projects a PP has checked out and reserved in the rounds, with details.", 
               "");
display_script(".", "user_ppv.php", "PF", 
               "Show how many projects a PPV has checked out, with details.", 
               "");
display_script(".", "users_with_high_daily.php", "PF", 
               "Users who proofed more than 100 pages yesterday .", 
               "Resource intensive??");

close_table();

U_GUTS;


    $modules['wrangle_projects']['title'] = 'Project Maintenance';
    $modules['wrangle_projects']['blurb'] = '';
    $modules['wrangle_projects']['guts'] = <<< EO_PROJ_MAN

open_table();

display_script(".", "add_text.php", "SA", 
               "Add text to a project for a round that's been done offline.", 
               "");

// this script doesn't do the additional checks that the crontab/ version does
// don't enable/use this one -- cpeel
//display_script(".", "archive_project.php", "any", 
//               "Find projects that were posted to PG a while ago (that haven't been archived yet), and archive them.", 
//               "");

display_script(".", "copy_pages.php", "SA", 
               "Copy pages from one project to another (Igor)", 
               "");
display_script(".", "delete_pages.php", "SA", 
               "Delete pages from a project", 
               "Can delete ranges of pages");
display_script(".", "handle_reruns.php", "SA", 
               "A rerun is a project that is put through P1 twice, both times starting with the OCR text. Then garweyne merges the two P1 results, and we put the merged page-texts in as the P2 result. ", 
               "Script is run from command line.");
display_script(".", "insert_blanks.php", "SA",
		"Insert blank pages of configurable dimension and format with default or custom blank page text from a given list.",
		"Features automagic decisions on page state and which rounds need text.");
display_script(".", "mass_transition.php", "SA", 
               "Do something to lots of projects at once", 
               "Edit source to set what is done to which projects.");
display_script(".", "project_jump.php", "SA", 
               "Jump a project to a round without changing page states", 
               "");
display_script(".", "qc_toP3.php", "QC", 
               "Put a QC project into P3", 
               "");
display_script(".", "qc_afterP3.php", "QC", 
               "Handle a QC project after it's finished P3", 
               "");
display_script(".", "send_again.php", "SA", 
               "Send a project through the rounds again", 
               "");

close_table();

EO_PROJ_MAN;


    $modules['project_info']['title'] = 'Project Information';
    $modules['project_info']['blurb'] = '';
    $modules['project_info']['guts'] = <<<EO_PROJ_INFO

open_table();

display_script(".", "all_projects_on_hold.php", "any", 
               "show all projects currently on (Hold)", 
               "");
display_script(".", "check_page_transitions.php", "SA", 
               "Look for odd page-transitions", 
               "Probably resource intensive, as it uses page_events. Only run if you know what you're doing.");
display_script(".", "check_special_codes.php", "SA", 
               "???", 
               "");
display_script(".", "clearance_check.php", "any", 
               "reports projects of one project manager with suspect clearance key", 
               "");
display_script(".", "clearance_check2.php", "PF", 
               "reports all running projects with suspect clearance key", 
               "");
display_script(".", "clearance_line.php", "PP", 
               "Show the clearance line for a project", 
               "");
display_script(".", "completed_projects_by_month.php", "any", 
               "Completed projects, by month", 
               "");
display_script(".", "deleteds.php", "SA", 
               "Deleted projects without a deleted reason", 
               "");
display_script(".", "get_total_size_of_files.php", "any", 
               "Crawls through the projects/ dir, totting up files and filesizes", 
               "Takes too long");
display_script(".", "graph_pages_in_states.php", "any", 
               "A graph of numbers of pages in each state", 
               "");
display_script(".", "in_progress_check.php", "any", 
               "check whether a project is in progress for Project Gutenberg, using existing sources of information.", 
               "");
display_script(".", "languages.php", "any", 
               "how many distinct values in the 'language' field of the 'projects' table", 
               "");
display_script(".", "misc_postednum.php", "any", 
               "gold texts that don't have a postednum, and non-gold texts that do.", 
               "");
display_script(".", "neglected_projects.php", "any", 
               "Available projects that haven't been worked on in the last 24 hours", 
               "");
display_script(".", "no_PPer_yet.php", "any", 
               "Pre-PP Projects with no explicitly assigned PPer", 
               "");
display_script(".", "occurrences_of_document.php", "PF", 
               "Projects (other than PG-posted projects) whose comments reference 'document.php'", 
               "");
display_script(".", "posted_without_html.php", "any", 
               "Projects posted without HTML", 
               "");
display_script(".", "project_hospital.php", "any", 
               "projects in the hospital", 
               "");
display_script(".", "project_flow.php", "any", 
               "Recent project transistions", 
               "");
display_script(".", "project_wordlists_report.php", "PF", 
               "find projects with empty wordlists", 
               "");
display_script(".", "projects_by_ppers.php", "any", 
               "Available projects, sorted by PPer", 
               "");
display_script(".", "projects_waiting_for_eval.php", "AR", 
               "find projects that have pages by people waiting for evaluation", 
               "");
display_script(".", "projects_with_odd_values.php", "SA", 
               "Projects with 'odd' values for some attribute(s)", 
               "");
display_script(".", "qc_select_pages.php", "QC", 
               "Select pages to go into QC projects", 
               "");
display_script(".", "shared_postednums.php", "any", 
               "Cases where multiple projects have the same postednum", 
               "");
display_script(".", "show_site-wide_retread_skip_recommendations.php", "any", 
               "This page shows all projects in the system that have a recommendation based on their wa/w value", 
               "");

close_table();

EO_PROJ_INFO;


    $modules['wrangle_queues']['title'] = 'Queue Maintenance';
    $modules['wrangle_queues']['blurb'] = '';
    $modules['wrangle_queues']['guts'] = <<<EO_Q_WRANGLE

open_table();

display_script(".", "addPMqueue.php", "SA", 
               "Add a PM queue", 
               "");

close_table();

EO_Q_WRANGLE;


    $modules['wrangle_users']['title'] = 'User Maintenance';
    $modules['wrangle_users']['blurb'] = '';
    $modules['wrangle_users']['guts'] = <<<EO_U_WRANGLE

open_table();

display_script(".", "changePMsettings.php", "SA",
		"Allows changing a PM's PP pool setting, un/setting their ability to be a PM, implements the usersetting for 'disable_project_loads', and sets the PPing project limit",
		"");

display_script(".", "changePMpool.php", "SA", 
               "change (or find out) whether a PM's projects go into the PP pool or are checked out to them for PPing", 
               "");

close_table();

EO_U_WRANGLE;

}

// vim: sw=4 ts=4 expandtab
?>
