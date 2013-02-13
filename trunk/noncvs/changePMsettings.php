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
<html>
<head>
<title>Edit PM Settings</title>
<style type='text/css'>table { border-collapse:collapse; }
table td, table th { border:1px solid black; padding:2px; text-align: left; }
.number {text-align: right; width: 2em;}
</style>
</head>
<body>
<pre>
";

echo "<h2>Edit PM Settings</h2>\n";

$submit_button = array_get( $_POST, 'submit_button', '' );
switch ( $submit_button )
{
    case '':
        echo "<form method='post'>\n";
        echo "username: <input name='username' type='text' size='26' value='$username'>\n";
        echo "<br>\n";
        // Adding control to set/unset PM status
        echo "User is a PM         : <select name='manager'>
             <option default value='Whatever'>Don't change</option>
             <option value='Yes'>Yes</option>
             <option value='No'>No</option>
             </select>\n";
        // Adding control to say whether a PM's projects go to PP pool
        echo "Send projects to pool: <select name='send_to_post'>
                     <option default value='Whatever'>Don't change</option>
                     <option value='Yes'>Yes</option>
                     <option value='No'>No</option>
                     </select>\n";
        // Adding control to disable new project loads
        echo "Disable Project Loads: <select name='disable_project_loads'>
            <option default value='Whatever'>Don't change</option>
            <option value='Yes'>Yes</option>
            <option value='No'>No</option>
            </select>\n";
        // Adding control to limit number of projects a PPer can check out
        echo "PP projects limit    : <input type='text' name='pp_limit_value' size='2' value='--'> (-1 means no limit, -- means don't change)\n";
        echo "<input type='submit' name='submit_button' value='Submit'>";
        echo "</form>";
        echo "</pre></body>";
        break;

    case 'Submit':
        $username = array_get( $_POST, 'username',  '' );
        $send_to_pool = array_get( $_POST, 'send_to_post',  '' );
        do_stuff_new($username, 'send_to_post', $send_to_pool);
        $ability_to_load = array_get( $_POST, 'disable_project_loads', '');
        do_stuff_new($username, 'disable_project_loads', $ability_to_load);
        $user_is_pm = array_get( $_POST, 'manager', '');
        set_pm_status($username, 'manager', $user_is_pm);
        // PP limit not yet implemented
        $pp_limit_value = array_get( $_POST, 'pp_limit_value', '');
        set_pp_limit($username, 'PP_checked_out_limit', $pp_limit_value);

        echo "</pre></body>";
        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        echo "</pre></body>";
        break;
}

function check_for_valid_inputs($username, $setting_name, $setting_value)
{
    if ( empty($username) )
    {
        echo "Error: no username supplied\n";
        return false;
    }
    if ( empty($setting_name) )
    {
        echo "Error: no setting supplied\n";
        return false;
    }
    if ( empty($setting_value) )
    {
        echo "Error: no value supplied for $setting_name\n";
        return false;
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
        echo "Error: no such user $username\n";
        return false;
    }

    echo "\nExamining request for $setting_name setting for $username ...\n";
    return true;
}

// Note that this is semi-frameworked for changing any "yes/blank" setting in the users table,
// in the sense that $setting_name is variable. The messages and logic would still need to be
// generalized as has been done in the do_stuff_new() function. -donovan

function set_pm_status($username, $setting_name, $setting_value)
{
    if (! check_for_valid_inputs($username, $setting_name, $setting_value))
    {
        die("Error: Invalid input(s)");
    }

    if ( $setting_value == 'Whatever' )
    {
        echo "Not changing PM setting for $username, who is currently ";
        if (! that_user_is_PM($username)) { echo "NOT "; }
        echo "a PM.\n";
        return;
    }

    $slashedname = addslashes($username);

    if ( $setting_value == 'Yes' )
    {
        $res = mysql_query("
        UPDATE users
        SET $setting_name = 'yes'
        WHERE username = '$slashedname'
        ");
        echo "PM setting changed: $username is now a PM.\n";
        return;
    }
    if ($setting_value == 'No' )
    {
        $res = mysql_query("
        UPDATE users
        SET $setting_name = ''
        WHERE username = '$slashedname'
        ");
        echo "PM setting changed: $username is no longer a PM.\n";
    }
}

function set_pp_limit($username, $setting_name, $setting_value)
{
    if (! check_for_valid_inputs($username, $setting_name, $setting_value))
    {
        die("Error: Invalid input(s)");
    }
    // see what the current situation is
    $res = mysql_query("
                SELECT value
                FROM usersettings
                WHERE username LIKE '$username'
                AND setting='$setting_name' 
            ");
    $old_setting = '0';
    if (mysql_num_rows($res) > 0)
    {
        $row =  mysql_fetch_row($res);
        $old_setting = $row[0];
    }
    if ($old_setting == $setting_value
        || $setting_value == '--')
    {
        // do nothing
        echo "Not changing $setting_name for $username.\n";
    }
    else 
    {
        // remove the existing setting
        $res = mysql_query("
               DELETE FROM usersettings 
               WHERE username='$username' AND setting='$setting_name' 
               ")  or die(mysql_error());
        if ($setting_value != '-1')
        {
            $res = mysql_query("
                   INSERT INTO usersettings 
                   SET username='$username', setting='$setting_name', value='$setting_value'
               ")  or die(mysql_error());
        }
    }
    // All should now be as we want it
    $res = mysql_query("
                SELECT value
                FROM usersettings
                WHERE username LIKE '$username'
                AND setting='$setting_name' 
            ");
    $new_setting = '0';
    if (mysql_num_rows($res) > 0)
    {
        $row =  mysql_fetch_row($res);
        $new_setting = $row[0];
        echo "$username may have up to $new_setting projects checked out for PPing.\n";
    }
    else
    {
        echo "There is no limit on the number of projects $username may have checked out for PPing.\n";
    }
}

function do_stuff_new($username, $setting_name, $setting_value)
{

    if (! check_for_valid_inputs($username, $setting_name, $setting_value))
    {
        die("Error: Invalid input(s)");
    }

    // check that the user is a PM
    // This is an inapproriate check if we're trying to MAKE a new PM
    // So, disabling
//    if (! that_user_is_PM($username))
//      {
//          die("Error: $username is not a PM");
//      }

    // see what the current situation is
    $res = mysql_query("
                SELECT *
                FROM usersettings
                WHERE username LIKE '$username'
                AND setting='$setting_name' 
        AND value = 'yes'
            ");
    $old_setting = 'No';
    if (mysql_num_rows($res) > 0)
    {
        $old_setting = 'Yes';
    }

    if ($old_setting == $setting_value ||
        $setting_value == 'Whatever')
    {
        // do nothing
        echo "Not changing $setting_name for $username.\n";
    }
    else if ($setting_value == 'Yes') 
    {
        $res = mysql_query("
               INSERT INTO usersettings 
               SET username='$username', setting='$setting_name', value='yes'
               ")  or die(mysql_error());
    }
    else 
    {
        $res = mysql_query("
               DELETE FROM usersettings 
               WHERE username='$username' AND setting='$setting_name' AND value='yes'
               ")  or die(mysql_error());

    }
    // All should now be as we want it
    $res = mysql_query("
                SELECT *
                FROM usersettings
                WHERE username LIKE '$username'
                AND setting='$setting_name' 
                AND value='yes'
            ");
    $new_setting = 'No';
    if (mysql_num_rows($res) > 0)
    {
        $new_setting = 'Yes';
        switch($setting_name) 
        {
        case 'send_to_post':
            echo "Projects for $username will be sent to the PP pool.\n";
            break;
        case 'disable_project_loads':
            echo "Project loads for $username are disabled.\n";
            break;
        default:
            echo "Changed $setting_name to $setting_value for $username.\n";      
        }
    }
    else 
    {
        switch($setting_name) {
        case 'send_to_post':
            echo "Projects for $username will be sent to them for PPing.\n";
            break;
        case 'disable_project_loads':
            echo "Project loads for $username are enabled.\n";
            break;
        default:
            echo "Changed $setting_name to $setting_value for $username.\n";
        }
    }
    
}
