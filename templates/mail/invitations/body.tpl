<html>
<body>
<p>{t}Hello valued miner{/t},</p><br />
<p>{nocache}{$DATA.username}{/nocache} {t}invited you to participate on this pool{/t}:
<p><a href="http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{if $smarty.server.SERVER_PORT != "443" && $smarty.server.SERVER_PORT != "80"}:{$smarty.server.SERVER_PORT}{/if}{$smarty.server.SCRIPT_NAME}?page=register&token={nocache}{$DATA.token}{/nocache}">http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=register&token={nocache}{$DATA.token}{/nocache}</a></p>
{if $DATA.message}<p>{t}Personal message{/t}:</p><p>{nocache}{$DATA.message}{/nocache}</p>{/if}
<p></p>
<p>{t}Cheers,{/t}</p>
<p>{$WEBSITENAME}</p>
</body>
</html>
