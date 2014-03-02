<html>
<body>
<p>Hello valued miner,</p><br />
<p>{nocache}{$DATA.username}{/nocache} invited you to participate on this pool:
<p>http://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=register&token={nocache}{$DATA.token}{/nocache}</p>
{if $DATA.message}<p>Personal message:</p><p>{nocache}{$DATA.message}{/nocache}</p>{/if}
<p></p>
<p>Cheers,</p>
<p>{$WEBSITENAME}</p>
</body>
</html>
