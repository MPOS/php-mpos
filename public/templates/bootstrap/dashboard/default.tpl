{if $smarty.session.AUTHENTICATED|default}
{literal}<div class="row">{/literal}
  {include file="dashboard/overview.tpl"}
  {include file="dashboard/system_stats.tpl"}
{literal}</div>{/literal}
  {if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  {include file="dashboard/js_api.tpl"}
  {else}
{literal}<div class="row">{/literal}
{literal}</div>{/literal}
  {include file="dashboard/js_static.tpl"}
  {/if}
{/if}
