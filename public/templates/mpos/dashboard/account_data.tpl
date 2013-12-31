<article class="module width_quarter">
  <header><h3>Account Information</h3></header>
    <table class="tablesorter" cellspacing="0">
      <tr>
        <td colspan="2">
{if $GLOBAL.userdata.no_fees}
        You are mining without any pool fees applied and
{else if $GLOBAL.fees > 0}
        You are mining at <font color="orange">{if $GLOBAL.fees < 0.0001}{$GLOBAL.fees|escape|number_format:"8"}{else}{$GLOBAL.fees|escape}{/if}%</font> pool fee and
{else}
        This pool does not apply fees and
{/if}
{if $GLOBAL.userdata.donate_percent > 0}
        you donate <font color="green">{$GLOBAL.userdata.donate_percent|escape}%</font>.
{else}
        you are not <a href="{$smarty.server.PHP_SELF}?page=account&action=edit">donating</a>.
{/if}
        </td>
      </tr>
    </table>
    <table class="tablesorter" cellspacing="0">
      <thead>
        <tr><th colspan="2"><b>{$GLOBAL.config.currency} Account Balance</b></th></tr>
      </thead>
      <tr>
        <td align="left" style="font-weight: bold;">Confirmed</td>
        <td align="right"><span id="b-confirmed" class="confirmed" style="width: calc(80px); font-size: 12px;">{$GLOBAL.userdata.balance.confirmed}</span></td>
      </tr>
      <tr>
        <td align="left" style="font-weight: bold;">Unconfirmed</td>
        <td align="right"><span id="b-unconfirmed" class="unconfirmed" style="width: calc(80px); font-size: 12px;">{$GLOBAL.userdata.balance.unconfirmed}</span></td>
      </tr>
    </table>
    {if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
    <table class="tablesorter" cellspacing="0">
     <thead>
      <tr>
        <th align="left">Worker</th>
        <th align="right">Hashrate</th>
        <th align="right" style="padding-right: 10px;">Difficulty</th>
      </tr>
      </thead>
      <tbody id="b-workers">
        <td colspan="3" align="center">No worker information available</td>
      </tbody>
      </tr>
    </table>
    {/if}
</article>
