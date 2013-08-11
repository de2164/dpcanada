<?
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

$no_stats = 1;

$title = _("All projects currently on (HOLD)");
theme($title, "header");

echo "<br><br><h2>$title</h2><br>\n";

echo "This is a listing of all projects currently on (HOLD). The title is a link to the project page, which contains more info on the project. Projects are sorted by State, then Project Manager, then Title. Use your browser's search/find function if you are after something in particular (such as particular author, or keyword, or genre, etc)."."<br><br>";

echo "If you are a Project Manager, you can see a list of your projects on hold <a href='my_projects_on_hold.php'>here</a>.<br><br>";

dpsql_dump_themed_query("
    SELECT
state as State,
username as PM,
        concat('<a href=\'$code_url/project.php?id=',projectid,'\'>',nameofwork,'</a>') AS Title,
        authorsname AS Author,
        language    AS Language,
        genre       AS Genre,
        difficulty  AS Difficulty
    FROM projects
  WHERE comments like '(HOLD)%' and state not like 'proj_post%'
            and state not like 'proj_submit%' and state != 'project_complete' and state != 'project_delete'
    ORDER BY state, username,   nameofwork
");

echo "<br>\n";

theme("","footer");

// vim: sw=4 ts=4 expandtab
?>
