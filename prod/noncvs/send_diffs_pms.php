<?
$relPath = '../c/pinc/';
include_once("./cli.inc");
include_once($relPath.'Project.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'project_trans.inc');

// config

$projectid = 'projectID4246e69c2c185';
$test_user = 'mikeyc21';

$from_userdata['username'] = 'mikeyc21';
$from_userdata['user_id'] = '18112';

$message_subject = 'Fast formatting feedback diffs available';

// %1$s: username
// %2$s: project title
// %3$s: projectid
// apostrophe must be written \\\'
$message_raw = 'Hi, %1$s:

You recently proofread some pages in "%2$s", a fast formatting feedback '.
'project. This project has completed F2, and you can now see your diffs by '.
'looking at the [url=http://www.pgdp.net/c/tools/project_manager/page_detail.php?project=%3$s&show_image_size=0&select_by_user]page details[/url].

You\\\'re welcome to format some more pages in another fast formatting '.
'feedback project if you\\\'d like more feedback, or feedback on different '.
' formatting features.

If you have any formatting questions, you can post a link to the diff in question (or just the project name and page number in question) in [url=http://www.pgdp.net/phpBB2/viewtopic.php?t=24068]this topic[/url], or a general formatting question in the '.
'[url=http://www.pgdp.net/phpBB2/viewforum.php?f=34]Common Formatting '.
'Q&A forum[/url]. If you have any other questions, feel free to reply '.
'to this message.

I hope you find the feedback provided by these diffs useful. You can request '.
'personalised feedback on any pages by sending a Private Message to "dp-feedback" '.
'with the projectid and page numbers.

Cheers,
Mike';


$result = mysql_query("SELECT * FROM projects WHERE projectid = '$projectid' LIMIT 1");

$project = mysql_fetch_object($result);


echo "Sending a test message to to $test_user...\n";

$to_user = $test_user;
$subject = $message_subject;
$message = sprintf($message_raw,$test_user,$project->nameofwork,$projectid);


require('inc/send_pm.inc');



echo "PM sent to $to_user. Review the message.\n";

get_response("To make changes, abort now; otherwise, 'yes' to continue: ",array("yes"));

$resultqqz = mysql_query("SELECT DISTINCT round4_user FROM $projectid") or die(mysql_error());

$i = 0;

echo "Messages to send: " . mysql_num_rows($resultqqz) . "\n";

while (list($this_username) = mysql_fetch_row($resultqqz))
{
    echo ++$i . " $this_username\n";
    $message = sprintf($message_raw,$this_username,$project->nameofwork,$projectid);
    $to_user = $this_username;
    require('inc/send_pm.inc');
}

echo "Done!";
   
?>

