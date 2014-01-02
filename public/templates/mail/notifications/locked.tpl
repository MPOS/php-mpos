<html>
<body>
<p>You account has been locked due to too many failed password or PIN attempts. Please follow the URL below to unlock your account.</p>
<p>http://{$smarty.server.SERVER_NAME}{$smarty.server.PHP_SELF}?page=account&action=unlock&token={nocache}{$DATA.token}{/nocache}</p>
<br/>
<br/>
</body>
</html>
