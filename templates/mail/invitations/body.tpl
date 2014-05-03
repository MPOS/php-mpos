<html>
<body>
<p>Hello valued miner,</p><br />
<p>{nocache}{$DATA.username}{/nocache} invited you to participate on this pool:
<p><a href="http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=register&token={nocache}{$DATA.token}{/nocache}">http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=register&token={nocache}{$DATA.token}{/nocache}</a></p>
{if $DATA.message}<p>Personal message:</p><p>{nocache}{$DATA.message}{/nocache}</p>{/if}
<p></p>
<p>Cheers,</p>
<p>{$WEBSITENAME}</p>
</body>
</html>
