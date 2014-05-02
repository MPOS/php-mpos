{if $smarty.session.AUTHENTICATED|default}
  <div class="row">
    <div class="col-md-8">
      {if $GLOBAL.config.currency != 'WC' and $GLOBAL.config.currency != 'SUM'}
      {include file="dashboard/overview.tpl"}
      {include file="dashboard/round_data.tpl"}
      {/if}
      {include file="dashboard/account_data.tpl"}
    </div>

    <div class="col-md-4">
      {if $GLOBAL.config.currency != 'WC' and $GLOBAL.config.currency != 'SUM'}
      {include file="dashboard/system_stats.tpl"}
      {/if}
    </div>
  </div>
  {if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  {include file="dashboard/js_api.tpl"}
  {else}
  {include file="dashboard/js_static.tpl"}
  {/if}
{/if}
