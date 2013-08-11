<?PHP

// For each round, we're interested in the number of people that have access
// but do not satisfy the round's numeric requirements. (These users were
// presumably seeded.)
// Also interested in the number that satisfy the numeric requirements, but
// do not have access (either because they haven't asked, or are awaiting
// evaluation, or have failed evaluation).

$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'dpsql.inc');

error_reporting(E_ALL);

if (!user_is_a_sitemanager()) die("permission denied");

dpsql_query("
    CREATE TEMPORARY TABLE quiz_P
        (INDEX (username))
    SELECT qp1.username
    FROM
        quiz_passes qp1
        JOIN quiz_passes qp2 USING (username)
        JOIN quiz_passes qp3 USING (username)
        JOIN quiz_passes qp4 USING (username)
        JOIN quiz_passes qp5 USING (username)
    WHERE
        qp1.quiz_page='step1' AND qp1.result='pass' AND
        qp2.quiz_page='step2' AND qp2.result='pass' AND
        qp3.quiz_page='step3' AND qp3.result='pass' AND
        qp4.quiz_page='step4' AND qp4.result='pass' AND
        qp5.quiz_page='step5' AND qp5.result='pass'
");

dpsql_query("
    CREATE TEMPORARY TABLE quiz_F
        (INDEX (username))
    SELECT qp1.username
    FROM
        quiz_passes qp1
        JOIN quiz_passes qp2 USING (username)
        JOIN quiz_passes qp3 USING (username)
        JOIN quiz_passes qp4 USING (username)
        JOIN quiz_passes qp5 USING (username)
    WHERE
        qp1.quiz_page='formatting1' AND qp1.result='pass' AND
        qp2.quiz_page='formatting2' AND qp2.result='pass' AND
        qp3.quiz_page='formatting3' AND qp3.result='pass' AND
        qp4.quiz_page='formatting4' AND qp4.result='pass' AND
        qp5.quiz_page='formatting5' AND qp5.result='pass'
");

define('TABULATE_COUNTS_FOR_ALL_COMBOS',1);
define('LIST_USERS_WITH_ONE_COMBO',     2);

$stage_id = @$_GET['stage_id'];
if ( $stage_id != '' )
{
    $which_output = LIST_USERS_WITH_ONE_COMBO;
    $stage = $Stage_for_id_[$stage_id];
    $satisfies_minima = @$_GET['satisfies_minima'];
    $has_access       = @$_GET['has_access'];
    assert( !is_null($stage) );
    assert( $satisfies_minima == '0' || $satisfies_minima == '1' );
    assert( $has_access       == '0' || $has_access       == '1' );

    $stages = array( $stage );
}
else
{
    $which_output = TABULATE_COUNTS_FOR_ALL_COMBOS;
    $stages = $Stage_for_id_;
}

foreach ( $stages as $stage )
{
    echo "<br><br>";
    echo "$stage->id<br>\n";

    $conditions = array();
    $score_exprs = array();
    $tables = "users\n";
    dpsql_query("
        CREATE TEMPORARY TABLE _users_with_access_to_this_stage
            (INDEX (username))
        SELECT username
        FROM usersettings
        WHERE setting='{$stage->id}.access' AND value='yes'
    ");

    $has_access_condition = "
        _users_with_access_to_this_stage.username IS NOT NULL
    ";
    $tables .= "LEFT OUTER JOIN _users_with_access_to_this_stage ON
            (_users_with_access_to_this_stage.username = users.username)\n";

    echo "<ul>\n";
    foreach ( $stage->access_minima as $criterion_code => $minimum )
    {
        echo "<li>$criterion_code => $minimum</li>\n";

        if ( $criterion_code == 'days since reg' )
        {
            $s = "(UNIX_TIMESTAMP() - date_created)/86400";
        }
        else if ( $criterion_code == 'quiz/P' )
        {
            $s = "quiz_P.username IS NOT NULL";
            $tables .= "LEFT OUTER JOIN quiz_P ON (quiz_P.username=users.username)";
        }
        else if ( $criterion_code == 'quiz/F' )
        {
            $s = "quiz_F.username IS NOT NULL";
            $tables .= "LEFT OUTER JOIN quiz_F ON (quiz_F.username=users.username)";
        }
        else if ( $criterion_code == 'R*+P1' )
        {
            // kludge
            $tallyboard1 = new TallyBoard( 'R*', 'U' );
            $tallyboard2 = new TallyBoard( 'P1', 'U' );
            list($j1,$c1) = $tallyboard1->get_sql_joinery_for_current_tallies('u_id');
            list($j2,$c2) = $tallyboard2->get_sql_joinery_for_current_tallies('u_id');
            $s = "($c1+$c2)";
            $tables .= "$j1\n$j2\n";
        }
        else
        {
            $tally_name = $criterion_code;
            $tallyboard = new TallyBoard( $tally_name, 'U' );
            list($j,$c) = $tallyboard->get_sql_joinery_for_current_tallies('u_id');
            $s = $c;
            $tables .= "$j\n";
        }
        $conditions[] = "($s) >= $minimum";
        $score_exprs[$criterion_code] = $s;
    }
    echo "</ul>\n";
    
    $minsat_condition = "1\n";
    foreach( $conditions as $condition )
    {
        $minsat_condition .= "AND ($condition)\n";
    }

    if ( $which_output == TABULATE_COUNTS_FOR_ALL_COMBOS )
    {
        $q = "
            SELECT
                ($minsat_condition),
                ($has_access_condition),
                COUNT(*)
            FROM $tables
            GROUP BY 1, 2
        ";
        // echo "<pre>$q</pre>\n";
        // dpsql_dump_query($q);

        $no_yes = array( 0 => 'no', 1 => 'YES' );
        $res = mysql_query($q) or die(mysql_error());
        echo "<table border='1'>\n";
        {
            echo "<tr>\n";
            echo "<th>satisfies minima?</th>\n";
            echo "<th>has access?</th>\n";
            echo "<th># of users with that combo</th>\n";
            echo "</tr>\n";
        }
        while ( list($satisfies_minima,$has_access,$number_with_that_combo) = mysql_fetch_row($res) )
        {
            echo "<tr>\n";
            echo "<td align='center'>", $no_yes[$satisfies_minima], "</td>\n";
            echo "<td align='center'>", $no_yes[$has_access],       "</td>\n";

            if ( $number_with_that_combo < 10000 )
            {
                // make it a link
                $url = "minima_sat_vs_access.php?stage_id={$stage->id}&amp;satisfies_minima=$satisfies_minima&amp;has_access=$has_access";
                $content = "<a href='$url'>$number_with_that_combo</a>";
            }
            else
            {
                $content = $number_with_that_combo;
            }
            echo "<td align='center'>$content</td>\n";

            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    elseif ( $which_output == LIST_USERS_WITH_ONE_COMBO )
    {
        echo "<br>\n";

        echo "Users that ";
        if ( $satisfies_minima == '0' ) echo "do not ";
        echo "satisfy the minima for $stage->id ";

        if ( $satisfies_minima == $has_access ) echo "and "; else echo "but ";

        if ( $has_access == '0' ) echo "do not ";
        echo "have $stage->id access";
        echo ":<br>\n";

        $items = "users.username\n";
        foreach ( $score_exprs as $criterion_code => $score_expr )
        {
            $items .= ", $score_expr AS '$criterion_code'\n";
        }

        $q = "
            SELECT $items
            FROM $tables
            WHERE
                ($minsat_condition) = $satisfies_minima
                AND ($has_access_condition) = $has_access
            ORDER BY users.username
        ";
        // echo "<pre>$q</pre>\n";
        dpsql_dump_query($q);
    }
    else
    {
        assert( FALSE );
    }


    dpsql_query("
        DROP TABLE _users_with_access_to_this_stage
    ");
}

// vim: sw=4 ts=4 expandtab
?>
