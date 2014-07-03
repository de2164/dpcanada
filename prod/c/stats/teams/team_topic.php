<?
// DP includes
$relPath="./../../pinc/";
include_once($relPath.'dpinit.php');

// Which team?
$team_id = $_GET['team'];
$team = new DpTeam($team_id);

// Get info about team

//Determine if there is an existing topic or not; if not, create one
if(! $team->TopicId()) {
        $tname          = $team->TeamName();
        $towner_name    = $team->OwnerName();
        $towner_id      = $team->OwnerId();
        $tinfo          = $team->Info();

        $message = "
Team Name: $tname
Created By: $towner_name
Info: $tinfo
Team Page: [url]" . url_for_team_stats($team->Id()) . "[/url]

Use this area to have a discussion with your fellow teammates! :-D

";


	// appropriate forum to create thread in
	$forum_id = $teams_forum_idx;

        $post_subject = $tname;

        $topic_id = create_topic(
                $forum_id,
                $post_subject,
                $message,
                $towner_name,
		        TRUE,
                FALSE );

        //Update user_teams with topic_id so it won't be created again
        $dpdb->SqlExecute("UPDATE user_teams SET topic_id = $topic_id WHERE id = $team_id");

}

// By here, either we had a topic or we've just created one, so redirect to it

$redirect_url = "$forums_url/viewtopic.php?t=$topic_id";
header("Location: $redirect_url");
