<?PHP

function dpsql_tdump_query( $query )
{
    $res = dpsql_query($query);
    dpsql_tdump_query_result( $res );
}

function dpsql_tdump_query_result( $res )
{
    $n_rows = mysql_num_rows($res);
    if ( $n_rows == 0 )
    {
        echo "Empty result\n";
        return;
    }

    $n_cols = mysql_num_fields($res);

    for ( $pass = 1; $pass <= 2; $pass++ )
    {
        if ( $pass == 1 )
        {
            $col_widths = array();
        }
        else
        {
            mysql_data_seek($res,0);
            $divider = '';
            for ($c = 0; $c < $n_cols; $c++ )
            {
                $divider .= "+-" .  str_repeat('-',$col_widths[$c]) . '-';
            }
            $divider .= "+\n";
            echo $divider;
        }

        {
            for ($c = 0; $c < $n_cols; $c++ )
            {
                $field_name = mysql_field_name($res, $c);
                if ( $pass == 1 )
                {
                    $w = strlen($field_name);
                    $col_widths[$c] = $w;
                }
                else
                {
                    echo "| ", str_pad($field_name,$col_widths[$c]), " ";
                }
            }
            if ( $pass == 2 ) echo "|\n";
        }

        if ( $pass == 2 ) echo $divider;

        while ( $row = mysql_fetch_row($res) )
        {
            for ($c = 0; $c < $n_cols; $c++ )
            {
                $val = $row[$c];
                if (is_null($val)) { $val = 'NULL'; }

                if ( $pass == 1 )
                {
                    $w = strlen($val);
                    if ( $w > $col_widths[$c] ) $col_widths[$c] = $w;
                }
                else
                {
                    echo "| ", str_pad($val,$col_widths[$c]), " ";
                }
            }
            if ( $pass == 2 ) echo "|\n";
        }

        if ( $pass == 2 ) echo $divider;
    }

    echo "$n_rows rows in set\n";

    mysql_data_seek($res,0);
}

// vim: sw=4 ts=4 expandtab
?>
