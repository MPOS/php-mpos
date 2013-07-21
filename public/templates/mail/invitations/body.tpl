<html>
<body>
<p>Hello valued miner,</p><br />
<p>{$DATA.username} invited you to participate on this pool:
<p>http://{$smarty.server.SERVER_NAME}{$smarty.server.PHP_SELF}?page=register&token={$DATA.token}</p>
{if $DATA.message}<p>Personal message:</p><p>{$DATA.message}</p>{/if}
<p></p>
<p>Cheers,</p>
<p>Website Administration</p>
</body>
</html>
