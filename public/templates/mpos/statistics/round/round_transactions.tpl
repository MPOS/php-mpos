<article class="module width_half">
  <header><h3>Round Transactions</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">TX #</th>
        <th>User Name</th>
        <th align="center">Type</th>
        <th align="right" style="padding-right: 25px;">Amount</th>
      </tr>
    </thead>
    <tbody>
{section txs $ROUNDTRANSACTIONS}
      <tr class="{cycle values="odd,even"}">
        <td align="center">{$ROUNDTRANSACTIONS[txs].id|default:"0"}</td>
        <td>{$ROUNDTRANSACTIONS[txs].username|escape}</td>
        <td align="center">{$ROUNDTRANSACTIONS[txs].type|default:""}</td>
        <td align="right" style="padding-right: 25px;">{$ROUNDTRANSACTIONS[txs].amount|default:"0"|number_format:"8"}</td>
      </tr>
{/section}
    </tbody>
  </table>
</article>
