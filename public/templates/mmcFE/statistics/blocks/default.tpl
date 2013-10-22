{include file="global/block_header.tpl" BLOCK_HEADER="Block Shares" BLOCK_STYLE="clear:none;"}
<table width="70%" class="stats" rel="line">
  <caption>Block Shares</caption> 
  <thead>
    <tr>
{section block $BLOCKSFOUND step=-1}
      <th scope="col">{$BLOCKSFOUND[block].height}</th>
{/section}
    </th>
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
      <td>{$BLOCKSFOUND[block].shares}</td>
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
<center><br>
<p style="padding-left:30px; padding-redight:30px; font-size:10px;">
The graph above illustrates N shares to find a block vs. E Shares expected to find a block based on
target and network difficulty and assuming a zero variance scenario.
</p>
<table align="left" width="100%" border="0" style="font-size:13px;">
    <tbody>
      <tr>
        <td class="left">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKSFOUND[$BLOCKLIMIT - 1].height}&prev=1"><img src="{$PATH}/images/prev.png" /></a>
        </td>
        <td class="right">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKSFOUND[0].height}&next=1"><img src="{$PATH}/images/next.png" /></a>
        </td>
      </tr>
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="Last $BLOCKLIMIT Blocks Found" BLOCK_STYLE="clear:none;"}
<center>
  <table width="100%" class="sortable" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th class="center">Block</th>
        <th class="center">Validity</th>
        <th>Finder</th>
        <th class="center">Time</th>
        <th class="right">Difficulty</th>
        <th class="right">Amount</th>
        <th class="right">Expected Shares</th>
        {if $GLOBAL.config.payout_system == 'pplns'}<th class="right">PPLNS Shares</th>{/if}
        <th class="right">Actual Shares</th>
        <th class="right">Percentage</th>
      </tr>
    </thead>
    <tbody>
{assign var=count value=0}
{assign var=totalexpectedshares value=0}
{assign var=totalshares value=0}
{assign var=totalpercentage value=0}
{assign var=pplnsshares value=0}
{section block $BLOCKSFOUND}
      {assign var="totalshares" value=$totalshares+$BLOCKSFOUND[block].shares}
      {assign var="count" value=$count+1}
      {if $GLOBAL.config.payout_system == 'pplns'}{assign var="pplnsshares" value=$pplnsshares+$BLOCKSFOUND[block].pplns_shares}{/if}
      <tr class="{cycle values="odd,even"}">
        <td class="center"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round&height={$BLOCKSFOUND[block].height}">{$BLOCKSFOUND[block].height}</a></td>
        <td class="center">
        {if $BLOCKSFOUND[block].confirmations >= $GLOBAL.confirmations}
          <font color="green">Confirmed</font>
        {else if $BLOCKSFOUND[block].confirmations == -1}
          <font color="red">Orphan</font>
        {else}{$GLOBAL.confirmations - $BLOCKSFOUND[block].confirmations} left{/if}</td>
        <td>{if $BLOCKSFOUND[block].is_anonymous|default:"0" == 1}anonymous{else}{$BLOCKSFOUND[block].finder|default:"unknown"|escape}{/if}</td>
        <td class="center">{$BLOCKSFOUND[block].time|date_format:"%d/%m %H:%M:%S"}</td>
        <td class="right">{$BLOCKSFOUND[block].difficulty|number_format:"8"}</td>
        <td class="right">{$BLOCKSFOUND[block].amount|number_format:"2"}</td>
        <td class="right">
        {$BLOCKSFOUND[block].estshares|number_format}
      	{assign var="totalexpectedshares" value=$totalexpectedshares+$BLOCKSFOUND[block].estshares}
        </td>
        {if $GLOBAL.config.payout_system == 'pplns'}<td class="right">{$BLOCKSFOUND[block].pplns_shares|number_format}</td>{/if}
        <td class="right">{$BLOCKSFOUND[block].shares|number_format}</td>
        <td class="right">
          {math assign="percentage" equation="shares / estshares * 100" shares=$BLOCKSFOUND[block].shares estshares=$BLOCKSFOUND[block].estshares}
	  {assign var="totalpercentage" value=$totalpercentage+$percentage}
          <font color="{if ($percentage <= 100)}green{else}red{/if}">{$percentage|number_format:"2"}</font>
        </td>
      </tr>
{/section}
    {if $count > 0}
    <tr>
      <td colspan="6" class="right"><b>Totals</b></td>
      <td class="right">{$totalexpectedshares|number_format}</td>
      {if $GLOBAL.config.payout_system == 'pplns'}<td class="right">{$pplnsshares|number_format}</td>{/if}
      <td class="right">{$totalshares|number_format}</td>
      <td class="right"><font color="{if (($totalpercentage / $count) <= 100)}green{else}red{/if}">{($totalpercentage / $count)|number_format:"2"}</font>
    </tr>
    {/if}
    </tbody>
  </table>
</center>
{if $GLOBAL.config.payout_system != 'pps'}
<ul>
  <li>Note: <font color="orange">Round Earnings are not credited until {$GLOBAL.confirmations} confirms.</font></li>
</ul>
{/if}
{include file="global/block_footer.tpl"}
