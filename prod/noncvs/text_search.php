<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');

if (!user_is_a_sitemanager()) die("permission denied");

header("Content-type: text/plain");

$project_condition = "1";
$page_condition = "INSTR(master_text,'sensim')";

$res = mysql_query("
    SELECT projectid, nameofwork
    FROM projects
    WHERE $project_condition
    AND archived != 1
") or die(mysql_error());

while ( list($projectid,$nameofwork) = mysql_fetch_row($res) )
{
    $res2 = mysql_query("
        SELECT image, master_text
        FROM $projectid
        WHERE $page_condition
    ");

    $count = mysql_num_rows($res2);
    if ( $count > 0 )
    {
        echo "\n";
        echo "----------------------------------\n";
        echo "$projectid ($nameofwork):\n";
        echo "    $count pages\n";
        while( list($image,$master_text) = mysql_fetch_row($res2) )
        {
            echo "\n";
            echo $image,"\n";
            echo $master_text;
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
