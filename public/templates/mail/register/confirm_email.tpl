<html>
<body>
<p>Hello {$DATA.username},</p><br />
<p>You have create a new account. In order to complete the registration process please follow this link:</p>
<p>http://{$smarty.server.SERVER_NAME}{$smarty.server.PHP_SELF}?page=account&action=confirm&token={$DATA.token}</p>
<p></p>
<p>Cheers,</p>
<p>Website Administration</p>
</body>
</html>
