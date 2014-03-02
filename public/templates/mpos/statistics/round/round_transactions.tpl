<article class="module width_half">
  <header><h3>Round Transactions</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th>User Name</th>
        <th align="center">Type</th>
        <th align="right">Round Shares</th>
        <th align="right" scope="col">Round %</th>
        <th align="right" style="padding-right: 25px;">Amount</th>
      </tr>
    </thead>
    <tbody>
{section txs $ROUNDTRANSACTIONS}
      <tr{if $GLOBAL.userdata.username|default:"" == $ROUNDTRANSACTIONS[txs].username} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td>{if $ROUNDTRANSACTIONS[txs].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$ROUNDTRANSACTIONS[txs].username|default:"unknown"|escape}{/if}</td>
        <td align="center">{$ROUNDTRANSACTIONS[txs].type|default:""}</td>
        <td align="right">{$ROUNDSHARES[$ROUNDTRANSACTIONS[txs].uid].valid|number_format}</td>
        <td align="right">{(( 100 / $BLOCKDETAILS.shares) * $ROUNDSHARES[$ROUNDTRANSACTIONS[txs].uid].valid)|default:"0"|number_format:"2"}</td>
        <td align="right" style="padding-right: 25px;">{$ROUNDTRANSACTIONS[txs].amount|default:"0"|number_format:"8"}</td>
      </tr>
{/section}
    </tbody>
  </table>
</article>
