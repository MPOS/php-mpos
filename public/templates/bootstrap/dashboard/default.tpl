{if $smarty.session.AUTHENTICATED|default}
<div class="row">
  {include file="dashboard/overview.tpl"}
  {include file="dashboard/system_stats.tpl"}
  {include file="dashboard/account_data.tpl"}
</div>
  {if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  {include file="dashboard/js_api.tpl"}
  {else}
  {include file="dashboard/js_static.tpl"}
  {/if}
{/if}
