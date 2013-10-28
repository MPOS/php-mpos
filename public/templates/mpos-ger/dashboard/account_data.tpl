<article class="module width_quarter">
  <header><h3>Benutzerinformationen</h3></header>
    <table class="tablesorter" cellspacing="0">
      <tr>
        <td colspan="2">
{if $GLOBAL.userdata.no_fees}
        a
{else if $GLOBAL.fees > 0}
        You are mining at <font color="orange">{$GLOBAL.fees|escape}%</font> pool fee and
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
        <td align="left" style="font-weight: bold;">Best&auml;tigt</td>
        <td align="right"><span id="b-confirmed" class="confirmed" style="width: calc(80px); font-size: 12px;"></span></td>
      </tr>
      <tr>
        <td align="left" style="font-weight: bold;">Unbest&auml;tigt</td>
        <td align="right"><span id="b-unconfirmed" class="unconfirmed" style="width: calc(80px); font-size: 12px;"></span></td>
      </tr>
    </table>
    <table class="tablesorter" cellspacing="0">
     <thead>
      <tr>
        <th align="left">Arbeiter</th>
        <th align="right">Hashrate</th>
        <th align="right" style="padding-right: 10px;">Schwierigkeit</th>
      </tr>
      </thead>
      <tbody id="b-workers">
        <td colspan="3" align="center">Lade Arbeiter Information</td>
      </tbody>
      </tr>
    </table>
</article>
