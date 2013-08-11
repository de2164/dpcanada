<?

include('site_vars.php');	//MS
include('connect.inc');	//MS
$db_Connection=new dbConnect();

mysql_query("	
	UPDATE users
	SET postproofer = 'yes'
	WHERE username IN 
	( SELECT postproofer FROM projects)";

// DAK - required adding index to "projects" on "postproofer"
// $result = mysql_query("SELECT DISTINCT(postproofer) FROM projects WHERE postproofer is not null");
// while (list($postproofer) = mysql_fetch_row($result)) {
	// mysql_query("UPDATE users SET postprocessor = 'yes' WHERE username = '$postproofer'");
	// if ( mysql_affected_rows() > 0 )
	// {
		// echo "Added: $postproofer<br>";
	// }
// }

echo "<br><center>Update of Post Processors Complete!</center>";

