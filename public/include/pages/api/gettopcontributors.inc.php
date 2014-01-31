<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user token and access level permissions
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Some settings
if ( ! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;

// Fetch raw data
$aContributorsHashes = $statistics->getTopContributors('hashes', 15);
$aContributorsShares = $statistics->getTopContributors('shares', 15);

// Honor the anonymous flag
foreach ($aContributorsHashes as $iKey => $aData) {
  if ($user->isAdmin($user_id)) {
    $aContributorsHashes = array(
      'account' => $aData['account'],
      'hashrate' => $aData['hashrate']
    );
  } else if ($aData['is_anonymous'] == 1) {
    $aContributorsHashes = array(
      'account' => 'anonymous',
      'hashrate' => $aData['hashrate']
    );
  } else {
    $aContributorsHashes = array(
      'account' => $aData['account'],
      'hashrate' => $aData['hashrate']
    );
  }
}

// Honor the anonymous flag
foreach ($aContributorsShares as $iKey => $aData) {
  if ($user->isAdmin($user_id)) {
    $aContributorsShares[$iKey] = array(
      'account' => $aData['account'],
      'shares' => $aData['shares']
    );
  } else if ($aData['is_anonymous'] == 1) {
    $aContributorsShares[$iKey] = array(
      'account' => 'anonymous',
      'shares' => $aData['shares']
    );
  } else {
    $aContributorsShares[$iKey] = array(
      'account' => $aData['account'],
      'shares' => $aData['shares']
    );
  }
}

// Output JSON format
$data = array(
  'hashes' => $aContributorsHashes,
  'shares' => $aContributorsShares
);
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
