<?
include('site_vars.php');	//MS
include('connect.inc');	//MS
include('project_states.inc');	//MS
include('maybe_mail.inc');	//MS
$db_Connection=new dbConnect();

    $old_date = time() - 7776000; // 90 days ago.

    //get projects that have been checked out longer than old_date
    $result = mysql_query("SELECT nameofwork, checkedoutby, modifieddate, projectid, authorsname, 
				DATE_FORMAT(FROM_UNIXTIME(modifieddate), '%e %M  %Y') as Nicedate
                     FROM projects
                     WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."' AND modifieddate <= $old_date ORDER BY checkedoutby, modifieddate");

    $numrows = mysql_num_rows($result);
    $rownum = 0;

    $PPinQuestion = "";
    $lastwork = "";	
    $projectslist = "";
    $displayprojectslist = "";
    $numprojs = 0;
    $urlbase = "$code_url/project.php?expected_state=proj_post_first_checked_out&id=";

   while ($rownum < $numrows) {

        $nameofwork = mysql_result($result, $rownum, "nameofwork");
        $authorsname = mysql_result($result, $rownum, "authorsname");
        $checkedoutby = mysql_result($result, $rownum, "checkedoutby");
        $modifieddate = mysql_result($result, $rownum, "modifieddate");
	$projectid = mysql_result($result, $rownum, "projectid");
	$nicedate = mysql_result($result, $rownum, "nicedate");

        if ($PPinQuestion != $checkedoutby) {
	    if ($rownum > 0) {

		    $userresult = mysql_query ("SELECT email FROM users WHERE username = '$PPinQuestion'");
		    $email = mysql_result($userresult, 0, "email");
			
		    echo $PPinQuestion . "<br>\n" . $displayprojectslist ."<br><br>\n\n";

		    if ($numprojs == 1) {
			$message = "Hello $PPinQuestion,\n\nThis is an automated message.\n\n
Our database indicates that you have had a PP project checked out for more than 90 days:\n\n
$projectslist\n\n 
If you haven't yet finished and wish to continue working on this book, please log in to www.pgdpcanada.net and visit $url . This will update the status of the project. If you need help please forward a copy of this email (quoting the information on the book, above) with a brief description of the status to dphelp@pgdpcanada.net.\n\n
If you have completed your work on the book, please log in to www.pgdpcanada.net and visit $url. Select the 'Upload for verification' option and follow the prompts. You will be able to leave a message for the verifier during this process, if you have any special information or comments to pass on.\n\n
If you are waiting on missing images or page scans, please add the details to the Missing Page Wiki at: $forums_url/viewtopic.php?t=7584\n\n
If you no longer wish to have this text assigned to you please visit the Distributed Proofreaders website Post Processing section and select Return to Available for this book, or forward this email to dphelp@pgdpcanada.net and state that you would no longer like to have the book in question assigned to you so that we may return it to the available pool for someone else to work on.\n\n 
Thanks!\nThe Distributed Proofreaders Team\n(http://www.pgdpcanada.net)";
		    } else {
			$message = "Hello $PPinQuestion,\n\nThis is an automated message.\n\n
Our database indicates that you have had $numprojs PP projects checked out for more than 90 days:\n\n 
$projectslist\n\n 
If you wish to continue working on some or all of these books, please log in to www.pgdpcanada.net and visit each such project's home-page (copy the URL listed with the project above and paste it into your browser's address-field). Doing this will update the status of the project and let us know that you are still working on it. If you need help please forward this email, quoting the list of books, with a brief description of the status for each of the various books listed above that you need help with to dphelp@pgdpcanada.net.\n\n
If you have completed your work on any of these books, please log in to www.pgdpcanada.net and visit each such project's home-page (copy the URL listed with the project above and paste it into your browser's address-field). Select the 'Upload for verification' option and follow the prompts. You will be able to leave a message for the verifier during this process, if you have any special information or comments to pass on.\n\n
If you are waiting on missing images or page scans, please add the details to the Missing Page Wiki at: $forums_url/viewtopic.php?t=7584\n\n
If you no longer wish to have some or all of these books assigned to you please visit the Distributed Proofreaders website Post Processing section and select Return to Available for the books in question or forward this email to dphelp@pgdpcanada.net and state that you would no longer like to have the books in question assigned to you so that we may return them to the available pool for someone else to work on.\n\n 
Thanks!\nThe Distributed Proofreaders Team\n(http://www.pgdpcanada.net)";
		    }

	            maybe_mail("$email", "$subject","$message", "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");

		    $projectslist = "";
		    $displayprojectslist = "";
		    $numprojs = 0;
 	    }
	    $PPinQuestion = $checkedoutby;
	}

	$numprojs++;

	$url = $urlbase . $projectid;
	
	$projectslist .= "$nameofwork by $authorsname ($projectid), out since $nicedate\n$url\n\n";
        if ($numprojs == 1) {
		$subject = "DP: Status update needed for 1 project checked out for PPing over 90 days";
	} else {
		$subject = "DP: Status updates needed for $numprojs projects checked out for PPing over 90 days";
	}	

	$displayprojectslist .= "<a href='$url'>$nameofwork by $authorsname ($projectid)</a>, out since $nicedate\n". "<br>";

        $rownum++;
}

?>
