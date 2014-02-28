{if $smarty.session.AUTHENTICATED|default}
  {include file="dashboard/overview.tpl"}
{literal}<div class="row">{/literal}
  {include file="dashboard/system_stats.tpl"}
  {include file="dashboard/round_data.tpl"}
  {include file="dashboard/account_data.tpl"}
{literal}</div>{/literal}

  {if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  {include file="dashboard/js_api.tpl"}
  {else}
  {include file="dashboard/js_static.tpl"}
  {/if}
{/if}
