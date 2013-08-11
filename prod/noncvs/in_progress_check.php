<style type='text/css'>
.searchword {/*font-weight: bold;*/ color:red;}
</style><pre>
<h3 class='searchword'>Warning: This script won't check David's list while it's not there</h3>

<?PHP

$davids_list_url = 'http://www.dprice48.freeserve.co.uk/GutIP.html';
$davids_list_local_path = '../d/pg/davids_list.txt';

$relPath='../c/pinc/';
//include_once($relPath.'dp_main.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'site_vars.php');

new dbConnect;

if (!isset($_REQUEST['title']) && !isset($_REQUEST['author']))
{

    echo <<<EOHTML
<p>The purpose of this page is to provide a quick way to check whether 
a project is in progress for Project Gutenberg, using existing sources 
of information. At the moment, it will search:
<ul><li>David's In-Progress List</li>
<li>Projects in-progress at DP</li>
<li>Projects at PG (I've heard reports that David's 
list doesn't include some projects already posted.)</li></ul>


<form method='post'>
Fill in <b>one</b> only. Search will be used as a single unit (like using a
seach engine "with quotes") and case doesn't matter.
Search:        <input type='text' name='title' size='30' />
<!-- Author:        <input type='text' name='author' size='30' /> -->
<input type='submit' value='Do search' />
</form>
EOHTML;

}
else
{
    ob_start();

    // is there a copy of David's list on the server? If not, or 
    // it's stale, or the request tells us to, get a new copy.

    if(!file_exists($davids_list_local_path) 
        || filemtime($davids_list_local_path) < (time() - 60*60*24) )
    {
        $david_list = file($davids_list_url);
        $file = fopen($davids_list_local_path,'w');
        fwrite($file,serialize($david_list));
    }
    else
    {
        $david_list = unserialize(file_get_contents($davids_list_local_path));
    }

    // ------------------------------------------------------------------------

    $search = $_REQUEST['title'];

    echo "<h1>Results for '$search'</h1>";

    echo "<h2>Looking through David's List</h2>";
    echo $david_list[8];

    foreach ($david_list as $line)
    {
        if (strpos(strtolower($line),strtolower($search)))
            $results_dl[] =$line;
    }
    
    if (!empty($results_dl))
    {
        foreach ($results_dl as $result)
        {
            echo preg_replace('/\<\/?p\>/','',$result);
        }
    }

    echo  "<h2>Searching the PG catalogue...</h2>";

    $pg_catalog_file = "$dyn_dir/pg/catalog.rdf";
    $fp = fopen($pg_catalog_file, 'r');
    while ( $line = fgets($fp) )
    {
        if (strpos(strtolower($line),strtolower($search)))
            $results_pg[] =$line;
    }
    fclose($fp);

    if (!empty($results_pg))
    {
        foreach ($results_pg as $result)
        {
            if (strpos($result,'dc:title'))
                 echo preg_replace('/\<[^\>]+\>/','',$result);
        }
    }

    echo "<h2>Searching DP projects</h2>";

    dpsql_dump_query("SELECT nameofwork as 'Title',
                    username as 'Manager',
                    projectid,
                    state as 'State'
                    FROM projects
                    WHERE nameofwork LIKE '%".mysql_real_escape_string($search)."%'");


    $output = ob_get_contents();

    ob_end_clean();

    if (strlen($output) > 100000 && !isset($_GET['foolish']))
        die("The output would be over 100KB (".strlen($output)." bytes). You can <a href='?title=$search&foolish'>see it anyway</a>, if you really want to.");

    echo preg_replace("/($search)/i",'<span class=\'searchword\'>$1</span>',$output);


}

// vim: sw=4 ts=4 expandtab
?>



<hr />
This service is provided as a convenience. Do not trust its advice. 
</pre>
