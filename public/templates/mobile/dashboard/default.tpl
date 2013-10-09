{if $smarty.session.AUTHENTICATED|default}
  {assign var=payout_system value=$GLOBAL.config.payout_system}
  {include file="dashboard/overview.tpl"}
  {include file="dashboard/js.tpl"}
{/if}
