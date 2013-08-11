<?PHP
function testsql($username)
{
    return "
            SELECT
                p.projectid,
                peco.round_id,
                CASE peco.round_id
                    WHEN 'P1' THEN 1
                    WHEN 'P2' THEN 2
                    WHEN 'P3' THEN 3
                    WHEN 'F1' THEN 4
                    WHEN 'F2' THEN 5
                    ELSE
                        peco.round_id
                END AS sortkey,
                p.nameofwork,
                p.state,
                SUM( CASE WHEN peco.round_id + '_avail' = p.state 
                            AND pefinal.username = peco.username
                            AND pefinal.event_type IN ( 'checkout',
                                        'saveAsInProgress', 'reopen' )
                                THEN 1 ELSE 0 END 
                    ) AS pages_active,
                SUM( CASE WHEN pesave.image IS NOT NULL
                            AND pesavecheck.image IS NULL
                                THEN 1 ELSE 0 END
                    ) AS pages_mine,
                SUM( CASE WHEN pediff.image IS NOT NULL 
                            AND pediffcheck.image IS NULL
                                THEN 1 ELSE 0 END 
                    ) AS pages_diffable,
                MAX( CASE WHEN pediff.timestamp IS NOT NULL
                            AND pediffcheck.image IS NULL
                                THEN pediff.timestamp ELSE null END 
                    ) AS date_diffable,
                MAX(peco.timestamp
                    ) AS max_timestamp,
                SUM(1) AS mypagecount

            FROM
                projects AS p


            JOIN
                page_events AS peco
            ON
                p.projectid         = peco.projectid
                AND p.archived      = 0
                AND p.state        != 'project_delete'
                AND peco.event_type   = 'checkout'
                AND peco.username     = peco.username

            LEFT JOIN
                page_events AS pecocheck
            ON
                peco.projectid      = pecocheck.projectid
                AND peco.image      = pecocheck.image
                AND peco.round_id   = pecocheck.round_id
                AND 'checkout'      = pecocheck.event_type
                AND peco.timestamp  < pecocheck.timestamp
            

            LEFT JOIN
                page_events AS pefinal
            ON
                peco.projectid        = pefinal.projectid
                AND peco.image        = pefinal.image

            LEFT JOIN 
                page_events AS pefinalcheck
            ON
                pefinal.projectid        = pefinalcheck.projectid
                AND pefinal.image        = pefinalcheck.image
                AND pefinal.timestamp    < pefinalcheck.timestamp



            LEFT JOIN 
                page_events AS pesave
            ON
                peco.projectid          = pesave.projectid
                AND peco.image          = pesave.image
                AND peco.round_id       = pesave.round_id
                AND pesave.event_type     = 'saveAsDone'
                AND pesave.username     = peco.username
                AND peco.timestamp      < pesave.timestamp

            LEFT JOIN 
                page_events AS pesavecheck
            ON
                pesave.projectid        = pesavecheck.projectid
                AND pesave.image        = pesavecheck.image
                AND pesave.round_id     = pesavecheck.round_id
                AND pesave.timestamp    < pesavecheck.timestamp



           LEFT JOIN
                page_events AS pediff
            ON
                pesave.projectid          = pediff.projectid
                AND pesave.image          = pediff.image
                AND pesave.event_type     = 'saveAsDone'
                AND pediff.event_type     = 'saveAsDone'
                AND pediff.round_id       = 
                    CASE
                        WHEN pesave.round_id = 'P1' THEN 'P2'
                        WHEN pesave.round_id = 'P2' THEN 'P3'
                        WHEN pesave.round_id = 'P3' THEN 'F1'
                        WHEN pesave.round_id = 'F1' THEN 'F2'
                    END
                AND pesave.timestamp    < pediff.timestamp  

            LEFT JOIN
                page_events AS pediffcheck
            ON
                pediff.projectid        = pediffcheck.projectid
                AND pediff.image        = pediffcheck.image
                AND pediff.round_id     = pediffcheck.round_id
                AND pediff.timestamp    < pediffcheck.timestamp 


           WHERE
                peco.username = '$username'
                AND pefinalcheck.image IS NULL
                AND pecocheck.image IS NULL
                AND pesavecheck.image IS NULL
                AND pediffcheck.image IS NULL

            GROUP BY
                p.projectid,
                peco.round_id,
                p.nameofwork,
                p.state
            
            ORDER BY
                p.projectid,
                sortkey\n";
}
?>
