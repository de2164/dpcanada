<?PHP

// Honour posted-notice subscriptions
// from merged projects to merge-target,
// If the merge-target has been posted,

$relPath='../c/pinc/';
include_once('cli.inc');
include_once('f_dpsql2.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'connect.inc');
new dbConnect;

// Look for cases where people are registered to get notice when a project posts,
// but the project is deleted.

$res = dpsql_query("
    SELECT usersettings.username AS notice_requestor, projects.*
    FROM usersettings JOIN projects ON (value=projectid)
    WHERE setting='posted_notice' AND state='project_delete'
    ORDER BY nameofwork
");
// dpsql_tdump_query_result($res); exit;

while ( $orig = mysql_fetch_object($res) )
{
    // echo $orig->notice_requestor, " ", substr($orig->nameofwork,0,50), "\n";

    $orig_deletion_date = strftime('%Y-%m-%d',$orig->modifieddate);

    if ( preg_match('/^merged into (projectID[0-9a-f]{13})\b/', $orig->deletion_reason, $matches ) )
    {
        $target_projectid = $matches[1];
        $verb = "was merged into";
    }
    else
    {
        stderr( "unhandled reason: '$orig->deletion_reason'\n" );
        continue;
    }

    $res2 = dpsql_query("
        SELECT *
        FROM projects
        WHERE projectid='$target_projectid'
    ");
    $target = mysql_fetch_object($res2);

    if ( $target->state != 'proj_submit_pgposted' )
    {
        stderr( "target hasn't posted yet, should simply shift notice to it\n" );
        // see shift_posted_notices.php
        continue;
    }

    $posting_date = strftime('%Y-%m-%d', $target->modifieddate);
    $pg_url = get_pg_catalog_url_for_etext( $target->postednum );

    $msg_body =
"(This is an automated message.)

Dear $orig->notice_requestor,

A while ago, you asked to be notified when the DP project
    $orig->nameofwork
was posted by Project Gutenberg.

Around $orig_deletion_date, that project was merged into the project
    $target->nameofwork
but we forgot to transfer your notification-request to that project.
The latter project was posted by Project Gutenberg around $posting_date,
and should be available as etext #$target->postednum:
    $pg_url

Sorry for the delay.
-- 
Distributed Proofreaders
$site_url";

    // can we send mail to $orig->notice_requestor?
    $res3 = dpsql_query("
        SELECT user_email
        FROM phpbb_users
        WHERE username='$orig->notice_requestor'
    ");
    if ( mysql_num_rows($res3) == 0 )
    {
        stderr("No user named '$orig->notice_requestor'\n");
        continue;
    }
    elseif ( mysql_num_rows($res3) > 1 )
    {
        stderr("Multiple users named '$orig->notice_requestor' ???\n");
        continue;
    }
    list($user_email) = mysql_fetch_row($res3);

    $subject = "'$target->nameofwork' Posted to Project Gutenberg";

    $just_echo = TRUE;

    if ($just_echo)
    {
        echo "To: $user_email\n";
        echo "Subject: $subject\n";
        echo "Body:\n";
        echo "$msg_body\n";
    }
    elseif (0)
    {
        maybe_mail(
            'jmdyck@metalab.unc.edu',
            // 'pryor@pobox.com',
            $subject,
            "Hi Louise, 
Here's an example of what I'm thinking of sending out.
-Michael
----------
$msg_body",
            "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n"
        );
    }
    else
    {
        maybe_mail(
            $user_email,
            $subject,
            $msg_body,
            "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n"
        );
    }

    $sql = "
        DELETE FROM usersettings
        WHERE
            username = '$orig->notice_requestor'
            AND
            setting = 'posted_notice'
            AND
            value = '$orig->projectid'
    ";
    echo "$sql\n";
    if ( !$just_echo )
    {
        dpsql_query($sql);
        echo mysql_affected_rows(), " rows affected\n";
    }

    $sql = "
        UPDATE projects
        SET int_level = int_level + 1
        WHERE projectid = '$target_projectid'
    ";
    echo "$sql\n";
    if ( !$just_echo )
    {
        dpsql_query($sql);
        echo mysql_affected_rows(), " rows affected\n";
    }

    exit;
}

// vim: sw=4 ts=4 expandtab
?>
