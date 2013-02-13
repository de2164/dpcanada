<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager()) die("permission denied");

error_reporting(E_ALL);

$categories_array = array(
     7 => "Activity Hub",
     2 => "Documentation",
     3 => "Entrance",
    20 => "HTML Pool",
     4 => "Log in/out",
    24 => "Mentoring",
    25 => "My Projects",
     5 => "New Member",
     1 => "None",
    19 => "OCR Pool",
    99 => "Other",
    26 => "Page Details, Diffs",
     6 => "Page Proofreading",
     8 => "Post-Processing",
    10 => "Pre-Processing",
     9 => "Preferences",
    11 => "Project Comments",
    12 => "Project Listing Interface",
    13 => "Project Manager",
    31 => "Project Notifications",
    23 => "Project Search",
    27 => "Quizzes",
    29 => "Release Queues",
    28 => "Rounds",
    14 => "Site wide",
    18 => "Smooth Reading",
    22 => "Spell Check",
    15 => "Statistics",
    17 => "Task Center",
    30 => "Teams",
    16 => "Translation",
    21 => "phpBB Forums",
);

// --------------------------------------------

$task_id = @$_GET['task_id'];
$new_cat_id = @$_GET['new_cat_id'];

if ( $task_id != '' && $new_cat_id != '' )
{
    $sql = "
        UPDATE tasks
        SET task_category = $new_cat_id
        WHERE task_id = $task_id
    ";
    echo "$sql\n";
    dpsql_query($sql);
}

if ( $task_id == '' ) $task_id = 0;

// --------------------------------------------

// $this_cat_id = 0;
// echo "<h1>", $categories_array[$this_cat_id], "</h1>\n";

$res = dpsql_query("
    SELECT *
    FROM tasks
    WHERE
        -- task_category = $this_cat_id
        instr(concat(task_summary,task_details),'message')
        AND
        task_id >= $task_id
    ORDER BY task_id
");

while ( $task = mysql_fetch_object($res) )
{
    echo "
        <hr>
        $task->task_id<br>
        <b>$task->task_summary</b><br>
        $task->task_details<br>

        <form method='get'>
        <input type='hidden' name='task_id' value='$task->task_id'>
        Recat to
        <select name='new_cat_id'>
    ";
    foreach ( $categories_array as $cat_id => $cat_name )
    {
        $selected = ( $cat_id == $task->task_category ? 'SELECTED' : '' );
        echo "<option value='$cat_id' $selected>$cat_name</option>\n";
    }
    echo "
        </select>
        <input type='submit'>
        </form>
    ";
}

// vim: sw=4 ts=4 expandtab
?>
