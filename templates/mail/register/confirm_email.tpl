<html>
<body>
<p>{t}Hello{/t} {nocache}{$DATA.username}{/nocache},</p><br />
<p>{t}You have created a new account. In order to complete the registration process please follow this link:{/t}</p>
<p>http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=account&action=confirm&token={nocache}{$DATA.token}{/nocache}</p>
<p></p>
<p>{t}Cheers,{/t}</p>
<p>{$WEBSITENAME}</p>
</body>
</html>
