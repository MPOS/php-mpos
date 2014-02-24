{nocache}
{include file="admin/dashboard/mpos.tpl"}
{include file="admin/dashboard/user.tpl"}
{include file="admin/dashboard/registrations.tpl"}
{if $GLOBAL.config.disable_invitations|default:"0" == 0}
{include file="admin/dashboard/invitation.tpl"}
{/if}
{/nocache}
