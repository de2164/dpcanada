<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
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
";


    echo "<form method='get'>\n";
    echo "<pre>";
    echo "username: <input name='username' type='text' size='26' value='$username'>\n";
    echo "<input type='submit'>\n";
    echo "</pre>";
    echo "</form>";
if ( ! empty($username) )
{


    echo "<h2>$username current and reserved PP projects</h2>";

    /* show the counts of projects

- finished PPing within the last year
- checked out for PP > 100 days
- all checked out for PP 
- reserved for PP in each round, avail and waiting
 then projects checked out and projects in rounds
    */
    $result = dpsql_query("SELECT (".time()." - t_last_activity), email, active FROM users WHERE username = '$username'");
    $row = mysql_fetch_row($result);
    if (user_is_a_sitemanager())
    {
        echo "<p>Email address: {$row[1]}</p>";
    }
    $ls = round($row[0]/(60 * 60 * 24));
    echo "<p>$username was last seen $ls days ago";
    if ($row[2] != 'yes')
    {
        echo " and has been marked as inactive";
    }
    echo ".</p>\n";
    echo "<p>PP unavailable projects are currently excluded from the counts.</p>\n";
    $rounds = array('P1', 'P2', 'P3', 'F1', 'F2');
    $what = array('unavail', 'waiting', 'avail'); 

    $psd = get_project_status_descriptor('PPd');
    $ppstate = "'proj_post_first_checked_out'";
    $days = 100;

    $sel_string = "SELECT  COUNT(*) FROM projects WHERE ";
    $user_string = "checkedoutby LIKE '$username' ";
    $posted_string = "postproofer LIKE '$username' ";
    $old_date = " AND modifieddate < (UNIX_TIMESTAMP() - (60 * 60 * 24 * $days)) ";
    $year_date = " AND modifieddate > (UNIX_TIMESTAMP() - (60 * 60 * 24 * 365)) ";
    $state_string = " AND state = ";

    function echo_count_from_query($q_string) {
        $query = dpsql_query($q_string);  
        $row = mysql_fetch_row($query);
        echo "<td class='number'>$row[0]</td>";
    }

    /* summarise: total PPed, most recent PPed */
    echo "<p>Total projects PPed: ";
    $q_string = $sel_string . $posted_string . " AND $psd->state_selector ";
    $query = dpsql_query($q_string);  
    $row = mysql_fetch_row($query);
    echo "$row[0] <br />";
    echo "Most recent was ";
    $q_string = "SELECT round((unix_timestamp() - MAX(modifieddate))/(24 * 60 * 60)) FROM projects WHERE " . 
                $posted_string . " AND $psd->state_selector ";
    $query = dpsql_query($q_string);  
    $row = mysql_fetch_row($query);
    echo "$row[0] days ago </p>";

    /* first do PP */
    echo "<table>";
    echo "<tr><th>&nbsp;</th><th>Recently completed</th><th>Checked out</th><th> &gt; $days days</th></tr>\n";
    echo "<tr><td>PP</td>";

    $q_string = $sel_string . $posted_string . " AND $psd->state_selector " . $year_date ;
    echo_count_from_query($q_string);
    $q_string = $sel_string . $user_string . $state_string . $ppstate;
    echo_count_from_query($q_string);
    $q_string .= $old_date;
    echo_count_from_query($q_string);
    echo "</tr>";

    /* now do reserved projects */
    echo "<tr><th>Reserved projects</th><th>Unavailable</th><th>Waiting</th><th>Available</th></tr>\n";

    /* get the number of projects for each state */
    for ($i = 0; $i < count($rounds); $i++) { 
        echo "<tr><td>$rounds[$i]</td>";
        for ($j = 0; $j < count($what); $j++) {
            $q_string = $sel_string . $user_string . $state_string . "'$rounds[$i].proj_$what[$j]'";
            echo_count_from_query($q_string);
        }
        echo "</tr>";
    }
    echo "</table>";

    echo "<p>The reserved projects are:</p>";
    /* now show the projects */
    echo "<table>";
    echo "<tr><td>Title</td>
          <td>Author</td>
          <td>Language</td>
          <td>Genre</td>
          <td>Available</td>
          <td>Total pages</td>
          <td>Manager</td>
          <td>Days</td>
          <td>State</td></tr>";

    $query = " 
        SELECT
            projectid, 
            nameofwork,
            authorsname, 
            language,
            genre, 
            username,
            checkedoutby,
            modifieddate,
            round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) as days_avail, 
            n_pages,
            n_available_pages,
            state
        FROM projects
        WHERE (state LIKE '%.%' )
        AND checkedoutby = '$username'
        ORDER BY " . sql_collater_for_project_state('state') . ", modifieddate";
    $result = mysql_query($query);
    $numrow = mysql_numrows($result);
    for ( $rownum = 0; $rownum < $numrow; $rownum++ )
    {
        echo "<tr>";
        $book = mysql_fetch_assoc($result);
        echo "\n<td><a href='http://www.pgdp.net/c/project.php?id=".$book['projectid'] . "'>" . $book['nameofwork']. "</a></td>";
        echo "\n<td>". $book['authorsname']. "</td>";
        echo "\n<td>". $book['language']. "</td>";
        echo "\n<td>". $book['genre']. "</td>";
        echo "\n<td class='number'>". $book['n_available_pages']. "</td>";
        echo "\n<td class='number'>". $book['n_pages']. "</td>";
        echo "\n<td>". $book['username']. "</td>";
        echo "\n<td class='number'>". $book['days_avail']. "</td>";
        echo "\n<td>". project_states_text($book['state']). "</td>";
        echo "</tr>\n";

    }

    echo "</table>\n\n";

    echo "<p>Projects currently being PPed are:</p>\n";
    echo "<table>";
    echo "<tr><td>Title</td>
          <td>Author</td>
          <td>Language</td>
          <td>Genre</td>
          <td>Available</td>
          <td>Total pages</td>
          <td>Manager</td>
          <td>Days</td>
          <td>Days in state</td>
          <td>State</td></tr>";

    $query = " 
        SELECT
            projects.projectid, 
            projects.nameofwork,
            projects.authorsname, 
            projects.language,
            projects.genre, 
            projects.username,
            projects.checkedoutby,
            projects.modifieddate,
            round((unix_timestamp() - projects.modifieddate)/(24 * 60 * 60)) as days_avail, 
            round((unix_timestamp() - max(project_events.timestamp))/(24 * 60 * 60)) as days_in_state,
            projects.n_pages,
            projects.n_available_pages,
            projects.state
        FROM projects, project_events
        WHERE ( projects.state = $ppstate )
        AND projects.checkedoutby = '$username'
        AND project_events.projectid = projects.projectid
        GROUP BY project_events.projectid
        ORDER BY days_avail DESC";
    $result = mysql_query($query);
    $numrow = mysql_numrows($result);
    for ( $rownum = 0; $rownum < $numrow; $rownum++ )
    {
        echo "<tr>";
        $book = mysql_fetch_assoc($result);
        echo "\n<td><a href='http://www.pgdp.net/c/project.php?id=".$book['projectid'] . "'>" . $book['nameofwork']. "</a></td>";
        echo "\n<td>". $book['authorsname']. "</td>";
        echo "\n<td>". $book['language']. "</td>";
        echo "\n<td>". $book['genre']. "</td>";
        echo "\n<td class='number'>". $book['n_available_pages']. "</td>";
        echo "\n<td class='number'>". $book['n_pages']. "</td>";
        echo "\n<td>". $book['username']. "</td>";
        echo "\n<td class='number'>". $book['days_avail']. "</td>";
        echo "\n<td class='number'>". $book['days_in_state']. "</td>";
        echo "\n<td>". project_states_text($book['state']). "</td>";
        echo "</tr>\n";

    }

    echo "</table>\n\n";

    echo "<p>Projects recently PPed are:</p>\n";

    $query = " 
        SELECT
            projectid, 
            nameofwork,
            authorsname, 
            language,
            genre, 
            username,
            postproofer,
            ppverifier,
            modifieddate,
            round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) as days_avail, 
            n_pages,
            state
        FROM projects
        WHERE {$psd->state_selector}
        AND postproofer = '$username'
        AND modifieddate > (UNIX_TIMESTAMP() - (60 * 60 * 24 * 365)) 
        ORDER BY " . sql_collater_for_project_state('state') . ", modifieddate";

    echo "<table>\n";
    echo "<tr><td>Title</td>
          <td>Author</td>
          <td>Language</td>
          <td>Genre</td>
          <td>Total pages</td>
          <td>Manager</td>
          <td>PPVer</td>
          <td>Days</td>
          <td>State</td></tr>\n";

    $result = mysql_query($query);
    $numrow = mysql_numrows($result);
    for ( $rownum = 0; $rownum < $numrow; $rownum++ )
    {
        echo "<tr>";
        $book = mysql_fetch_assoc($result);
        echo "\n<td><a href='http://www.pgdp.net/c/project.php?id=".$book['projectid'] . "'>" . $book['nameofwork']. "</a></td>";
        echo "\n<td>". $book['authorsname']. "</td>";
        echo "\n<td>". $book['language']. "</td>";
        echo "\n<td>". $book['genre']. "</td>";
        echo "\n<td class='number'>". $book['n_pages']. "</td>";
        echo "\n<td>". $book['username']. "</td>";
        echo "\n<td>". $book['ppverifier']. "</td>";
        echo "\n<td class='number'>". $book['days_avail']. "</td>";
        echo "\n<td>". project_states_text($book['state']). "</td>";
        echo "</tr>\n";

    }
    echo "</table>\n\n";
}
echo "</html>";




