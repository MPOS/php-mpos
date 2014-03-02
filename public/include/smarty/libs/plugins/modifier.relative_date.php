<?php
 
/*
* Smarty plugin
* -------------------------------------------------------------
* Type:     modifier
* Name:     relative_date
* Version:  1.1
* Date:     November 28, 2008
* Author:   Chris Wheeler <chris@haydendigital.com>
* Purpose:  Output dates relative to the current time
* Input:    timestamp = UNIX timestamp or a date which can be converted by strtotime()
*           days = use date only and ignore the time
*           format = (optional) a php date format (for dates over 1 year)
* -------------------------------------------------------------
*/
 
function smarty_modifier_relative_date($timestamp, $days = false, $format = "M j, Y") {
  
  if (!is_numeric($timestamp)) {
    // It's not a time stamp, so try to convert it...
    $timestamp = strtotime($timestamp);
  }
  
  if (!is_numeric($timestamp)) {
    // If its still not numeric, the format is not valid
    return false;
  }
  
  // Calculate the difference in seconds
  $difference = time() - $timestamp;
  
  // Check if we only want to calculate based on the day
  if ($days && $difference < (60*60*24)) { 
    return "Today"; 
  }
  if ($difference < 3) { 
    return "Just now"; 
  }
  if ($difference < 60) {    
    return $difference . " seconds ago"; 
  }
  if ($difference < (60*2)) {    
    return "1 minute ago"; 
  }
  if ($difference < (60*60)) { 
    return intval($difference / 60) . " minutes ago"; 
  }
  if ($difference < (60*60*2)) { 
    return "1 hour ago"; 
  }
  if ($difference < (60*60*24)) {    
    return intval($difference / (60*60)) . " hours ago"; 
  }
  if ($difference < (60*60*24*2)) { 
    return "1 day ago"; 
  }
  if ($difference < (60*60*24*7)) { 
    return intval($difference / (60*60*24)) . " days ago"; 
  }
  if ($difference < (60*60*24*7*2)) { 
    return "1 week ago"; 
  }
  if ($difference < (60*60*24*7*(52/12))) { 
    return intval($difference / (60*60*24*7)) . " weeks ago"; 
  }
  if ($difference < (60*60*24*7*(52/12)*2)) { 
    return "1 month ago"; 
  }
  if ($difference < (60*60*24*364)) { 
    return intval($difference / (60*60*24*7*(52/12))) . " months ago"; 
  }
  
  // More than a year ago, just return the formatted date
  return @date($format, $timestamp);
 
}
 
?>