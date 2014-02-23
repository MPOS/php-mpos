{nocache}
{include file="admin/dashboard/version.tpl"}
{include file="admin/dashboard/users.tpl"}
{include file="admin/dashboard/status.tpl"}
{if $GLOBAL.config.disable_invitations|default:"0" == 0}{include file="admin/dashboard/invitations.tpl"}{/if}
{include file="admin/dashboard/logins.tpl"}
{include file="admin/dashboard/registrations.tpl"}
{/nocache}
