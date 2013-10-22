{include file="global/block_header.tpl" ALIGN="left" BLOCK_STYLE="width: 100%" BLOCK_HEADER="Round Transactions"}
<center>
  <table width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th scope="col">User Name</th>
        <th class="right" scope="col">Round Shares</th>
        <th class="right" scope="col">Round %</th>
        <th class="right" scope="col">PPLNS Shares</th>
        <th class="right" scope="col">PPLNS Round %</th>
        <th class="right" scope="col">Variance</th>
        <th class="right" scope="col">Amount</th>
      </tr>
    </thead>
    <tbody>
{assign var=percentage1 value=0}
{section txs $ROUNDTRANSACTIONS}
      <tr{if $GLOBAL.userdata.username|default:"" == $ROUNDTRANSACTIONS[txs].username}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td>{if $ROUNDTRANSACTIONS[txs].is_anonymous|default:"0" == 1}anonymous{else}{$ROUNDTRANSACTIONS[txs].username|escape}{/if}</td>
        <td class="right">{$SHARESDATA[$ROUNDTRANSACTIONS[txs].username].valid|number_format}</td>
        <td class="right">{if $SHARESDATA[$ROUNDTRANSACTIONS[txs].username].valid > 0 }{(( 100 / $BLOCKDETAILS.shares) * $SHARESDATA[$ROUNDTRANSACTIONS[txs].username].valid)|number_format:"2"}{else}0.00{/if}</td>
        <td class="right">{$PPLNSROUNDSHARES[txs].pplns_valid|number_format|default:"0"}</td>
	<td class="right">{if $PPLNSROUNDSHARES[txs].pplns_valid > 0 }{(( 100 / $PPLNSSHARES) * $PPLNSROUNDSHARES[txs].pplns_valid)|number_format:"2"|default:"0"}{else}0{/if}</td>
        <td class="right">{if $SHARESDATA[$ROUNDTRANSACTIONS[txs].username].valid > 0  && $PPLNSROUNDSHARES[txs].pplns_valid > 0}{math assign="percentage1" equation=(100 / ((( 100 / $BLOCKDETAILS.shares) * $SHARESDATA[$ROUNDTRANSACTIONS[txs].username].valid) / (( 100 / $PPLNSSHARES) * $PPLNSROUNDSHARES[txs].pplns_valid)))}{else if $PPLNSROUNDSHARES[txs].pplns_valid == 0}{assign var=percentage1 value=0}{else}{assign var=percentage1 value=100}{/if}
          <font color="{if ($percentage1 >= 100)}green{else}red{/if}">{$percentage1|number_format:"2"}</font></b></td>
        <td class="right">{$ROUNDTRANSACTIONS[txs].amount|default:"0"|number_format:"8"}</td>
        {assign var=percentage1 value=0}
      </tr>
{/section}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

