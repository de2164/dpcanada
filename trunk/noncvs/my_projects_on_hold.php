<?
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

$no_stats = 1;

$title = _("My projects currently on (HOLD)");
theme($title, "header");

echo "<br><br><h2>$title</h2><br>\n";

echo "This is a listing of all projects you (".$pguser.") are managing that are currently on (HOLD). The title is a link to the project page, which contains more info on the project. Projects are sorted by State, then Title. Use your browser's search/find function if you are after something in particular (such as particular author, or keyword, or genre, etc)."."<br><br>";

echo "A list of all projects on hold is available <a href='all_projects_on_hold.php'>here</a>.<br><br>";

dpsql_dump_themed_query("
    SELECT
state as State, 
        concat('<a href=\'$code_url/project.php?id=',projectid,'\'>',nameofwork,'</a>') AS Title,
        authorsname AS Author,
        language    AS Language,
        genre       AS Genre,
        difficulty  AS Difficulty
    FROM projects
    WHERE comments like '(HOLD)%' and username = '".$pguser."' and state not like 'proj_post%'
            and state not like 'proj_submit%' and state != 'project_complete' and state != 'project_delete'
    ORDER BY state, nameofwork
");

echo "<br>\n";

theme("","footer");

// vim: sw=4 ts=4 expandtab
?>
