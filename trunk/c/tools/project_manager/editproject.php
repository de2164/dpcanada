<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'marc_format.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'comment_inclusions.inc');
include_once('edit_common.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'project_events.inc');

$popHelpDir="$code_url/faq/pophelp/project_manager/";
include_once($relPath.'js_newpophelp.inc');

if ( !user_is_PM() )
{
    die('permission denied');
}

$pih = new ProjectInfoHolder;

if (isset($_POST['saveAndQuit']) || isset($_POST['saveAndProject']) || isset($_POST['saveAndPreview']) )
{
    $errors = $pih->set_from_post();
    if (empty($errors))
    {
        $pih->save_to_db();
        if (isset($_POST['saveAndQuit']))
        {
            metarefresh(0, "projectmgr.php", _("Save and Quit"), "");
            exit;
        }
        elseif (isset($_POST['saveAndProject']))
        {
            metarefresh(0, "$code_url/project.php?id=$pih->projectid", _("Save and Go To Project"), "");
            exit;
        }
        elseif (isset($_POST['saveAndPreview']))
        {
            // No errors, but fall through.
        }
    }
    else
    {
        // Errors.
        // fall through
    }

    if ( isset($pih->projectid) )
    {
        $page_title = _("Edit a Project");
    }
    else
    {
        if ( isset($pih->up_projectid) )
        {
            $page_title = _("Create a Project from an Uber Project");
        }
        elseif ( isset($pih->original_marc_array_encd) )
        {
            $page_title = _("Create a Project from a MARC Record");
        }
        else
        {
            $page_title = _("Create a Project");
        }
    }

    theme($page_title, "header");
    echo "<br><h2 align='center'>$page_title</h2>\n";

    if ($errors != '')
    {
        echo "<br><center><font size='+1' color='#ff0000'><b>$errors</b></font></center>";
    }

    $pih->show_form();

    if ( isset($_POST['saveAndPreview']))
    {
        $pih->preview();
    }

    theme("", "footer");
}
else
{
    $requested_action = @$_REQUEST['action'];

    if ( $requested_action == 'createnew' )
    {
        $page_title = _("Create a Project");
        $fatal_error = $pih->set_from_nothing();
    }
    elseif ( $requested_action == 'createnewfromuber' )
    {
        $page_title = _("Create a Project from an Uber Project");
        $fatal_error = $pih->set_from_uberproject();
    }
    elseif ( $requested_action == 'create_from_marc_record' )
    {
        $page_title = _("Create a Project from a MARC Record");
        $fatal_error = $pih->set_from_marc_record();
    }
    elseif ( $requested_action == 'edit' )
    {
        $page_title = _("Edit a Project");
        $fatal_error = $pih->set_from_db();
    }
    else
    {
        $page_title = 'editproject.php';
        $fatal_error = _("parameter 'action' is invalid") . ": '$requested_action'";
    }

    theme($page_title, "header");
    echo "<br><h2 align='center'>$page_title</h2>\n";

    if ($fatal_error != '')
    {
        $fatal_error = _('site error') . ': ' . $fatal_error;
        echo "<br><center><font size='+1' color='#ff0000'><b>$fatal_error</b></font></center>";
        theme('', 'footer');
        exit;
    }

    $pih->show_form();

    theme("", "footer");
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class ProjectInfoHolder
{
    function set_from_nothing()
    {
        global $pguser;

        $this->nameofwork       = '';
        $this->authorsname      = '';
        $this->checkedoutby     = '';
        $this->language         = '';
        $this->scannercredit    = '';
        $this->comments         = '';
        $this->clearance        = '';
        $this->postednum        = '';
        $this->genre            = '';
        $this->difficulty_level = ( $pguser == "BEGIN" ? "beginner" : "average" );
        $this->special_code     = '';
        $this->image_source     = '_internal';
        $this->image_preparer   = $pguser;
        $this->text_preparer    = $pguser;
        $this->extra_credits    = '';
        $this->deletion_reason  = '';
        // $this->year          = '';
    }

    // -------------------------------------------------------------------------

    function set_from_marc_record()
    {
        global $pguser;

        if (!isset($_POST['rec']))
        {
            return _("parameter 'rec' is unset");
        }

        $r1 = $_POST['rec'];
        if ( $r1 == '' )
        {
            return _("parameter 'rec' is empty");
        }

        $r2 = base64_decode($r1);
        if ( $r2 === FALSE )
        {
            return _("parameter 'rec' cannot be decoded");
        }

        $r3 = unserialize($r2);
        if ( $r3 === FALSE )
        {
            return _("parameter 'rec' cannot be unserialized");
        }

        $this->nameofwork  = marc_title($r3);
        $this->authorsname = marc_author($r3);
        $this->language    = marc_language($r3);
        $this->genre       = marc_literary_form($r3);

        $this->checkedoutby     = '';
        $this->scannercredit    = '';
        $this->comments         = '';
        $this->clearance        = '';
        $this->postednum        = '';
        $this->difficulty_level = ( $pguser == "BEGIN" ? "beginner" : "average" );
        $this->special_code     = '';
        $this->image_source     = '_internal';
        $this->image_preparer   = $pguser;
        $this->text_preparer    = $pguser;
        $this->extra_credits    = '';
        $this->deletion_reason  = '';

        $this->original_marc_array_encd = $r1;
    }

    // -------------------------------------------------------------------------

    function set_from_uberproject()
    {
        if (!isset($_GET['up_projectid']))
        {
            return _("parameter 'up_projectid' is unset");
        }

        $up_projectid = $_GET['up_projectid'];
        if ( $up_projectid == '' )
        {
            return _("parameter 'up_projectid' is empty");
        }

        $result = mysql_query("SELECT * FROM uber_projects WHERE up_projectid = $up_projectid");
        if (mysql_num_rows($result) == 0)
        {
            return _("parameter 'up_projectid' is invalid") . ": '$up_projectid'";
        }

        // TODO: check that user has permission to create a project from this UP

        $up_info = mysql_fetch_assoc($result);

        $this->nameofwork       = $up_info['d_nameofwork'];
        $this->authorsname      = $up_info['d_authorsname'];
        $this->checkedoutby     = $up_info['d_checkedoutby'];
        $this->language         = $up_info['d_language'];
        $this->scannercredit    = $up_info['d_scannercredit'];
        $this->comments         = $up_info['d_comments'];
        $this->clearance        = $up_info['d_clearance'];
        $this->postednum        = $up_info['d_postednum'];
        $this->genre            = $up_info['d_genre'];
        $this->difficulty_level = $up_info['d_difficulty'];
        $this->special_code     = $up_info['d_special'];
        $this->image_source     = $up_info['d_image_source'];
        $this->image_preparer   = $up_info['d_image_preparer'];
        $this->text_preparer    = $up_info['d_text_preparer'];
        $this->extra_credits    = $up_info['d_extra_credits'];
        $this->deletion_reason  = '';

        // $this->year          = $up_info['d_year'];

        $this->up_projectid     = $up_projectid;
    }

    // -------------------------------------------------------------------------

    function set_from_db()
    {
        if (!isset($_GET['project']))
        {
            return _("parameter 'project' is unset");
        }

        $projectid = $_GET['project'];
        if ( $projectid == '' )
        {
            return _("parameter 'project' is empty");
        }

        $ucep_result = user_can_edit_project($projectid);
        if ( $ucep_result == PROJECT_DOES_NOT_EXIST )
        {
            return _("parameter 'project' is invalid: no such project").": '$projectid'";
        }
        else if ( $ucep_result == USER_CANNOT_EDIT_PROJECT )
        {
            return _("you are not allowed to edit this project").": '$projectid'";
        }
        else if ( $ucep_result == USER_CAN_EDIT_PROJECT )
        {
            // fine
        }
        else
        {
            return _("unexpected return value from user_can_edit_project") . ": '$ucep_result'";
        }

        $res = mysql_query("SELECT * FROM projects WHERE projectid = '$projectid'");
        if (mysql_num_rows($res) == 0)
        {
            return _("parameter 'project' is invalid") . ": '$projectid'";
        }

        $ar = mysql_fetch_array($res);

        $this->projectid        = $ar['projectid'];
        $this->nameofwork       = $ar['nameofwork'];
        $this->authorsname      = $ar['authorsname'];
        $this->checkedoutby     = $ar['checkedoutby'];
        $this->language         = $ar['language'];
        $this->scannercredit    = $ar['scannercredit'];
        $this->comments         = $ar['comments'];
        $this->clearance        = $ar['clearance'];
        $this->postednum        = $ar['postednum'];
        $this->genre            = $ar['genre'];
        $this->difficulty_level = $ar['difficulty'];
        $this->special_code     = $ar['special_code'];
        $this->image_source     = $ar['image_source'];
        $this->image_preparer   = $ar['image_preparer'];
        $this->text_preparer    = $ar['text_preparer'];
        $this->extra_credits    = $ar['extra_credits'];
        $this->deletion_reason  = $ar['deletion_reason'];
        $this->up_projectid     = $ar['up_projectid'];

        $this->posted = @$_GET['posted'];
    }

    // -------------------------------------------------------------------------

    function set_from_post()
    {
        if ( get_magic_quotes_gpc() )
        {
            // Values in $_POST come with backslashes added.
            // We want the fields of $this to be unescaped strings,
            // so we strip the slashes.
            $_POST = array_map('stripslashes', $_POST);
        }

        $errors = '';

        if ( isset($_POST['projectid']) )
        {
            $this->projectid = $_POST['projectid'];

            $ucep_result = user_can_edit_project($this->projectid);
            if ( $ucep_result == PROJECT_DOES_NOT_EXIST )
            {
                return _("parameter 'projectid' is invalid: no such project").": '$this->projectid'";
            }
            else if ( $ucep_result == USER_CANNOT_EDIT_PROJECT )
            {
                return _("you are not allowed to edit this project").": '$this->projectid'";
            }
            else if ( $ucep_result == USER_CAN_EDIT_PROJECT )
            {
                // fine
            }
            else
            {
                return _("unexpected return value from user_can_edit_project") . ": '$ucep_result'";
            }
        }

        $this->nameofwork = @$_POST['nameofwork'];
        if ( $this->nameofwork == '' ) { $errors .= "Name of work is required.<br>"; }

        $this->authorsname = @$_POST['authorsname'];
        if ( $this->authorsname == '' ) { $errors .= "Author is required.<br>"; }

        $pri_language = @$_POST['pri_language'];
        if ( $pri_language == '' ) { $errors .= "Primary Language is required.<br>"; }

        $sec_language = @$_POST['sec_language'];

        $this->language = (
            $sec_language != ''
            ? "$pri_language with $sec_language"
            : $pri_language );

        $this->genre = @$_POST['genre'];
        if ( $this->genre == '' ) { $errors .= "Genre is required.<br>"; }

        $this->image_source = @$_POST['image_source'];
        if ($this->image_source == '')
        {
            $errors .= "Image Source is required. If the one you want isn't in list, you can propose to add it.<br>";
            $this->image_source = '_internal';
        }

	/*
        else
        {
            if ($this->image_source == 'OTHER')
            {
                if (empty($_POST['imso_other']))
                {
                    $errors .= "When Image Source is OTHER, details must be supplied.<br>";
                }
                else
                {
                    $imso_other = $_POST['imso_other'];
                    $this->image_source = "O:".$imso_other;
                }
            }
        }

	*/


        $this->special_code = @$_POST['special_code'];
        if ($this->special_code != '')
        {
            if ( startswith($this->special_code, 'Birthday') ||
                 startswith($this->special_code, 'Otherday')
            )
            {
                if (empty($_POST['bdayday']) or empty($_POST['bdaymonth']))
                {
                    $errors .= "Month and Day are required for Birthday or Otherday Specials.<br>";
                }
                else
                {
                    $bdaymonth = $_POST['bdaymonth'];
                    $bdayday = $_POST['bdayday'];
                    if (!checkdate ( $bdaymonth, $bdayday, 2000))
                    {
                        $errors .= "Invalid date supplied for Birthday or Otherday Special.<br>";
                    }
                    else
                    {
                        if (strlen($this->special_code) == 8) { $this->special_code .= " ".$bdaymonth.$bdayday; }
                    }
                }
            }
        }

        $this->checkedoutby = @$_POST['checkedoutby'];
        if ($this->checkedoutby != '')
        {
            $res = mysql_query("
                SELECT u_id
                FROM users
                WHERE BINARY username = '".addslashes($this->checkedoutby)."'
            ");
            if (mysql_num_rows($res) == 0)
            {
                $errors .= "PPer/PPVer must be an existing user - check case and spelling of username.<br>";
            }
        }

        $this->image_preparer = @$_POST['image_preparer'];
        if ($this->image_preparer != '')
        {
            $res = mysql_query("
                SELECT u_id
                FROM users
                WHERE BINARY username = '".addslashes($this->image_preparer)."'
            ");
            if (mysql_num_rows($res) == 0)
            {
                $errors .= "Image Preparer must be an existing user - check case and spelling of username.<br>";
            }
        }

        $this->text_preparer = @$_POST['text_preparer'];
        if ($this->text_preparer != '')
        {
            $res = mysql_query("
                SELECT u_id
                FROM users
                WHERE BINARY username = '".addslashes($this->text_preparer)."'
            ");
            if (mysql_num_rows($res) == 0)
            {
                $errors .= "Text Preparer must be an existing user - check case and spelling of username.<br>";
            }
        }

        $this->posted    = @$_POST['posted'];
        $this->postednum = @$_POST['postednum'];
        if ( $this->posted )
        {
            // We are in the process of marking this project as posted.
            if ( $this->postednum == '' )
            {
                $errors .= "Posted Number is required.<br>";
            }
            else if ( ! preg_match('/^[1-9][0-9]*$/', $this->postednum ) )
            {
                $errors .= "Posted Number \"$this->postednum\" is not of the correct format.<br>";
            }
        }

        $this->scannercredit    = @$_POST['scannercredit'];
        $this->comments         = @$_POST['comments'];
        $this->clearance        = @$_POST['clearance'];
        $this->difficulty_level = @$_POST['difficulty_level'];
        $this->up_projectid     = @$_POST['up_projectid'];
        $this->original_marc_array_encd = @$_POST['rec'];
        $this->extra_credits    = @$_POST['extra_credits'];
        $this->deletion_reason  = @$_POST['deletion_reason'];

        if ($this->difficulty_level == '')
        {
            global $pguser;
            $this->difficulty_level = ( $pguser == "BEGIN" ? "beginner" : "average" );
        }

        return $errors;
    }

    // -------------------------------------------------------------------------

    function save_to_db()
    {
        global $projects_dir, $uploads_dir, $pguser;

        $postednum_str = ($this->postednum == "") ? "NULL" : "'$this->postednum'";

        // Call addslashes() on any members of $this that might contain 
        // single-quotes/apostrophes (because they are unescaped, and
        // would otherwise break the query).

        $common_project_settings = "
            t_last_edit    = UNIX_TIMESTAMP(),
            up_projectid   = '{$this->up_projectid}',
            nameofwork     = '".addslashes($this->nameofwork)."',
            authorsname    = '".addslashes($this->authorsname)."',
            language       = '{$this->language}',
            genre          = '{$this->genre}',
            difficulty     = '{$this->difficulty_level}',
            special_code   = '{$this->special_code}',
            clearance      = '".addslashes($this->clearance)."',
            comments       = '".addslashes($this->comments)."',
            image_source   = '{$this->image_source}',
            scannercredit  = '".addslashes($this->scannercredit)."',
            checkedoutby   = '{$this->checkedoutby}',
            postednum      = $postednum_str,
            image_preparer = '{$this->image_preparer}',
            text_preparer  = '{$this->text_preparer}',
            extra_credits  = '".addslashes($this->extra_credits)."',
            deletion_reason= '".addslashes($this->deletion_reason)."'
        ";

        if (isset($this->projectid))
        {
            // We are updating an already-existing project.

            // Update the projects database with the updated info
            mysql_query("
                UPDATE projects SET
                    $common_project_settings
                WHERE projectid='{$this->projectid}'
            ") or die(mysql_error());

            $e = log_project_event( $this->projectid, $GLOBALS['pguser'], 'edit' );
            if ( !empty($e) ) die($e);

            $result = mysql_query("
                SELECT updated_array
                FROM marc_records
                WHERE projectid = '{$this->projectid}'
            ");
            $current_marc_array_encd = mysql_result($result,0,"updated_array");
            $current_marc_array = unserialize(base64_decode($current_marc_array_encd));

            // Update the MARC array with any info we've received.
            $updated_marc_array = update_marc_array($current_marc_array);
            $updated_marc_str = convert_marc_array_to_str($updated_marc_array);

            mysql_query("
                UPDATE marc_records
                SET
                    updated_array = '".base64_encode(serialize($updated_marc_array))."',
                    updated_marc  = '".base64_encode(serialize($updated_marc_str))."'
                WHERE projectid = '$this->projectid'
            ");
        }
        else
        {
            $this->projectid = uniqid("projectID"); // The project ID

            // Insert a new row into the projects table
            mysql_query("
                INSERT INTO projects
                SET
                    projectid    = '{$this->projectid}',
                    username     = '{$GLOBALS['pguser']}',
                    state        = '".PROJ_NEW."',
                    modifieddate = UNIX_TIMESTAMP(),
                    $common_project_settings
            ") or die(mysql_error());

            $e = log_project_event( $this->projectid, $GLOBALS['pguser'], 'creation' );
            if ( !empty($e) ) die($e);

            $e = project_allow_pages( $this->projectid );
            if ( !empty($e) ) die($e);  // 2008-09-16 jmdyck: rev 1.164

            // Make a directory in the projects_dir for this project
            mkdir("$projects_dir/$this->projectid", 0777) or die("System error: unable to mkdir '$projects_dir/$this->projectid'");
            chmod("$projects_dir/$this->projectid", 0777);

            $original_marc_array = unserialize(base64_decode($this->original_marc_array_encd));
            $original_marc_str = convert_marc_array_to_str($original_marc_array);

            // Update the MARC array with any info we've received.
            $updated_marc_array = update_marc_array($original_marc_array);
            $updated_marc_str = convert_marc_array_to_str($updated_marc_array);

            mysql_query("
                INSERT INTO marc_records
                SET
                    projectid      = '$this->projectid',
                    original_array = '".base64_encode(serialize($original_marc_array))."',
                    original_marc  = '".base64_encode(serialize($original_marc_str))."',
                    updated_array  = '".base64_encode(serialize($updated_marc_array))."',
                    updated_marc   = '".base64_encode(serialize($updated_marc_str))."'
            ");
        }

// TODO not scannercredit below!

        // Create/update the Dublin Core file for the project.
        create_dc_xml_oai(
            $this->projectid,
            $this->scannercredit,
            $this->genre,
            $this->language,
            $this->authorsname,
            $this->nameofwork,
            $updated_marc_array );

        // If the project has been posted to PG, make the appropriate transition.
        if ($this->posted)
        {
            $err = project_transition( $this->projectid, PROJ_SUBMIT_PG_POSTED, $pguser );
            if ( $err != '' )
            {
                echo "$err<br>\n";
                exit;
            }
        }
    }

    // =========================================================================

    function show_form()
    {
        global $theme;

        echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>";

        $this->show_hidden_controls();

        echo "<br>";
        echo "<center>";
        echo "<table cellspacing='0' cellpadding='5' border='1' width='90%' bordercolor='#000000' style='border-collapse:collapse'>";

        $this->show_visible_controls();

        echo "<tr>";
        echo   "<td bgcolor='#CCCCCC' colspan='2' align='center'>";
        echo     "<input type='submit' name='saveAndQuit' value='"._("Save and Quit")."'>";
        echo     "<input type='submit' name='saveAndProject' value='"._("Save and Go To Project")."'>";
        echo     "<input type='submit' name='saveAndPreview' value='"._("Save and Preview")."'>";
        echo     "<input type='button' value='"._("Quit Without Saving")."' onclick='javascript:location.href=\"projectmgr.php\";'>";
        echo   "</td>";
        echo "</tr>\n";

        echo "</table>";
        echo "</center>";
        echo "</form>";
        echo "\n";
    }

    // -------------------------------------------------------------------------

    function show_hidden_controls()
    {
        if (!empty($this->original_marc_array_encd))
        {
            echo "<input type='hidden' name='rec' value='$this->original_marc_array_encd'>";
        }
        if (!empty($this->posted))
        {
            echo "<input type='hidden' name='posted' value='1'>";
        }
        if (!empty($this->projectid))
        {
            echo "<input type='hidden' name='projectid' value='$this->projectid'>";
        }
        if (!empty($this->up_projectid))
        {
            echo "<input type='hidden' name='up_projectid' value='$this->up_projectid'>";
        }
    }

    // -------------------------------------------------------------------------

    function show_visible_controls()
    {
        if (!empty($this->projectid))
        {
            $this->row( _("Project ID"), 'just_echo', $this->projectid );

            // Somewhat kludgey to have to do this query here.
            $res = mysql_query("
                SELECT state
                FROM projects
                WHERE projectid='{$this->projectid}'
            ") or die(mysql_error());
            list($state) = mysql_fetch_row($res);
            if ($state == PROJ_DELETE)
            {
                $this->row( _("Reason for Deletion"),         'text_field',          $this->deletion_reason, 'deletion_reason' );
            }
        }
        if (!empty($this->up_projectid))
        {
            $res2 = mysql_query("
                SELECT up_nameofwork
                FROM uber_projects
                WHERE up_projectid = '$this->up_projectid'
            ");
            $up_nameofwork = mysql_result($res2, 0, "up_nameofwork");

            $this->row( _("Related Uber Project"), 'just_echo', $up_nameofwork );
        }
        $this->row( _("Name of Work"),                'text_field',          $this->nameofwork,      'nameofwork' );
        $this->row( _("Author's Name"),               'text_field',          $this->authorsname,     'authorsname' );
        $this->row( _("Language"),                    'language_list',       $this->language         );
        $this->row( _("Genre"),                       'genre_list',          $this->genre            );
        $this->row( _("Difficulty Level"),            'difficulty_list',     $this->difficulty_level );
        $this->row( _("Special Day (optional)"),      'special_list',        $this->special_code     );
        $this->row( _("PPer/PPVer"),                  'DP_user_field',       $this->checkedoutby,    'checkedoutby' , _("Optionally reserve for a PPer. DP username only."));
        $this->row( _("Original Image Source"),       'image_source_list',   $this->image_source     );
        $this->row( _("Image Preparer"),              'DP_user_field',       $this->image_preparer,  'image_preparer', _("DP user who scanned or harvested the images."));
        $this->row( _("Text Preparer"),               'DP_user_field',       $this->text_preparer,   'text_preparer', _("DP user who prepared the text files.") );
        $this->row( _("Extra Credits (to be included in list of names)"),   
                                               'extra_credits_field', $this->extra_credits);
        if ($this->scannercredit != '') {
            $this->row( _("Scanner Credit (deprecated)"), 'text_field',      $this->scannercredit,   'scannercredit' );
        }
        $this->row( _("Clearance Information"),       'text_field',          $this->clearance,       'clearance' );
        $this->row( _("Posted Number"),               'text_field',          $this->postednum,       'postednum' );
        $this->row( _("Project Comments"),            'proj_comments_field', $this->comments         );
    }

    function row( $label, $display_function, $field_value, $field_name=NULL, $explan='' )
    {
        echo "<tr>";
        echo   "<td bgcolor='#CCCCCC'>";
        echo     "<b>$label</b>";
        echo   "</td>";
        echo   "<td>";
        $display_function( $field_value, $field_name );
        echo   "  ";
        echo   $explan;
        echo   "</td>";
        echo "</tr>";
        echo "\n";
    }

    // =========================================================================

    function preview()
    {
        global $pguser;

        // insert e.g. templates and biographies
        $comments = parse_project_comments($this->comments);

        $a = _("The Guidelines give detailed instructions for working in this round.");
        $b = _('The instructions below are particular to this project, and <b>take precedence over those guidelines</b>.');

        $now = strftime(_("%A, %B %e, %Y at %X"));

        echo "<br><table width='90%' border=1>";
        echo "<tr><td align='middle' bgcolor='#cccccc'><h3>Preview<br>Project</h3></td>";
        echo "<td bgcolor='#cccccc'><b>This is a preview of your project and roughly how it will look to the proofreaders.</b></td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Title</b></td><td>$this->nameofwork</td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Author</b></td><td>$this->authorsname</td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Project Manager</b></td><td>$pguser</td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Last Proofread</b></td><td>$now</td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Forum</b></td><td>Start a discussion about this project</td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Book Completed</b></td><td>Yes, I would like to be notified when this has been posted to Project Gutenberg.</td></tr>\n";

        echo "<tr><td colspan='2' bgcolor='#cccccc' align='center'>";
        echo "<font size='+1'><b>Project Comments</b></font>";
        echo "<br>$a<br>$b";
        echo "</td></tr>\n";
        echo "<tr><td colspan='2'>";
        echo $comments;
        echo "</td></tr>\n";

        echo "</table><br><br>";
    }
}

// vim: sw=4 ts=4 expandtab
?>
