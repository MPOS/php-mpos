<html>
<body>
<p>You account has been locked due to too many failed password or PIN attempts. Please follow the URL below to unlock your account.</p>
<p><a href="http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=account&action=unlock&token={nocache}{$DATA.token}{/nocache}">http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=account&action=unlock&token={nocache}{$DATA.token}{/nocache}</a></p>
<br/>
<br/>
</body>
</html>
