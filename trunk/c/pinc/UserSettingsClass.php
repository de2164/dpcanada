<?php

// $Id: UserSettingsClass.php

/**
 * UserSettings Class.

  Note: Everything marked "Implementation:" is information
        that should remain encapsulated inside the class.

 This class provides access to user settings.

 There are three setting types:

1. Boolean settings. Return value is True or False.
 Example setting: post_proofer

Setting to True will set the value column to "yes".
Setting to anything else will delete the record.
Returns whether there is a record with "yes" for in value field.

2. Value settings. Non-empty string settings.
Example: R1order (sort order of project listing) 
Example value: "GenreA" (Genre Ascending)

Setting will deleted a matching record if the value 
resolves to Null in PHP (including zero-valued numbers).

Will provide a record with the value set to the string argument,
converted if necessary.

Getting will return the string in the value column.

3. Two-key boolean (e.g. username and settingname).
Example: posted_notice, value "projectID3dfe7f4b3c2ca"

Setting: Set two strings and a boolean;
Getting: Given two strings, return a boolean.
*/
        

function ExecSQL($sql)
{
    $result = mysql_query($sql);
    if(mysql_errno())
        echo mysql_error();
}

class Settings
{
    // assoc. array containing all user setting name/value pairs.
    var $settings_array = array();
    // username of subject user.
    var $username;

    function Settings($name)
    {
        $this->username = $name;

        if($name == '')
        {
            // nothing to do.
            return;
        }


        // Get an array with the usersettings rows for our user.
        $sql = "SELECT  u.sitemanager,
                        u.manager,
                        u.postprocessor,
                        us.*
            FROM users AS u
            LEFT JOIN usersettings AS us 
                ON u.username = us.username
            WHERE u.username = '$name'";

        $result = mysql_query($sql);
        // To know whether we've populated fields from 'users' table yet:
        $isUserVals = false;
        while ($row = mysql_fetch_assoc($result))
        {
            // get the onesies from the first row
            // yes, this is more efficient than two separate queries.
            if(!$isUserVals)
            {
                $isUserVals = true;
                if ($row['sitemanager'] == 'yes')
                    $this->settings_array['sitemanager'] = 'yes';
                if ($row['manager'] == 'yes')
                    $this->settings_array['manager'] = 'yes';
                if ($row['postprocessor'] == 'yes')
                    $this->settings_array['postprocessor'] = 'yes';
            }
            // continue to add values from usersettings table.
            // (null values if the outer join matched nothing)
            // check there is something in the "setting" column.
            if($row['setting'])
                $this->settings_array[$row['setting']] = $row['value'];
        }
        mysql_free_result($result);
    }

    // check if this is an updateable setting in this context.
    function _isSettable($settingCode)
    {
        switch($settingCode)
        {
            default:
                return true;
            case 'sitemanager':
            case 'manager':
            case 'postprocessor':
                return false;
        }
    }

    // whom do I describe?
    function UserName()
    {
        return $this->username ? $this->username : "[none]";
    }

    // -------------------------------------------------------------------------

    // Setting to True
    function set_true($settingCode)
    {
        $this->set_boolean($settingCode, TRUE);
    }

    // Setting to False
    function set_false($settingCode)
    {
        $this->set_boolean($settingCode, FALSE);
    }

    function set_boolean($settingCode, $boolval)
    // A wrapper around set_value() for boolean values.
    {
        $this->set_value($settingCode, ($boolval ? 'yes' : NULL) );
    }

    // Return True iff the setting exists and its value is 'yes'.
    // Otherwise, return False.
    function get_boolean($settingCode)
    {
        return ( $this->get_value($settingCode) === 'yes' );
    }

    // -------------------------------------------------------------------------

    // If $value is NULL, remove the setting.
    // Otherwise, set Value to $value.
    function set_value($settingCode, $value)
    {
        if(!$this->_isSettable($settingCode))
        {
            die("Error: cannot set '$settingCode'");
        }

        if(is_null($value))
        {
            $sql = "DELETE FROM usersettings 
                    WHERE username = '$this->username'
                    AND setting = '$settingCode'" ;
            ExecSQL($sql);
            unset($this->settings_array[$settingCode]);
        }
        else
        {
            if (array_key_exists($settingCode, $this->settings_array)) {
                $sql = "UPDATE usersettings
                        SET value = '$value'
                        WHERE username = '$this->username'
                        AND setting = '$settingCode'" ;
            }
            else {
                $sql = "INSERT INTO usersettings
                        (username, setting, value)
                        VALUES ('$this->username', '$settingCode', '$value')" ;
            }
            ExecSQL($sql);
            $this->settings_array[$settingCode] = $value;
        }
    }

    // If no record exists, return $default.
    // Otherwise return what's in the Value column.
    // Note: if setting is boolean, this will return 'yes'.

    function get_value($settingCode, $default = Null)
    {
        if (!array_key_exists($settingCode, 
                        $this->settings_array)) 
            return $default;
        return $this->settings_array[$settingCode];
    }

    // -------------------------------------------------------------------------

    function settings_count()
    {
        return count($this->settings_array);
    }

    // Get an object for this $username. If such an object has
    // already been created, return it. By using this function only
    // and not the constructor, there will only be one object
    // around for each user, and no problems will arise with
    // setting/getting the same settings at various places.
    // If the name is not set, null is returned.
    //
    // We use assignment-by-reference and return-by-reference (note the ampersands)
    // to ensure that multiple returns for the same $username are references to
    // the originally created object, rather than copies of it.
    // (See the PHP manual under "Assignment Operators" and "References Explained".)
    // Callers should also use assignment-by-reference, e.g.
    //     $settings =& Settings::get_settings($username);
    // to ensure that changes in settings are visible everywhere they should be.
    //
    function & get_Settings($username)
    {
        static $Settings_for_ = array();
        if (array_key_exists($username, $Settings_for_))
        {
            return $Settings_for_[$username];
        }
        else
        {
            $settings =& new Settings($username);
            $Settings_for_[$username] =& $settings;
            return $settings;
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
