<?php
$smarty->registerPlugin("function","acl_check", "check_acl_access");

function check_acl_access($params, $smarty)
{
  $isAuthenticated = isset($_SESSION['AUTHENTICATED']) ? true : false;
  $iAclSetting = $params['acl'];
  // $params['icon'] is deprecated, only needed for mpos compatibility
  if (isset($params['icon'])) {
  	$sUrl = '<li class="'.$params['icon'].'"><a href="'.$_SERVER['SCRIPT_NAME'].'?page='.$params['page'].'&action='.$params['action'].'">'.$params['name'].'</a></li>';
  } else {
  	$sUrl = '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?page='.$params['page'].'&action='.$params['action'].'">'.$params['name'].'</a></li>';
  }
  if (isset($params['fallback']))
    $sFallbackUrl = '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?page='.$params['page'].'">'.$params['name'].'</a></li>';
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
?>
