<?php

namespace App\Helpers;

use Carbon\Carbon;

function inflectByCount($count, $forms) {
    if($count == 1) return $forms['one'];
    switch($count % 10) {
        case 1:
            return $forms['others'];
        case 2:
        case 3:
        case 4:
            return $forms['many'];
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 0:
            return $forms['others'];
    }
}

function russianDate($dt) {
    $carbonDate = Carbon::createFromTimestamp($dt->timestamp)->format('jS F Y г.');
    $replacements = [
        '/(\d+)st/' => '\1',
        '/(\d+)nd/' => '\1',
        '/(\d+)rd/' => '\1',
        '/(\d+)th/' => '\1',
        '/January/' => 'января',
        '/February/' => 'февраля',
        '/March/' => 'марта',
        '/May/' => 'мая',
        '/April/' => 'апреля',
        '/June/' => 'июня',
        '/July/' => 'июля',
        '/August/' => 'августа',
        '/September/' => 'сентября',
        '/October/' => 'октября',
        '/November/' => 'ноября',
        '/December/' => 'декабря',
    ];
    return preg_replace(array_keys($replacements), array_values($replacements), $carbonDate);
}

function russianShortDate($dt) {
    return Carbon::createFromTimestamp($dt->timestamp)->format('j.m.Y');
}
/**
 * Replace all content block variables with rendered values
 *
 * @param $array array ["block_name" => "rendered_value"]
 * @param $string string with variables: <!--{{block_name}}-->
 * @return string
 */
function process_vars($string, $array = null)
{
    // Return content if nothing to replace
    if( !$array )
        return $string;

    $n = $c = array();

    foreach ($array as $k => $v) {
        $n[] = '<!--{{'.$k.'}}-->';
        $c[] = $v;
    }

    return str_replace($n, $c, $string);
}
