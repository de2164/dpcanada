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

$username = @$_GET['username'];

echo "
<!-- Trigger IE quirks mode -- not that it needs any more quirks! -->
<html><head><title></title><style type='text/css'>table { border-collapse:collapse; }
table td, table th { border:1px solid black; padding:2px; text-align: left; }
.number {text-align: right; width: 2em;}
</style>

</head><body>
<pre>
";

echo "<h2>PM pool setting</h2>\n";

$submit_button = array_get( $_POST, 'submit_button', '' );
switch ( $submit_button )
{
    case '':
        echo "<form method='post'>\n";
        echo "username: <input name='username' type='text' size='26' value='$username'>\n";
        echo "send projects to pool: <select name='send_to_pool'>
                     <option value='Whatever'>Don't change</option>
                     <option value='Yes'>Yes</option>
                     <option value='No'>No</option>
                     </select>\n";
        echo "<input type='submit' name='submit_button' value='Submit'>";
        echo "</form>";
        echo "</pre></body>";
        break;

    case 'Submit':
        $username = array_get( $_POST, 'username',  '' );
        $send_to_pool = array_get( $_POST, 'send_to_pool',  '' );
        do_stuff($username, $send_to_pool);
        echo "</pre></body>";
        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        echo "</pre></body>";
        break;
}

function do_stuff($username, $send_to_pool)
{
    if ( empty($username) )
    {
        die("Error: no username supplied");
    }
    if ( empty($send_to_pool) )
    {
        die("Error: no option supplied");
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
    if (! that_user_is_PM($username))
      {
	die("Error: $username is not a PM");
      }

    // see what the current situation is
    $res = mysql_query("
                SELECT *
                FROM usersettings
                WHERE username LIKE '$username'
                AND setting='send_to_post' 
                AND value='yes'
            ");
    $old_setting = 'No';
    if (mysql_num_rows($res) > 0)
    {
      $old_setting = 'Yes';
    }

    if ($old_setting == $send_to_pool ||
	$send_to_pool == 'Whatever')
      {
	// do nothing
	echo "The existing setting has not been changed";
      }
    else if ($send_to_pool == 'Yes') 
      {
	$res = mysql_query("
               INSERT INTO usersettings 
               SET username='$username', setting='send_to_post', value='yes'
               ")  or die(mysql_error());
      }
    else {
	$res = mysql_query("
               DELETE FROM usersettings 
               WHERE username='$username' AND setting='send_to_post' AND value='yes'
               ")  or die(mysql_error());

    }
    // All should now be as we want it
    $res = mysql_query("
                SELECT *
                FROM usersettings
                WHERE username LIKE '$username'
                AND setting='send_to_post' 
                AND value='yes'
            ");
    $new_setting = 'No';
    if (mysql_num_rows($res) > 0)
    {
      $new_setting = 'Yes';
      echo "<p>Projects for $username will be sent to the PP pool.</p>";
    }
    else 
      {
      echo "<p>Projects for $username will be sent to them for PPing.</p>";
      }

    
}
