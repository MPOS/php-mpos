<article class="module width_quarter">
  <header><h3>Account Information</h3></header>
  <div class="module_content">
{if $GLOBAL.userdata.no_fees}
    <p>You are mining without any pool fees applied.</p>
{else if $GLOBAL.fees > 0}
    <p>You are mining at <font color="orange">{$GLOBAL.fees|escape}%</font> pool fee.</p>
{/if}
{if $GLOBAL.userdata.donate_percent > 0}
    <p>You are donating <font color="green">{$GLOBAL.userdata.donate_percent|escape}%</font> of your mined coins to the pool.</p>
{else}
    <p>Please consider <a href="{$smarty.server.PHP_SELF}?page=account&action=edit">donating</a> some of your mined coins to the pool operator.</p>
{/if}
    <table width="100%">
      <caption style="text-align: left;">{$GLOBAL.config.currency} Account Balance</caption>
      <tr>
        <th align="left">Confirmed</th>
        <td id="b-confirmed" align="right"></td>
      </tr>
      <tr>
        <th align="left">Unconfirmed</th>
        <td id="b-unconfirmed" align="right"></td>
      </tr>
    </table>
  </div>
</article>
