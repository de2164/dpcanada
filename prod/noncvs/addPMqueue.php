<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

if ( !user_is_a_sitemanager() )
{
    die("permission denied");
}

echo "
<!-- Trigger IE quirks mode -- not that it needs any more quirks! -->
<html><head><title></title><style type='text/css'>table { border-collapse:collapse; }
table td, table th { border:1px solid black; padding:2px; text-align: left; }
.number {text-align: right; width: 2em;}
</style>

</head><body>
<pre>
";
echo "<h2>Add a PM queue</h2>\n";

$submit_button = array_get( $_POST, 'submit_button', '' );
switch ( $submit_button )
{
    case '':
        echo "<form method='post'>\n";
        echo "username: <input name='username' type='text' size='26' value='$username'>\n";
        echo "round: <select name='round'>
                     <option value='P1'>P1</option>
                     <option value='P2'>P2</option>
                     <option value='P3'>P3</option>
                     </select>\n";
        echo "<input type='submit' name='submit_button' value='Submit'>";
        echo "</form>";
        echo "</pre></body>";
        break;

    case 'Submit':
        $username = array_get( $_POST, 'username',  '' );
        $round = array_get( $_POST, 'round',  '' );
        do_stuff($username, $round);
        echo "</pre></body>";
        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        echo "</pre></body>";
        break;
}

function do_stuff($username, $round)
{
    if ( empty($username) )
    {
        die("Error: no username supplied");
    }
    if ( empty($round) )
    {
        die("Error: no round supplied");
    }
    $slashedname = addslashes($username);
    // check that the user exists
    $res = mysql_query("
                SELECT u_id
                FROM users
                WHERE username = '$slashedname'
            ");
    if (mysql_num_rows($res) == 0)
    {
        die("Error: no such user $username");
    }
    // check that the user is a PM
    // put this back in when user_is.inc has been updated on the main site
//     if (!that_user_is_PM($username))
//     {
//         die("Error: $username is not a PM");
//     }

    // see if the queue already exists
    $res = mysql_query("
                SELECT *
                FROM queue_defns
                WHERE round_id LIKE '$round'
                AND name LIKE 'PM $username'
            ");
    if (mysql_num_rows($res) > 0)
    {
        die("Error: $round PM queue already exists for $username");
    }

    // get the ordering number
    // note we will run into problems if there is no PM queue for
    // the specified round
    $res = mysql_query("
                SELECT MAX(ordering)
                FROM queue_defns
                WHERE round_id LIKE '$round'
                AND name LIKE 'PM%'
            ")  or die(mysql_error());
    $row = mysql_fetch_row($res);
    $ord = $row[0] + 1;

    // make the project_selector
    $p_sel = "";
    if ($round == 'P1') {
      // P1 PM queues ignore special days
      $p_sel = "special_code = '' AND ";
    }
  
    $p_sel .= "(comments NOT LIKE '(nopmq)%')"; // ignore ones that the PM doesn't want in the PM queue
    $p_sel .= " AND username = '$slashedname'"; // select on the PM
    $p_sel .= " AND (state LIKE '%\_avail' OR comments NOT LIKE '(HOLD)%')"; // don't release ones that are being held
    $p_sel = addslashes($p_sel);
    
    // insert the new row
    $res = mysql_query("
               INSERT into queue_defns
               (round_id, ordering, enabled, name, project_selector, release_criterion, comment)
               VALUES ('$round', $ord, 1, 'PM $slashedname',
                       '$p_sel',
                       'projects < 1',
                       'Let this PM have one project available')
            ") or die(mysql_error());
    echo "\n\n$round PM queue for $username added:\n";
    $res = mysql_query("
                SELECT * FROM queue_defns
                WHERE round_id LIKE '$round'
                AND name LIKE 'PM%'
                AND ordering = $ord
            ")  or die(mysql_error());               
    $row = mysql_fetch_assoc($res);
    echo "Round:               {$row['round_id']}\n";
    echo "Ordering:            {$row['ordering']}\n";
    echo "Enabled:             {$row['enabled']}\n";
    echo "Queue name:          {$row['name']}\n";
    echo "Project selector:    {$row['project_selector']}\n";
    echo "Release criterion:   {$row['release_criterion']}\n";
    echo "Comment:             {$row['comment']}\n";
    
}






