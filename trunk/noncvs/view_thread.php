<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

//$thread = '110';
$thread = '2622';
$thread = $_REQUEST['thread'] or die("Which thread?");

dpsql_dump_themed_query("
    SELECT
        DISTINCT phpbb_posts.post_id, 
        phpbb_posts_text.post_text
    FROM
        phpbb_posts, phpbb_users, phpbb_posts_text
    WHERE
        phpbb_posts.topic_id = '$thread'
        AND phpbb_posts.post_id = phpbb_posts_text.post_id");


        //AND phpbb_posts.post_username = phpbb_users.user_id
        //phpbb_users.username,

// vim: sw=4 ts=4 expandtab
?>
