<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

$res = dpsql_query("
    SELECT DISTINCT language
    FROM projects
    ORDER BY language
");
$n = mysql_num_rows($res);
echo "$n distinct values in the 'language' field of the 'projects' table:";

$d = array();
echo "<ul>\n";
while ( list($language) = mysql_fetch_row($res) )
{
    echo "<li>$language</li>\n";
    $language = preg_replace('/\(.*?\)/', '', $language);

    $langs = preg_split('# with |, some | and | then |[-+/;]|, (?=(Latin|English))#', $language);
    foreach ( $langs as $lang )
    {
        $lang = preg_replace('/\s+/', ' ', $lang);
        $lang = preg_replace( '/^ /', '', $lang);
        $lang = preg_replace('/ \.$/', '', $lang);
        $lang = preg_replace('/ (TRAINEES ONLY|TRAINEES|HTML NEEDED)/', '', $lang);
        $lang = preg_replace('/ ?\.$/', '', $lang);
        $lang = preg_replace('/ (Fantasy|Fiction|Juv Fiction|Notes|SciFi|intro|dialogue)$/i', '', $lang);
        $lang = preg_replace('/, etc$/i', '', $lang);
        $lang = preg_replace('/^(small|less) /', '', $lang );
        $lang = preg_replace('/ $/', '', $lang );

        $d[$lang] = 1;
    }
}
echo "</ul>\n";

ksort($d);
$n = count($d);
echo "If we munge that list in various ways, we get $n distinct values:";
echo "<ul>";
foreach ( $d as $lang => $dummy )
{
    echo "<li>$lang</li>\n";
}
echo "</ul>";

// vim: sw=4 ts=4 expandtab
?>
