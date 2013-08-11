<?
include_once($relPath."connect.inc");
include_once($relPath."site_vars.php");
class Quiz
{
  function Quiz($id,$name,$descrption,$pages, $pass_requirements)
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $descrption;
    $this->pages = $pages;
    $this->pass_requirements = $pass_requirements;
    // Currently supported pass_requirements:
    // ['maximum_age'] => time_in_seconds:
    //     Passes recorded longer than time_in_seconds ago are not valid.
  }

  function user_has_passed($username)
  {
    foreach ($this->pages as $page)
    {
      // false would be more logical than string "no",
      // but you can't array_search on a boolean.
      $pages_required_results[$page] = "no";
    }

    $result = mysql_query("SELECT * FROM `quiz_passes` WHERE `username` ='$username'");
    while ($attempt = mysql_fetch_object($result))
    {
      if($attempt->result != "pass") continue;
      if(array_key_exists($attempt->quiz_page,$pages_required_results))
      {
        $pages_required_results[$attempt->quiz_page] = $attempt->date;
      }
      else
      {
        //This quiz page isn't relevant to this quiz.
        continue;
      }
    }

    if (isset($this->pass_requirements['maximum_age']))
    {
      foreach ($pages_required_results as $page => $value)
      {
        if($value == "no") continue; // The user hasn't passed the quiz anyway.
        if ((time() - $value) > $this->pass_requirements['maximum_age'])
        {
          $pages_required_results[$page] = "no";
        }
      }
    }

    // At this point, if a user has passed the quiz, the $pages_required_results array should
    // have only pagename => timestamp  rows. If any "no" values remain, the user has not passed
    // all the pages required, or the result of that page is invalid (e.g. too old).
    if (array_search("no",$pages_required_results) !== false)
    {
      return false;
    }
    else
    {
      return true;
    }
  }

  function show_results_table($username)
  {
    global $code_url;
    echo "<p>
          <table border='0' cellpadding='2' style='border:2px solid black;'>
          <tr><th class='q' colspan='5'>$this->name</th></tr>
	  <tr><td colspan='5'>$this->description</td></tr>
    ";
    $pages = $this->pages;
    foreach ($pages as $desc => $page)
    {
      echo "<tr><td>$desc</td>
            <td><a href='generic/main.php?type=$page&quiz_id=$this->id'>Attempt this page</a></td>";
      if (!empty($username))
      {
        $passed = user_has_passed_quiz_page($username, $page);
        $text = $passed ? _("Passed") : _("Not passed");
        $bgcolor = $passed ? '#ccffcc' : '#ffcccc';
        echo "<td bgcolor='$bgcolor'>$text</td>";
        $date = get_last_attempt_date_for_quiz_page($username, $page);
        $text = ($date != 0) ? strftime("%d-%b-%y",$date) : "Not attempted";
        $max = $this->pass_requirements['maximum_age'];
        $date_ok = ((time() - $date) < $max) || empty($max);
        $bgcolor = $date_ok ? '#ccffcc' : '#ffcccc';
        echo "<td bgcolor='$bgcolor'>$text</td>";
        $text = ($passed && $date_ok) ?	"<img src='$code_url/quiz/tick.png' alt='Tick!' title='Quiz page passed!' width='25' height='24' />"
                                      : "<img src='$code_url/quiz/cross.png' alt='Cross' title='Quiz page not passed' width='22' height='25'  />";
        $bgcolor = ($passed && $date_ok) ? '#88ff88' : '#ff8888';
        echo "<td bgcolor='$bgcolor'>$text</td>";
      }
      else
      {
        echo "<td /><td /><td />";
      }
     echo "</tr>";
    }
    if (!empty($username))
    {
      $total_pass = $this->user_has_passed($username);
      $text = $total_pass ? _("Quiz passed") : _("Quiz not passed");
      $bgcolor = $total_pass ? '#88ff88' : '#ff8888';
      echo "<tr><td colspan='5'  bgcolor='$bgcolor' style='font-weight: bold;text-align:center;'>$text</td></tr>";
    }
    echo "</table></p>";
  }
}

# -----------------------------------------------------------------------------

function record_quiz_attempt($username, $quiz_page, $result)
{
  new dbConnect();
  $res = mysql_query("SELECT * FROM `quiz_passes`
  WHERE `username` ='$username' AND `quiz_page` ='$quiz_page'");
  if (mysql_num_rows($res) > 0)
  {
    // The user has already passed this page; update the timestamp
    mysql_query("UPDATE `quiz_passes` SET `date` = '".time()."'
    WHERE `username` ='$username' AND `quiz_page` ='$quiz_page'");
  }
  else
  {
    mysql_query("INSERT INTO `quiz_passes` VALUES('$username','".time()."','$quiz_page','$result')");
  }
}

function user_has_passed_quiz_page($username, $quiz_page, $pass_requirements = array())
{
  // This function could fairly easily lookup the quizzes to see what $pass_requirements should be,
  // but by calling it without that argument, it's possible to tell the user *why* they haven't passed.
  $res = mysql_query("SELECT * FROM `quiz_passes`
  WHERE `username` ='$username' AND `quiz_page` ='$quiz_page'");
  if (mysql_num_rows($res) == 0)
      return false;
  if (isset($pass_requirements['minimum_age']))
  {
    $value = mysql_result($res,0,"date");
    if ((time() - $value) > $this->pass_requirements['maximum_age'])
        return false;
  }
  return true;
}

function get_last_attempt_date_for_quiz_page($username, $quiz_page)
{
  $res = mysql_query("SELECT date FROM `quiz_passes`
  WHERE `username` ='$username' AND `quiz_page` ='$quiz_page'");
  if (mysql_num_rows($res) == 0)
      return false;

  $value = mysql_result($res,0,"date");

  return $value;
}

?>