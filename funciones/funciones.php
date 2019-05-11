<?php 
/* 
* days_in_month($month, $year) 
* Returns the number of days in a given month and year, taking into account leap years. 
* 
* $month: numeric month (integers 1-12) 
* $year: numeric year (any integer) 
* 
* Prec: $month is an integer between 1 and 12, inclusive, and $year is an integer. 
* Post: none 
*/ 
// corrected by ben at sparkyb dot net 
function days_in_month($month, $year) 
{ 
// calculate number of days in a month 
return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); 
} 
?> 