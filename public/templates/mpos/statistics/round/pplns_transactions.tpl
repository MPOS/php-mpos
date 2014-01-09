<article class="module width_full">
  <header><h3>Round Transactions</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th >User Name</th>
        <th align="right">Round Shares</th>
        <th align="right">Round %</th>
        <th align="right">PPLNS Shares</th>
        <th align="right">PPLNS Round %</th>
        <th align="right">Variance</th>
        <th align="right" style="padding-right: 25px;">Amount</th>
      </tr>
    </thead>
    <tbody>
{assign var=percentage1 value=0}
{section txs $ROUNDTRANSACTIONS}
      <tr{if $GLOBAL.userdata.username|default:"" == $ROUNDTRANSACTIONS[txs].username}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td>{if $ROUNDTRANSACTIONS[txs].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$ROUNDTRANSACTIONS[txs].username|default:"unknown"|escape}{/if}</td>
        <td align="right">{$ROUNDSHARES[$ROUNDTRANSACTIONS[txs].uid].valid|number_format}</td>
        <td align="right">{if $ROUNDSHARES[$ROUNDTRANSACTIONS[txs].uid].valid > 0 }{(( 100 / $BLOCKDETAILS.shares) * $ROUNDSHARES[$ROUNDTRANSACTIONS[txs].uid].valid)|number_format:"2"}{else}0.00{/if}</td>
        <td align="right">{$PPLNSROUNDSHARES[txs].pplns_valid|number_format|default:"0"}</td>
	<td align="right">{if $PPLNSROUNDSHARES[txs].pplns_valid > 0 }{(( 100 / $PPLNSSHARES) * $PPLNSROUNDSHARES[txs].pplns_valid)|number_format:"2"|default:"0"}{else}0{/if}</td>
        <td align="right">{if $ROUNDSHARES[$ROUNDTRANSACTIONS[txs].uid].valid > 0  && $PPLNSROUNDSHARES[txs].pplns_valid > 0}{math assign="percentage1" equation=(100 / ((( 100 / $BLOCKDETAILS.shares) * $ROUNDSHARES[$ROUNDTRANSACTIONS[txs].uid].valid) / (( 100 / $PPLNSSHARES) * $PPLNSROUNDSHARES[txs].pplns_valid)))}{else if $PPLNSROUNDSHARES[txs].pplns_valid == 0}{assign var=percentage1 value=0}{else}{assign var=percentage1 value=100}{/if}
          <font color="{if ($percentage1 >= 100)}green{else}red{/if}">{$percentage1|number_format:"2"}</font></b></td>
        <td align="right" style="padding-right: 25px;">{$ROUNDTRANSACTIONS[txs].amount|default:"0"|number_format:"8"}</td>
        {assign var=percentage1 value=0}
      </tr>
{/section}
    </tbody>
  </table>
</article>
