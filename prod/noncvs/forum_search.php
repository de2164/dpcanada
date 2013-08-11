<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');

if (!user_is_a_sitemanager()) die("permission denied");

if ( !isset($_GET['query_text']) )
{
    show_search_form();
}
else
{
    $err = handle_search_request();
    if ( $err != '' )
    {
        echo "<font color='red'>$err</font>\n";
    }
    echo "<hr>";
    show_search_form();
}

// -----------------------------------------------------------------------------

function show_search_form()
{
    ?>
    <form method='get' action=''>
    words to search for: <input name='query_text' type='text' size='30' value='<?= $_GET['query_text'] ?>'>
    <br>
    <input name='howsearch' type='radio' value='as_phrase' checked>as exact phrase (but case insensitive)
    <br>
    <input name='howsearch' type='radio' value='as_sep_words'>as separate words (all must appear, but not necessarily together or in that order)
    <br>
    in topic #: <input name='topic_id' type='text' size='5' value='<?= $_GET['topic_id'] ?>'> (leave blank to search all topics)
    <br>
    by user: <input name='poster_name' type='text' size='26' value='<?= $_GET['poster_name'] ?>'> (leave blank to search all users)
    <br>
    Max # results: <input name='max_hits' type='text' size='5' value='<?= array_get( $_GET, 'max_hits', 100 ); ?>'>
    <br>
    <input type='submit'>
    </form>
    <?
}

// -----------------------------------------------------------------------------

function handle_search_request()
// If there are problems, returns error(s) as string.
{
    $query_text = trim($_GET['query_text']);
    if ( $query_text == '' )
    {
        return "query field was empty!";
    }

    $howsearch = $_GET['howsearch'];
    if ( $howsearch == 'as_phrase' )
    {
        $condition = "INSTR(post_text,'$query_text')";
    }
    else if ( $howsearch == 'as_sep_words' )
    {
        $words = preg_split( '/\s+/', $query_text );
        $condition = surround_and_join( $words, "INSTR(post_text,'", "')", " AND " );
    }
    else
    {
        return "bad value for howsearch: '$howsearch'";
    }

    $topic_id = $_GET['topic_id'];
    if ( $topic_id == '' )
    {
        // fine, don't change $condition
    }
    elseif ( preg_match('/^\d+$/', $topic_id ) )
    {
        $condition .= " AND topic_id=$topic_id";
    }
    else
    {
        return "bad value for topic_id: '$topic_id'";
    }

    $poster_name = $_GET['poster_name'];
    if ( $poster_name == '' )
    {
        // fine
    }
    else
    {
        $condition .= " AND username = '$poster_name'";
    }

    $max_hits = trim($_GET['max_hits']);
    if ( ! preg_match('/^\d+$/', $max_hits ) )
    {
        return "bad value for max_hits: '$max_hits'";
    }


    $sql = "
        SELECT SQL_CALC_FOUND_ROWS *
        FROM phpbb_posts_text
            JOIN phpbb_posts
                USING (post_id)
            JOIN phpbb_users
                ON (phpbb_users.user_id = phpbb_posts.poster_id)
            JOIN phpbb_topics
                USING (topic_id)
        WHERE $condition
        ORDER BY post_id
        LIMIT $max_hits
    ";
    echo "<pre>$sql</pre>";
    $res = mysql_query($sql) or die(mysql_error());
    $n_returned_rows = mysql_num_rows($res);

    $res_found = mysql_query("SELECT FOUND_ROWS()");
    $n_found_rows = mysql_result($res_found,0);

    echo "$n_found_rows posts satisfied the search criteria.<br>\n";
    echo "$n_returned_rows posts shown here.<br>\n";

    while ( $row = mysql_fetch_object($res) )
    {
        echo "<hr>";
        echo "\n<br>",
            "<a href='http://www.pgdp.net/phpBB2/viewtopic.php?t=$row->topic_id'>",
            "topic",
            "</a>",
            ": ",
            $row->topic_title;
        echo "\n<br>",
            "<a href='http://www.pgdp.net/phpBB2/viewtopic.php?p=$row->post_id#$row->post_id'>",
            "post",
            "</a>",
            ": ",
            strftime('%Y-%m-%d %H:%M:%S', $row->post_time ),
            " ",
            $row->username;
        echo "\n<br>",
            $row->post_text;
            // substr($row->post_text,0, 300);
    }
}

// vim: sw=4 ts=4 expandtab
?>
