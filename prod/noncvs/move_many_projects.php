<?PHP

// XXX INCOMPLETE!

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'ProjectTransition.inc');
new dbConnect;

$curr_state = 'P1.proj_waiting';
$next_state = 'P1.proj_unavail';
$project_selector = "username='papeters'";
$mover = 'foo'; // Ascribe the move to this user.

echo "Requested transition: $curr_state -> $next_state\n";
$transition = get_transition( $curr_state, $next_state );
if ( is_null($transition) )
{
    die( "not a legal transition" );
}
var_dump($transition);
// XXX Do some checks on $transition.

$res = mysql_query("
    SELECT *
    FROM projects
    WHERE state='$from_state' AND $project_selector
    ORDER BY projectid
") or die(mysql_error());
while ( $p = mysql_fetch_assoc($res) )
{
    $project = new Project($p);
    echo "$project->projectid $project->nameofwork\n";
    // XXX $transition->do_state_change( $project, $mover, $extras );
}

// vim: sw=4 ts=4 expandtab
?>
