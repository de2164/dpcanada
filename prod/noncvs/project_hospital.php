<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'dpsql.inc');
include_once('inc/Sajax.php');

$non_pf_healers = array('JHowse','puppernutter');

/*

 if ($pguser != 'mikeyc21')
 die("The project hospital is temporarily closed while we install a new machine that goes \"beep\". (I should be
    done in a couple of hours... --Mike)");
  */  
sajax_init();
// $sajax_debug_mode = 1;
sajax_export("update_tasks_list","fetch_task_list");
sajax_handle_client_request();

#if (!(user_is_a_sitemanager() || user_is_proj_facilitator() || in_array($pguser,$non_pf_healers)) ) die("permission denied");

init();

$page = 'main';
$no_stats = 1;
$colour = $theme['color_headerbar_bg'];

$theme_args['css_data'] = <<<EOCSS

h1 {color: $colour; text-align: center; margin-left: auto;}

.link {color: blue; cursor: pointer; cursor: hand;}

.updater {font-family: Tahoma, Arial, sans-serif; color: green; font-size:80%;}

fieldset p, fieldset table tr td {font-family: Tahoma, Arial, sans-serif;}

fieldset {margin-top: 1em; width: 95%; margin-left: auto; margin-right:auto;} 
fieldset legend {font-family: Tahoma, Arial, sans-serif; 
    color: $colour; 
    cursor: pointer; cursor:  hand;}

table#projects tr td { border-bottom: 1px solid black; margin-bottom: .5em; vertical-align: top; padding-top: 1em; }
table#projects { border-collapse: collapse;}
table#projects tr td ul { list-style: none; }

div#task_list_con {white-space: pre; font-family: monospace;}

.ccon {border-radius: 1em; -moz-border-radius: 1em; padding: 1em;}

span.pre { white-space: pre; font-family: monospace; display: inline; font-weight: bold; }


EOCSS;

$fade = file_get_contents('inc/fat.js');

$sajax_js = sajax_get_javascript();

$theme_args['js_data'] = <<<EOJS

var editing = false;

function toggle_module(module)
{
    var moduleDiv = document.getElementById(module + '_div');
    if (moduleDiv.style.display == 'none')
    {
        moduleDiv.style.display = 'block';
    }
    else
    {
        moduleDiv.style.display = 'none';
    }
}

function edit_task_list()
{
    editing = true;
    show_updating_thingo();
    x_fetch_task_list('foo',handle_ajax_tl);
}

function update_task_list()
{
    show_updating_thingo();
    x_fetch_task_list('foo',handle_ajax_tl);
}

function show_updating_thingo()
{
    msg = createElementById('task_list_controls','updater',
        'span','Updating&hellip;','updater');
}

function hide_updating_thingo()
{
    if (document.getElementById('updater'))
{    killElementById('updater');}
}

function set_editable(base_id, editable)
{
    if (editable)
    {
        static_div = document.getElementById(base_id + '_stat');
        controls = document.getElementById(base_id + '_controls');
        var newHTML = static_div.innerHTML.replace(/</g,'&lt;');
        newHTML = newHTML.replace(/>/g,'&gt;');
        new_textarea = createElementById(base_id + '_con',base_id + '_textarea','textarea',newHTML,'ctext');
        new_textarea.rows = '6';
        new_textarea.cols = '90';
        new_textarea.wrap = 'physical';
        killElementById(base_id + '_stat'); 

        controls.innerHTML = "<p><input type='button' value='Save' onclick='do_task_list_save()' /> Surround a projectID in [ ] for auto-linkage</p>";
    }
    else
    {
        textarea = document.getElementById(base_id + '_textarea');
        controls = document.getElementById(base_id + '_controls');
        static_div = createElementById(base_id + '_con', base_id + '_stat','div',textarea.innerHTML,'ccon');
        killElementById(base_id + '_textarea');
    
controls.innerHTML = "<span onclick='edit_task_list();' class='link'>[Edit]</span> <span onclick='update_task_list();' class='link'>[Update]</span>";
    Fat.fade_element(base_id + '_stat',30,5000,'$colour','#FFFFFF');
    }

    return true;

}

function do_task_list_save()
{
    new_text = document.getElementById('task_list_textarea');
    x_update_tasks_list(new_text.value,'$pguser',handle_ajax_tl)
}

function handle_ajax_tl(new_text)
{
/*    alert('handle_ajax_tl() involved'); */
    if ((document.getElementById('task_list_textarea') && editing != true) )
    { set_editable('task_list',false); }


    textdiv = document.getElementById('task_list_stat');

    texts = new_text.split(/\t/,2);

    textdiv.innerHTML = texts[1];

    updated = document.getElementById('last_updated');
    updated.innerHTML = texts[0];

    hide_updating_thingo();

    if (editing == true)
    {
        set_editable('task_list',true)
        editing = false;
    }
}


function createElementById(parentname,myid,elementType,innerHtml,className)
{
    var parentElement = document.getElementById(parentname);
    var newElement = parentElement.ownerDocument.createElement(elementType);
    newElement.innerHTML = innerHtml;
    newElement.id = myid;
    newElement.className = className;
    parentElement.appendChild(newElement);
    return newElement;
}

function killElementById(id)
{
    dgid = document.getElementById(id);
    dgid.parentNode.removeChild(dgid);
}


$sajax_js
$fade
EOJS;

theme('Project Hospital','header', $theme_args);

echo '<h1>Project Hospital</h1>'; 

if ($page=='main')
{
    show('welcome','open');
    show('help','open');

/*    show('task_list','closed');*/
    show('quick_links','open');
    show('case_list','open');
}    

theme('','footer');
// -------------------------------------------------------------


function show($module,$state)
{
    global $modules,$code_url;
    $display = ($state=='open') ? 'block' : 'none';

    echo "<fieldset id='{$module}_fset'><legend onclick='toggle_module(\"$module\")'>{$modules[$module]['title']}</legend>";

    echo "<div style='display: $display;' id='{$module}_div'>
        <p>{$modules[$module]['blurb']}</p>";

    if (!empty($modules[$module]['guts']))
        eval($modules[$module]['guts']);

    echo "</div></fieldset>";


}

function update_tasks_list($new_text,$username)
{
    $date = time();
    $type = 'task_list';
    $text = insert_links($new_text);


    dpsql_query("INSERT INTO project_hospital
            SET
                date = '$date',
                type = '$type',
                username = '$username',
                text = '$text'");
    
    return fetch_task_list('foo');

}

function insert_links($text)
{
    global $code_url;
    $search = '/\[(projectID[0-9a-f]+)\]/';
    $replace = "<a href=\"$code_url/project.php?id=$1\">$1</a>";
    $text = preg_replace($search,$replace,$text);

    return $text;
}

function fetch_task_list($foo)
{
    $result = dpsql_query("SELECT * FROM project_hospital
        WHERE type = 'task_list'
        ORDER BY date DESC
        LIMIT 1");

    $task_list = mysql_fetch_object($result);

    $last_updated =  "Last updated ".date("r",$task_list->date)." by $task_list->username";

    return $last_updated . "\t" . $task_list->text;

}


function init()
{ global $modules;
$modules['welcome']['title'] = 'Welcome to the Project Hospital!';
$modules['welcome']['blurb'] = "

<p>Open or close any of the bordered sections (\"modules\") below by clicking on its title.</p>

<p><b>Squirrels&mdash;New Best Practice:</b> <small>(July 2008)</small> Please add clear and detailed
hospital notes to the project thread once fixes are completed so that there is a formal record outside
of the project comments.<br>Use '[b]Hospital Notes:[/b]' at the beginning to help make them quickly
searchable. Thanks!</p>

<p><b>PFs and PMs:</b> Add the '{needs fixing, DETAILS}' tag to <b>all</b> projects that you make unavailable, so that
the project will automatically appear on this page and in the Missing Pages wiki.<br> In that title tag, DETAILS should
be replaced with a short phrase, preferably 'see notes', and you should add your notes to the Project Comments as
described here.<br> For Hospital notes in the Project Comments to be displayed automatically in the Missing Pages
wiki, they <b>must</b> appear as the very first part of the PCs and be wrapped in &lt;pre&gt; tags &lt;/pre&gt;.<br> Please be detailed,
clear and accurate in your notes so that Missing Page Finders don't have to do extra work such as making an
extra trip to correct any oversights.<br> Make especially sure to include edition information (publisher, year, etc.)
if the project has many editions, so that it will be easier for the Missing Page Finders to locate the correct one.</p>
<p>Thanks!</p>";

$modules['welcome']['guts'] = '';


$modules['help']['title'] = 'Help';
$modules['help']['blurb'] = 'Open any of the modules below to find help for specific topics.';
$modules['help']['guts'] = "show('missing_pages_help','closed'); show('hq_illos_help','closed');";


$modules['missing_pages_help']['title'] = 'Missing Pages';
$modules['missing_pages_help']['blurb'] = "
<p>See the <a href='http://www.pgdp.net/wiki/Db-req#Missing_pages'>Missing Pages</a> section of the DP wiki entry for db-req.</p>";

$modules['missing_pages_help']['guts'] = '';

$modules['hq_illos_help']['title'] = 'High-Quality Illustrations';
$modules['hq_illos_help']['blurb'] = "
<p>HQ illustrations are preferred in 300 dpi greyscale, or 300 dpi color for color images.</p>

<p>See the <a href='http://www.pgdp.net/wiki/Db-req#Add_illustration_images_to_a_project'>Add Illustrations</a>
section of the DP wiki entry for db-req.</p>";

$modules['hq_illos_help']['guts'] = '';

$modules['quick_links']['title'] = 'Handy Links';
$modules['quick_links']['blurb'] = <<<EOBLURB

<table border='0'>

<tr>
<th>Local:</th>
<td>
<ul>
<li><a href='mailto:db-req@pgdp.net'>Email db-req</a></li>
</ul>
</td><td>
<ul>
<li><a href='http://www.pgdp.net/wiki/Missing_pages'>Missing Pages Wiki</a></li>
</ul>
</td>
</tr>

<!--ROW-->

<tr>
<th>External:</th>
<td>
<ul>
<li><a href='http://books.google.com/'>Google Books</a></li>
</ul>
</td>
<td>
<ul>
<li><a href='http://www.archive.org/advancedsearch.php'>archive.org</a></li>
</ul>
</td>
<td>
<ul>
<li><a href='http://catalog.loc.gov/'>LOC Catalog</a></li>
</ul>
</td>
<td>
<ul>
<li><a href='http://www.worldcat.org/'>WorldCat.org</a></li>
</ul>
</td>

</tr></table>

EOBLURB;





$modules['task_list']['title'] = 'Open Tasks';
$modules['task_list']['blurb'] = '';
$modules['task_list']['guts'] = <<<GUTS

\$result = dpsql_query("SELECT * FROM project_hospital
        WHERE type = 'task_list'
        ORDER BY date DESC
        LIMIT 1");

\$task_list = mysql_fetch_object(\$result);

echo "<p id='last_updated' style='font-size: 80%;'>Last updated ".date("r",\$task_list->date)." by \$task_list->username</p>";

echo "<div id='task_list_con'>".
       "<div id='task_list_stat'>\$task_list->text</div>".
      "</div>";

echo "<div id='task_list_controls'>
    <span onclick='edit_task_list();' class='link'>[Edit]</span>
    <span onclick='update_task_list()' class='link'>[Update]</span>
</div>";

GUTS;

$modules['case_list']['title'] = 'Broken Projects';
$modules['case_list']['blurb'] = '<p>[CS] and [E] in this module stand for \'Change State\' and \'Edit project\' respectively.</p>';
$modules['case_list']['guts'] = <<<GUTS

\$result = dpsql_query("SELECT * FROM projects 
        WHERE nameofwork LIKE '%needs fixing%'
            AND state != 'project_delete'
        ORDER BY t_last_edit DESC");


echo "<h3>The following ";
echo mysql_num_rows(\$result);
echo " projects (identified by '{needs fixing}' in the title) are known to be broken in some way.</h3>";
echo "<table id='projects'><tr><th>Project Details</th><th>Manager</th><th>Known Problem(s)</th></tr>";

while (\$project = mysql_fetch_object(\$result))
{

// Wish I could make the display name work properly
//        \$imso_code = \$project->image_source;
//      if ( strcmp(\$imso_code,'_internal') = 0 )
//		{
//            \$credit_name = "DP Internal";
//		}
//	else
//            {
//            \$imso_res = mysql_fetch_assoc(mysql_query("
//            SELECT full_name, credit FROM image_sources WHERE code_name = '$imso_code'
//            "));
//            \$credit_name = \$imso_res['full_name'];
//            }

    echo "<tr>
        <td>
	    <a href='\$code_url/project.php?id=\$project->projectid'>\$project->nameofwork</a>
	<ul>
	    <li><span class='pre'>State :</span> ".project_states_text(\$project->state)."</li>
	    <li><span class='pre'>Author:</span> \$project->authorsname</li>
	    <li><span class='pre'>Source:</span> \$project->image_source</li>
	    <li><span class='pre'>Pages :</span> \$project->n_pages</li>
	</ul>
            <a href='\$code_url/tools/project_manager/projectmgr.php?show=search&projectid=\$project->projectid'>[CS]</a>&nbsp;
            <a href='\$code_url/tools/project_manager/editproject.php?action=edit&project=\$project->projectid'>[E]</a>&nbsp;
	    <a href='\$code_url/tools/proofers/project_topic.php?project=\$project->projectid'>[Forum Thread]</a>
	    <br>&nbsp;

	</td>
        <td>
        \$project->username
	<br>
	</td>

        <td><pre>";

    \$matches = '';

    \$problems = preg_match('/<pre>([^<]*)<\/pre>/',
        \$project->comments,\$matches);

    echo (\$matches) ?  wordwrap(\$matches[0],75) : '&nbsp;';

    echo "</pre></td></tr>";
}

echo "</table>";

GUTS;


}
// vim: sw=4 ts=4 expandtab
?>
