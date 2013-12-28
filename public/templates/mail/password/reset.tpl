<html>
<body>
<p>Hello {$DATA.username},</p><br />
<p>You have requested a password reset through our online form. In order to complete the request please follow this link:</p>
<p>http://{$smarty.server.SERVER_NAME}{$smarty.server.PHP_SELF}?page=password&action=change&token={$DATA.token}</p>
<p>You will be asked to change your password. You can then use this new password to login to your account.</p>
<p>Cheers,</p>
<p>Website Administration</p>
</body>
</html>
