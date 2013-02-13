<?
error_reporting(E_ALL);
$relPath='../c/pinc/';
include_once($relPath.'dpsql.inc');
if (0)
{
    mysql_connect('localhost','xxxx','xxxx');
    mysql_select_db('dproofreaders');
}
else
{
    include_once($relPath.'connect.inc');
    new dbConnect;
}

function _GET( $param_name, $default )
{
    if ( isset( $_GET[$param_name] ) )
    {
        $result = $_GET[$param_name];
    }
    else
    {
        $result = $default;
    }
    return $result;
}

function main()
{
    $action = _GET( 'action', 'show_start' );

    switch( $action )
    {
        case 'show_start':
        {
            echo "
                <html>
                <form method='get'>
                    start after
                    <input type='hidden' name='action'    value='show_frameset_for_next_after'>
                    <input type='text'   name='postednum' size='5'>
                    <input type='submit' />
                </form>
                </html>
            ";
            break;
        }

        case 'show_frameset_for_next_after':
        {
            $postednum = $_GET['postednum'];

            $res = dpsql_query("
                SELECT MIN(postednum) as postednum
                FROM projects
                WHERE postednum > $postednum AND postednum != 6000
            ");
            list($next_postednum) = mysql_fetch_row($res);

            $_GET['action'] = 'show_frameset_for';
            $_GET['postednum'] = $next_postednum;
            main();
            break;
        }

        case 'show_frameset_for':
        {
            $postednum = $_GET['postednum'];

            if ( isset($_GET['continue_after']) )
            {
                $ca="&amp;continue_after={$_GET['continue_after']}";
            }
            else
            {
                $ca='';
            }

            echo "
                <html>
                <frameset rows='25%,75%'>
                    <frame name='dp' src='?frame=1&amp;action=show_frame_for_postednum&amp;postednum=$postednum$ca'>
                    <frame name='pg' src='http://www.gutenberg.net/etext/$postednum'>
                </frameset>
                </html>
            ";

            break;
        }

        case 'show_frame_for_postednum':
        {
            $postednum = $_GET['postednum'];
            echo "postednum = $postednum";

            $continuer = _GET( 'continue_after', $postednum );

            echo "
                <table>
                <tr>
                <td>
                <form method='get' target='_top'>
                    <input type='hidden' name='action'    value='show_frameset_for_next_after'>
                    <input type='hidden' name='postednum' value='$continuer'>
                    <input type='submit' name='submit1'   value='next'>
                </form>
                </td>
                <td>
                &nbsp;&nbsp;
                </td>
                <td>
                <form method='get'>
                    <input type='hidden' name='action'         value='change_postednum'>
                    <input type='hidden' name='from_postednum' value='$postednum'>
                    <input type='submit'                       value='change postednum to:'>
                    <input type='text'   name='to_postednum'   value='$postednum'>
                </form>
                </td>
            ";

            dpsql_dump_query("
                SELECT nameofwork, authorsname
                FROM projects
                WHERE postednum=$postednum
            ");

            break;
        }

        case 'change_postednum':
        {
            $from_postednum = $_GET['from_postednum'];
            $to_postednum = $_GET['to_postednum'];
            if ( $from_postednum == $to_postednum )
            {
                // $_GET['action'] = 'show_frameset_for_next_after';
                // $_GET['postednum'] = $from_postednum;
                // main();
                break;
            }
            dpsql_query("
                UPDATE projects
                SET postednum=$to_postednum
                WHERE postednum=$from_postednum
            ");
            echo "
                <form method='get' target='_top'>
                    <input type='hidden' name='action'         value='show_frameset_for'>
                    <input type='hidden' name='postednum'      value='$to_postednum'>
                    <input type='hidden' name='continue_after' value='$from_postednum'>
                    <input type='submit'                       value='continue'>
                </form>
            ";

            break;
        }

        default:
        {
            echo "action = '$action'<br>";
            break;
        }
    }
}

main();

// vim: ts=4 sw=4 expandtab
?>
