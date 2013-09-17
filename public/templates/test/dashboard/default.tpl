{if $smarty.session.AUTHENTICATED|default}
  {assign var=payout_system value=$GLOBAL.config.payout_system}
  {include file="dashboard/overview.tpl"}
  {include file="dashboard/round_data.tpl"}
  {include file="dashboard/account_data.tpl"}
  {include file="dashboard/default_$payout_system.tpl"}
  {include file="dashboard/js.tpl"}
{/if}
