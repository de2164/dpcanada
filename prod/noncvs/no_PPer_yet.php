<?
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

$no_stats = 1;

$title = _("Pre-PP Projects with no explicitly assigned PPer");
theme($title, "header");

echo "<br><br><h2>$title</h2><br>\n";

echo "<b>Aspiring Post Processors</b> The title is a link to the project page, which contains more info on the project. Projects are sorted by State, then Language, then Title. This is a list of all projects that have not yet reached PP and do not have have a PPer explicitly assigned (excepting projects in an 'unavailable' state'). <b>Not all projects listed are necessarily available for PPer reservation - it's up to the Project Manager.</b> Contact the Project Manager to find out or to request a reservation. If the PM seems absentee, try posting to the project thread. Use your browser's search/find function if you are after something in particular (such as particular author, or keyword, or genre, etc)."."<br><br>";

echo "<b>Project Managers</b> You can remove your project from this list by assigning a PPer to it in the Edit project page."."<br><br>";

dpsql_dump_themed_query("
    SELECT
        concat('<a href=\'$code_url/project.php?id=',projectid,'\'>',nameofwork,'</a>') AS Title,
        authorsname AS Author,
        language    AS Language,
        username    AS PM,
        state       AS State,
        genre       AS Genre,
        difficulty  AS Difficulty
    FROM projects
    WHERE checkedoutby = ''
        AND (state LIKE 'NEW%' OR state LIKE 'P1%' OR state LIKE 'P2%' OR state LIKE 'P3%' or state like 'F1%' or state like 'F2%')
        AND state NOT LIKE '%unavail%'
    ORDER BY state, language, nameofwork
");

echo "<br>\n";

theme("","footer");

// vim: sw=4 ts=4 expandtab
?>
