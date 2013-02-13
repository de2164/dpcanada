<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

$projectids = array(
'projectID40637ac03e704',
'projectID40637b66323b2',
'projectID40e9fe913575a',
'projectID4265df431aa18',
'projectID428ae2469d7c3',
'projectID42a3800f7828b',

);

foreach ( $projectids as $projectid )
{
    echo "<hr>\n";
    echo "$projectid :<br>\n";
    $res = dpsql_query("SELECT * FROM phpbb_posts_text WHERE instr(post_text,'$projectid')");
    while ( $post = mysql_fetch_object($res) )
    {
        echo "<hr width='50%'>\n";
        if ( $post->post_id == 99420 )
        {
            echo "that one";
        }
        else
        {
            echo "post_id: {$post->post_id}<br>\n";
            echo preg_replace(
                "/$projectid/",
                "<b>$projectid</b>",
                nl2br($post->post_text) );
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
