<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'genres.inc');

error_reporting(E_ALL);

//function str_contains( $haystack, $needle )
//{
//    return ( strpos( $haystack, $needle ) !== FALSE );
//}

if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) die("permission denied");

echo "<h1>Projects with 'odd' values for some attribute(s)</h1>\n";

// -----------------------------------

$lc = array();

$lc['language'] = array();
foreach ( $lang_list as $lang )
{
    $lc['language'][ strtolower($lang['lang_name']) ] = 1;
}

$lc['genre'] = array();
foreach( array_map( 'strtolower', array_keys($GENRES) ) as $genre )
{
    $lc['genre'][$genre] = 1;
}

$lc['difficulty'] = array(
    'beginner' => 1,
    'easy'     => 1,
    'average'  => 1,
    'hard'     => 1
);

$lc['special_code'] = array();
$res = dpsql_query("
    SELECT spec_code
    FROM special_days
    WHERE enable = 1
");
while ( list($spec_code) = mysql_fetch_row($res) )
{
    $lc['special_code'][strtolower($spec_code)] = 1;
}
$lc['special_code'][''] = 1;
for ( $m = 1; $m <= 12; $m++ )
{
    for ( $d = 1; $d <= 31; $d++ )
    {
        foreach ( array('birthday','otherday') as $x )
        {
            $v = sprintf( '%s %02d%02d', $x, $m, $d );
            $lc['special_code'][$v] = 1;
        }
    }
}

// -------------------------------------

$projects_with_bad = array();
foreach ( array_keys($lc) as $property )
{
    $projects_with_bad[$property] = array();
    echo "<a href='#$property'>$property</a>\n";
}

// -------------------------------------

$res = dpsql_query("
    SELECT *
    FROM projects
    ORDER BY projectid
");
while ( $project = mysql_fetch_object($res) )
{
    // language
    if ( str_contains( $project->language, ' with ' ) )
    {
        $langs = explode(' with ', $project->language);
    }
    else
    {
        $langs = array($project->language);
    }
    foreach ( $langs as $lang )
    {
        if ( ! array_key_exists( strtolower($lang), $lc['language'] ) )
        {
            $projects_with_bad['language'][] = $project;
            break;
        }
    }

    foreach ( array('genre', 'difficulty', 'special_code' ) as $property )
    {
        if ( ! array_key_exists( strtolower($project->$property), $lc[$property] ) )
        {
            $projects_with_bad[$property][] = $project;
        }
    }

}

foreach ( $projects_with_bad as $property => $projects )
{
    echo "<a name='$property'>\n";
    echo "<h2>$property</h2>\n";
    echo "<table border='1'>\n";
    foreach ( $projects as $project )
    {
        echo "<tr>\n";
        echo "<td>{$project->$property}</td>\n";
        $url = "$code_url/project.php?id={$project->projectid}";
        echo "<td><a href='$url'>{$project->nameofwork}</a></td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
}


// vim: sw=4 ts=4 expandtab
?>
