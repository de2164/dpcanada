<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

if (!user_is_a_sitemanager()) die("permission denied");

$projectid =@ mysql_real_escape_string($_GET['project']);

if (empty($projectid)) die("blah blah projectid");

theme('','header');

$result = dpsql_query(" SELECT fileid,round1_text,round1_user,round1_time,
round2_text,round2_user,round2_time
FROM $projectid");

while ($page = mysql_fetch_object($result))
{
    $s = 0;
    echo "Page $page->fileid by $page->round1_user, $page->round2_user: 
    chars matching:  ";
    $p = similar_text($page->round1_text,$page->round2_text,$s);
    echo "$p ($s%) <br />";
    $percs[] = $s;
}

echo "<p>Average similarity for book: " . (array_sum($percs) / count($percs))."%";

theme('','footer');i

function common_errors_check($text)
{
    
// vim: sw=4 ts=4 expandtab
?>
