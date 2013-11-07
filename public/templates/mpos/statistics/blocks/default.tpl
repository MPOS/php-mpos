<article class="module width_full">
  <header><h3>Block Share Overview</h3></header>
  <table width="70%" class="visualize" rel="line">
    <caption>Block Shares</caption> 
    <thead>
      <tr>
{section block $BLOCKSFOUND step=-1}
        <th scope="col">{$BLOCKSFOUND[block].height}</th>
{/section}
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">Expected</th>
{section block $BLOCKSFOUND step=-1}
        <td>{$BLOCKSFOUND[block].estshares}</td>
{/section}
      </tr>
      <tr>
        <th scope="row">Actual</th>
{section block $BLOCKSFOUND step=-1}
        <td>{$BLOCKSFOUND[block].shares|default:"0"}</td>
{/section}
     </tr>
    {if $GLOBAL.config.payout_system == 'pplns'}<tr>
      <th scope="row">PPLNS</th>
{section block $BLOCKSFOUND step=-1}
      <td>{$BLOCKSFOUND[block].pplns_shares}</td>
{/section}
   </tr>{/if}
    {if $USEBLOCKAVERAGE}<tr>
      <th scope="row">Average</th>
{section block $BLOCKSFOUND step=-1}
      <td>{$BLOCKSFOUND[block].block_avg}</td>
{/section}
   </tr>{/if}
    </tbody>
  </table>

  <br>
  <header><h3>Last 24 hour totals</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="center">Blocks Found</th>
        <th align="center">Rewards</th>
        <th align="center">Avg Difficulty</th>
        <th align="center">Expected Shares</th>
        <th align="center">Actual Shares</th>
        <th align="center" style="padding-right: 25px;">Percentage</th>
      </tr>
    </thead>
    <tbody>
      {assign var=percentage1 value=0}
      <tr>
         <td align="center">{$POOLSTATS.count|number_format:"0"}</td>
         <td align="center">{$POOLSTATS.rewards|number_format:"4"}</td>
         <td align="center">{$POOLSTATS.average|number_format:"4"}</td>
         <td align="center">{$POOLSTATS.expected|number_format:"0"}</td>
         <td align="center">{$POOLSTATS.shares|number_format:"0"}</td>
         <td align="center" style="padding-right: 25px;">{if $POOLSTATS.shares > 0}{math assign="percentage1" equation="shares1 / estshares1 * 100" shares1=$POOLSTATS.shares estshares1=$POOLSTATS.expected}{/if}
          <font color="{if ($percentage1 <= 100)}green{else}red{/if}">{$percentage1|number_format:"2"}</font></b></td>
      </tr>
    </tbody>
  </table>

<table class="tablesorter">
    <tbody>
      <tr>
        <td align="left">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={if is_array($BLOCKSFOUND) && count($BLOCKSFOUND) > ($BLOCKLIMIT - 1)}{$BLOCKSFOUND[$BLOCKLIMIT - 1].height}{/if}&prev=1"><i class="icon-left-open"></i></a>
        </td>
        <td align="right">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={if is_array($BLOCKSFOUND) && count($BLOCKSFOUND) > 0}{$BLOCKSFOUND[0].height}{/if}&next=1"><i class="icon-right-open"></i></a>
        </td>
      </tr>
    </tbody>
  </table>
  <footer>
    <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
    The graph above illustrates N shares to find a block vs. E Shares expected to find a block based on
    target and network difficulty and assuming a zero variance scenario.
    </p>
  </footer>
</article>

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
        <td align="center"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round&height={$BLOCKSFOUND[block].height}">{$BLOCKSFOUND[block].height}</a></td>
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
