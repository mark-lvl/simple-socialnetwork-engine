<?php

function second_to_time($time) {
	if(is_numeric($time)) {
		$value = array(
			"years" => 0, "days" => 0, "hours" => 0,
			"minutes" => 0, "seconds" => 0,
		);
		if($time >= 31556926) {
			$value["years"] = floor($time/31556926);
			$time = ($time%31556926);
		}
		if($time >= 86400) {
			$value["days"] = floor($time/86400);
			$time = ($time%86400);
		}
		if($time >= 3600) {
			$value["hours"] = floor($time/3600);
			$time = ($time%3600);
		}
		if($time >= 60) {
			$value["minutes"] = floor($time/60);
			$time = ($time%60);
		}
		$value["seconds"] = floor($time);
		return (array) $value;
	}
	else{
		return (bool) FALSE;
	}
}

function fa_strftime($format ,$date) {

	$date = strtotime($date);
	
	static $weekdayName		= array("شنبه","يكشنبه","دوشنبه","سه شنبه","چهارشنبه","پنجشنبه","جمعه");
	static $aWeekdayName	= array("ش","ي","د","س","چ","پ","ج");
    static $monthName		= array("فروردين","ارديبهشت","خرداد","تیر","مرداد","شهریور","مهر","آبان","آذر","دی","بهمن","اسفند");
	
	static $trans = array(
		'%b' => '%B',
		'%c' => '%Y/%m/%d %X',
		'%D' => '%m/%d/%y',
		'%h' => '%B',
		'%x' => '%Y/%m/%d'
	);
	$format = strtr($format, $trans);
	
	$weekday = (strftime('%w', $date)+1)%7;
	$jdate = jd_to_persian(gregorian_to_jd(date('Y',$date),date('m',$date),date('d',$date)));

	$year = $jdate['year'];
	$mon = $jdate['mon'];
	$day = $jdate['mday'];
	
	$conv = array(
		'%a' => $aWeekdayName[$weekday],
		'%A' => $weekdayName[$weekday],
		'%B' => $monthName[$mon-1],
		'%C' => sprintf("%02d",floor($year/100)),
		'%d' => sprintf("%02d",$day),
		'%e' => sprintf("%2d",$day),
		'%m' => sprintf("%02d",$mon),
		'%p' => date("a",$date)=='pm' ? 'ب ظ' : 'ق ظ',
		'%p' => date("a",$date)=='pm' ? 'ب.ظ' : 'ق.ظ',
		'%u' => $weekday+1,
		'%w' => $weekday,
		'%y' => sprintf("%02d",$year % 100),
		'%Y' => $year
	);
	return strftime(strtr($format, $conv),$date);
}

//====================================================================
//                           calendar.php
//                          by John Walker
//				    converted by Mohammad Taha Jahangir
//              http://www.fourmilab.ch/documents/calendar/

function mod($a, $b){
    return $a - ($b * floor($a / $b));
}

//  LEAP_GREGORIAN  --  Is a given $year in the Gregorian calendar a leap $year ?
function leap_gregorian($year)
{
    return (($year % 4) == 0) &&
            (!((($year % 100) == 0) && (($year % 400) != 0)));
}

//  GREGORIAN_TO_JD  --  Determine Julian $day number from Gregorian calendar date
define('GREGORIAN_EPOCH', 1721425.5);

//function gregorian_to_jd_date($datee){
//	return gregorian_to_jd($datee['year'],$datee['mon'],$datee['mday']);
//}
function gregorian_to_jd($year, $month, $day)
{
    return (GREGORIAN_EPOCH - 1) +
           (365 * ($year - 1)) +
           floor(($year - 1) / 4) +
           (-floor(($year - 1) / 100)) +
           floor(($year - 1) / 400) +
           floor((((367 * $month) - 362) / 12) +
           (($month <= 2) ? 0 :
                               (leap_gregorian($year) ? -1 : -2)
           ) +
           $day);
}

//  JD_TO_GREGORIAN  --  Calculate Gregorian calendar date from Julian $day

function jd_to_gregorian($jd) {
    //var $wjd, $depoch, $quadricent, $dqc, $cent, $dcent, $quad, $dquad,
    //    $yindex, $dyindex, $year, $yearday, $leapadj;
    $wjd = floor($jd - 0.5) + 0.5;
    $depoch = $wjd - GREGORIAN_EPOCH;
    $quadricent = floor($depoch / 146097);
    $dqc = mod($depoch, 146097);
    $cent = floor($dqc / 36524);
    $dcent = mod($dqc, 36524);
    $quad = floor($dcent / 1461);
    $dquad = mod($dcent, 1461);
    $yindex = floor($dquad / 365);
    $year = ($quadricent * 400) + ($cent * 100) + ($quad * 4) + $yindex;
    if (!(($cent == 4) || ($yindex == 4))) {
        $year++;
    }
    $yearday = $wjd - gregorian_to_jd($year, 1, 1);
    $leapadj = (($wjd - gregorian_to_jd($year, 3, 1)) ? 0 : (leap_gregorian($year) ? 1 : 2));
    $month = floor(((($yearday + $leapadj) * 12) + 373) / 367);
    $day = ($wjd - gregorian_to_jd($year, $month, 1)) + 1;

    return array('year'=>$year, 'mon'=>$month,'mday'=> $day);
}



//  LEAP_PERSIAN  --  Is a given $year a leap $year in the Persian calendar ?
function leap_persian($year) {
    return (((((($year - (($year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816) < 682;
}

//  PERSIAN_TO_JD  --  Determine Julian $day from Persian date
define('PERSIAN_EPOCH', 1948320.5);

function persian_to_jd($year, $month, $day)
{
    //var $epbase, $epyear;
    $epbase = $year - (($year >= 0) ? 474 : 473);
    $epyear = 474 + mod($epbase, 2820);

    return $day +
            (($month <= 7) ?
                (($month - 1) * 31) :
                ((($month - 1) * 30) + 6)
            ) +
            floor((($epyear * 682) - 110) / 2816) +
            ($epyear - 1) * 365 +
            floor($epbase / 2820) * 1029983 +
            (PERSIAN_EPOCH - 1);
}

//  JD_TO_PERSIAN  --  Calculate Persian date from Julian $day

function jd_to_persian($jd)
{
    //var $year, $month, $day, $depoch, $cycle, $cyear, $ycycle,
    //    $aux1, $aux2, $yday;

    $jd = floor($jd) + 0.5;

    $depoch = $jd - persian_to_jd(475, 1, 1);
    $cycle = floor($depoch / 1029983);
    $cyear = mod($depoch, 1029983);
    if ($cyear == 1029982) {
        $ycycle = 2820;
    } else {
        $aux1 = floor($cyear / 366);
        $aux2 = mod($cyear, 366);
        $ycycle = floor(((2134 * $aux1) + (2816 * $aux2) + 2815) / 1028522) +
                    $aux1 + 1;
    }
    $year = $ycycle + (2820 * $cycle) + 474;
    if ($year <= 0) {
        $year--;
    }
    $yday = ($jd - persian_to_jd($year, 1, 1)) + 1;
    $month = ($yday <= 186) ? ceil($yday / 31) : ceil(($yday - 6) / 30);
    $day = ($jd - persian_to_jd($year, $month, 1)) + 1;
    return array('year'=>$year, 'mon'=>$month,'mday'=> $day);
}

?>