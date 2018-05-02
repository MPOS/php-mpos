<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Output JSON format
if ($this->getGetCache() && $data = $this->memcache->getStatic(STATISTICS_ALL_USER_HASHRATES)) {
    echo $api->get_json($this->memcache->getStatic(STATISTICS_ALL_USER_HASHRATES));
} else {
    echo $api->get_json('');
}

// Supress master template
$supress_master = 1;
