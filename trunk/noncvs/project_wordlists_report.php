<?
$relPath="../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'iso_lang_list.inc');

if( user_is_a_sitemanager() || user_is_proj_facilitator() || $pguser == 'cpeel') {
    $can_select_pm = TRUE;
} elseif( user_is_PM()) {
    $can_select_pm = FALSE;
} else {
	echo "You are not authorized to invoke this script.";
	exit;
}

// get default options
$doSearch = array_get($_GET,"doSearch",0);
$optState = array_get($_GET,"optState","_ALL_");
$optPM = array_get($_GET,"optPM","_ALL_");
$optLang = array_get($_GET,"optLang","_ALL_");
$optNoGoodList = array_get($_GET,"optNoGoodList",0);
$optNoBadList = array_get($_GET,"optNoBadList",0);

if ( !$can_select_pm )
{
    $optPM = $pguser;
}

// if the form hasn't been submitted yet, set optNoGoodList
// to 1 so the initial form has it selected
if(!isset($_GET["optState"]))
    $optNoGoodList=1;

// order by string for state ordering
$state_collator = sql_collater_for_project_state('state');

// select string for states that we're interested in
$state_clause = "(state like '%proj_avail' or state like '%proj_waiting')";

// get a string containing the options for states
$states = array();
$query = "SELECT distinct state FROM projects WHERE $state_clause ORDER BY $state_collator";
$result = mysql_query($query);
$stateSelectString="<option value='_ALL_'>All States</option>";
while ($project_assoc = mysql_fetch_assoc($result)) {
    $stateSelectString.="<option value='" . $project_assoc['state'] . "'";
    if($optState === $project_assoc['state']) $stateSelectString.=" selected";
    $stateSelectString.=">" . $project_assoc['state'] . "</option>";
    array_push($states, $project_assoc['state']);
}
mysql_free_result($result);

// the initial state_clause with the 'like' operator is very inefficient
// so convert it to a more efficient one for the bigger queries
$state_clause = "(state in (";
foreach($states as $state) {
    $state_clause.="'$state',";
}
$state_clause=preg_replace("/,$/","))",$state_clause);


if ( $can_select_pm )
{
    // get a string containing the options for project managers
    $query = "SELECT distinct username FROM projects WHERE $state_clause ORDER BY username";
    $result = mysql_query($query);
    $pmSelectString="<option value='_ALL_'>All PMs</option>";
    while ($project_assoc = mysql_fetch_assoc($result)) {
        $pmSelectString.="<option value='" . $project_assoc['username'] . "'";
        if($optPM === $project_assoc['username']) $pmSelectString.=" selected";
        $pmSelectString.=">" . $project_assoc['username'] . "</option>";
    }
    mysql_free_result($result);
}

// create a string listing all languages
$languageSelectString="<option value='_ALL_'>All Languages</option>";
foreach($lang_list as $lang_record) {
    $language = $lang_record["lang_name"];
    $languageSelectString.="<option value='".htmlspecialchars($language,ENT_QUOTES)."'";
    if($optLang === $language) $languageSelectString.=" selected";
    $languageSelectString.=">" . $language . "</option>";
}


// start the page
$no_stats = 1;
theme(_("Project word list report"), "header");

echo "<h1>" . _("Project word list report") . "</h1>";

// display the form
echo "<form action='project_wordlists_report.php' method='GET'>";
echo "<input type='hidden' name='doSearch' value='1'>";
echo "<p>" . _("Limit the search results to the following criteria:"). "</p>";
echo "<table>\n";
echo  "<tr>";
echo   "<td>" . _("Current state:") . "</td>";
echo   "<td><select name='optState'>$stateSelectString</select></td>";
echo  "</tr>\n";
if ( $can_select_pm )
{
    echo  "<tr>";
    echo   "<td>" . _("Project Manager:") . "</td>";
    echo   "<td><select name='optPM'>$pmSelectString</select></td>";
    echo  "</tr>\n";
}
echo  "<tr>";
echo   "<td>" . _("Language:") . "</td>";
echo   "<td><select name='optLang'>$languageSelectString</select></td>";
echo  "</tr>\n";
echo  "<tr>";
echo   "<td>" . _("Empty Good Word List:") . "</td>";
echo   "<td><input type='checkbox' name='optNoGoodList' value='1'";
if($optNoGoodList) echo " checked";
echo   "></td>";
echo  "</tr>\n";
echo  "<tr>";
echo   "<td>" . _("Empty Bad Word List:") . "</td>";
echo   "<td><input type='checkbox' name='optNoBadList' value='1'";
if($optNoBadList) echo " checked";
echo   "></td>";
echo  "</tr>\n";
echo "</table>";
echo "<input type='submit' value='Search'>";
echo "</form>\n";

echo "<hr>";


if($doSearch) {
    // build the query based on what is currently selected
    $query = "SELECT * FROM projects";
    $orderByClause="$state_collator, nameofwork";
    $whereClause=$state_clause;
    if($optState!="_ALL_") {
        $whereClause.=" and state = '$optState'";
        $orderByClause="username, nameofwork";
    }
    if($optPM!="_ALL_")
        $whereClause.=" and username = '$optPM'";
    if($optLang!="_ALL_")
        $whereClause.=" and language like '%$optLang%'";
    $whereClause=preg_replace("/^ and/","",$whereClause);

    if(!empty($whereClause))
        $query.=" WHERE $whereClause";
    if(!empty($orderByClause))
        $query.=" ORDER BY $orderByClause";

    // echo the query as a comment, good for debugging and explain'ing
    echo "\n<!-- $query -->\n";
    $result = mysql_query($query);
    
    // create header
    echo "<table width='100%'>";
    echo "<tr>";
    echo "<th>" . _("Name") . "</th>";
    echo "<th>" . _("Proj Mgr") . "</th>";
    echo "<th>" . _("Current State") . "</th>";
    echo "<th>" . _("Languages") . "</th>";
    echo "<th>" . _("Good") . "</th>";
    echo "<th>" . _("Bad") . "</th>";
    echo "<th>" . _("Sugg.") . "</th>";
    echo "</tr>";
    while ($project_assoc = mysql_fetch_assoc($result)) {
        $project = new Project($project_assoc);
        $projectid = $project->projectid;
        $num_good_words = count(load_project_good_words($project->projectid));
        $num_bad_words = count(load_project_bad_words($project->projectid));
    
        // if optionEmpty*List is enabled, skip projects with the requested
        // empty list
        if($optNoGoodList && $num_good_words > 0) continue;
        if($optNoBadList && $num_bad_words > 0) continue;
    
        // parse the suggestions complex array
        // here we're just adding up all the words suggested for
        // a ballpark figure of "suggestion" activity
        $suggestions = load_project_good_word_suggestions($project->projectid);
        $num_suggestions = 0;
        if(is_array($suggestions)) {
            foreach( $suggestions as $round => $pageArray ) {
                foreach( $pageArray as $page => $words) {
                    $num_suggestions += count($words);
                }
            }
        }
    
        echo "<tr>";
        echo "<td width='40%'><a href='$code_url/tools/project_manager/edit_project_word_lists.php?projectid={$project->projectid}'>{$project->nameofwork}</a></td>";
        echo "<td>" . $project->username . "</td>";
        echo "<td>" . $project->state . "</td>";
        echo "<td>" . $project->language . "</td>";
        echo "<td>" . $num_good_words . "</td>";
        echo "<td>" . $num_bad_words . "</td>";
        echo "<td>" . $num_suggestions . "</td>";
        echo "</tr>\n";
    }
    mysql_free_result($result);
    echo "</table>";
}

theme("","footer");
// vim: sw=4 ts=4 expandtab
?>
