<html>
<body>
<p>You have a pending request to change your password.</p>
<p>If you initiated this request, please follow the link below to confirm your changes. If you did NOT, please notify an administrator.</p>
<p><a href="http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=account&action=edit&cp_token={nocache}{$DATA.token}{/nocache}">http{if $smarty.server.HTTPS|default:"" eq "on"}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=account&action=edit&cp_token={nocache}{$DATA.token}{/nocache}</a></p>
<br/>
<br/>
</body>
</html>
