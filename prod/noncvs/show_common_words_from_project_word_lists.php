<?
$relPath="./../c/pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'links.inc');

// check to see if the user is authorized to be here
if ( !(user_is_a_sitemanager()) )
{
    echo "You are not authorized to use this form.";
    exit;
}


// fetch any data sent our way.
$action = array_get($_REQUEST, "action", "list");
$language = array_get($_REQUEST, "language", "");
$language = urldecode($language);
$list_type = array_get($_REQUEST, "list_type", "");
$cutoff = array_get($_REQUEST, "cutoff", 50);
$lang_match = array_get($_REQUEST, "lang_match", "exact");

$title = _("Show common words for project word lists");

$extra_args['css_data'] = _get_stylesheet();

$no_stats = 1;
theme($title, "header", $extra_args);

echo "<h1>$title</h1>";

$display_list = _handle_action($action, $list_type, $language, $cutoff, $lang_match);

if($display_list)
{
    echo "<h2>" . _("Count common words for language") . "</h2>";

    // show create form
    echo "<form action='show_common_words_from_project_word_lists.php' method='post'>";
    echo "<input type='hidden' name='action' value='show'>";
    echo "<table>";
    echo "<tr>";
    echo "<td>" . _("Project Languages:") . "</td>";
    echo "<td><select name='language'>";
    // load all project languages
    $res = mysql_query("
        SELECT language, count(language)
        FROM projects
        GROUP BY language
    ");
    $used_languages = array();
    while( list($language,$language_count) = mysql_fetch_row($res) )
    {
        if(strpos($language," with "))
        {
            list($language1,$language2) = explode(" with ", $language);
            $used_languages[$language1] += $language_count;
            $used_languages[$language2] += $language_count;
        }
        else
        {
            $used_languages[$language] += $language_count;
        }
    }
    ksort($used_languages);
    foreach( $used_languages as $language => $language_count )
    {
        $option_string = sprintf(_("%s (%d projects)"), $language, $language_count);
        $option_value = urlencode($language);
        echo "<option value='$option_value'>$option_string</option>";
    }
    mysql_free_result($res);
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>" . _("Word list type:") . "</td>";
    echo "<td><select name='list_type'>";
    echo "<option value='good'>" . _("Good") . "</option>";
    echo "<option value='bad'>" . _("Bad") . "</option>";
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>" . _("Percentage cutoff:") . "</td>";
    echo "<td><input type='text' name='cutoff' value='50' size='4'>%</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td></td>";
    echo "<td>";
    echo "<input type='radio' name='lang_match' value='any'> " . _("Use any language match (matches primary or secondary languages)") . "<br>";
    echo "<input type='radio' name='lang_match' value='primary' checked> " . _("Use primary language match (matches primary language only)") . "<br>";
    echo "<input type='radio' name='lang_match' value='exact'> " . _("Use exact language match (secondary languages won't be used)") . "<br>";
    echo "</td>";
    echo "</tr>";

    echo "</table>";

    echo "<input type='submit' value='" ._("Show") . "'>";
    echo "</form>";

    echo "</table>";
}

theme($title, "footer");

// Everything else is just function declarations.

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// handle any actionable request
// arguments:
//        $action - action to take
//                  one of: show, list
//     $list_type - list type to show
//                  one of: good, bad
//      $language - the list language
//        $cutoff - words that occur less than $cutoff percent are not shown
//    $lang_match - how to match the language
//                  one of: exact, primary, any
// return codes:
//   TRUE - request was handled (don't display default list)
//   FALSE - request wasn't handled (display list)
function _handle_action($action, $list_type, $language, $cutoff, $lang_match)
{
    $display_list = FALSE;

    switch($action)
    {
        case "show":
            $word_freq = array();
            $total_projects = 0;
            $total_projects_with_words = 0;

            // figure out what kind of language matching we're going to use
            $where_clause = "";
            switch($lang_match)
            {
                case "exact":
                    $where_clause = "language = '$language'";
                    break;

                case "primary":
                    $where_clause = "language like '$language%'";
                    break;

                case "any":
                    $where_clause = "language like '%$language%'";
                    break;

                default:
                    die("Unknown language match used: $lang_match");
            }

            // loop through all projects that use $language
            $res = mysql_query("
                SELECT projectid
                FROM projects
                WHERE $where_clause
            ");
            while( list($projectid) = mysql_fetch_row($res) )
            {
                if($list_type == "good")
                    $words = load_project_good_words($projectid);
                elseif($list_type == "bad")
                    $words = load_project_bad_words($projectid);
                else
                    die("Unknown list type: $list_type");

                foreach( $words as $word )
                    @$word_freq[$word]++;

                if(count($words))
                    $total_projects_with_words++;

                $total_projects++;
            }
            mysql_free_result($res);

            // sort the results
            arsort($word_freq);

            // show the results
            echo "<pre>";
            echo "Language: $language<br>";
            echo "Word list type: $list_type<br>";
            echo "Cutoff percentage: $cutoff<br>";
            echo "Language match: $lang_match<br>";
            echo "Total projects: $total_projects<br>";
            echo "Total projects with words: $total_projects_with_words<br>";
            echo "<br>";
            echo "Note: Percentages are calculated as frequency over the total number of projects with words.<br>";
            echo "<br>";
            foreach($word_freq as $word => $freq)
            {
                $percentage = ($freq/$total_projects_with_words)*100;

                if($percentage < $cutoff)
                    break;

                echo sprintf("%30s  %5d  (%-3.2f%%)<br>", $word, $freq, $percentage);
            }
            echo "</pre>";
            break;

        case "list":
            $display_list = TRUE;
            break;

        default:
            die("Invalid action encountered.");
    }

    return $display_list;
}

function _get_stylesheet() {
    return "
        p.error { color: red; }
    ";
}


// vim: sw=4 ts=4 expandtab
?>
