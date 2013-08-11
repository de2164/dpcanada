<?PHP

// This script allows you to modify db values using preg_replace().

$relPath='../c/pinc/';
include_once('cli.inc');
include_once('showdiff.inc');
include_once($relPath.'connect.inc');
new dbConnect;

function tweak_column( $table_name, $key_cols, $condition, $subject_name, $patterns, $replacements )
{
    $key_cols_commalist = implode(',', $key_cols);
    $res = mysql_query("
        SELECT $key_cols_commalist, $subject_name
        FROM $table_name
        WHERE $condition
        ORDER BY $key_cols_commalist
    ") or die(mysql_error(). "\n");

    $num_rows = mysql_num_rows($res);
    if ($num_rows == 0)
    {
        echo "no rows in $table_name satisfy '$condition'\n";
        return;
    }

    echo "$num_rows rows matched...\n";
    sleep(2);

    $force_yes = FALSE;

    $n_regex_had_no_effect = 0;

    $n = 0;
    while( $row = mysql_fetch_assoc($res) )
    {
        $subject_curr_value = $row[$subject_name];

        $key_settings = array();
        foreach ( $key_cols as $key_col )
        {
            $key_value = $row[$key_col];
            $enc_key_value = mysql_real_escape_string( $key_value );
            $key_settings[] = "$key_col='$enc_key_value'";
        }
        $key_settings_str = implode(', ', $key_settings);
        $key_where = implode(' AND ', $key_settings);

        $n += 1;
        echo "\n";
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
        echo "($n/$num_rows) $key_settings_str\n";
        $subject_new_value = preg_replace( $patterns, $replacements, $subject_curr_value );
        if ( is_null($subject_new_value) )
        {
            die("preg_replace returned null\n");
        }

        echo "\n";
        echo "    $subject_curr_value\n";
        echo "---\n";
        echo "    $subject_new_value\n";
        echo "\n";

        if ( $subject_curr_value == $subject_new_value )
        {
            echo "regex had no effect\n";
            $n_regex_had_no_effect += 1;
            // $line = readline('hit return to continue ');
            // if ( $line == 'q' ) return;
            continue;
        }

        if (0)
        {
            if ( strlen($subject_curr_value) > 200 || substr_count($subject_curr_value,"\n") > 3 )
            {
                showdiff( $subject_curr_value, $subject_new_value );
            }
        }

        while ( TRUE )
        {
            if ( $force_yes )
            {
                $response = 'y';
            }
            else
            {
                $response = readline('make change? ');
            }
            switch ($response)
            {
                case 'q':
                    return;

                case 'd':
                    showdiff( $subject_curr_value, $subject_new_value );
                    break; // break out of switch, but continue the while

                case 'yes to all':
                    $force_yes = TRUE;
                    // FALL-THROUGH!

                case 'y':
                    $subject_new_value_q = mysql_real_escape_string($subject_new_value);
                    // $extra = ", bbcode_uid='0000000000'";
                    $extra = '';
                    $cmd = "UPDATE $table_name SET $subject_name = '$subject_new_value_q' $extra WHERE $key_where";
                    if (0)
                    {
                        echo "$cmd\n";
                    }
                    else
                    {
                        mysql_query($cmd) or die(mysql_error(). "\n");
                    }

                    $r = mysql_query("
                        SELECT $subject_name
                        FROM $table_name
                        WHERE $key_where
                    ") or die(mysql_error(). "\n");
                    list($subject_new_value2) = mysql_fetch_row($r);
                    if ($subject_new_value2 != $subject_new_value)
                    {
                        echo "value from table:\n";
                        echo "    $subject_new_value2\n";
                        die("doesn't match!\n");
                    }
                    break 2; // break the switch and the while

                case 'n': 
                    echo "okay, skipping it\n";
                    break 2; // break the switch and the while

                default:
                    echo "you responded '$response'\n";
                    break; // break the switch but continue the while
            }
        }
    }

    echo "\n";
    echo "regex had no effect in $n_regex_had_no_effect cases\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// queue_defns

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        "project_selector like '%\r\n%'",
        "project_selector",
        '/\r\n/',
        ' '
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        "project_selector like '%state%'",
        "project_selector",
        array('/state like "avail%"/', "/state = 'avail_1'/", '/ or FALSE */', '/\(special_code = \'\'\)/'),
        array('FALSE',                 "FALSE",               ''             , 'special_code = \'\'')
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        "project_selector like '%state%'",
        "project_selector",
        '/\(special_code = "Emergency" or state = \'avail_1\'\)/',
           'special_code = "Emergency"'
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        "project_selector like '%special_code !=%'",
        "project_selector",
        "/not \(special_code != ''\) and special_code = ''/",
        "special_code = ''"
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        "project_selector like '%)a%'",
        "project_selector",
        "/\)and/",
        ") and"
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        "project_selector like '%\"(nopmq%'",
        "project_selector",
        '/"/',
        "'"
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        "project_selector like '%\"%'",
        "project_selector",
        '/"([^\'"]+)"/',
        "'\$1'"
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        "project_selector like '% '",
        "project_selector",
        '/\s+$/',
        ""
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        'project_selector regexp "  "',
        "project_selector",
        '/\s\s+/',
        ' '
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        'project_selector like "% )%"',
        "project_selector",
        '/ \)/',
        ')'
    );
}

if (0)
{
    tweak_column(
        'queue_defns', array('round_id','name'),
        'project_selector like "%WWI%"',
        "project_selector",
        "/'WWI'/",
        "'WWIStart'"
    );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// phpbb_posts_text

if (0)
{
    tweak_column(
        'phpbb_posts_text', array('post_id'),
        'INSTR(post_text,"//www.pgdp.net/c/tools/proofers/projects.php") AND INSTR(post_text,"prooflevel") AND post_id NOT IN (4363,4852,4863)',
        'post_text',
        '#http://www.pgdp.net/c/tools/proofers/projects.php\?project=(projectID[0-9a-f]{13})&prooflevel=0#',
        'http://www.pgdp.net/c/project.php?id=\1&detail_level=1'
    );
}

if (0)
{
    tweak_column(
        'phpbb_posts_text', array('post_id'),
        'INSTR(post_text,"//www.pgdp.net/c/tools/proofers/projects.php") AND INSTR(post_text,"proofing=1") AND post_id NOT IN (51434,73531,73534)',
        'post_text',
        '#http://www.pgdp.net/c/tools/proofers/projects.php\?project=(projectID[0-9a-f]{13})(&amp;proofstate=\w+)?&amp;proofing=1#',
        'http://www.pgdp.net/c/project.php?id=\1&amp;detail_level=1'
    );
}

if (0)
{
    tweak_column(
        'phpbb_posts_text', array('post_id'),
        'INSTR(post_text,"//www.pgdp.net/c/tools/proofers/projects.php") AND INSTR(post_text,"proofstate") AND post_id NOT IN (20695,51434,73328,73332,73531,73534,91738)',
        'post_text',
        '#http://www.pgdp.net/c/tools/proofers/projects.php\?project=(projectID[0-9a-f]{13})&amp;proofstate=\w+#',
        'http://www.pgdp.net/c/project.php?id=\1'
    );
}

if (0)
{
    tweak_column(
        'phpbb_posts_text', array('post_id'),
        'INSTR(post_text,"//www.pgdp.net/c/tools/proofers/projects.php") AND post_id NOT IN (20695,51434,73328,73332,73531,73534,91738)',
        'post_text',
        '#http://www.pgdp.net/c/tools/proofers/projects.php\?project=(projectID[0-9a-f]{13})(?!&)#',
        'http://www.pgdp.net/c/project.php?id=\1'
    );
}

if (0)
{
    tweak_column(
        'phpbb_posts_text', array('post_id'),
        'INSTR(post_text,"<a href") AND INSTR(post_text,"project comments")',
        'post_text',
        // '#<br><br>Please <a href=([^"\'&>]+)&([^&"\';>]+)>review</a> the project comments #',
        // '#<br><br>Please <a href=([^"\'&>]+)&amp;([^&"\';>]+)>review</a> the project comments #',
        // '#<br>\n<br>\nPlease review the <a href=\'([^"\'&>]+)&amp;([^&"\';>]+)\'>project comments</a> #',
        array(
            '#<br>#',
            '#Please review the <a href=\'([^"\'&>]+)&amp;([^&"\';>]+)\'>project comments</a> #'
        ),
        array(
            "\n",
            "Please review the [url=\\1&amp;\\2]project comments[/url] "
        )
    );
}

if (0)
{
    tweak_column(
        'phpbb_posts_text', array('post_id'),
        'INSTR(post_text,"userteams")',
        'post_text',
        '#http://www.pgdp.net/c/userteams.php\?tid=(\d+)#',
        'http://www.pgdp.net/c/stats/teams/tdetail.php?tid=\1'
    );
}

if (1)
{
    tweak_column(
        'phpbb_posts_text', array('post_id'),
        'post_id=23625',
        'post_text',
        '#http://www.pgdp.net/c/userteams.php\?#',
        'http://www.pgdp.net/c/stats/teams/tlist.php?'
    );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// projects

// projects.nameofwork

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork, "Qual)")',
        'nameofwork',
        '#\(([PF][123] Qual)\)#',
        '{$1}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork, "skipped]")',
        'nameofwork',
        '#\[([PF][123] skipped)\]#i',
        '{$1}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork, "duplicate")',
        'nameofwork',
        '#{Duplicate}#',
        '[duplicate]'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork, "P2alt")',
        'nameofwork',
        '#\(P2alt-r\)#',
        '{P2alt-r}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'nameofwork REGEXP "fraktur[^}]" OR nameofwork REGEXP "[^{]fraktur"',
        'nameofwork',
        // '#[[(]Fraktur[])]#i', '{fraktur}'
        '#\(fraktur, type-in\)#', '{fraktur} (type-in)'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'nameofwork REGEXP "type.in([^}]|$)" OR nameofwork REGEXP "[^{]type.in"',
        'nameofwork',
        // '#[[(]type.in[])]#i', '{type-in}'
        '#\(type-in, missing pages\)#', '[missing pages] {type-in}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        // 'nameofwork REGEXP "missing"',
        'INSTR(nameofwork,"missing") AND NOT INSTR(BINARY nameofwork, " [missing pages]")',
        'nameofwork',
        // '/,? *-* *\(?missing[- ]pages?( project)?\)?($| {)/i', ' [missing pages]\2'
        // '/Missing Pages (--|for) (.*)/', '$2 [missing pages]'
        // '/Missing Pages( Project)?/', 'missing pages'
        // '/([^ ])\[missing/', '$1 [missing'
        // '/(Problem: Found book.*)/', '{$1}'
        '/-- (ToC, missing pages and Index)/', ' [$1]'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork,"--")',
        'nameofwork',
        // '/ -- pages that went to Bahrain instead/', ' [missing pages]'
        // '/--Need Replacements$/', ' {need replacements}'
        '/\((has board game DIAGRAMS--see directions)\)/', '{$1}'
        // '/--(needs fixing[^{}]*)($| {)/', ' {$1}$2'
        // '/ *-+(needs fixing, see notes|transferred)$/', ' {$1}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork,"needs fixing")',
        'nameofwork',
        // '/needs fixing,? \((.*)\)/', 'needs fixing, $1'
        '/needs fixing--/', 'needs fixing, '
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork,"(")',
        'nameofwork',
        // '/\(LATEX\)/', '{LaTeX}'
        // '/\(P3Kwal.\)/', '{P3 Kwal.}'
        '/\((DP did preface|Duplicate|LaTeX|Leftovers|Lilypond|P1\.5|RERUN|R)\)/', '{$1}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'nameofwork REGEXP "^ "',
        'nameofwork',
        // '/\t/', ' '
        '/^ */', ''
        // '/  +/', ' '
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'nameofwork REGEXP "[^ ][[({]"',
        'nameofwork',
        '/([^ ])([[({])/', '$1 $2'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'nameofwork REGEXP "Newcomer|mentoring"',
        'nameofwork',
        '/\((Newcomers only, please|Proof-only mentoring)\)/', '{$1}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'nameofwork REGEXP "Newcomer|mentoring"',
        'nameofwork',
        '/\((Newcomers only, please|Proof-only mentoring)\)/', '{$1}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'nameofwork REGEXP "(Lilypond|R1/R2 project)"',
        'nameofwork',
        '!\[(Lilypond|R1/R2 project)\]!', '{$1}'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork, "missing page") AND nameofwork NOT LIKE "%[missing pages]%" AND nameofwork NOT LIKE "%[missing page]%"',
        'nameofwork',
        // '!{missing pages}!i', '[missing pages]'
        '!{missing pages 2}!i', '[missing pages 2]'
        // '!\s*--\s*missing pages\b!i', ' [missing pages]'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(nameofwork, "missing page") AND nameofwork NOT LIKE "%[missing pages]%" AND nameofwork NOT LIKE "%[missing page]%"',
        'nameofwork',
        // '!{missing pages}!i', '[missing pages]'
        '!{missing pages 2}!i', '[missing pages 2]'
        // '!\s*--\s*missing pages\b!i', ' [missing pages]'
    );
}

// -----------------------------------------------------------------------------

// projects.language

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'instr(language,"trainee")',
        'language',
        '/\.? +TRAINEES( ONLY)?$/', ''
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'instr(language,"html")',
        'language',
        '/\.? +HTML NEEDED$/', ''
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'instr(language,"fraktur")',
        'language',
        '!\s*/\s*Fraktur$!', ''
    );
}

// -----------------------------------------------------------------------------

// projects.comments

if (0)
{
    // remove whitespace in href =, to make next ones easier
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments,"href ") OR INSTR(comments,"href= ")',
        'comments',
        '!(href)\s*=\s*!i', '\1='
    );
}

if (0)
{
    // fix <a href "foo">, insert =
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments,"href ")',
        'comments',
        '!href ([^= ])!', 'href=\1'
    );
}

if (0)
{
    // fix <a href=foo>, insert quotes
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "href=[^\"\']"',
        'comments',
        '!href=([^"\'>]*)>!', 'href="\1">'
    );
}

if (0)
{
    // insert missing " at end of href value
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE \'href[:space:]*=[:space:]*"[^">]*>\'',
        'comments',
        '!(href\s*=\s*"[^">]*)>!', '\1">'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "href[-:]"',
        'comments',
        '!href[-:]"!', 'href="'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        "comments RLIKE 'href\"'",
        'comments',
        '!href"!', 'href="'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments, "<a href>")',
        'comments',
        '!<a href>([^<]+)</a>!', '<a href="\1">\1</a>'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "<table [^>]* table "',
        'comments',
        '!(<table [^>]*) table !', '\1 '
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "<table [^>]* table>"',
        'comments',
        '!(<table [^>]*) table>!i', '\1>'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "<!- [^>]* ->"',
        'comments',
        '#<!- ([^>]*) ->#', '<!-- \1 -->'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments, "<o:p>")',
        'comments',
        '#<o:p>\s*</o:p>#', ''
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments, "</tr\r\n<tr>")',
        'comments',
        '#</tr(\r\n<tr>)#', '</tr>\1'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments, "8221;width")',
        'comments',
        '!&#8221;width:95%;margin:auto;&#8221;!', '"width:95%;margin:auto;">'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments, "<br \\\\>")',
        'comments',
        '!<br \\\\>!', '<br />'
    );
}

if (0)
{
    // $x = '</br >';
    // $x = '<br/ >';
    // $x = '</ br>';
    $x = '</br />';
    tweak_column(
        'projects', array('projectid'),
        "INSTR(comments, '$x')",
        'comments',
        "!$x!", '<br />'
    );
}

if (0)
{
    // insert missing >
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "</?[a-z]+[^>]*<[a-z]"',
        'comments',
        '!(</?[a-z]+[^>\n]*?)(\s*<[a-z])!i', '\1>\2'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments,"<p\r\nSome")',
        'comments',
        '!<p(\r\nSome)!', '<p>\1'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "</font[^>]+>"',
        'comments',
        '!(</font)[^>]+>!i', '\1>'
    );
}

// Change < to &lt; and > to &gt;

if (0)
{
    $x = 'slshell@cox.net';
    $x = 'sjg1978@myrealbox.com';
    $x = 'cweyant@twcny.rr.com';
    $x = 'traverso@dm.unip.it';
    $x = 'martin.agren@home.se';
    $x = 'www.sacred-texts.com';
    $x = 'charlz@lvcablemodem.com';

    tweak_column(
        'projects', array('projectid'),
        "comments RLIKE '<$x>'",
        'comments',
        "#<($x)>#", '&lt;\1&gt;'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments," >this< ")',
        'comments',
        '! >(this|so)< !', ' &gt;\1&lt; '
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'INSTR(comments," >The Great Round World< ")',
        'comments',
        '!< sc >The Great Round World< /sc > \(spaces added for display purposes\)\.!', '&lt;sc&gt;The Great Round World&lt;/sc&gt;.'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "< s"',
        'comments',
        '!< (/?)\s*s\s*c\s*>!', '&lt;\1sc&gt;'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "< [a-z] "',
        'comments',
        '!< (/?)\s*([a-z]) >!i', '&lt;\1\2&gt;'
    );
}

// ---

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "</(\r\n)+[a-z]"',
        'comments',
        '!</\s+([a-z])!', '</\1'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "[a-z](\r\n)+>"',
        'comments',
        '!([a-z])\s+>!', '\1>'
    );
}

if (0)
{
    tweak_column(
        'projects', array('projectid'),
        'comments RLIKE "<(\r\n)+<"',
        'comments',
        '!<(\s*<)!', '\1'
    );
}

if (0)
{
    // $x = '<br<br>'; $y = '<br><br>';
    // $x = '<br<'; $y = '<br>';
    // $x = '<br\?'; $y = '<br>';
    // $x = '<br\.'; $y = '<br>';
    // $x = '</p.>'; $y = '</p>';
    // $x = '</ p>'; $y = '</p>';
    // $x = '</p<'; $y = '</p>';
    // $x = '</p\.'; $y = '</p>';
    // $x = '</p\?'; $y = '</p>';
    // $x = '</li\?'; $y = '</li>';
    // $x = '</b\?'; $y = '</b>';
    // $x = '<td colspan-2>'; $y = '<td colspan=2>';
    // $x = 'Verdana; width="100%"'; $y = 'Verdana;" width="100%"';
    // $x = '<font color=dark red>'; $y = '<font color="dark red">';
    // $x = 'valign>'; $y = 'valign=top>';
    // $x = '</b<'; $y = '</b>';
    $x = '<< or >>'; $y = '&lt;&lt; or &gt;&gt;';
    tweak_column(
        'projects', array('projectid'),
        "INSTR(comments, '$x')",
        'comments',
        "!$x!", $y
    );
}

if (0)
{
    tweak_column(
        'users', array('username'),
        // 'email LIKE "%."', 'email', '/\.$/', ''
        // 'email NOT LIKE "%@%" AND email LIKE "%.%"', 'email', '/2/', '@'
        // 'email NOT LIKE "%@%" AND email LIKE "%.%"', 'email', '/[2#":à]/', '@'
        // 'email NOT LIKE "%@%" AND email LIKE "%.%"', 'email', '/_hot/', '@hot'
        // 'email NOT LIKE "%@%" AND email LIKE "% %"', 'email', array('/ at /','/ dot /'), array('@','.')
        // 'email LIKE "%;%"', 'email', '/;.*/', ''
        // 'email LIKE "%,%"', 'email', '/,/', '.'
        // 'email LIKE "%,%"', 'email', '/,.*/', ''
        // 'email LIKE "%,%"', 'email', '/,/', ''
        // 'email RLIKE ".*@[a-z]+com$"', 'email', '/com$/', '.com'
        // 'email RLIKE ".*@[a-z]+net$"', 'email', '/net$/', '.net'
        // 'email RLIKE ".*@[a-z]+$"', 'email', '/$/', '.com'
        // 'email LIKE "%@%.%" AND email NOT RLIKE "^[-a-z0-9_.+]+@([a-z0-9]+\.)+[a-z0-9]+$"'
        // 'email RLIKE "[^-a-z0-9_.+@]"', 'email', '@[<>/#*]@', ''
        // 'email LIKE "%..%"', 'email', '/\.\./', '.'
        // 'email = "xxx@"', 'email', '/^xxx@$/', ''
        'email = ".com"', 'email', '/^\.com$/', ''
    );
}

// -----------------------------------------------------------------------------

// vim: sw=4 ts=4 expandtab
?>
