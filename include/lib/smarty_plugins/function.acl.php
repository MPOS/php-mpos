<?php
$smarty->registerPlugin("function","acl_check", "check_acl_access");

function check_acl_access($params, $smarty)
{
  $isAuthenticated = isset($_SESSION['AUTHENTICATED']) ? true : false;
  $iAclSetting = $params['acl'];
  $name = gettext($params['name']);
  $namehtml = isset($params['namehtml']) ? $params['namehtml'] : '';
  // $params['icon'] is deprecated, only needed for mpos compatibility
  if (isset($params['icon'])) {
  	$sUrl = '<li class="'.$params['icon'].'"><a href="'.$_SERVER['SCRIPT_NAME'].'?page='.$params['page'].'&action='.$params['action'].'">'.$namehtml.$name.'</a></li>';
  } else {
  	$sUrl = '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?page='.$params['page'].'&action='.$params['action'].'">'.$namehtml.$name.'</a></li>';
  }
  if (isset($params['fallback']))
    $sFallbackUrl = '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?page='.$params['page'].'">'.$namehtml.$name.'</a></li>';
  switch($iAclSetting) {
  case '0':
    if ($isAuthenticated) {
      echo $sUrl;
    } else if (isset($params['fallback']) && !$isAuthenticated) {
      echo $sFallbackUrl;
    }
    break;
  case '1':
      echo $sUrl;
    break;
  case '2':
    break;
  default:
    echo $sUrl;
    break;
  }
}
