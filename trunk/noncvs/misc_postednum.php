<?
error_reporting(E_ALL);
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
new dbConnect();

function show_inconsistent()
// "gold" texts that don't have a postednum,
// and non-gold texts that do.
{
	dpsql_dump_query("
		SELECT
			postednum,
			state,
			FROM_UNIXTIME(modifieddate) as mod_date,
			nameofwork
		FROM projects
		WHERE ".SQL_CONDITION_GOLD." = (postednum='6000')
		ORDER BY postednum
	");
}

function show_by_postednum()
{
	$res = dpsql_query("
		SELECT postednum, projectid, nameofwork, authorsname
		FROM projects
		WHERE postednum != 6000
		ORDER BY postednum, nameofwork
	");
	header( "Content-Type: text/plain" );
	while ( list($pgnum,$projectid,$title,$author) = mysql_fetch_row($res) )
	{
		echo "$pgnum\t$projectid\t$title\t$author\n";
	}
}

function each_gold()
{
	$res = dpsql_query( "
		SELECT projectid, nameofwork, authorsname, postednum, txtlink, htmllink, ziplink
		FROM projects
		WHERE ".SQL_CONDITION_GOLD."
		ORDER BY projectid
	" );
	echo "<pre>\n";
	while( $row = mysql_fetch_assoc($res) )
	{
		if (1)
		{
			if ( $row['postednum'] < 12840 )
			{
				print "\n";
				print "projid: {$row['projectid']}\n";
				// print "title : {$row['nameofwork']}\n";
				// print "author: {$row['authorsname']}\n";
				print "pgnum : {$row['postednum']}\n";
			}
		}
		else
		{
			$postednum = $row['postednum'];
			if ( (0+$postednum) >= 10000 )
			{
				foreach ( array('txtlink','ziplink','htmllink') as $fieldname )
				{
					$link = $row[$fieldname];
					if ( !ok( $link, $postednum, $fieldname ) )
					{
						$f = str_pad($fieldname,8);
						print "\n";
						print "postednum    : $postednum\n";
						print "$f : $link\n";
					}
				}
			}
		}
	}
	echo "</pre>\n";
}

function ok( $link, $postednum, $fieldname )
{
	if ( $link == '' ) return True;

	// ---------------------

	$r = preg_match(
		"#^(http://[a-z./]+)(.*)$#",
		$link, $groups );
	if ( $r != 1 ) return False;

	list($_,$doeach_gold,$rest) = $groups;

	if ( !in_array( $doeach_gold,
			array(
				'http://gutenberg.net/',
				'http://www.gutenberg.net/',
				'http://www.ibiblio.org/gutenberg/',
				'http://ibiblio.unc.edu/pub/docs/books/gutenberg/',
				'http://www.gutenberg.net/dirs/'
			)
		)
	)
	{
		// return False;
	}

	// ---------------------

	$r = preg_match(
		"#^([0-9/]+)(.*)$#",
		$rest, $groups );
	if ( $r != 1 ) return False;

	list($_,$base,$rest) = $groups;

	$chars = preg_split('//', $postednum, -1, PREG_SPLIT_NO_EMPTY);
	$singles = implode('/', array_slice($chars,0,-1) );
	$expected_base = "$singles/$postednum/$postednum";

	if ( $base != "$singles/$postednum/$postednum" && $base != "$singles/$postednum" )
	{
		return False;
	}
	
	return True;
}



// show_by_postednum();
// show_inconsistent();
// each_gold();
// vim: sw=4 ts=4
?>
