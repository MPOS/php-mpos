<?php
/**
 * @copyright 2010 MAPIX Technologies Ltd, UK, http://mapix.com/
 * @license http://en.wikipedia.org/wiki/BSD_licenses  BSD License
 * @package Smarty
 * @subpackage PluginsModifier
 */

function smarty_modifier_seconds_to_hhmmss($sec, $padHours = false) {
      $hms = "";
      $hours = intval(intval($sec) / 3600); 
      $hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':' : $hours. ':';
      $minutes = intval(($sec / 60) % 60); 
      $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
      $seconds = intval($sec % 60); 
      $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
      return $hms;
}
