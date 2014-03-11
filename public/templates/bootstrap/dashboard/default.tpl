{if $smarty.session.AUTHENTICATED|default}
<div class="row">
  {* Load our payout system so we can load some payout specific templates *}
  {assign var=PAYOUT_SYSTEM value=$GLOBAL.config.payout_system}
  {include file="dashboard/overview/default.tpl"}
  {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/default.tpl"}
  {include file="dashboard/account_data/default.tpl"}
  {include file="dashboard/worker_information/default.tpl"}
</div>
  {* Include our JS libraries, we allow a live updating JS and a static one *}
  {if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  {include file="dashboard/js/api.tpl"}
  {else}
  {include file="dashboard/js/static.tpl"}
  {/if}
{/if}
