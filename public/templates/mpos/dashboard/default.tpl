{if $smarty.session.AUTHENTICATED|default}
  {include file="dashboard/overview.tpl"}
  {include file="dashboard/system_stats.tpl"}
  {include file="dashboard/round_data.tpl"}
  {include file="dashboard/account_data.tpl"}
  {include file="dashboard/js.tpl"}
{/if}
