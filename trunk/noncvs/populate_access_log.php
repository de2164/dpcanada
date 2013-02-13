<?
$relPath = "../c/pinc/";
include_once($relPath."connect.inc");
include_once($relPath."dpsql.inc");
new dbConnect();

$genesis = 1117720800;

if (!isset($_REQUEST['step']))
{
  echo "<p /><p>Hello! I am going to help you populate the <tt>access_log</tt> table.
        We'll do it in two easy steps, and you won't even need any papyrus!</p>

        <form method='post'>
          <input type='hidden' name='step' value='step2' />
          <input type='submit' value=\"Yes! I agree to pay a one-off fee of $499.95 and thereafter purchase four books per month at DP's exclusive prices!\" />
        </form>";
}

elseif ($_REQUEST['step'] == 'step2')
{
  echo "Getting a list of users with current round-access . . . .
        <form method='post'>
          <input type='hidden' name='step' value='step3' />";
  $result = dpsql_query(" SELECT username,setting 
                          FROM usersettings 
                          WHERE setting LIKE '%.access' 
                          AND value ='yes'");
  while ($cur_user = mysql_fetch_object($result))
  {
    echo "<input type='text' name='users_to_process[]' value='$cur_user->username' /> -- $cur_user->setting <br />";
  }
  echo "The script will record the started-in-round time of each user above, unless 
        you clear all textboxes with the username in it.<br />";

  $access_res = dpsql_query("SELECT * FROM access_log");

  if (mysql_num_rows($access_res) != 0)
    die("There are already rows in the access_log table. 
    This script is designed to be run <b>only</b> on an empty table. Aborting.");

  echo "<input type='submit' value='Okay, do the thing' />";
  echo "</form>";
}

elseif ($_REQUEST['step'] == 'step3')
{
   $users_to_process = array_unique($_POST['users_to_process']);
   sort($users_to_process);

   echo count($users_to_process)." users to process.<br /><br />";

   foreach ($users_to_process as $i => $user)
   {
     echo "Examining user ".++$i.", $user. . .<br />";

     $u_id = mysql_result(dpsql_query("SELECT u_id FROM users WHERE username='$user'"),0);

     $user_access_rounds_result = dpsql_query("SELECT setting FROM usersettings
                                               WHERE setting LIKE '%.access'
                                               AND value ='yes'
                                               AND username = '$user'");

     for ($i = 0; $i < mysql_num_rows($user_access_rounds_result); $i++)
     {
       $user_access_rounds[] = mysql_result($user_access_rounds_result,$i,"setting");
     }

     echo "Rounds this user can currently access:<br />";

     foreach ($user_access_rounds as $key => $round)
     {
       $round = str_replace('.access','',$round);
       echo "$round &mdash; ";
       $user_access_rounds[$key] = $round;

       // Did the user first save a page in this round pre-genesis?
       $first_pages = @mysql_result(dpsql_query("SELECT timestamp FROM past_tallies
                                          WHERE holder_type ='U'
                                          AND holder_id ='$u_id'
                                          AND tally_name ='$round'
                                          ORDER BY timestamp ASC
                                          LIMIT 1"),0);

       if (!$first_pages)
       {
         $started_in_round = $genesis;
        echo " hasn't worked here yet, setting round-start as genesis: ".strftime("%D",$started_in_round);
       }

       elseif ($first_pages < $genesis)
       {
         $started_in_round =  $first_pages;
         echo "First worked here pre-genesis: ".strftime("%D",$started_in_round);
       }

       else
       {
         $started_in_round = $genesis;
         echo "First worked here post-genesis, setting first-access as genesis time: ".strftime("%D",$started_in_round);
       }

       // Assume people already in F1 were auto-granted. Those in P2/F2 were done
       // by P or Juliet, so just record that the person was 'seeded'.
       $blame = ($round == 'F1') ? 'AUTO-GRANTED' : 'SEEDED';

       echo ". Blaming $blame.<br />";

       dpsql_query("INSERT INTO access_log
                    SET
                      timestamp = '$started_in_round',
                      subject_username = '$user',
                      modifier_username = '$blame',
                      activity = '$round',
                      action = 'grant'");

     }
     echo "<br /><br />";

     echo "<hr /><br />";
     unset($user_access_rounds);
     flush();
   }


}
?>
