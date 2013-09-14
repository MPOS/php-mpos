{if $smarty.session.AUTHENTICATED|default}
{assign var=payout_system value=$GLOBAL.config.payout_system}
{include file="dashboard/default_$payout_system.tpl"}
{include file="dashboard/gauges.tpl"}
{include file="dashboard/graph.tpl"}
{else}
{include file="login/default.tpl"}
{/if}
