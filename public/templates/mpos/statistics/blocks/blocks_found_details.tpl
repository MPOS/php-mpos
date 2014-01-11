<article class="module width_full">
  <header><h3>Last {$BLOCKLIMIT} Blocks Found</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Block</th>
        <th align="center">Validity</th>
        <th>Finder</th>
        <th align="center">Time</th>
        <th align="right">Difficulty</th>
        <th align="right">Amount</th>
        <th align="right">Expected Shares</th>
{if $GLOBAL.config.payout_system == 'pplns'}<th align="right">PPLNS Shares</th>{/if}
        <th align="right">Actual Shares</th>
        <th align="right" style="padding-right: 25px;">Percentage</th>
      </tr>
    </thead>
    <tbody>
{assign var=count value=0}
{assign var=totalexpectedshares value=0}
{assign var=totalshares value=0}
{assign var=pplnsshares value=0}
{section block $BLOCKSFOUND}
      {assign var="totalshares" value=$totalshares+$BLOCKSFOUND[block].shares}
      {assign var="count" value=$count+1}
      {if $GLOBAL.config.payout_system == 'pplns'}{assign var="pplnsshares" value=$pplnsshares+$BLOCKSFOUND[block].pplns_shares}{/if}
      <tr class="{cycle values="odd,even"}">
{if ! $GLOBAL.website.blockexplorer.disabled}
        <td align="center"><a href="{$smarty.server.SCRIPT_NAME}?page=statistics&action=round&height={$BLOCKSFOUND[block].height}">{$BLOCKSFOUND[block].height}</a></td>
{else}
        <td align="center">{$BLOCKSFOUND[block].height}</td>
{/if}
        <td align="center">
{if $BLOCKSFOUND[block].confirmations >= $GLOBAL.confirmations}
          <span class="confirmed">Confirmed</span>
{else if $BLOCKSFOUND[block].confirmations == -1}
          <span class="orphan">Orphan</span>
{else}
          <span class="unconfirmed">{$GLOBAL.confirmations - $BLOCKSFOUND[block].confirmations} left</span>
{/if}
        </td>
        <td>{if $BLOCKSFOUND[block].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$BLOCKSFOUND[block].finder|default:"unknown"|escape}{/if}</td>
        <td align="center">{$BLOCKSFOUND[block].time|date_format:"%d/%m %H:%M:%S"}</td>
        <td align="right">{$BLOCKSFOUND[block].difficulty|number_format:"2"}</td>
        <td align="right">{$BLOCKSFOUND[block].amount|number_format:"2"}</td>
        <td align="right">
{assign var="totalexpectedshares" value=$totalexpectedshares+$BLOCKSFOUND[block].estshares}
          {$BLOCKSFOUND[block].estshares|number_format}
        </td>
{if $GLOBAL.config.payout_system == 'pplns'}<td align="right">{$BLOCKSFOUND[block].pplns_shares|number_format}</td>{/if}
        <td align="right">{$BLOCKSFOUND[block].shares|number_format}</td>
        <td align="right" style="padding-right: 25px;">
{math assign="percentage" equation="shares / estshares * 100" shares=$BLOCKSFOUND[block].shares|default:"0" estshares=$BLOCKSFOUND[block].estshares}
          <font color="{if ($percentage <= 100)}green{else}red{/if}">{$percentage|number_format:"2"}</font>
        </td>
      </tr>
{/section}
    <tr>
      <td colspan="6" align="right"><b>Totals</b></td>
      <td align="right">{$totalexpectedshares|number_format}</td>
      {if $GLOBAL.config.payout_system == 'pplns'}<td align="right">{$pplnsshares|number_format}</td>{/if}
      <td align="right">{$totalshares|number_format}</td>
      <td align="right" style="padding-right: 25px;">{if $count > 0}<font color="{if (($totalshares / $totalexpectedshares * 100) <= 100)}green{else}red{/if}">{($totalshares / $totalexpectedshares * 100)|number_format:"2"}</font>{else}0{/if}</td>
    </tr>
    </tbody>
  </table>
  <footer>
    {if $GLOBAL.config.payout_system != 'pps'}<ul><li>Note: Round Earnings are not credited until <font color="orange">{$GLOBAL.confirmations}</font> confirms.</li></ul>{/if}
  </footer>
</article>
