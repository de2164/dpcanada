<?
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

$title = _("Old Miscellaneous Statistics");
theme($title, "header");

echo "<br><br><h2>$title</h2><br>\n";

echo "<br><br><h3>Stats on this page were collected under the old two round system, between Dec 1st 2000 and 30th May 2005<h3><br><br>";
echo "They are shown here for historical interest<br><br>";

show_all_time_total();

show_month_sums( 'top_ten' );

show_top_days( 100, 'ever' );
//show_top_days( 10, 'this_year' );

show_month_sums( 'all_chron' );
show_month_sums( 'all_by_pages' );

show_months_with_most_days_over(5000);
show_months_with_most_days_over(6000);
show_months_with_most_days_over(7000);
show_months_with_most_days_over(8000);
show_months_with_most_days_over(9000);
show_months_with_most_days_over(10000);
show_months_with_most_days_over(11000);
show_months_with_most_days_over(12000);

// -----------------------------------------------------------------------------

function show_all_time_total()
{
$sub_title = _("Total Pages Proofread between 1st Dec 2000 and 30th May 2005");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_themed_query("
SELECT
SUM(pages) as 'Total Pages Proofread So Far',
count(*) as 'Number of Days Proofing',
sum(pages)/count(*) as 'Average Pages Proofread per Day under Old System'
FROM pagestats
");

echo "<br>\n";

$sub_title = _("Average Pages Proofread per Day for each year under the Old System");
echo "<h3>$sub_title</h3>\n";


dpsql_dump_themed_query("
SELECT
Year,
SUM(pages) as 'Total Pages Proofread',
count(*) as 'Number of Days Proofing',
sum(pages)/count(*) as 'Average Pages Proofread per Day under Old System'
FROM pagestats
group by year
");

}

function show_top_days( $n, $when )
{
switch ( $when )
{
case 'ever':
$where = '';
$sub_title = sprintf( _('Top %d Proofreading Days Ever'), $n );
break;

case 'this_year':
$where = 'WHERE year = YEAR(NOW())';
$sub_title = sprintf( _('Top %d Proofreading Days This Year'), $n );
break;

default:
die( "bad value for 'when': '$when'" );
}

echo "<h3>$sub_title</h3>\n";

dpsql_dump_themed_ranked_query("
SELECT
date as 'Date',
pages as 'Pages Proofread',
comments as 'Comment' 
FROM pagestats
$where
ORDER BY 2 DESC
LIMIT $n
");

echo "<br>\n";
}

function show_month_sums( $which )
{
switch ( $which )
{
case 'top_ten':
$sub_title = _("Top Ten Best Proofreading Months");
$order = '2 DESC';
$limit = 'LIMIT 10';
break;

case 'all_chron':
$sub_title = _("Historical Log of Total Pages Proofread Per Month");
$order = '1'; // chronological
$limit = '';
break;

case 'all_by_pages':
$sub_title = _("Total Pages Proofread Per Month");
$order = '2 DESC';
$limit = '';
break;

default:
die( "bad value for 'which': '$which'" );
}

echo "<h3>$sub_title</h3>\n";

dpsql_dump_themed_ranked_query("
SELECT
DATE_FORMAT(date,'%Y-%m') as 'Month',
SUM(pages) as 'Pages Proofread',
SUM(dailygoal) as 'Monthly Goal'
FROM pagestats
GROUP BY year, month
ORDER BY $order
$limit
");

echo "<br>\n";
}

function show_months_with_most_days_over( $n )
{
$sub_title = sprintf( _('Months with most days over %s pages'), number_format($n) );
echo "<h3>$sub_title</h3>\n";

dpsql_dump_themed_ranked_query("
SELECT
DATE_FORMAT(date,'%Y-%m') as 'Month',
count(*) as 'Number of Days'
FROM pagestats
WHERE pages >= $n
GROUP BY year, month
ORDER BY 2 DESC
LIMIT 10
");

echo "<br>\n";
}

theme("","footer");
?>

